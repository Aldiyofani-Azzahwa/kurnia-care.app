<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        if (! auth()->check()) {
            return false;
        }

        $role = strtolower(trim((string) auth()->user()->role));

        return in_array($role, ['pasien', 'user'], true);
    }

    public function rules(): array
    {
        return [
            'child_name' => ['required', 'string', 'max:150'],
            'child_age' => ['required', 'integer', 'min:1', 'max:60'],
            'child_weight' => ['required', 'numeric', 'min:1', 'max:200'],

            'drug_allergy' => ['nullable', 'string'],
            'bleeding_history' => ['nullable', 'string'],
            'surgery_history' => ['nullable', 'string'],
            'disease_history' => ['nullable', 'string'],

            'province_code' => ['required', 'string'],
            'province_name' => ['nullable', 'string', 'max:150'],

            'city_code' => ['required', 'string'],
            'city_name' => ['nullable', 'string', 'max:150'],

            'district_code' => ['required', 'string'],
            'district_name' => ['nullable', 'string', 'max:150'],

            'village_code' => ['required', 'string'],
            'village_name' => ['nullable', 'string', 'max:150'],

            'father_name' => ['required', 'string', 'max:150'],
            'mother_name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],

            'instagram' => ['nullable', 'string', 'max:100'],
            'facebook' => ['nullable', 'string', 'max:100'],
            'information_source' => ['nullable', 'in:Instagram,Facebook,Google,Lainnya'],

            'child_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],

            'doctor_id' => ['nullable', 'exists:doctors,id'],
            'service_id' => ['required', 'exists:services,id'],
            'schedule_id' => ['nullable', 'exists:schedules,id'],

            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'appointment_time' => ['required', 'date_format:H:i'],

            'medicine_type' => ['required', 'in:puyer,tablet,syrup'],
            'circumcision_package' => ['nullable', 'string', 'max:150'],
        ];
    }

    public function messages(): array
    {
        return [
            'child_name.required' => 'Nama lengkap anak wajib diisi.',

            'child_age.required' => 'Umur anak wajib diisi.',
            'child_age.integer' => 'Umur anak harus berupa angka.',
            'child_age.min' => 'Umur anak minimal 1 tahun.',
            'child_age.max' => 'Umur anak tidak boleh lebih dari 60 tahun.',

            'child_weight.required' => 'Berat badan anak wajib diisi.',
            'child_weight.numeric' => 'Berat badan harus berupa angka.',
            'child_weight.min' => 'Berat badan anak tidak valid.',
            'child_weight.max' => 'Berat badan anak terlalu besar.',

            'province_code.required' => 'Provinsi wajib dipilih.',
            'city_code.required' => 'Kabupaten/Kota wajib dipilih.',
            'district_code.required' => 'Kecamatan wajib dipilih.',
            'village_code.required' => 'Desa/Kelurahan wajib dipilih.',

            'father_name.required' => 'Nama ayah wajib diisi.',
            'mother_name.required' => 'Nama ibu wajib diisi.',
            'phone.required' => 'Nomor HP/WA wajib diisi.',

            'child_photo.image' => 'File foto anak harus berupa gambar.',
            'child_photo.mimes' => 'Foto anak hanya boleh JPG, JPEG, PNG, atau WEBP.',
            'child_photo.max' => 'Ukuran foto maksimal 5MB.',

            'doctor_id.exists' => 'Dokter tidak valid.',

            'service_id.required' => 'Layanan wajib dipilih.',
            'service_id.exists' => 'Layanan tidak valid.',

            'schedule_id.exists' => 'Jadwal tidak valid.',

            'appointment_date.required' => 'Tanggal khitan wajib dipilih.',
            'appointment_date.date' => 'Format tanggal khitan tidak valid.',
            'appointment_date.after_or_equal' => 'Tanggal khitan tidak boleh sebelum hari ini.',

            'appointment_time.required' => 'Jam khitan wajib dipilih.',
            'appointment_time.date_format' => 'Format jam tidak valid.',

            'medicine_type.required' => 'Jenis obat wajib dipilih.',
            'medicine_type.in' => 'Jenis obat tidak valid.',
        ];
    }

    public function attributes(): array
    {
        return [
            'child_name' => 'nama anak',
            'child_age' => 'umur anak',
            'child_weight' => 'berat badan anak',
            'province_code' => 'provinsi',
            'city_code' => 'kabupaten/kota',
            'district_code' => 'kecamatan',
            'village_code' => 'desa/kelurahan',
            'father_name' => 'nama ayah',
            'mother_name' => 'nama ibu',
            'phone' => 'nomor HP',
            'child_photo' => 'foto anak',
            'service_id' => 'layanan',
            'appointment_date' => 'tanggal khitan',
            'appointment_time' => 'jam khitan',
            'medicine_type' => 'jenis obat',
        ];
    }
}