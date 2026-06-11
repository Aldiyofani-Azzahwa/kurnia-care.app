<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
     */
    public function verify(Payment $payment): RedirectResponse
    {
        $payment->load('appointment');

        if ($payment->status !== 'pending') {
            return back()->with('error', 'Pembayaran ini sudah diproses dan tidak bisa diverifikasi ulang.');
        }

        if ($payment->appointment && $payment->appointment->status === 'batal') {
            return back()->with('error', 'Transaksi sudah batal dan tidak bisa diverifikasi.');
        }

        if (!$payment->proof_image) {
            return back()->with('error', 'Bukti pembayaran belum diupload.');
        }

        $payment->update([
            'status' => 'diverifikasi',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'rejection_reason' => null,
        ]);

        if ($payment->appointment) {
            $payment->appointment->update([
                'status' => 'diproses',
            ]);
        }

        return redirect()
            ->route('admin.payments.show', $payment)
            ->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    /**
     * Tolak pembayaran.
     */
    public function reject(Request $request, Payment $payment): RedirectResponse
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $payment->load('appointment');

        if ($payment->status !== 'pending') {
            return back()->with('error', 'Pembayaran ini sudah diproses dan tidak bisa ditolak ulang.');
        }

        $payment->update([
            'status' => 'ditolak',
            'rejection_reason' => $request->rejection_reason,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        if ($payment->appointment) {
            $payment->appointment->update([
                'status' => 'batal',
            ]);
        }

        return redirect()
            ->route('admin.payments.show', $payment)
            ->with('success', 'Pembayaran ditolak dan transaksi dibatalkan.');
    }
}