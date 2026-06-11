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
        $doctor = auth()->user()->doctor;
        $doctorId = $doctor ? $doctor->id : 0;

        $appointments = Appointment::with([
            'patient',
            'doctor',
            'service',
            'payment',
        ])
            ->where('doctor_id', $doctorId)
            ->where('status', 'diproses')
            ->latest('appointment_date')
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
        $doctor = auth()->user()->doctor;
        $doctorId = $doctor ? $doctor->id : 0;

        abort_if(!$appointment->doctor_id || $appointment->doctor_id !== $doctorId, 403, 'Anda tidak memiliki akses ke data pasien ini.');

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
        $doctor = auth()->user()->doctor;
        $doctorId = $doctor ? $doctor->id : 0;

        $appointments = Appointment::with([
            'patient',
            'doctor',
            'service',
            'payment',
            'medicalNotes',
        ])
            ->where('doctor_id', $doctorId)
            ->where('status', 'selesai')
            ->latest('updated_at')
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
        $doctor = auth()->user()->doctor;
        $doctorId = $doctor ? $doctor->id : 0;

        $appointments = Appointment::with([
            'patient',
            'doctor',
            'service',
            'medicalNotes',
        ])
            ->where('doctor_id', $doctorId)
            ->whereHas('medicalNotes')
            ->latest('updated_at')
            ->paginate(10);

        return view('doctor.appointments.medical-notes', [
            'appointments' => $appointments,
        ]);
    }
}