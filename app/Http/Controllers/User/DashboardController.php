<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $userId = auth()->id();

        $activeAppointments = Appointment::whereHas('patient', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->whereIn('status', ['menunggu', 'dikonfirmasi'])
            ->count();

        $pendingPayments = Appointment::whereHas('patient', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->whereHas('payment', function ($query) {
                $query->where('status', 'pending');
            })
            ->count();

        $totalAppointments = Appointment::whereHas('patient', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->count();

        return view('user.dashboard', compact(
            'activeAppointments',
            'pendingPayments',
            'totalAppointments'
        ));
    }
}