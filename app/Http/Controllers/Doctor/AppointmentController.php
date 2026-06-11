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
        $appointments = Appointment::with([
            'patient',
            'doctor',
            'service',
            'payment',
        ])
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
        $appointments = Appointment::with([
            'patient',
            'doctor',
            'service',
            'payment',
            'medicalNotes',
        ])
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
        $appointments = Appointment::with([
            'patient',
            'doctor',
            'service',
            'medicalNotes',
        ])
            ->whereHas('medicalNotes')
            ->latest('updated_at')
            ->paginate(10);

        return view('doctor.appointments.medical-notes', [
            'appointments' => $appointments,
        ]);
    }
}