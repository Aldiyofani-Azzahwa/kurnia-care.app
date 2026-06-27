<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Payment;
use App\Services\SupabaseStorageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

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
                    $patientQuery->where(function ($q) use ($search) {
                        $q->where('child_name', 'like', '%' . $search . '%')
                            ->orWhere('father_name', 'like', '%' . $search . '%')
                            ->orWhere('mother_name', 'like', '%' . $search . '%')
                            ->orWhere('phone', 'like', '%' . $search . '%');
                    });
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
     * Upload bukti pembayaran oleh admin.
     */
    public function uploadProof(
        Request $request,
        Payment $payment,
        SupabaseStorageService $storage
    ): RedirectResponse {
        $request->validate([
            'proof_image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'proof_image.required' => 'Bukti pembayaran wajib diupload.',
            'proof_image.image' => 'File harus berupa gambar.',
            'proof_image.mimes' => 'Format bukti pembayaran harus JPG, JPEG, PNG, atau WEBP.',
            'proof_image.max' => 'Ukuran bukti pembayaran maksimal 5 MB.',
        ]);

        $payment->load('appointment');

        if (! $payment->appointment) {
            return back()->with('error', 'Data appointment tidak ditemukan.');
        }

        if ($payment->appointment->status === Appointment::STATUS_DIBATALKAN) {
            return back()->with('error', 'Appointment sudah dibatalkan. Bukti pembayaran tidak bisa diupload.');
        }

        if ($payment->appointment->status === Appointment::STATUS_SELESAI) {
            return back()->with('error', 'Appointment sudah selesai. Bukti pembayaran tidak bisa diubah.');
        }

        if ($payment->status === Payment::STATUS_DITERIMA) {
            return back()->with('error', 'Pembayaran sudah diterima. Bukti pembayaran tidak bisa diubah.');
        }

        if (! in_array($payment->status, [
            Payment::STATUS_PENDING,
            Payment::STATUS_DITOLAK,
        ], true)) {
            return back()->with('error', 'Status pembayaran tidak valid untuk upload bukti pembayaran.');
        }

        $oldProofPath = $payment->proof_image;
        $newProofUrl = null;

        try {
            $newProofUrl = $storage->upload($request->file('proof_image'), 'payments');

            DB::transaction(function () use ($payment, $newProofUrl) {
                $lockedPayment = Payment::whereKey($payment->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $lockedPayment->load('appointment');

                if (! $lockedPayment->appointment) {
                    throw new \RuntimeException('Data appointment tidak ditemukan.');
                }

                if ($lockedPayment->appointment->status === Appointment::STATUS_DIBATALKAN) {
                    throw new \RuntimeException('Appointment sudah dibatalkan.');
                }

                if ($lockedPayment->appointment->status === Appointment::STATUS_SELESAI) {
                    throw new \RuntimeException('Appointment sudah selesai.');
                }

                if ($lockedPayment->status === Payment::STATUS_DITERIMA) {
                    throw new \RuntimeException('Pembayaran sudah diterima.');
                }

                $updateData = [
                    'proof_image' => $newProofUrl,
                ];

                if ($lockedPayment->status === Payment::STATUS_DITOLAK) {
                    $updateData['status'] = Payment::STATUS_PENDING;
                    $updateData['rejection_reason'] = null;
                    $updateData['verified_by'] = null;
                    $updateData['verified_at'] = null;
                }

                $lockedPayment->update($updateData);
            });

            $this->deleteProofImage($oldProofPath, $storage);

            return redirect()
                ->route('admin.payments.show', $payment)
                ->with('success', 'Bukti pembayaran berhasil diupload.');
        } catch (Throwable $e) {
            $this->deleteProofImage($newProofUrl, $storage);

            report($e);

            return back()
                ->withInput()
                ->with('error', 'ERROR: ' . $e->getMessage());
        }
    }

    /**
     * Verifikasi pembayaran.
     * Method ini tetap ada agar route lama tidak error.
     */
    public function verify(Payment $payment): RedirectResponse
    {
        return $this->accept($payment);
    }

    /**
     * Terima pembayaran.
     */
    public function accept(Payment $payment): RedirectResponse
    {
        try {
            DB::transaction(function () use ($payment) {
                $lockedPayment = Payment::with('appointment')
                    ->whereKey($payment->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (! $lockedPayment->appointment) {
                    throw new \RuntimeException('Data appointment tidak ditemukan.');
                }

                if ($lockedPayment->status !== Payment::STATUS_PENDING) {
                    throw new \RuntimeException('Pembayaran ini sudah dikonfirmasi dan tidak bisa diterima ulang.');
                }

                if ($lockedPayment->appointment->status === Appointment::STATUS_DIBATALKAN) {
                    throw new \RuntimeException('Appointment sudah dibatalkan dan tidak bisa diterima.');
                }

                if ($lockedPayment->appointment->status === Appointment::STATUS_SELESAI) {
                    throw new \RuntimeException('Appointment sudah selesai dan pembayaran tidak bisa diubah.');
                }

                if (! $lockedPayment->proof_image) {
                    throw new \RuntimeException('Bukti pembayaran belum diupload.');
                }

                $lockedPayment->update([
                    'status' => Payment::STATUS_DITERIMA,
                    'verified_by' => auth()->id(),
                    'verified_at' => now(),
                    'rejection_reason' => null,
                ]);

                $lockedPayment->appointment->update([
                    'status' => Appointment::STATUS_DIKONFIRMASI,
                ]);
            });

            return redirect()
                ->route('admin.payments.show', $payment)
                ->with('success', 'Pembayaran berhasil diterima dan appointment telah dikonfirmasi.');
        } catch (Throwable $e) {
            report($e);

            return back()->with('error', $e->getMessage() ?: 'Pembayaran gagal diterima.');
        }
    }

    /**
     * Tolak pembayaran.
     */
    public function reject(Request $request, Payment $payment): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
        ]);

        try {
            DB::transaction(function () use ($payment, $validated) {
                $lockedPayment = Payment::with('appointment')
                    ->whereKey($payment->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (! $lockedPayment->appointment) {
                    throw new \RuntimeException('Data appointment tidak ditemukan.');
                }

                if ($lockedPayment->status !== Payment::STATUS_PENDING) {
                    throw new \RuntimeException('Pembayaran ini sudah dikonfirmasi dan tidak bisa ditolak ulang.');
                }

                if ($lockedPayment->appointment->status === Appointment::STATUS_SELESAI) {
                    throw new \RuntimeException('Appointment sudah selesai dan pembayaran tidak bisa ditolak.');
                }

                if ($lockedPayment->appointment->status === Appointment::STATUS_DIBATALKAN) {
                    throw new \RuntimeException('Appointment sudah dibatalkan dan pembayaran tidak bisa ditolak.');
                }

                $lockedPayment->update([
                    'status' => Payment::STATUS_DITOLAK,
                    'rejection_reason' => $validated['rejection_reason'],
                    'verified_by' => auth()->id(),
                    'verified_at' => now(),
                ]);

                $lockedPayment->appointment->update([
                    'status' => Appointment::STATUS_MENUNGGU,
                ]);
            });

            return redirect()
                ->route('admin.payments.show', $payment)
                ->with('success', 'Pembayaran ditolak. Pasien dapat mengunggah ulang bukti pembayaran.');
        } catch (Throwable $e) {
            report($e);

            return back()->with('error', $e->getMessage() ?: 'Pembayaran gagal ditolak.');
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
}