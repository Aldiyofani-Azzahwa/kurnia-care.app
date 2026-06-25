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
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

class AppointmentController extends Controller
{
    /**
     * Menampilkan form pendaftaran online.
     */
    public function create(AppointmentQuotaService $quotaService): View
    {
        return view('user.appointments.create', [
            'services' => Service::where('is_active', true)->get(),
            'availableDates' => $quotaService->availableDates(14),
        ]);
    }

    /**
     * Menyimpan data pendaftaran online.
     */
    public function store(
        StoreAppointmentRequest $request,
        AppointmentQuotaService $quotaService
    ): RedirectResponse {
        $validated = $request->validated();

        if ($quotaService->isFull($validated['appointment_date'])) {
            return back()
                ->withInput()
                ->with('error', 'Jadwal penuh, silakan pilih hari lain.')
                ->with('nearest_date', $quotaService->nearestAvailableDate($validated['appointment_date']));
        }

        $service = Service::where('is_active', true)
            ->where('id', $validated['service_id'])
            ->first();

        if (! $service) {
            return back()
                ->withInput()
                ->with('error', 'Layanan tidak tersedia atau tidak aktif.');
        }

        $doctor = Doctor::where('is_active', true)->first();

        if (! $doctor) {
            return back()
                ->withInput()
                ->with('error', 'Belum ada dokter aktif. Silakan hubungi admin.');
        }

        $photoPath = null;

        try {
            if ($request->hasFile('child_photo')) {
                $photoPath = $request->file('child_photo')->store('patients', 'public');
            }

            $appointment = DB::transaction(function () use ($validated, $service, $doctor, $photoPath) {
                $fullAddress = $validated['village_name'] . ', ' .
                    $validated['district_name'] . ', ' .
                    $validated['city_name'] . ', ' .
                    $validated['province_name'];

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

                    'child_photo' => $photoPath,
                ]);

                $appointment = Appointment::create([
                    'patient_id' => $patient->id,
                    'doctor_id' => $doctor->id,
                    'service_id' => $service->id,
                    'schedule_id' => $validated['schedule_id'] ?? null,

                    'appointment_date' => $validated['appointment_date'],
                    'appointment_day' => Carbon::parse($validated['appointment_date'])
                        ->locale('id')
                        ->isoFormat('dddd'),
                    'appointment_time' => $validated['appointment_time'],

                    'medicine_type' => $validated['medicine_type'],
                    'circumcision_package' => $validated['circumcision_package'] ?? 'Paket Standar',

                    'status' => Appointment::STATUS_MENUNGGU,
                ]);

                Payment::create([
                    'appointment_id' => $appointment->id,
                    'amount' => $service->price,
                    'payment_method' => 'Transfer Bank',
                    'status' => Payment::STATUS_PENDING,
                ]);

                return $appointment;
            });

            return redirect()
                ->route('user.appointments.show', $appointment)
                ->with('success', 'Pendaftaran berhasil dibuat. Silakan upload bukti pembayaran.');

        } catch (Throwable $e) {
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }

            report($e);

            return back()
                ->withInput()
                ->with('error', 'Pendaftaran gagal disimpan. Silakan coba lagi.');
        }
    }

    /**
     * Menampilkan riwayat pendaftaran pasien milik user login.
     */
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

    /**
     * Menampilkan detail pendaftaran.
     */
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

    /**
     * Cek kuota tanggal secara realtime.
     */
    public function checkQuota(
        Request $request,
        AppointmentQuotaService $quotaService
    ): JsonResponse {
        $request->validate([
            'date' => ['required', 'date'],
        ]);

        $date = $request->input('date');

        $isFull = $quotaService->isFull($date);

        return response()->json([
            'is_full' => $isFull,
            'remaining_quota' => $quotaService->remainingQuota($date),
            'message' => $isFull
                ? 'Kuota tanggal ini sudah penuh.'
                : 'Kuota masih tersedia.',
        ]);
    }

    /**
     * Pastikan appointment milik pasien yang sedang login.
     */
    private function authorizePatientAppointment(Appointment $appointment): void
    {
        $appointment->loadMissing('patient');

        abort_if(
            ! $appointment->patient || $appointment->patient->user_id !== auth()->id(),
            403
        );
    }
}