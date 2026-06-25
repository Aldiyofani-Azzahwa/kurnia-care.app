<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
use Illuminate\View\View;

class PatientController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $patients = Patient::with([
            'user',
            'appointments.service',
            'appointments.payment',
        ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('child_name', 'like', "%{$search}%")
                        ->orWhere('father_name', 'like', "%{$search}%")
                        ->orWhere('mother_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('province_name', 'like', "%{$search}%")
                        ->orWhere('city_name', 'like', "%{$search}%")
                        ->orWhere('district_name', 'like', "%{$search}%")
                        ->orWhere('village_name', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalPatients = Patient::count();

        $todayRegistrations = Appointment::whereDate('created_at', today())
            ->count();

        $pendingAppointments = Appointment::where('status', 'menunggu')
            ->count();

        $completedAppointments = Appointment::where('status', 'selesai')
            ->count();

        return view('admin.patients.index', compact(
            'patients',
            'search',
            'totalPatients',
            'todayRegistrations',
            'pendingAppointments',
            'completedAppointments'
        ));
    }

    public function create(AppointmentQuotaService $quotaService): View
    {
        return view('admin.patients.create', [
            'services' => Service::where('is_active', true)->get(),
            'availableDates' => $quotaService->availableDates(14),
        ]);
    }

    public function store(Request $request, AppointmentQuotaService $quotaService): RedirectResponse
    {
        $validated = $request->validate($this->storeRules(), $this->messages());

        if ($quotaService->isFull($validated['appointment_date'])) {
            return back()
                ->withInput()
                ->with('error', 'Jadwal penuh, silakan pilih hari lain.')
                ->with('nearest_date', $quotaService->nearestAvailableDate($validated['appointment_date']));
        }

        $service = Service::findOrFail($validated['service_id']);

        $doctor = Doctor::where('is_active', true)->first();

        if (!$doctor) {
            return back()
                ->withInput()
                ->with('error', 'Belum ada dokter aktif. Silakan tambahkan dokter aktif terlebih dahulu.');
        }

        $photoPath = null;

        if ($request->hasFile('child_photo')) {
            $photoPath = $request->file('child_photo')->store('patients', 'public');
        }

        $fullAddress = $validated['village_name'] . ', ' .
            $validated['district_name'] . ', ' .
            $validated['city_name'] . ', ' .
            $validated['province_name'];

        /*
        |--------------------------------------------------------------------------
        | SIMPAN PASIEN OFFLINE
        |--------------------------------------------------------------------------
        | Pasien offline tidak punya akun login.
        | Maka user_id dibuat null.
        | Admin yang sedang login dicatat di registered_by_id.
        */
        $patient = Patient::create([
            'user_id' => null,
            'registered_by_id' => auth()->id(),
            'registration_type' => 'offline',

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
            'service_id' => $validated['service_id'],
            'schedule_id' => null,

            'appointment_date' => $validated['appointment_date'],
            'appointment_day' => Carbon::parse($validated['appointment_date'])
                ->locale('id')
                ->isoFormat('dddd'),
            'appointment_time' => $validated['appointment_time'],

            'medicine_type' => $validated['medicine_type'],
            'circumcision_package' => 'Paket Standar',

            'status' => Appointment::STATUS_MENUNGGU,
        ]);

        Payment::create([
            'appointment_id' => $appointment->id,
            'amount' => $service->price,
            'payment_method' => 'Transfer Bank',
            'status' => Payment::STATUS_PENDING,
        ]);

        return redirect()
            ->route('admin.patients.index')
            ->with('success', 'Pasien offline berhasil didaftarkan oleh admin.');
    }

    public function show(Patient $patient): View
    {
        $patient->load([
            'user',
            'appointments.doctor',
            'appointments.service',
            'appointments.payment',
            'appointments.medicalNotes',
        ]);

        return view('admin.patients.show', compact('patient'));
    }

    public function edit(Patient $patient): View
    {
        return view('admin.patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient): RedirectResponse
    {
        $validated = $request->validate($this->updateRules(), $this->messages());

        $fullAddress = $validated['village_name'] . ', ' .
            $validated['district_name'] . ', ' .
            $validated['city_name'] . ', ' .
            $validated['province_name'];

        $patient->update([
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
        ]);

        return redirect()
            ->route('admin.patients.index')
            ->with('success', 'Data pasien berhasil diperbarui.');
    }

    public function destroy(Patient $patient): RedirectResponse
    {
        if ($patient->appointments()->exists()) {
            return back()
                ->with('error', 'Pasien tidak dapat dihapus karena sudah memiliki data pendaftaran.');
        }

        $patient->delete();

        return redirect()
            ->route('admin.patients.index')
            ->with('success', 'Data pasien berhasil dihapus.');
    }

    public function checkQuota(Request $request, AppointmentQuotaService $quotaService): JsonResponse
    {
        $request->validate([
            'date' => ['required', 'date'],
        ]);

        $date = $request->input('date');

        return response()->json([
            'date' => $date,
            'is_full' => $quotaService->isFull($date),
            'remaining_quota' => $quotaService->remainingQuota($date),
            'nearest_available_date' => $quotaService->nearestAvailableDate($date),
        ]);
    }

    private function storeRules(): array
    {
        return array_merge($this->patientRules(), [
            'child_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],

            'service_id' => ['required', 'exists:services,id'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'appointment_time' => ['required', 'date_format:H:i'],
            'medicine_type' => ['required', 'in:puyer,tablet,syrup'],
        ]);
    }

    private function updateRules(): array
    {
        return $this->patientRules();
    }

    private function patientRules(): array
    {
        return [
            'child_name' => ['required', 'string', 'max:150'],
            'child_age' => ['required', 'integer', 'min:1', 'max:60'],
            'child_weight' => ['required', 'numeric', 'min:1', 'max:200'],

            'drug_allergy' => ['nullable', 'string'],
            'bleeding_history' => ['nullable', 'string'],
            'surgery_history' => ['nullable', 'string'],
            'disease_history' => ['nullable', 'string'],

            'province_code' => ['required', 'string'],
            'province_name' => ['required', 'string'],

            'city_code' => ['required', 'string'],
            'city_name' => ['required', 'string'],

            'district_code' => ['required', 'string'],
            'district_name' => ['required', 'string'],

            'village_code' => ['required', 'string'],
            'village_name' => ['required', 'string'],

            'father_name' => ['required', 'string', 'max:150'],
            'mother_name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],

            'instagram' => ['nullable', 'string', 'max:100'],
            'facebook' => ['nullable', 'string', 'max:100'],
            'information_source' => ['nullable', 'in:Instagram,Facebook,Google,Lainnya'],
        ];
    }

    private function messages(): array
    {
        return [
            'child_name.required' => 'Nama anak wajib diisi.',
            'child_age.required' => 'Umur anak wajib diisi.',
            'child_weight.required' => 'Berat badan anak wajib diisi.',

            'province_code.required' => 'Provinsi wajib dipilih.',
            'city_code.required' => 'Kabupaten/kota wajib dipilih.',
            'district_code.required' => 'Kecamatan wajib dipilih.',
            'village_code.required' => 'Desa/kelurahan wajib dipilih.',

            'father_name.required' => 'Nama ayah wajib diisi.',
            'mother_name.required' => 'Nama ibu wajib diisi.',
            'phone.required' => 'Nomor HP wajib diisi.',

            'child_photo.image' => 'File foto anak harus berupa gambar.',
            'child_photo.mimes' => 'Foto anak harus berformat JPG, JPEG, atau PNG.',
            'child_photo.max' => 'Ukuran foto anak maksimal 5MB.',

            'service_id.required' => 'Layanan wajib dipilih.',
            'service_id.exists' => 'Layanan tidak valid.',

            'appointment_date.required' => 'Tanggal khitan wajib dipilih.',
            'appointment_date.after_or_equal' => 'Tanggal khitan tidak boleh sebelum hari ini.',

            'appointment_time.required' => 'Jam khitan wajib dipilih.',
            'appointment_time.date_format' => 'Format jam tidak valid.',

            'medicine_type.required' => 'Jenis obat wajib dipilih.',
            'medicine_type.in' => 'Jenis obat tidak valid.',
        ];
    }
}