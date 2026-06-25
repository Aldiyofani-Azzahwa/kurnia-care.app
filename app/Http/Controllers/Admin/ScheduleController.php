<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $selectedDate = $request->input('date', today()->format('Y-m-d'));
        $search = $request->input('search');
        $status = $request->input('status');

        $appointments = Appointment::with([
                'patient',
                'doctor',
                'service',
                'payment',
            ])
            ->whereDate('appointment_date', $selectedDate)
            ->when($search, function ($query) use ($search) {
                $query->whereHas('patient', function ($patientQuery) use ($search) {
                    $patientQuery->where('child_name', 'like', "%{$search}%")
                        ->orWhere('father_name', 'like', "%{$search}%")
                        ->orWhere('mother_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                })
                ->orWhereHas('doctor', function ($doctorQuery) use ($search) {
                    $doctorQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('service', function ($serviceQuery) use ($search) {
                    $serviceQuery->where('name', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('appointment_time')
            ->paginate(10)
            ->withQueryString();

        $totalToday = Appointment::whereDate('appointment_date', $selectedDate)->count();

        $waitingCount = Appointment::whereDate('appointment_date', $selectedDate)
            ->where('status', 'menunggu')
            ->count();

        $processCount = Appointment::whereDate('appointment_date', $selectedDate)
            ->where('status', 'dikonfirmasi')
            ->count();

        $doneCount = Appointment::whereDate('appointment_date', $selectedDate)
            ->where('status', 'selesai')
            ->count();

        return view('admin.schedules.index', compact(
            'appointments',
            'selectedDate',
            'search',
            'status',
            'totalToday',
            'waitingCount',
            'processCount',
            'doneCount'
        ));
    }

    public function show(Appointment $appointment): View
    {
        $appointment->load([
            'patient',
            'doctor',
            'service',
            'payment',
            'medicalNotes',
        ]);

        return view('admin.schedules.show', compact('appointment'));
    }

    public function updateStatus(Request $request, Appointment $appointment): RedirectResponse
    {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in(['menunggu', 'dikonfirmasi', 'selesai', 'dibatalkan']),
            ],
        ], [
            'status.required' => 'Status jadwal wajib dipilih.',
            'status.in' => 'Status jadwal tidak valid.',
        ]);

        $appointment->update([
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'Status jadwal pasien berhasil diperbarui.');
    }
}