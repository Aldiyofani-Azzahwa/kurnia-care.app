<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    /**
     * Jadwal pasien yang siap ditangani.
     */
    public function index(): View
    {
        $doctorId = $this->currentDoctorId();

        $appointments = Appointment::with([
            'patient',
            'doctor',
            'service',
            'payment',
        ])
            ->where('doctor_id', $doctorId)
            ->where('status', 'dikonfirmasi')
            ->orderByDesc('appointment_date')
            ->orderByDesc('appointment_time')
            ->paginate(10);

        return view('doctor.appointments.index', [
            'appointments' => $appointments,
        ]);
    }

    /**
     * Detail jadwal pasien.
     */
    public function show(Appointment $appointment): View
    {
        $doctorId = $this->currentDoctorId();

        abort_if(
            ! $appointment->doctor_id || (int) $appointment->doctor_id !== $doctorId,
            403,
            'Anda tidak memiliki akses ke data pasien ini.'
        );

        $appointment->load([
            'patient',
            'doctor',
            'service',
            'payment',
            'medicalNotes',
        ]);

        return view('doctor.appointments.show', [
            'appointment' => $appointment,
        ]);
    }

    /**
     * Riwayat tindakan dokter.
     */
    public function history(): View
    {
        $doctorId = $this->currentDoctorId();

        $appointments = Appointment::with([
            'patient',
            'doctor',
            'service',
            'payment',
            'medicalNotes',
        ])
            ->where('doctor_id', $doctorId)
            ->where('status', 'selesai')
            ->orderByDesc('updated_at')
            ->paginate(10);

        return view('doctor.appointments.history', [
            'appointments' => $appointments,
        ]);
    }

    /**
     * Catatan medis semua pasien.
     */
    public function medicalNotes(): View
    {
        $doctorId = $this->currentDoctorId();

        $appointments = Appointment::with([
            'patient',
            'doctor',
            'service',
            'payment',
            'medicalNotes',
        ])
            ->where('doctor_id', $doctorId)
            ->whereHas('medicalNotes')
            ->orderByDesc('updated_at')
            ->paginate(10);

        return view('doctor.appointments.medical-notes', [
            'appointments' => $appointments,
        ]);
    }

    /**
     * Ambil ID dokter dari user login.
     */
    private function currentDoctorId(): int
    {
        $doctor = auth()->user()?->doctor;

        abort_if(
            ! $doctor,
            403,
            'Akun dokter belum terhubung dengan data dokter.'
        );

        return (int) $doctor->id;
    }
}