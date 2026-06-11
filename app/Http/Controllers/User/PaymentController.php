<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Appointment;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * Menampilkan halaman upload bukti pembayaran.
     */
    public function edit(Appointment $appointment): View|RedirectResponse
    {
        $this->authorizePatientAppointment($appointment);

        $appointment->load(['patient', 'service', 'payment']);

        if (!$appointment->payment) {
            return redirect()
                ->route('user.appointments.show', $appointment)
                ->with('error', 'Data pembayaran tidak ditemukan.');
        }

        if ($appointment->status === 'batal') {
            return redirect()
                ->route('user.appointments.show', $appointment)
                ->with('error', 'Pendaftaran sudah dibatalkan, pembayaran tidak bisa diupload.');
        }

        if ($appointment->payment->proof_image) {
            return redirect()
                ->route('user.appointments.show', $appointment)
                ->with('error', 'Bukti pembayaran sudah dikirim dan tidak bisa diupload ulang.');
        }

        if ($appointment->payment->status !== 'pending') {
            return redirect()
                ->route('user.appointments.show', $appointment)
                ->with('error', 'Pembayaran ini sudah diproses dan tidak bisa diubah.');
        }

        return view('user.payments.edit', [
            'appointment' => $appointment,
        ]);
    }

    /**
     * Menyimpan bukti pembayaran.
     */
    public function update(
        StorePaymentRequest $request,
        Appointment $appointment
    ): RedirectResponse {
        $this->authorizePatientAppointment($appointment);

        $appointment->load(['patient', 'payment']);

        if (!$appointment->payment) {
            return back()->with('error', 'Data pembayaran tidak ditemukan.');
        }

        if ($appointment->status === 'batal') {
            return redirect()
                ->route('user.appointments.show', $appointment)
                ->with('error', 'Pendaftaran sudah dibatalkan, pembayaran tidak bisa diupload.');
        }

        if ($appointment->payment->proof_image) {
            return redirect()
                ->route('user.appointments.show', $appointment)
                ->with('error', 'Bukti pembayaran sudah dikirim dan tidak bisa diupload ulang.');
        }

        if ($appointment->payment->status !== 'pending') {
            return redirect()
                ->route('user.appointments.show', $appointment)
                ->with('error', 'Pembayaran ini sudah diproses dan tidak bisa diubah.');
        }

        $proofPath = $request->file('proof_image')->store('payments', 'public');

        $appointment->payment->update([
            'proof_image' => $proofPath,
            'status' => 'pending',
            'rejection_reason' => null,
            'verified_by' => null,
            'verified_at' => null,
        ]);

        return redirect()
            ->route('user.appointments.show', $appointment)
            ->with('success', 'Bukti pembayaran berhasil diupload. Silakan tunggu verifikasi admin.');
    }

    /**
     * Pastikan appointment milik user yang sedang login.
     */
    private function authorizePatientAppointment(Appointment $appointment): void
    {
        $appointment->loadMissing('patient');

        abort_if(
            !$appointment->patient || $appointment->patient->user_id !== auth()->id(),
            403
        );
    }
}