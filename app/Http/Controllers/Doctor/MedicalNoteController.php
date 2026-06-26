<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalNote;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

class MedicalNoteController extends Controller
{
    /**
     * Menyimpan catatan tindakan dokter.
     */
    public function store(Request $request, Appointment $appointment): RedirectResponse
    {
        $doctorId = $this->currentDoctorId();

        $validated = $request->validate([
            'action_status' => ['required', 'in:berhasil,perlu_kontrol,gagal,lainnya'],
            'note' => ['required', 'string', 'max:5000'],
        ], [
            'action_status.required' => 'Status tindakan wajib dipilih.',
            'action_status.in' => 'Status tindakan tidak valid.',
            'note.required' => 'Catatan tindakan wajib diisi.',
            'note.max' => 'Catatan tindakan maksimal 5000 karakter.',
        ]);

        try {
            DB::transaction(function () use ($appointment, $doctorId, $validated) {
                /*
                 * Lock payment dulu agar urutan lock konsisten
                 * dengan PaymentController dan ScheduleController.
                 */
                $lockedPayment = Payment::where('appointment_id', $appointment->id)
                    ->lockForUpdate()
                    ->first();

                $lockedAppointment = Appointment::whereKey($appointment->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (
                    ! $lockedAppointment->doctor_id ||
                    (int) $lockedAppointment->doctor_id !== $doctorId
                ) {
                    throw new RuntimeException('Anda tidak memiliki akses ke data pasien ini.');
                }

                if ($lockedAppointment->status !== Appointment::STATUS_DIKONFIRMASI) {
                    throw new RuntimeException('Catatan tindakan hanya bisa dibuat untuk appointment yang sudah dikonfirmasi.');
                }

                if (! $lockedPayment) {
                    throw new RuntimeException('Data pembayaran tidak ditemukan.');
                }

                if ($lockedPayment->status !== Payment::STATUS_DITERIMA) {
                    throw new RuntimeException('Catatan tindakan hanya bisa dibuat jika pembayaran sudah diterima.');
                }

                MedicalNote::create([
                    'appointment_id' => $lockedAppointment->id,
                    'doctor_id' => $doctorId,
                    'action_status' => $validated['action_status'],
                    'note' => $validated['note'],
                ]);

                if ($validated['action_status'] === 'berhasil') {
                    $lockedAppointment->update([
                        'status' => Appointment::STATUS_SELESAI,
                    ]);
                }
            });

            return redirect()
                ->route('doctor.appointments.show', $appointment)
                ->with('success', 'Catatan tindakan berhasil disimpan.');

        } catch (RuntimeException $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());

        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with('error', 'Catatan tindakan gagal disimpan. Silakan coba lagi.');
        }
    }

    /**
     * Ambil ID dokter dari user login.
     */
    private function currentDoctorId(): int
    {
        $doctor = auth()->user()?->doctor;

        abort_if(
            ! $doctor,
            403,
            'Akun dokter belum terhubung dengan data dokter.'
        );

        return (int) $doctor->id;
    }
}