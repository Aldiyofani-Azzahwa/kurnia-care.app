<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class ScheduleController extends Controller
{
    /**
     * Menampilkan daftar jadwal appointment.
     */
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'date' => ['nullable', 'date'],
            'search' => ['nullable', 'string', 'max:100'],
            'status' => [
                'nullable',
                Rule::in([
                    Appointment::STATUS_MENUNGGU,
                    Appointment::STATUS_DIKONFIRMASI,
                    Appointment::STATUS_SELESAI,
                    Appointment::STATUS_DIBATALKAN,
                ]),
            ],
        ]);

        $selectedDate = Carbon::parse($validated['date'] ?? today()->toDateString())->toDateString();
        $search = $validated['search'] ?? null;
        $status = $validated['status'] ?? null;

        $appointments = Appointment::with([
            'patient',
            'doctor',
            'service',
            'payment',
        ])
            ->whereDate('appointment_date', $selectedDate)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery
                        ->whereHas('patient', function ($patientQuery) use ($search) {
                            $patientQuery->where(function ($q) use ($search) {
                                $q->where('child_name', 'like', "%{$search}%")
                                    ->orWhere('father_name', 'like', "%{$search}%")
                                    ->orWhere('mother_name', 'like', "%{$search}%")
                                    ->orWhere('phone', 'like', "%{$search}%");
                            });
                        })
                        ->orWhereHas('doctor', function ($doctorQuery) use ($search) {
                            $doctorQuery->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('service', function ($serviceQuery) use ($search) {
                            $serviceQuery->where('name', 'like', "%{$search}%");
                        });
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
            ->where('status', Appointment::STATUS_MENUNGGU)
            ->count();

        $processCount = Appointment::whereDate('appointment_date', $selectedDate)
            ->where('status', Appointment::STATUS_DIKONFIRMASI)
            ->count();

        $doneCount = Appointment::whereDate('appointment_date', $selectedDate)
            ->where('status', Appointment::STATUS_SELESAI)
            ->count();

        $cancelledCount = Appointment::whereDate('appointment_date', $selectedDate)
            ->where('status', Appointment::STATUS_DIBATALKAN)
            ->count();

        return view('admin.schedules.index', compact(
            'appointments',
            'selectedDate',
            'search',
            'status',
            'totalToday',
            'waitingCount',
            'processCount',
            'doneCount',
            'cancelledCount'
        ));
    }

    /**
     * Menampilkan detail jadwal appointment.
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

        return view('admin.schedules.show', compact('appointment'));
    }

    /**
     * Mengubah status appointment dari halaman jadwal admin.
     */
    public function updateStatus(Request $request, Appointment $appointment): RedirectResponse
    {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in([
                    Appointment::STATUS_MENUNGGU,
                    Appointment::STATUS_DIKONFIRMASI,
                    Appointment::STATUS_SELESAI,
                    Appointment::STATUS_DIBATALKAN,
                ]),
            ],
        ], [
            'status.required' => 'Status jadwal wajib dipilih.',
            'status.in' => 'Status jadwal tidak valid.',
        ]);

        $targetStatus = $validated['status'];

        try {
            DB::transaction(function () use ($appointment, $targetStatus) {
                /*
                 * Lock payment dulu agar urutan lock konsisten
                 * dengan proses verifikasi payment di PaymentController.
                 */
                Payment::where('appointment_id', $appointment->id)
                    ->lockForUpdate()
                    ->first();

                $lockedAppointment = Appointment::with(['payment'])
                    ->whereKey($appointment->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($lockedAppointment->status === $targetStatus) {
                    return;
                }

                if ($lockedAppointment->status === Appointment::STATUS_SELESAI) {
                    throw new RuntimeException('Appointment yang sudah selesai tidak dapat diubah statusnya.');
                }

                if ($targetStatus === Appointment::STATUS_DIKONFIRMASI) {
                    $this->ensurePaymentAccepted($lockedAppointment);
                }

                if ($targetStatus === Appointment::STATUS_SELESAI) {
                    $this->ensurePaymentAccepted($lockedAppointment);
                    $this->ensureMedicalNoteExists($lockedAppointment);
                }

                if (
                    $targetStatus === Appointment::STATUS_DIBATALKAN &&
                    $lockedAppointment->payment?->status === Payment::STATUS_DITERIMA
                ) {
                    throw new RuntimeException('Appointment dengan pembayaran diterima tidak bisa dibatalkan langsung.');
                }

                $lockedAppointment->update([
                    'status' => $targetStatus,
                ]);
            });

            return back()->with('success', 'Status jadwal pasien berhasil diperbarui.');

        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());

        } catch (Throwable $e) {
            report($e);

            return back()->with('error', 'Status jadwal gagal diperbarui. Silakan coba lagi.');
        }
    }

    /**
     * Pastikan pembayaran sudah diterima.
     */
    private function ensurePaymentAccepted(Appointment $appointment): void
    {
        if (! $appointment->payment) {
            throw new RuntimeException('Data pembayaran tidak ditemukan.');
        }

        if ($appointment->payment->status !== Payment::STATUS_DITERIMA) {
            throw new RuntimeException('Appointment hanya bisa dikonfirmasi atau diselesaikan jika pembayaran sudah diterima.');
        }
    }

    /**
     * Pastikan appointment sudah memiliki catatan medis.
     */
    private function ensureMedicalNoteExists(Appointment $appointment): void
    {
        if (! $appointment->medicalNotes()->exists()) {
            throw new RuntimeException('Appointment hanya bisa diselesaikan jika sudah ada catatan medis.');
        }
    }
}