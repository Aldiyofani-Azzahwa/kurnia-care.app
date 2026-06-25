<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        [$startDate, $endDate, $type, $date, $month] = $this->resolveDateRange($request);

        $status = $request->input('status');
        $paymentStatus = $request->input('payment_status');

        $appointmentsQuery = $this->appointmentQuery($startDate, $endDate, $status, $paymentStatus);

        $appointments = $appointmentsQuery
            ->latest('appointment_date')
            ->orderBy('appointment_time')
            ->paginate(10)
            ->withQueryString();

        $summaryAppointments = $this->appointmentQuery($startDate, $endDate, $status, $paymentStatus)
            ->get();

        $totalAppointments = $summaryAppointments->count();

        $waitingCount = $summaryAppointments
            ->where('status', 'menunggu')
            ->count();

        $processCount = $summaryAppointments
            ->where('status', 'dikonfirmasi')
            ->count();

        $doneCount = $summaryAppointments
            ->where('status', 'selesai')
            ->count();

        $cancelCount = $summaryAppointments
            ->where('status', 'dibatalkan')
            ->count();

        $verifiedPaymentCount = $summaryAppointments
            ->filter(fn ($appointment) => optional($appointment->payment)->status === 'diterima')
            ->count();

        $pendingPaymentCount = $summaryAppointments
            ->filter(fn ($appointment) => optional($appointment->payment)->status === 'pending')
            ->count();

        $rejectedPaymentCount = $summaryAppointments
            ->filter(fn ($appointment) => optional($appointment->payment)->status === 'ditolak')
            ->count();

        $totalRevenue = $summaryAppointments
            ->filter(fn ($appointment) => optional($appointment->payment)->status === 'diterima')
            ->sum(fn ($appointment) => optional($appointment->payment)->amount ?? 0);

        $serviceRecaps = $summaryAppointments
            ->groupBy(fn ($appointment) => optional($appointment->service)->name ?? 'Tanpa Layanan')
            ->map(function ($items, $serviceName) {
                return [
                    'service_name' => $serviceName,
                    'total' => $items->count(),
                    'revenue' => $items
                        ->filter(fn ($appointment) => optional($appointment->payment)->status === 'diterima')
                        ->sum(fn ($appointment) => optional($appointment->payment)->amount ?? 0),
                ];
            })
            ->values();

        return view('admin.reports.index', compact(
            'appointments',
            'type',
            'date',
            'month',
            'startDate',
            'endDate',
            'status',
            'paymentStatus',
            'totalAppointments',
            'waitingCount',
            'processCount',
            'doneCount',
            'cancelCount',
            'verifiedPaymentCount',
            'pendingPaymentCount',
            'rejectedPaymentCount',
            'totalRevenue',
            'serviceRecaps'
        ));
    }

    public function print(Request $request): View
    {
        [$startDate, $endDate, $type, $date, $month] = $this->resolveDateRange($request);

        $status = $request->input('status');
        $paymentStatus = $request->input('payment_status');

        $appointments = $this->appointmentQuery($startDate, $endDate, $status, $paymentStatus)
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get();

        $totalAppointments = $appointments->count();

        $waitingCount = $appointments
            ->where('status', 'menunggu')
            ->count();

        $processCount = $appointments
            ->where('status', 'dikonfirmasi')
            ->count();

        $doneCount = $appointments
            ->where('status', 'selesai')
            ->count();

        $cancelCount = $appointments
            ->where('status', 'dibatalkan')
            ->count();

        $verifiedPaymentCount = $appointments
            ->filter(fn ($appointment) => optional($appointment->payment)->status === 'diterima')
            ->count();

        $pendingPaymentCount = $appointments
            ->filter(fn ($appointment) => optional($appointment->payment)->status === 'pending')
            ->count();

        $rejectedPaymentCount = $appointments
            ->filter(fn ($appointment) => optional($appointment->payment)->status === 'ditolak')
            ->count();

        $totalRevenue = $appointments
            ->filter(fn ($appointment) => optional($appointment->payment)->status === 'diterima')
            ->sum(fn ($appointment) => optional($appointment->payment)->amount ?? 0);

        return view('admin.reports.print', compact(
            'appointments',
            'type',
            'date',
            'month',
            'startDate',
            'endDate',
            'status',
            'paymentStatus',
            'totalAppointments',
            'waitingCount',
            'processCount',
            'doneCount',
            'cancelCount',
            'verifiedPaymentCount',
            'pendingPaymentCount',
            'rejectedPaymentCount',
            'totalRevenue'
        ));
    }

    private function appointmentQuery(
        string $startDate,
        string $endDate,
        ?string $status,
        ?string $paymentStatus
    ): Builder {
        return Appointment::with([
                'patient',
                'doctor',
                'service',
                'payment',
            ])
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($paymentStatus, function ($query) use ($paymentStatus) {
                $query->whereHas('payment', function ($paymentQuery) use ($paymentStatus) {
                    $paymentQuery->where('status', $paymentStatus);
                });
            });
    }

    private function resolveDateRange(Request $request): array
    {
        $type = $request->input('type', 'daily');
        $date = $request->input('date', today()->format('Y-m-d'));
        $month = $request->input('month', today()->format('Y-m'));

        if ($type === 'monthly') {
            $startDate = Carbon::parse($month . '-01')
                ->startOfMonth()
                ->format('Y-m-d');

            $endDate = Carbon::parse($month . '-01')
                ->endOfMonth()
                ->format('Y-m-d');

            return [$startDate, $endDate, $type, $date, $month];
        }

        if ($type === 'custom') {
            $startDate = $request->input('start_date', today()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->input('end_date', today()->format('Y-m-d'));

            return [$startDate, $endDate, $type, $date, $month];
        }

        $startDate = Carbon::parse($date)->format('Y-m-d');
        $endDate = Carbon::parse($date)->format('Y-m-d');

        return [$startDate, $endDate, 'daily', $date, $month];
    }
}