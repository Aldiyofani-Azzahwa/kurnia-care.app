@extends('layouts.admin')

@section('title', 'Tambah Pasien Offline')

@section('content')

    @if (session('error'))
        <div class="mb-6 rounded-lg border border-red-300 bg-red-100 px-4 py-3 text-red-700">
            {{ session('error') }}

            @if (session('nearest_date'))
                <div class="mt-2 text-sm">
                    Rekomendasi jadwal terdekat:
                    <strong>{{ session('nearest_date') }}</strong>
                </div>
            @endif
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-red-300 bg-red-100 px-4 py-3 text-red-700">
            <strong>Data belum lengkap.</strong>

            <ul class="mt-2 list-inside list-disc text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h3 class="text-xl font-bold text-emerald-700">
                Tambah Pasien Offline
            </h3>

            <p class="text-sm text-gray-500">
                Form ini digunakan admin untuk mendaftarkan pasien yang datang langsung ke klinik.
            </p>
        </div>

        <a href="{{ route('admin.patients.index') }}"
            class="rounded-lg bg-gray-200 px-5 py-3 text-center text-gray-700 hover:bg-gray-300">
            Kembali
        </a>
    </div>

    <form action="{{ route('admin.patients.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- DATA ANAK --}}
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-lg font-semibold text-emerald-700">
                Data Anak
            </h3>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium">
                        Nama Anak
                    </label>

                    <input
                        type="text"
                        name="child_name"
                        value="{{ old('child_name') }}"
                        placeholder="Contoh: Muhammad Rizky"
                        class="h-12 w-full rounded-lg border border-gray-400 bg-white px-4 py-2 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                    >

                    @error('child_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">
                        Umur Anak
                    </label>

                    <input
                        type="number"
                        name="child_age"
                        value="{{ old('child_age') }}"
                        placeholder="Contoh: 7"
                        min="1"
                        max="60"
                        class="h-12 w-full rounded-lg border border-gray-400 bg-white px-4 py-2 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                    >

                    @error('child_age')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">
                        Berat Badan
                    </label>

                    <input
                        type="number"
                        step="0.1"
                        name="child_weight"
                        value="{{ old('child_weight') }}"
                        placeholder="Contoh: 25"
                        min="1"
                        max="200"
                        class="h-12 w-full rounded-lg border border-gray-400 bg-white px-4 py-2 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                    >

                    @error('child_weight')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">
                        Foto Anak
                    </label>

                    <input
                        type="file"
                        name="child_photo"
                        accept="image/png,image/jpeg,image/jpg"
                        class="w-full rounded-lg border border-gray-400 bg-white px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                    >

                    <p class="mt-1 text-xs text-gray-500">
                        JPG/PNG maksimal 5MB.
                    </p>

                    @error('child_photo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- RIWAYAT KESEHATAN --}}
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-lg font-semibold text-emerald-700">
                Riwayat Kesehatan
            </h3>

            <div class="space-y-6">
                <div>
                    <label class="mb-1 block text-sm font-medium">
                        Punya Alergi Obat?
                    </label>

                    <select
                        name="drug_allergy_status"
                        data-target="drug_allergy_field"
                        data-textarea="drug_allergy"
                        class="health-status h-12 w-full rounded-lg border border-gray-400 bg-white px-4 py-2 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                    >
                        <option value="Ya" @selected(old('drug_allergy_status', old('drug_allergy') ? 'Ya' : 'Ya') === 'Ya')>
                            Ya
                        </option>

                        <option value="Tidak" @selected(old('drug_allergy_status') === 'Tidak')>
                            Tidak
                        </option>
                    </select>

                    <div id="drug_allergy_field" class="mt-2">
                        <textarea
                            name="drug_allergy"
                            id="drug_allergy"
                            rows="3"
                            placeholder="Jelaskan alergi obat... (contoh: Alergi Amoxicillin)"
                            class="w-full rounded-lg border border-gray-400 bg-white px-4 py-2 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                        >{{ old('drug_allergy') }}</textarea>
                    </div>

                    @error('drug_allergy')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">
                        Pernah Luka / Perdarahan Sulit Berhenti?
                    </label>

                    <select
                        name="bleeding_history_status"
                        data-target="bleeding_history_field"
                        data-textarea="bleeding_history"
                        class="health-status h-12 w-full rounded-lg border border-gray-400 bg-white px-4 py-2 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                    >
                        <option value="Ya" @selected(old('bleeding_history_status', old('bleeding_history') ? 'Ya' : 'Ya') === 'Ya')>
                            Ya
                        </option>

                        <option value="Tidak" @selected(old('bleeding_history_status') === 'Tidak')>
                            Tidak
                        </option>
                    </select>

                    <div id="bleeding_history_field" class="mt-2">
                        <textarea
                            name="bleeding_history"
                            id="bleeding_history"
                            rows="3"
                            placeholder="Jelaskan riwayat perdarahan..."
                            class="w-full rounded-lg border border-gray-400 bg-white px-4 py-2 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                        >{{ old('bleeding_history') }}</textarea>
                    </div>

                    @error('bleeding_history')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">
                        Pernah Operasi?
                    </label>

                    <select
                        name="surgery_history_status"
                        data-target="surgery_history_field"
                        data-textarea="surgery_history"
                        class="health-status h-12 w-full rounded-lg border border-gray-400 bg-white px-4 py-2 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                    >
                        <option value="Ya" @selected(old('surgery_history_status', old('surgery_history') ? 'Ya' : 'Ya') === 'Ya')>
                            Ya
                        </option>

                        <option value="Tidak" @selected(old('surgery_history_status') === 'Tidak')>
                            Tidak
                        </option>
                    </select>

                    <div id="surgery_history_field" class="mt-2">
                        <textarea
                            name="surgery_history"
                            id="surgery_history"
                            rows="3"
                            placeholder="Jelaskan riwayat operasi..."
                            class="w-full rounded-lg border border-gray-400 bg-white px-4 py-2 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                        >{{ old('surgery_history') }}</textarea>
                    </div>

                    @error('surgery_history')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">
                        Riwayat Penyakit
                    </label>

                    <select
                        name="disease_history_status"
                        data-target="disease_history_field"
                        data-textarea="disease_history"
                        class="health-status h-12 w-full rounded-lg border border-gray-400 bg-white px-4 py-2 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                    >
                        <option value="Ya" @selected(old('disease_history_status', old('disease_history') ? 'Ya' : 'Ya') === 'Ya')>
                            Ya
                        </option>

                        <option value="Tidak" @selected(old('disease_history_status') === 'Tidak')>
                            Tidak
                        </option>
                    </select>

                    <div id="disease_history_field" class="mt-2">
                        <textarea
                            name="disease_history"
                            id="disease_history"
                            rows="3"
                            placeholder="Jelaskan riwayat penyakit..."
                            class="w-full rounded-lg border border-gray-400 bg-white px-4 py-2 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                        >{{ old('disease_history') }}</textarea>
                    </div>

                    @error('disease_history')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- DATA ORANG TUA --}}
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-lg font-semibold text-emerald-700">
                Data Orang Tua / Wali
            </h3>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium">
                        Nama Ayah
                    </label>

                    <input
                        type="text"
                        name="father_name"
                        value="{{ old('father_name') }}"
                        placeholder="Contoh: Bapak Ahmad"
                        class="h-12 w-full rounded-lg border border-gray-400 bg-white px-4 py-2 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                    >

                    @error('father_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">
                        Nama Ibu
                    </label>

                    <input
                        type="text"
                        name="mother_name"
                        value="{{ old('mother_name') }}"
                        placeholder="Contoh: Ibu Siti"
                        class="h-12 w-full rounded-lg border border-gray-400 bg-white px-4 py-2 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                    >

                    @error('mother_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">
                        Nomor HP / WhatsApp
                    </label>

                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone') }}"
                        placeholder="Contoh: 082285662642"
                        class="h-12 w-full rounded-lg border border-gray-400 bg-white px-4 py-2 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                    >

                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">
                        Instagram
                    </label>

                    <input
                        type="text"
                        name="instagram"
                        value="{{ old('instagram') }}"
                        placeholder="Contoh: @namaakun"
                        class="h-12 w-full rounded-lg border border-gray-400 bg-white px-4 py-2 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                    >
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">
                        Facebook
                    </label>

                    <input
                        type="text"
                        name="facebook"
                        value="{{ old('facebook') }}"
                        placeholder="Contoh: Nama Facebook"
                        class="h-12 w-full rounded-lg border border-gray-400 bg-white px-4 py-2 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                    >
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">
                        Sumber Informasi
                    </label>

                    <select
                        name="information_source"
                        class="h-12 w-full rounded-lg border border-gray-400 bg-white px-4 py-2 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                    >
                        <option value="">Pilih sumber informasi</option>
                        <option value="Instagram" @selected(old('information_source') === 'Instagram')>Instagram</option>
                        <option value="Facebook" @selected(old('information_source') === 'Facebook')>Facebook</option>
                        <option value="Google" @selected(old('information_source') === 'Google')>Google</option>
                        <option value="Lainnya" @selected(old('information_source') === 'Lainnya')>Lainnya</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- ALAMAT PASIEN --}}
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-lg font-semibold text-emerald-700">
                Alamat Pasien
            </h3>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="relative">
                    <label class="mb-1 block text-sm font-medium">
                        Provinsi
                    </label>

                    <input
                        type="text"
                        id="province_search"
                        value="{{ old('province_name') }}"
                        autocomplete="off"
                        placeholder="Ketik provinsi, contoh: Jawa Timur"
                        class="h-12 w-full rounded-lg border border-gray-400 bg-white px-4 py-2 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                    >

                    <div
                        id="province_dropdown"
                        class="region-dropdown hidden absolute z-50 mt-1 max-h-60 w-full overflow-y-auto rounded-lg border border-emerald-100 bg-white shadow-lg"
                    ></div>

                    <input type="hidden" name="province_code" id="province_code" value="{{ old('province_code') }}">
                    <input type="hidden" name="province_name" id="province_name" value="{{ old('province_name') }}">

                    @error('province_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="relative">
                    <label class="mb-1 block text-sm font-medium">
                        Kabupaten / Kota
                    </label>

                    <input
                        type="text"
                        id="city_search"
                        value="{{ old('city_name') }}"
                        autocomplete="off"
                        placeholder="Pilih provinsi dulu"
                        disabled
                        class="h-12 w-full rounded-lg border border-gray-400 bg-white px-4 py-2 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 disabled:bg-gray-100"
                    >

                    <div
                        id="city_dropdown"
                        class="region-dropdown hidden absolute z-50 mt-1 max-h-60 w-full overflow-y-auto rounded-lg border border-emerald-100 bg-white shadow-lg"
                    ></div>

                    <input type="hidden" name="city_code" id="city_code" value="{{ old('city_code') }}">
                    <input type="hidden" name="city_name" id="city_name" value="{{ old('city_name') }}">

                    @error('city_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="relative">
                    <label class="mb-1 block text-sm font-medium">
                        Kecamatan
                    </label>

                    <input
                        type="text"
                        id="district_search"
                        value="{{ old('district_name') }}"
                        autocomplete="off"
                        placeholder="Pilih kabupaten/kota dulu"
                        disabled
                        class="h-12 w-full rounded-lg border border-gray-400 bg-white px-4 py-2 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 disabled:bg-gray-100"
                    >

                    <div
                        id="district_dropdown"
                        class="region-dropdown hidden absolute z-50 mt-1 max-h-60 w-full overflow-y-auto rounded-lg border border-emerald-100 bg-white shadow-lg"
                    ></div>

                    <input type="hidden" name="district_code" id="district_code" value="{{ old('district_code') }}">
                    <input type="hidden" name="district_name" id="district_name" value="{{ old('district_name') }}">

                    @error('district_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="relative">
                    <label class="mb-1 block text-sm font-medium">
                        Desa / Kelurahan
                    </label>

                    <input
                        type="text"
                        id="village_search"
                        value="{{ old('village_name') }}"
                        autocomplete="off"
                        placeholder="Pilih kecamatan dulu"
                        disabled
                        class="h-12 w-full rounded-lg border border-gray-400 bg-white px-4 py-2 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 disabled:bg-gray-100"
                    >

                    <div
                        id="village_dropdown"
                        class="region-dropdown hidden absolute z-50 mt-1 max-h-60 w-full overflow-y-auto rounded-lg border border-emerald-100 bg-white shadow-lg"
                    ></div>

                    <input type="hidden" name="village_code" id="village_code" value="{{ old('village_code') }}">
                    <input type="hidden" name="village_name" id="village_name" value="{{ old('village_name') }}">

                    @error('village_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- DATA KHITAN --}}
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-lg font-semibold text-emerald-700">
                Data Khitan
            </h3>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium">
                        Layanan / Paket
                    </label>

                    <select
                        name="service_id"
                        class="h-12 w-full rounded-lg border border-gray-400 bg-white px-4 py-2 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                    >
                        <option value="">Pilih layanan</option>

                        @foreach ($services as $service)
                            <option value="{{ $service->id }}" @selected(old('service_id') == $service->id)>
                                {{ $service->name }} - Rp{{ number_format($service->price, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>

                    @error('service_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">
                        Tanggal Khitan
                    </label>

                    <input
                        type="date"
                        id="appointment_date"
                        name="appointment_date"
                        value="{{ old('appointment_date') }}"
                        min="{{ now()->format('Y-m-d') }}"
                        class="h-12 w-full rounded-lg border border-gray-400 bg-white px-4 py-2 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                    >

                    <div id="quota-message" class="mt-2 text-sm"></div>

                    @error('appointment_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">
                        Jam Khitan
                    </label>

                    <input
                        type="time"
                        name="appointment_time"
                        value="{{ old('appointment_time') }}"
                        class="h-12 w-full rounded-lg border border-gray-400 bg-white px-4 py-2 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                    >

                    @error('appointment_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">
                        Jenis Obat
                    </label>

                    <select
                        name="medicine_type"
                        class="h-12 w-full rounded-lg border border-gray-400 bg-white px-4 py-2 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                    >
                        <option value="">Pilih obat</option>
                        <option value="puyer" @selected(old('medicine_type') === 'puyer')>Puyer</option>
                        <option value="tablet" @selected(old('medicine_type') === 'tablet')>Tablet</option>
                        <option value="syrup" @selected(old('medicine_type') === 'syrup')>Syrup</option>
                    </select>

                    @error('medicine_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- KETERSEDIAAN JADWAL --}}
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-lg font-semibold text-emerald-700">
                Ketersediaan Jadwal 14 Hari
            </h3>

            <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
                @foreach ($availableDates as $date)
                    <div
                        class="rounded-lg border p-3 {{ $date['is_full'] ? 'border-red-200 bg-red-50' : 'border-emerald-200 bg-emerald-50' }}"
                    >
                        <p class="text-sm font-semibold">
                            {{ $date['date'] }}
                        </p>

                        <p class="mt-1 text-xs">
                            Sisa kuota: {{ $date['remaining_quota'] }}
                        </p>

                        <p class="mt-1 text-xs font-semibold {{ $date['is_full'] ? 'text-red-600' : 'text-emerald-600' }}">
                            {{ $date['is_full'] ? 'Penuh' : 'Tersedia' }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- BUTTON --}}
        <div class="flex flex-col gap-3 pt-4 md:flex-row md:justify-end">
            <a href="{{ route('admin.patients.index') }}"
                class="rounded-lg bg-gray-200 px-5 py-3 text-center text-gray-700 hover:bg-gray-300">
                Dibatalkan
            </a>

            <button
                type="submit"
                class="rounded-lg bg-emerald-600 px-5 py-3 font-semibold text-white hover:bg-emerald-700"
            >
                Simpan Pendaftaran
            </button>
        </div>
    </form>

    <style>
        .region-dropdown::-webkit-scrollbar {
            width: 8px;
        }

        .region-dropdown::-webkit-scrollbar-track {
            background: #ecfdf5;
            border-radius: 999px;
        }

        .region-dropdown::-webkit-scrollbar-thumb {
            background: #10b981;
            border-radius: 999px;
        }

        .region-option {
            padding: 10px 14px;
            cursor: pointer;
            font-size: 14px;
            color: #064e3b;
            background: #ffffff;
            border-bottom: 1px solid #ecfdf5;
            transition: all 0.15s ease;
        }

        .region-option:hover {
            background: #ecfdf5;
            color: #047857;
            padding-left: 18px;
        }

        .region-option-empty {
            padding: 10px 14px;
            font-size: 14px;
            color: #6b7280;
            background: #f9fafb;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            /*
            |--------------------------------------------------------------------------
            | DROPDOWN RIWAYAT KESEHATAN
            |--------------------------------------------------------------------------
            */
            const healthSelects = document.querySelectorAll('.health-status');

            function syncHealthField(select) {
                const targetId = select.dataset.target;
                const textareaId = select.dataset.textarea;

                const target = document.getElementById(targetId);
                const textarea = document.getElementById(textareaId);

                if (!target || !textarea) {
                    return;
                }

                if (select.value === 'Ya') {
                    target.classList.remove('hidden');
                    textarea.disabled = false;
                } else {
                    target.classList.add('hidden');
                    textarea.disabled = true;
                    textarea.value = '';
                }
            }

            healthSelects.forEach(function (select) {
                syncHealthField(select);

                select.addEventListener('change', function () {
                    syncHealthField(select);
                });
            });

            /*
            |--------------------------------------------------------------------------
            | CUSTOM DROPDOWN WILAYAH
            |--------------------------------------------------------------------------
            */
            const provinceSearch = document.getElementById('province_search');
            const citySearch = document.getElementById('city_search');
            const districtSearch = document.getElementById('district_search');
            const villageSearch = document.getElementById('village_search');

            const provinceDropdown = document.getElementById('province_dropdown');
            const cityDropdown = document.getElementById('city_dropdown');
            const districtDropdown = document.getElementById('district_dropdown');
            const villageDropdown = document.getElementById('village_dropdown');

            const provinceCode = document.getElementById('province_code');
            const provinceName = document.getElementById('province_name');

            const cityCode = document.getElementById('city_code');
            const cityName = document.getElementById('city_name');

            const districtCode = document.getElementById('district_code');
            const districtName = document.getElementById('district_name');

            const villageCode = document.getElementById('village_code');
            const villageName = document.getElementById('village_name');

            const appointmentDateInput = document.getElementById('appointment_date');
            const quotaMessage = document.getElementById('quota-message');

            let provinces = [];
            let cities = [];
            let districts = [];
            let villages = [];

            function normalize(text) {
                return String(text || '').trim().toLowerCase();
            }

            function filterData(data, keyword) {
                const search = normalize(keyword);

                if (!search) {
                    return data.slice(0, 30);
                }

                return data.filter(function (item) {
                    return normalize(item.name).includes(search);
                }).slice(0, 30);
            }

            function showDropdown(dropdown) {
                dropdown.classList.remove('hidden');
            }

            function hideDropdown(dropdown) {
                dropdown.classList.add('hidden');
            }

            function hideAllDropdowns() {
                hideDropdown(provinceDropdown);
                hideDropdown(cityDropdown);
                hideDropdown(districtDropdown);
                hideDropdown(villageDropdown);
            }

            function renderDropdown(dropdown, data, onSelect) {
                dropdown.innerHTML = '';

                if (data.length === 0) {
                    const empty = document.createElement('div');
                    empty.className = 'region-option-empty';
                    empty.textContent = 'Data tidak ditemukan';
                    dropdown.appendChild(empty);
                    showDropdown(dropdown);
                    return;
                }

                data.forEach(function (item) {
                    const option = document.createElement('div');
                    option.className = 'region-option';
                    option.textContent = item.name;

                    option.addEventListener('click', function () {
                        onSelect(item);
                        hideDropdown(dropdown);
                    });

                    dropdown.appendChild(option);
                });

                showDropdown(dropdown);
            }

            async function fetchJson(url) {
                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Gagal mengambil data dari ' + url);
                }

                return await response.json();
            }

            function resetCity() {
                cities = [];

                citySearch.value = '';
                citySearch.disabled = true;
                citySearch.placeholder = 'Pilih provinsi dulu';

                cityCode.value = '';
                cityName.value = '';

                cityDropdown.innerHTML = '';
                hideDropdown(cityDropdown);
            }

            function resetDistrict() {
                districts = [];

                districtSearch.value = '';
                districtSearch.disabled = true;
                districtSearch.placeholder = 'Pilih kabupaten/kota dulu';

                districtCode.value = '';
                districtName.value = '';

                districtDropdown.innerHTML = '';
                hideDropdown(districtDropdown);
            }

            function resetVillage() {
                villages = [];

                villageSearch.value = '';
                villageSearch.disabled = true;
                villageSearch.placeholder = 'Pilih kecamatan dulu';

                villageCode.value = '';
                villageName.value = '';

                villageDropdown.innerHTML = '';
                hideDropdown(villageDropdown);
            }

            async function loadProvinces() {
                try {
                    provinceSearch.placeholder = 'Memuat provinsi...';

                    provinces = await fetchJson("{{ route('regions.provinces') }}");

                    provinceSearch.placeholder = 'Ketik provinsi, contoh: Jawa Timur';
                } catch (error) {
                    console.error(error);
                    provinceSearch.placeholder = 'Gagal memuat provinsi';
                }
            }

            async function loadCities(selectedProvinceCode) {
                try {
                    citySearch.disabled = true;
                    citySearch.placeholder = 'Memuat kabupaten/kota...';

                    cities = await fetchJson(`/regions/cities/${selectedProvinceCode}`);

                    citySearch.disabled = false;
                    citySearch.placeholder = 'Ketik kabupaten/kota';
                } catch (error) {
                    console.error(error);
                    citySearch.placeholder = 'Gagal memuat kabupaten/kota';
                }
            }

            async function loadDistricts(selectedCityCode) {
                try {
                    districtSearch.disabled = true;
                    districtSearch.placeholder = 'Memuat kecamatan...';

                    districts = await fetchJson(`/regions/districts/${selectedCityCode}`);

                    districtSearch.disabled = false;
                    districtSearch.placeholder = 'Ketik kecamatan';
                } catch (error) {
                    console.error(error);
                    districtSearch.placeholder = 'Gagal memuat kecamatan';
                }
            }

            async function loadVillages(selectedDistrictCode) {
                try {
                    villageSearch.disabled = true;
                    villageSearch.placeholder = 'Memuat desa/kelurahan...';

                    villages = await fetchJson(`/regions/villages/${selectedDistrictCode}`);

                    villageSearch.disabled = false;
                    villageSearch.placeholder = 'Ketik desa/kelurahan';
                } catch (error) {
                    console.error(error);
                    villageSearch.placeholder = 'Gagal memuat desa/kelurahan';
                }
            }

            provinceSearch.addEventListener('focus', function () {
                renderDropdown(
                    provinceDropdown,
                    filterData(provinces, provinceSearch.value),
                    async function (item) {
                        provinceSearch.value = item.name;
                        provinceCode.value = item.code;
                        provinceName.value = item.name;

                        resetCity();
                        resetDistrict();
                        resetVillage();

                        await loadCities(item.code);
                    }
                );
            });

            provinceSearch.addEventListener('input', function () {
                provinceCode.value = '';
                provinceName.value = '';

                resetCity();
                resetDistrict();
                resetVillage();

                renderDropdown(
                    provinceDropdown,
                    filterData(provinces, provinceSearch.value),
                    async function (item) {
                        provinceSearch.value = item.name;
                        provinceCode.value = item.code;
                        provinceName.value = item.name;

                        resetCity();
                        resetDistrict();
                        resetVillage();

                        await loadCities(item.code);
                    }
                );
            });

            citySearch.addEventListener('focus', function () {
                if (citySearch.disabled) return;

                renderDropdown(
                    cityDropdown,
                    filterData(cities, citySearch.value),
                    async function (item) {
                        citySearch.value = item.name;
                        cityCode.value = item.code;
                        cityName.value = item.name;

                        resetDistrict();
                        resetVillage();

                        await loadDistricts(item.code);
                    }
                );
            });

            citySearch.addEventListener('input', function () {
                cityCode.value = '';
                cityName.value = '';

                resetDistrict();
                resetVillage();

                renderDropdown(
                    cityDropdown,
                    filterData(cities, citySearch.value),
                    async function (item) {
                        citySearch.value = item.name;
                        cityCode.value = item.code;
                        cityName.value = item.name;

                        resetDistrict();
                        resetVillage();

                        await loadDistricts(item.code);
                    }
                );
            });

            districtSearch.addEventListener('focus', function () {
                if (districtSearch.disabled) return;

                renderDropdown(
                    districtDropdown,
                    filterData(districts, districtSearch.value),
                    async function (item) {
                        districtSearch.value = item.name;
                        districtCode.value = item.code;
                        districtName.value = item.name;

                        resetVillage();

                        await loadVillages(item.code);
                    }
                );
            });

            districtSearch.addEventListener('input', function () {
                districtCode.value = '';
                districtName.value = '';

                resetVillage();

                renderDropdown(
                    districtDropdown,
                    filterData(districts, districtSearch.value),
                    async function (item) {
                        districtSearch.value = item.name;
                        districtCode.value = item.code;
                        districtName.value = item.name;

                        resetVillage();

                        await loadVillages(item.code);
                    }
                );
            });

            villageSearch.addEventListener('focus', function () {
                if (villageSearch.disabled) return;

                renderDropdown(
                    villageDropdown,
                    filterData(villages, villageSearch.value),
                    function (item) {
                        villageSearch.value = item.name;
                        villageCode.value = item.code;
                        villageName.value = item.name;
                    }
                );
            });

            villageSearch.addEventListener('input', function () {
                villageCode.value = '';
                villageName.value = '';

                renderDropdown(
                    villageDropdown,
                    filterData(villages, villageSearch.value),
                    function (item) {
                        villageSearch.value = item.name;
                        villageCode.value = item.code;
                        villageName.value = item.name;
                    }
                );
            });

            document.addEventListener('click', function (event) {
                if (!event.target.closest('.relative')) {
                    hideAllDropdowns();
                }
            });

            /*
            |--------------------------------------------------------------------------
            | CEK KUOTA
            |--------------------------------------------------------------------------
            */
            if (appointmentDateInput && quotaMessage) {
                appointmentDateInput.addEventListener('change', async function () {
                    const date = this.value;

                    if (!date) {
                        quotaMessage.innerHTML = '';
                        return;
                    }

                    try {
                        quotaMessage.innerHTML = '<span class="text-gray-500">Mengecek kuota...</span>';

                        const data = await fetchJson(`/admin/patients/check-quota?date=${encodeURIComponent(date)}`);

                        if (data.is_full) {
                            quotaMessage.innerHTML = `
                                <span class="font-medium text-red-600">
                                    Kuota tanggal ini sudah penuh.
                                </span>
                            `;
                        } else {
                            quotaMessage.innerHTML = `
                                <span class="font-medium text-emerald-600">
                                    Sisa kuota: ${data.remaining_quota} pasien.
                                </span>
                            `;
                        }
                    } catch (error) {
                        console.error(error);

                        quotaMessage.innerHTML = `
                            <span class="text-red-600">
                                Gagal mengecek kuota.
                            </span>
                        `;
                    }
                });
            }

            async function initRegions() {
                resetCity();
                resetDistrict();
                resetVillage();

                await loadProvinces();
            }

            initRegions();
        });
    </script>

@endsection