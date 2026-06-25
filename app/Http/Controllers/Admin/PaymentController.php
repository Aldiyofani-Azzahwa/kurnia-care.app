<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * Menampilkan semua data pembayaran.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $payments = Payment::with([
            'appointment.patient',
            'appointment.doctor',
            'appointment.service',
            'verifier',
        ])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('appointment.patient', function ($patientQuery) use ($search) {
                    $patientQuery->where('child_name', 'like', '%' . $search . '%')
                        ->orWhere('father_name', 'like', '%' . $search . '%')
                        ->orWhere('mother_name', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%');
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.payments.index', [
            'payments' => $payments,
            'search' => $search,
            'status' => $status,
        ]);
    }

    /**
     * Menampilkan detail pembayaran.
     */
    public function show(Payment $payment): View
    {
        $payment->load([
            'appointment.patient',
            'appointment.doctor',
            'appointment.service',
            'verifier',
        ]);

        return view('admin.payments.show', [
            'payment' => $payment,
        ]);
    }

    /**
     * Verifikasi pembayaran.
     *
     * Method verify tetap dipertahankan agar route/view lama tidak error.
     */
    public function verify(Payment $payment): RedirectResponse
    {
        return $this->accept($payment);
    }

    /**
     * Terima pembayaran.
     *
     * Saat pembayaran diterima:
     * - payments.status menjadi diterima
     * - appointments.status menjadi dikonfirmasi
     */
    public function accept(Payment $payment): RedirectResponse
    {
        $payment->load('appointment');

        if (! $payment->appointment) {
            return back()->with('error', 'Data appointment tidak ditemukan.');
        }

        if ($payment->status !== Payment::STATUS_PENDING) {
            return back()->with('error', 'Pembayaran ini sudah diproses dan tidak bisa diverifikasi ulang.');
        }

        if ($payment->appointment->status === Appointment::STATUS_DIBATALKAN) {
            return back()->with('error', 'Appointment sudah dibatalkan dan tidak bisa diverifikasi.');
        }

        if ($payment->appointment->status === Appointment::STATUS_SELESAI) {
            return back()->with('error', 'Appointment sudah selesai dan pembayaran tidak bisa diubah.');
        }

        if (! $payment->proof_image) {
            return back()->with('error', 'Bukti pembayaran belum diupload.');
        }

        DB::transaction(function () use ($payment) {
            $payment->update([
                'status' => Payment::STATUS_DITERIMA,
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'rejection_reason' => null,
            ]);

            $payment->appointment->update([
                'status' => Appointment::STATUS_DIKONFIRMASI,
            ]);
        });

        return redirect()
            ->route('admin.payments.show', $payment)
            ->with('success', 'Pembayaran berhasil diterima dan appointment telah dikonfirmasi.');
    }

    /**
     * Tolak pembayaran.
     *
     * Saat pembayaran ditolak:
     * - payments.status menjadi ditolak
     * - appointments.status kembali menunggu
     *
     * Appointment tidak langsung dibatalkan agar pasien masih bisa upload ulang bukti pembayaran.
     */
    public function reject(Request $request, Payment $payment): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $payment->load('appointment');

        if (! $payment->appointment) {
            return back()->with('error', 'Data appointment tidak ditemukan.');
        }

        if ($payment->status !== Payment::STATUS_PENDING) {
            return back()->with('error', 'Pembayaran ini sudah diproses dan tidak bisa ditolak ulang.');
        }

        if ($payment->appointment->status === Appointment::STATUS_SELESAI) {
            return back()->with('error', 'Appointment sudah selesai dan pembayaran tidak bisa ditolak.');
        }

        DB::transaction(function () use ($payment, $validated) {
            $payment->update([
                'status' => Payment::STATUS_DITOLAK,
                'rejection_reason' => $validated['rejection_reason'],
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);

            $payment->appointment->update([
                'status' => Appointment::STATUS_MENUNGGU,
            ]);
        });

        return redirect()
            ->route('admin.payments.show', $payment)
            ->with('success', 'Pembayaran ditolak. Pasien dapat mengunggah ulang bukti pembayaran.');
    }
}