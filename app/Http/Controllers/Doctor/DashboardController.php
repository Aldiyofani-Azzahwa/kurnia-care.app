<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $doctor = auth()->user()->doctor;

        if (!$doctor) {
            return view('doctor.dashboard', [
                'todayAppointments' => 0,
                'processedAppointments' => 0,
                'completedAppointments' => 0,
            ]);
        }

        $todayAppointments = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', today())
            ->count();

        $processedAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'diproses')
            ->count();

        $completedAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'selesai')
            ->count();

        return view('doctor.dashboard', compact(
            'todayAppointments',
            'processedAppointments',
            'completedAppointments'
        ));
    }
}