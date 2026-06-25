<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
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

        if ($appointment->status === Appointment::STATUS_DIBATALKAN || $appointment->status === 'dibatalkan') {
            return redirect()
                ->route('user.appointments.show', $appointment)
                ->with('error', 'Pendaftaran sudah dibatalkan, pembayaran tidak bisa diupload.');
        }

        if ($appointment->status === Appointment::STATUS_SELESAI) {
            return redirect()
                ->route('user.appointments.show', $appointment)
                ->with('error', 'Pendaftaran sudah selesai, pembayaran tidak bisa diubah.');
        }

        if ($appointment->payment->status === Payment::STATUS_DITERIMA) {
            return redirect()
                ->route('user.appointments.show', $appointment)
                ->with('error', 'Pembayaran sudah diterima dan tidak bisa diubah.');
        }

        if (
            !in_array($appointment->payment->status, [
                Payment::STATUS_PENDING,
                Payment::STATUS_DITOLAK,
            ], true)
        ) {
            return redirect()
                ->route('user.appointments.show', $appointment)
                ->with('error', 'Pembayaran ini sudah dikonfirmasi dan tidak bisa diubah.');
        }

        return view('user.payments.edit', [
            'appointment' => $appointment,
        ]);
    }

    /**
     * Menyimpan atau mengganti bukti pembayaran.
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

        if ($appointment->status === Appointment::STATUS_DIBATALKAN || $appointment->status === 'dibatalkan') {
            return redirect()
                ->route('user.appointments.show', $appointment)
                ->with('error', 'Pendaftaran sudah dibatalkan, pembayaran tidak bisa diupload.');
        }

        if ($appointment->status === Appointment::STATUS_SELESAI) {
            return redirect()
                ->route('user.appointments.show', $appointment)
                ->with('error', 'Pendaftaran sudah selesai, pembayaran tidak bisa diubah.');
        }

        if ($appointment->payment->status === Payment::STATUS_DITERIMA) {
            return redirect()
                ->route('user.appointments.show', $appointment)
                ->with('error', 'Pembayaran sudah diterima dan tidak bisa diubah.');
        }

        if (
            !in_array($appointment->payment->status, [
                Payment::STATUS_PENDING,
                Payment::STATUS_DITOLAK,
            ], true)
        ) {
            return redirect()
                ->route('user.appointments.show', $appointment)
                ->with('error', 'Pembayaran ini sudah dikonfirmasi dan tidak bisa diubah.');
        }

        if ($appointment->payment->proof_image && Storage::disk('public')->exists($appointment->payment->proof_image)) {
            Storage::disk('public')->delete($appointment->payment->proof_image);
        }

        $proofPath = $request->file('proof_image')->store('payments', 'public');

        $appointment->payment->update([
            'proof_image' => $proofPath,
            'status' => Payment::STATUS_PENDING,
            'rejection_reason' => null,
            'verified_by' => null,
            'verified_at' => null,
        ]);

        $appointment->update([
            'status' => Appointment::STATUS_MENUNGGU,
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