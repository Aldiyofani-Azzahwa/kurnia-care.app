<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Patient;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard admin.
     */
    public function index(): View
    {
        $totalPatients = Patient::count();

        $totalAppointments = Appointment::count();

        $todayAppointments = Appointment::whereDate('appointment_date', today())
            ->count();

        $waitingAppointments = Appointment::where('status', 'menunggu')
            ->count();

        $processedAppointments = Appointment::where('status', 'dikonfirmasi')
            ->count();

        $completedAppointments = Appointment::where('status', 'selesai')
            ->count();

        $cancelledAppointments = Appointment::where('status', 'dibatalkan')
            ->count();

        $pendingPayments = Payment::where('status', 'pending')
            ->count();

        $verifiedPayments = Payment::where('status', 'diterima')
            ->count();

        $rejectedPayments = Payment::where('status', 'ditolak')
            ->count();

        $needVerificationPayments = Payment::where('status', 'pending')
            ->whereNotNull('proof_image')
            ->count();

        $latestAppointments = Appointment::with([
            'patient',
            'service',
            'payment',
        ])
            ->latest()
            ->limit(5)
            ->get();

        $latestPayments = Payment::with([
            'appointment.patient',
            'appointment.service',
        ])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'totalPatients' => $totalPatients,
            'totalAppointments' => $totalAppointments,
            'todayAppointments' => $todayAppointments,
            'waitingAppointments' => $waitingAppointments,
            'processedAppointments' => $processedAppointments,
            'completedAppointments' => $completedAppointments,
            'cancelledAppointments' => $cancelledAppointments,
            'pendingPayments' => $pendingPayments,
            'verifiedPayments' => $verifiedPayments,
            'rejectedPayments' => $rejectedPayments,
            'needVerificationPayments' => $needVerificationPayments,
            'latestAppointments' => $latestAppointments,
            'latestPayments' => $latestPayments,
        ]);
    }
}