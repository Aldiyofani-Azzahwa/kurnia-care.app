<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    /**
     * User harus login untuk daftar.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'pasien';
    }

    /**
     * Aturan validasi form pendaftaran.
     */
    public function rules(): array
    {
        return [
            /*
            |--------------------------------------------------------------------------
            | Identitas Anak
            |--------------------------------------------------------------------------
            */
            'child_name' => ['required', 'string', 'max:150'],
            'child_age' => ['required', 'integer', 'min:1', 'max:60'],
            'child_weight' => ['required', 'integer', 'min:1', 'max:200'],

            'drug_allergy' => ['nullable', 'string'],
            'bleeding_history' => ['nullable', 'string'],
            'surgery_history' => ['nullable', 'string'],
            'disease_history' => ['nullable', 'string'],

            /*
            |--------------------------------------------------------------------------
            | Alamat Wilayah Laravolt
            |--------------------------------------------------------------------------
            */
            'province_code' => ['required', 'string'],
            'province_name' => ['required', 'string', 'max:150'],

            'city_code' => ['required', 'string'],
            'city_name' => ['required', 'string', 'max:150'],

            'district_code' => ['required', 'string'],
            'district_name' => ['required', 'string', 'max:150'],

            'village_code' => ['required', 'string'],
            'village_name' => ['required', 'string', 'max:150'],

            /*
            |--------------------------------------------------------------------------
            | Identitas Orang Tua
            |--------------------------------------------------------------------------
            */
            'father_name' => ['required', 'string', 'max:150'],
            'mother_name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],

            'instagram' => ['nullable', 'string', 'max:100'],
            'facebook' => ['nullable', 'string', 'max:100'],
            'information_source' => ['nullable', 'in:Instagram,Facebook,Google,Lainnya'],

            /*
            |--------------------------------------------------------------------------
            | Foto Anak
            |--------------------------------------------------------------------------
            */
            'child_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],

            /*
            |--------------------------------------------------------------------------
            | Data Appointment
            |--------------------------------------------------------------------------
            */
            'doctor_id' => ['nullable', 'exists:doctors,id'],
            'service_id' => ['required', 'exists:services,id'],
            'schedule_id' => ['nullable', 'exists:schedules,id'],

            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'appointment_time' => ['required', 'date_format:H:i'],

            'medicine_type' => ['required', 'in:puyer,tablet,syrup'],

        ];
    }

    /**
     * Custom pesan error.
     */
    public function messages(): array
    {
        return [
            /*
            |--------------------------------------------------------------------------
            | Identitas Anak
            |--------------------------------------------------------------------------
            */
            'child_name.required' => 'Nama lengkap anak wajib diisi.',

            'child_age.required' => 'Umur anak wajib diisi.',
            'child_age.integer' => 'Umur anak harus berupa angka.',
            'child_age.min' => 'Umur anak minimal 1 tahun.',
            'child_age.max' => 'Umur anak tidak boleh lebih dari 60 tahun.',

            'child_weight.required' => 'Berat badan anak wajib diisi.',
            'child_weight.integer' => 'Berat badan harus berupa angka bulat.',
            'child_weight.min' => 'Berat badan anak tidak valid.',
            'child_weight.max' => 'Berat badan anak terlalu besar.',

            /*
            |--------------------------------------------------------------------------
            | Alamat Wilayah
            |--------------------------------------------------------------------------
            */
            'province_code.required' => 'Provinsi wajib dipilih.',
            'province_name.required' => 'Nama provinsi tidak terbaca, silakan pilih ulang provinsi.',

            'city_code.required' => 'Kabupaten/Kota wajib dipilih.',
            'city_name.required' => 'Nama kabupaten/kota tidak terbaca, silakan pilih ulang kabupaten/kota.',

            'district_code.required' => 'Kecamatan wajib dipilih.',
            'district_name.required' => 'Nama kecamatan tidak terbaca, silakan pilih ulang kecamatan.',

            'village_code.required' => 'Desa/Kelurahan wajib dipilih.',
            'village_name.required' => 'Nama desa/kelurahan tidak terbaca, silakan pilih ulang desa/kelurahan.',

            /*
            |--------------------------------------------------------------------------
            | Identitas Orang Tua
            |--------------------------------------------------------------------------
            */
            'father_name.required' => 'Nama ayah wajib diisi.',
            'mother_name.required' => 'Nama ibu wajib diisi.',
            'phone.required' => 'Nomor HP/WA wajib diisi.',

            /*
            |--------------------------------------------------------------------------
            | Foto Anak
            |--------------------------------------------------------------------------
            */
            'child_photo.image' => 'File foto anak harus berupa gambar.',
            'child_photo.mimes' => 'Foto anak hanya boleh JPG, JPEG, atau PNG.',
            'child_photo.max' => 'Ukuran foto maksimal 5MB.',

            /*
            |--------------------------------------------------------------------------
            | Data Appointment
            |--------------------------------------------------------------------------
            */

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

    /**
     * Nama atribut agar error lebih rapi.
     */
    public function attributes(): array
    {
        return [
            /*
            |--------------------------------------------------------------------------
            | Identitas Anak
            |--------------------------------------------------------------------------
            */
            'child_name' => 'nama anak',
            'child_age' => 'umur anak',
            'child_weight' => 'berat badan anak',
            'drug_allergy' => 'alergi obat',
            'bleeding_history' => 'riwayat perdarahan',
            'surgery_history' => 'riwayat operasi',
            'disease_history' => 'riwayat penyakit',

            /*
            |--------------------------------------------------------------------------
            | Alamat Wilayah
            |--------------------------------------------------------------------------
            */
            'province_code' => 'provinsi',
            'province_name' => 'nama provinsi',

            'city_code' => 'kabupaten/kota',
            'city_name' => 'nama kabupaten/kota',

            'district_code' => 'kecamatan',
            'district_name' => 'nama kecamatan',

            'village_code' => 'desa/kelurahan',
            'village_name' => 'nama desa/kelurahan',

            /*
            |--------------------------------------------------------------------------
            | Identitas Orang Tua
            |--------------------------------------------------------------------------
            */
            'father_name' => 'nama ayah',
            'mother_name' => 'nama ibu',
            'phone' => 'nomor HP',
            'instagram' => 'instagram',
            'facebook' => 'facebook',
            'information_source' => 'sumber informasi',

            /*
            |--------------------------------------------------------------------------
            | Foto dan Appointment
            |--------------------------------------------------------------------------
            */
            'child_photo' => 'foto anak',
            'doctor_id' => 'dokter',
            'service_id' => 'layanan',
            'schedule_id' => 'jadwal',
            'appointment_date' => 'tanggal khitan',
            'appointment_time' => 'jam khitan',
            'medicine_type' => 'jenis obat',
            'circumcision_package' => 'paket khitan',
        ];
    }
}