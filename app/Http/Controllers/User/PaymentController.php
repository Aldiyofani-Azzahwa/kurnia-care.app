<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Appointment;
use App\Models\Payment;
use App\Services\SupabaseStorageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

class PaymentController extends Controller
{
    /**
     * Menampilkan halaman upload bukti pembayaran.
     */
    public function edit(Appointment $appointment): View|RedirectResponse
    {
        $this->authorizePatientAppointment($appointment);

        $appointment->load(['patient', 'service', 'payment']);

        if (! $appointment->payment) {
            return redirect()
                ->route('user.appointments.show', $appointment)
                ->with('error', 'Data pembayaran tidak ditemukan.');
        }

        if (
            in_array($appointment->status, [
                Appointment::STATUS_DIBATALKAN,
                'dibatalkan',
            ], true)
        ) {
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

        if (! in_array($appointment->payment->status, [
            Payment::STATUS_PENDING,
            Payment::STATUS_DITOLAK,
        ], true)) {
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
        Appointment $appointment,
        SupabaseStorageService $storage
    ): RedirectResponse {
        $this->authorizePatientAppointment($appointment);

        $appointment->load(['patient', 'payment']);

        if (! $appointment->payment) {
            return back()->with('error', 'Data pembayaran tidak ditemukan.');
        }

        if (
            in_array($appointment->status, [
                Appointment::STATUS_DIBATALKAN,
                'dibatalkan',
            ], true)
        ) {
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

        if (! in_array($appointment->payment->status, [
            Payment::STATUS_PENDING,
            Payment::STATUS_DITOLAK,
        ], true)) {
            return redirect()
                ->route('user.appointments.show', $appointment)
                ->with('error', 'Pembayaran ini sudah dikonfirmasi dan tidak bisa diubah.');
        }

        $newProofUrl = null;
        $oldProofPath = $appointment->payment->proof_image;

        try {
            $newProofUrl = $storage->upload($request->file('proof_image'), 'payments');

            DB::transaction(function () use ($appointment, $newProofUrl) {
                $lockedAppointment = Appointment::with('payment')
                    ->whereKey($appointment->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (! $lockedAppointment->payment) {
                    throw new \RuntimeException('Data pembayaran tidak ditemukan.');
                }

                $lockedAppointment->payment->update([
                    'proof_image' => $newProofUrl,
                    'status' => Payment::STATUS_PENDING,
                    'rejection_reason' => null,
                    'verified_by' => null,
                    'verified_at' => null,
                ]);

                $lockedAppointment->update([
                    'status' => Appointment::STATUS_MENUNGGU,
                ]);
            });

            $this->deleteProofImage($oldProofPath, $storage);

            return redirect()
                ->route('user.appointments.show', $appointment)
                ->with('success', 'Bukti pembayaran berhasil diupload. Silakan tunggu verifikasi admin.');
        } catch (Throwable $e) {
            $this->deleteProofImage($newProofUrl, $storage);

            report($e);

            return back()
                ->withInput()
                ->with('error', 'ERROR: ' . $e->getMessage());
        }
    }

    /**
     * Hapus bukti lama, baik dari Supabase Storage maupun storage lokal lama.
     */
    private function deleteProofImage(?string $proofPath, SupabaseStorageService $storage): void
    {
        if (! $proofPath) {
            return;
        }

        if (str_starts_with($proofPath, 'http://') || str_starts_with($proofPath, 'https://')) {
            $storage->delete($proofPath);
            return;
        }

        if (Storage::disk('public')->exists($proofPath)) {
            Storage::disk('public')->delete($proofPath);
        }
    }

    /**
     * Pastikan appointment milik user yang sedang login.
     */
    private function authorizePatientAppointment(Appointment $appointment): void
    {
        $appointment->loadMissing('patient');

        abort_if(
            ! $appointment->patient || $appointment->patient->user_id !== auth()->id(),
            403
        );
    }
}