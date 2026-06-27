<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Service;
use App\Services\AppointmentQuotaService;
use App\Services\SupabaseStorageService;
use Carbon\Carbon;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

class AppointmentController extends Controller
{
    public function create(AppointmentQuotaService $quotaService): View
    {
        return view('user.appointments.create', [
            'services' => Service::where('is_active', true)->get(),
            'availableDates' => $quotaService->availableDates(14),
        ]);
    }

    public function store(
        StoreAppointmentRequest $request,
        AppointmentQuotaService $quotaService,
        SupabaseStorageService $storage
    ): RedirectResponse {
        $validated = $request->validated();

        $validated = $this->completeRegionNames($validated);

        $appointmentDate = Carbon::parse($validated['appointment_date'])->toDateString();
        $lockKey = 'appointment-quota-' . $appointmentDate;

        $photoUrl = null;

        try {
            return Cache::lock($lockKey, 10)->block(5, function () use (
                $request,
                $validated,
                $quotaService,
                $appointmentDate,
                $storage,
                &$photoUrl
            ) {
                if ($quotaService->isFull($appointmentDate)) {
                    return back()
                        ->withInput()
                        ->with('error', 'Jadwal penuh, silakan pilih hari lain.')
                        ->with('nearest_date', $quotaService->nearestAvailableDate($appointmentDate));
                }

                $service = Service::where('is_active', true)
                    ->where('id', $validated['service_id'])
                    ->first();

                if (! $service) {
                    return back()
                        ->withInput()
                        ->with('error', 'Layanan tidak tersedia atau tidak aktif.');
                }

                $doctor = Doctor::where('is_active', true)
                    ->orderBy('id')
                    ->first();

                if (! $doctor) {
                    return back()
                        ->withInput()
                        ->with('error', 'Belum ada dokter aktif. Silakan hubungi admin.');
                }

                if ($request->hasFile('child_photo')) {
                    $photoUrl = $storage->upload($request->file('child_photo'), 'patients');
                }

                $appointment = DB::transaction(function () use (
                    $validated,
                    $service,
                    $doctor,
                    $appointmentDate,
                    $photoUrl
                ) {
                    $fullAddress = trim(
                        ($validated['village_name'] ?? '') . ', ' .
                        ($validated['district_name'] ?? '') . ', ' .
                        ($validated['city_name'] ?? '') . ', ' .
                        ($validated['province_name'] ?? ''),
                        ' ,'
                    );

                    $patient = Patient::create([
                        'user_id' => auth()->id(),
                        'registered_by_id' => null,
                        'registration_type' => 'online',

                        'child_name' => $validated['child_name'],
                        'child_age' => $validated['child_age'],
                        'child_weight' => $validated['child_weight'],

                        'drug_allergy' => $validated['drug_allergy'] ?? null,
                        'bleeding_history' => $validated['bleeding_history'] ?? null,
                        'surgery_history' => $validated['surgery_history'] ?? null,
                        'disease_history' => $validated['disease_history'] ?? null,

                        'province_code' => $validated['province_code'],
                        'province_name' => $validated['province_name'],

                        'city_code' => $validated['city_code'],
                        'city_name' => $validated['city_name'],

                        'district_code' => $validated['district_code'],
                        'district_name' => $validated['district_name'],

                        'village_code' => $validated['village_code'],
                        'village_name' => $validated['village_name'],

                        'address' => $fullAddress,

                        'father_name' => $validated['father_name'],
                        'mother_name' => $validated['mother_name'],
                        'phone' => $validated['phone'],

                        'instagram' => $validated['instagram'] ?? null,
                        'facebook' => $validated['facebook'] ?? null,
                        'information_source' => $validated['information_source'] ?? null,

                        'child_photo' => $photoUrl,
                    ]);

                    $appointment = Appointment::create([
                        'patient_id' => $patient->id,
                        'doctor_id' => $doctor->id,
                        'service_id' => $service->id,
                        'schedule_id' => $validated['schedule_id'] ?? null,

                        'appointment_date' => $appointmentDate,
                        'appointment_day' => Carbon::parse($appointmentDate)
                            ->locale('id')
                            ->isoFormat('dddd'),
                        'appointment_time' => $validated['appointment_time'],

                        'medicine_type' => $validated['medicine_type'],
                        'circumcision_package' => $validated['circumcision_package'] ?? 'Paket Standar',

                        'status' => Appointment::STATUS_MENUNGGU,
                    ]);

                    Payment::create([
                        'appointment_id' => $appointment->id,
                        'amount' => config('payment.dp_amount', 100000),
                        'payment_method' => 'Transfer Bank DP',
                        'status' => Payment::STATUS_PENDING,
                    ]);

                    return $appointment;
                });

                return redirect()
                    ->route('user.appointments.show', $appointment)
                    ->with('success', 'Pendaftaran berhasil dibuat. Silakan upload bukti pembayaran DP.');
            });
        } catch (LockTimeoutException $e) {
            return back()
                ->withInput()
                ->with('error', 'Sistem sedang memproses pendaftaran lain pada tanggal yang sama. Silakan coba lagi.');
        } catch (Throwable $e) {
            $this->deleteUploadedFile($photoUrl, $storage);

            report($e);

            return back()
                ->withInput()
                ->with('error', 'ERROR: ' . $e->getMessage());
        }
    }

