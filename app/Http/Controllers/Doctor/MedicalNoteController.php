<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalNote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MedicalNoteController extends Controller
{
    /**
     * Menyimpan catatan tindakan dokter.
     */
    public function store(Request $request, Appointment $appointment): RedirectResponse
    {
        $doctorId = $this->currentDoctorId();

        abort_if(
            ! $appointment->doctor_id || (int) $appointment->doctor_id !== $doctorId,
            403,
            'Anda tidak memiliki akses ke data pasien ini.'
        );

        $validated = $request->validate([
            'action_status' => ['required', 'in:berhasil,perlu_kontrol,gagal,lainnya'],
            'note' => ['required', 'string', 'max:5000'],
        ], [
            'action_status.required' => 'Status tindakan wajib dipilih.',
            'action_status.in' => 'Status tindakan tidak valid.',
            'note.required' => 'Catatan tindakan wajib diisi.',
            'note.max' => 'Catatan tindakan maksimal 5000 karakter.',
        ]);

        if (! in_array($appointment->status, ['dikonfirmasi', 'selesai'], true)) {
            return back()
                ->withInput()
                ->with('error', 'Catatan tindakan hanya bisa dibuat untuk pasien yang sudah dikonfirmasi.');
        }

        MedicalNote::create([
            'appointment_id' => $appointment->id,
            'doctor_id' => $doctorId,
            'action_status' => $validated['action_status'],
            'note' => $validated['note'],
        ]);

        if ($validated['action_status'] === 'berhasil') {
            $appointment->update([
                'status' => 'selesai',
            ]);
        }

        return redirect()
            ->route('doctor.appointments.show', $appointment)
            ->with('success', 'Catatan tindakan berhasil disimpan.');
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