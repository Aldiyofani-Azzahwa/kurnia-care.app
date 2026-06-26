<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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

        $summary = $this->buildSummary($summaryAppointments);
        $serviceRecaps = $this->buildServiceRecaps($summaryAppointments);

        return view('admin.reports.index', array_merge($summary, [
            'appointments' => $appointments,
            'type' => $type,
            'date' => $date,
            'month' => $month,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'status' => $status,
            'paymentStatus' => $paymentStatus,
            'serviceRecaps' => $serviceRecaps,
        ]));
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

        $summary = $this->buildSummary($appointments);
        $serviceRecaps = $this->buildServiceRecaps($appointments);

        return view('admin.reports.print', array_merge($summary, [
            'appointments' => $appointments,
            'type' => $type,
            'date' => $date,
            'month' => $month,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'status' => $status,
            'paymentStatus' => $paymentStatus,
            'serviceRecaps' => $serviceRecaps,
        ]));
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

    private function buildSummary(Collection $appointments): array
    {
        $acceptedAppointments = $appointments->filter(function ($appointment) {
            return optional($appointment->payment)->status === Payment::STATUS_DITERIMA;
        });

        $totalDpRevenue = $acceptedAppointments->sum(function ($appointment) {
            return $this->dpAmount($appointment);
        });

        $totalRevenue = $acceptedAppointments->sum(function ($appointment) {
            return $this->fullPaymentAmount($appointment);
        });

        return [
            'totalAppointments' => $appointments->count(),
            'waitingCount' => $appointments->where('status', Appointment::STATUS_MENUNGGU)->count(),
            'processCount' => $appointments->where('status', Appointment::STATUS_DIKONFIRMASI)->count(),
            'doneCount' => $appointments->where('status', Appointment::STATUS_SELESAI)->count(),
            'cancelCount' => $appointments->where('status', Appointment::STATUS_DIBATALKAN)->count(),

            'verifiedPaymentCount' => $acceptedAppointments->count(),
            'pendingPaymentCount' => $appointments->filter(function ($appointment) {
                return optional($appointment->payment)->status === Payment::STATUS_PENDING;
            })->count(),
            'rejectedPaymentCount' => $appointments->filter(function ($appointment) {
                return optional($appointment->payment)->status === Payment::STATUS_DITOLAK;
            })->count(),

            'totalDpRevenue' => $totalDpRevenue,
            'totalRevenue' => $totalRevenue,
            'totalRemainingRevenue' => max($totalRevenue - $totalDpRevenue, 0),
        ];
    }

    private function buildServiceRecaps(Collection $appointments): Collection
    {
        return $appointments
            ->groupBy(function ($appointment) {
                return optional($appointment->service)->name ?? 'Tanpa Layanan';
            })
            ->map(function ($items, $serviceName) {
                $acceptedItems = $items->filter(function ($appointment) {
                    return optional($appointment->payment)->status === Payment::STATUS_DITERIMA;
                });

                $dpRevenue = $acceptedItems->sum(function ($appointment) {
                    return $this->dpAmount($appointment);
                });

                $revenue = $acceptedItems->sum(function ($appointment) {
                    return $this->fullPaymentAmount($appointment);
                });

                return [
                    'service_name' => $serviceName,
                    'total' => $items->count(),
                    'paid_count' => $acceptedItems->count(),
                    'dp_revenue' => $dpRevenue,
                    'revenue' => $revenue,
                    'remaining_revenue' => max($revenue - $dpRevenue, 0),
                ];
            })
            ->values();
    }

    private function fullPaymentAmount(Appointment $appointment): float
    {
        $servicePrice = (float) (optional($appointment->service)->price ?? 0);

        if ($servicePrice > 0) {
            return $servicePrice;
        }

        return $this->dpAmount($appointment);
    }

    private function dpAmount(Appointment $appointment): float
    {
        return (float) (optional($appointment->payment)->amount ?? 0);
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
            $startDate = Carbon::parse($request->input('start_date', today()->startOfMonth()->format('Y-m-d')))
                ->format('Y-m-d');

            $endDate = Carbon::parse($request->input('end_date', today()->format('Y-m-d')))
                ->format('Y-m-d');

            if ($startDate > $endDate) {
                [$startDate, $endDate] = [$endDate, $startDate];
            }

            return [$startDate, $endDate, $type, $date, $month];
        }

        $startDate = Carbon::parse($date)->format('Y-m-d');
        $endDate = Carbon::parse($date)->format('Y-m-d');

        return [$startDate, $endDate, 'daily', $date, $month];
    }
}