    public function index(): View
    {
        $appointments = Appointment::with(['patient', 'doctor', 'service', 'payment'])
            ->whereHas('patient', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->latest()
            ->paginate(10);

        return view('user.appointments.index', [
            'appointments' => $appointments,
        ]);
    }

    public function show(Appointment $appointment): View
    {
        $this->authorizePatientAppointment($appointment);

        $appointment->load([
            'patient',
            'doctor',
            'service',
            'payment',
            'medicalNotes',
        ]);

        return view('user.appointments.show', [
            'appointment' => $appointment,
        ]);
    }

    public function checkQuota(
        Request $request,
        AppointmentQuotaService $quotaService
    ): JsonResponse {
        $request->validate([
            'date' => ['required', 'date'],
        ]);

        $date = Carbon::parse($request->input('date'))->toDateString();

        $isFull = $quotaService->isFull($date);

        return response()->json([
            'is_full' => $isFull,
            'remaining_quota' => $quotaService->remainingQuota($date),
            'message' => $isFull
                ? 'Kuota tanggal ini sudah penuh.'
                : 'Kuota masih tersedia.',
        ]);
    }

    private function completeRegionNames(array $validated): array
    {
        $validated['province_name'] = $validated['province_name']
            ?? $this->regionName('indonesia_provinces', $validated['province_code'] ?? null)
            ?? '-';

        $validated['city_name'] = $validated['city_name']
            ?? $this->regionName('indonesia_cities', $validated['city_code'] ?? null)
            ?? '-';

        $validated['district_name'] = $validated['district_name']
            ?? $this->regionName('indonesia_districts', $validated['district_code'] ?? null)
            ?? '-';

        $validated['village_name'] = $validated['village_name']
            ?? $this->regionName('indonesia_villages', $validated['village_code'] ?? null)
            ?? '-';

        return $validated;
    }

    private function regionName(string $table, ?string $code): ?string
    {
        if (! $code) {
            return null;
        }

        return DB::table($table)
            ->where('code', $code)
            ->value('name');
    }

    private function deleteUploadedFile(?string $path, SupabaseStorageService $storage): void
    {
        if (! $path) {
            return;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            $storage->delete($path);
            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function authorizePatientAppointment(Appointment $appointment): void
    {
        $appointment->loadMissing('patient');

        abort_if(
            ! $appointment->patient || $appointment->patient->user_id !== auth()->id(),
            403
        );
    }
}