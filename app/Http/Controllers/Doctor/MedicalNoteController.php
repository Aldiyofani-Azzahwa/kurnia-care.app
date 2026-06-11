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
        $validated = $request->validate([
            'action_status' => ['required', 'in:berhasil,perlu_kontrol,gagal,lainnya'],
            'note' => ['required', 'string', 'max:5000'],
        ], [
            'action_status.required' => 'Status tindakan wajib dipilih.',
            'action_status.in' => 'Status tindakan tidak valid.',
            'note.required' => 'Catatan tindakan wajib diisi.',
            'note.max' => 'Catatan tindakan maksimal 5000 karakter.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | PASTIKAN APPOINTMENT SUDAH DIPROSES
        |--------------------------------------------------------------------------
        | Dokter hanya boleh memberi catatan pada appointment yang sudah
        | diverifikasi pembayarannya oleh admin.
        */
        if (!in_array($appointment->status, ['diproses', 'selesai'])) {
            return back()
                ->withInput()
                ->with('error', 'Catatan tindakan hanya bisa dibuat untuk pasien yang sudah diproses.');
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN CATATAN MEDIS
        |--------------------------------------------------------------------------
        */
        MedicalNote::create([
            'appointment_id' => $appointment->id,
            'doctor_id' => $appointment->doctor_id,
            'action_status' => $validated['action_status'],
            'note' => $validated['note'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | UPDATE STATUS APPOINTMENT
        |--------------------------------------------------------------------------
        | Kalau tindakan berhasil, appointment otomatis selesai.
        */
        if ($validated['action_status'] === 'berhasil') {
            $appointment->update([
                'status' => 'selesai',
            ]);
        }

        return redirect()
            ->route('doctor.appointments.show', $appointment)
            ->with('success', 'Catatan tindakan berhasil disimpan.');
    }
}