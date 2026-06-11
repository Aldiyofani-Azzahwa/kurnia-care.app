@extends('layouts.user')

@section('title', 'Pendaftaran Sunat')

@section('content')

    <style>
        .kurnia-form-field {
            width: 100% !important;
            min-height: 48px !important;
            border-radius: 0.5rem !important;
            border: 1px solid #9ca3af !important;
            background-color: #ffffff !important;
            padding: 0.5rem 1rem !important;
            font-size: 0.875rem !important;
            outline: none !important;
            box-sizing: border-box !important;
        }

        .kurnia-form-field:focus {
            border-color: #10b981 !important;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2) !important;
        }

        textarea.kurnia-form-field {
            min-height: 58px !important;
            resize: vertical;
        }

        .kurnia-form-field:disabled {
            background-color: #f3f4f6 !important;
            color: #9ca3af !important;
            cursor: not-allowed !important;
        }

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

    @if (session('error'))
        <div class="mb-6 rounded-lg bg-red-100 border border-red-300 text-red-700 px-4 py-3">
            {{ session('error') }}

            @if (session('nearest_date'))
                <div class="mt-2">
                    Rekomendasi jadwal terdekat:
                    <strong>{{ session('nearest_date') }}</strong>
                </div>
            @endif
        </div>
    @endif

    @if (session('success'))
        <div class="mb-6 rounded-lg bg-emerald-100 border border-emerald-300 text-emerald-700 px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-lg bg-red-100 border border-red-300 text-red-700 px-4 py-3">
            <strong>Data belum lengkap.</strong>

            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-emerald-700">
                Pendaftaran Sunat Online
            </h3>
            <p class="text-sm text-gray-500">
                Silakan lengkapi data anak, orang tua, alamat, layanan, dan jadwal khitan.
            </p>
        </div>

        <a href="{{ route('user.appointments.index') }}"
            class="px-5 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-center">
            Kembali
        </a>
    </div>

    <form action="{{ route('user.appointments.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- IDENTITAS ANAK --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4 text-emerald-700">
                Identitas Anak
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-medium mb-1">
                        Nama Lengkap Anak
                    </label>

                    <input type="text" name="child_name" value="{{ old('child_name') }}" class="kurnia-form-field">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">
                        Umur Anak
                    </label>

                    <input type="number" name="child_age" value="{{ old('child_age') }}" min="1" max="60" step="1"
                        inputmode="numeric" class="kurnia-form-field">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">
                        Berat Badan
                    </label>

                    <input type="number" name="child_weight" value="{{ old('child_weight') }}" min="1" max="200" step="1"
                        inputmode="numeric" class="kurnia-form-field">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">
                        Foto Anak
                    </label>

                    <input type="file" name="child_photo" class="kurnia-form-field">

                    <p class="text-xs text-gray-500 mt-1">
                        Format JPG/PNG, maksimal 5MB.
                    </p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">
                        Punya Alergi Obat?
                    </label>

                    <textarea name="drug_allergy" rows="2" class="kurnia-form-field">{{ old('drug_allergy') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">
                        Pernah Luka / Perdarahan Sulit Berhenti?
                    </label>

                    <textarea name="bleeding_history" rows="2"
                        class="kurnia-form-field">{{ old('bleeding_history') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">
                        Pernah Operasi?
                    </label>

                    <textarea name="surgery_history" rows="2"
                        class="kurnia-form-field">{{ old('surgery_history') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">
                        Riwayat Penyakit
                    </label>

                    <textarea name="disease_history" rows="2"
                        class="kurnia-form-field">{{ old('disease_history') }}</textarea>
                </div>
            </div>
        </div>

        {{-- ALAMAT WILAYAH --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4 text-emerald-700">
                Alamat Pasien
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- PROVINSI --}}
                <div class="relative region-field">
                    <label class="block text-sm font-medium mb-1">
                        Provinsi
                    </label>

                    <input type="text" id="province_search" value="{{ old('province_name') }}"
                        placeholder="Ketik provinsi, contoh: Jawa Timur" autocomplete="off" class="kurnia-form-field">

                    <div id="province_dropdown"
                        class="region-dropdown hidden absolute z-50 mt-1 w-full bg-white border border-emerald-200 rounded-xl shadow-lg max-h-60 overflow-y-auto">
                    </div>

                    <input type="hidden" name="province_code" id="province_code" value="{{ old('province_code') }}">

                    <input type="hidden" name="province_name" id="province_name" value="{{ old('province_name') }}">
                </div>

                {{-- KABUPATEN / KOTA --}}
                <div class="relative region-field">
                    <label class="block text-sm font-medium mb-1">
                        Kabupaten / Kota
                    </label>

                    <input type="text" id="city_search" value="{{ old('city_name') }}" placeholder="Pilih provinsi dulu"
                        autocomplete="off" disabled class="kurnia-form-field">

                    <div id="city_dropdown"
                        class="region-dropdown hidden absolute z-50 mt-1 w-full bg-white border border-emerald-200 rounded-xl shadow-lg max-h-60 overflow-y-auto">
                    </div>

                    <input type="hidden" name="city_code" id="city_code" value="{{ old('city_code') }}">

                    <input type="hidden" name="city_name" id="city_name" value="{{ old('city_name') }}">
                </div>

                {{-- KECAMATAN --}}
                <div class="relative region-field">
                    <label class="block text-sm font-medium mb-1">
                        Kecamatan
                    </label>

                    <input type="text" id="district_search" value="{{ old('district_name') }}"
                        placeholder="Pilih kabupaten/kota dulu" autocomplete="off" disabled class="kurnia-form-field">

                    <div id="district_dropdown"
                        class="region-dropdown hidden absolute z-50 mt-1 w-full bg-white border border-emerald-200 rounded-xl shadow-lg max-h-60 overflow-y-auto">
                    </div>

                    <input type="hidden" name="district_code" id="district_code" value="{{ old('district_code') }}">

                    <input type="hidden" name="district_name" id="district_name" value="{{ old('district_name') }}">
                </div>

                {{-- DESA / KELURAHAN --}}
                <div class="relative region-field">
                    <label class="block text-sm font-medium mb-1">
                        Desa / Kelurahan
                    </label>

                    <input type="text" id="village_search" value="{{ old('village_name') }}"
                        placeholder="Pilih kecamatan dulu" autocomplete="off" disabled class="kurnia-form-field">

                    <div id="village_dropdown"
                        class="region-dropdown hidden absolute z-50 mt-1 w-full bg-white border border-emerald-200 rounded-xl shadow-lg max-h-60 overflow-y-auto">
                    </div>

                    <input type="hidden" name="village_code" id="village_code" value="{{ old('village_code') }}">

                    <input type="hidden" name="village_name" id="village_name" value="{{ old('village_name') }}">
                </div>
            </div>
        </div>

        {{-- IDENTITAS ORANG TUA --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4 text-emerald-700">
                Identitas Orang Tua
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-medium mb-1">
                        Nama Ayah
                    </label>

                    <input type="text" name="father_name" value="{{ old('father_name') }}" class="kurnia-form-field">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">
                        Nama Ibu
                    </label>

                    <input type="text" name="mother_name" value="{{ old('mother_name') }}" class="kurnia-form-field">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">
                        No HP / WA
                    </label>

                    <input type="text" name="phone" value="{{ old('phone') }}" class="kurnia-form-field">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">
                        Instagram Opsional
                    </label>

                    <input type="text" name="instagram" value="{{ old('instagram') }}" placeholder="Boleh dikosongkan"
                        class="kurnia-form-field">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">
                        Facebook Opsional
                    </label>

                    <input type="text" name="facebook" value="{{ old('facebook') }}" placeholder="Boleh dikosongkan"
                        class="kurnia-form-field">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">
                        Dapat Informasi Dari
                    </label>

                    <select name="information_source" class="kurnia-form-field">
                        <option value="">Pilih sumber informasi</option>
                        <option value="Instagram" @selected(old('information_source') === 'Instagram')>Instagram</option>
                        <option value="Facebook" @selected(old('information_source') === 'Facebook')>Facebook</option>
                        <option value="Google" @selected(old('information_source') === 'Google')>Google</option>
                        <option value="Lainnya" @selected(old('information_source') === 'Lainnya')>Lainnya</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- DATA KHITAN --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4 text-emerald-700">
                Data Khitan
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-medium mb-1">
                        Layanan / Paket
                    </label>

                    <select name="service_id" class="kurnia-form-field">
                        <option value="">Pilih layanan</option>

                        @foreach ($services as $service)
                            <option value="{{ $service->id }}" @selected(old('service_id') == $service->id)>
                                {{ $service->name }} - Rp{{ number_format($service->price, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">
                        Tanggal Khitan
                    </label>

                    <input type="date" id="appointment_date" name="appointment_date" value="{{ old('appointment_date') }}"
                        min="{{ now()->format('Y-m-d') }}" class="kurnia-form-field">

                    <div id="quota-message" class="mt-2 text-sm"></div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">
                        Jam Khitan
                    </label>

                    <input type="time" name="appointment_time" value="{{ old('appointment_time') }}"
                        class="kurnia-form-field">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">
                        Jenis Obat
                    </label>

                    <select name="medicine_type" class="kurnia-form-field">
                        <option value="">Pilih obat</option>
                        <option value="puyer" @selected(old('medicine_type') === 'puyer')>Puyer</option>
                        <option value="tablet" @selected(old('medicine_type') === 'tablet')>Tablet</option>
                        <option value="syrup" @selected(old('medicine_type') === 'syrup')>Syrup</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- KETERSEDIAAN JADWAL --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4 text-emerald-700">
                Ketersediaan Jadwal 14 Hari
            </h3>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @foreach ($availableDates as $date)
                    <div
                        class="rounded-lg border p-3 {{ $date['is_full'] ? 'bg-red-50 border-red-200' : 'bg-emerald-50 border-emerald-200' }}">
                        <p class="text-sm font-semibold">
                            {{ $date['date'] }}
                        </p>

                        <p class="text-xs mt-1">
                            Sisa kuota: {{ $date['remaining_quota'] }}
                        </p>

                        <p class="text-xs mt-1 font-semibold {{ $date['is_full'] ? 'text-red-600' : 'text-emerald-600' }}">
                            {{ $date['is_full'] ? 'Penuh' : 'Tersedia' }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- BUTTON --}}
        <div class="flex flex-col md:flex-row gap-3 md:justify-end pt-4">
            <a href="{{ route('user.appointments.index') }}"
                class="px-5 py-3 text-center bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                Batal
            </a>

            <button type="submit" class="px-5 py-3 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700">
                Simpan Pendaftaran
            </button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log('Custom dropdown wilayah user pasien berjalan');

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

            const appointmentDate = document.getElementById('appointment_date');
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
                if (!event.target.closest('.region-field')) {
                    hideAllDropdowns();
                }
            });

            if (appointmentDate && quotaMessage) {
                appointmentDate.addEventListener('change', async function () {
                    const date = this.value;

                    if (!date) {
                        quotaMessage.innerHTML = '';
                        return;
                    }

                    try {
                        quotaMessage.innerHTML = `
                                    <div class="text-gray-500">
                                        Mengecek kuota...
                                    </div>
                                `;

                        const data = await fetchJson(`/user/check-quota?date=${date}`);

                        if (data.is_full) {
                            quotaMessage.innerHTML = `
                                        <div class="text-red-600 font-medium">
                                            Kuota tanggal ini sudah penuh.
                                        </div>
                                    `;
                        } else {
                            quotaMessage.innerHTML = `
                                        <div class="text-emerald-600 font-medium">
                                            Sisa kuota: ${data.remaining_quota} pasien.
                                        </div>
                                    `;
                        }
                    } catch (error) {
                        console.error(error);

                        quotaMessage.innerHTML = `
                                    <div class="text-red-600">
                                        Gagal mengecek kuota.
                                    </div>
                                `;
                    }
                });
            }

            resetCity();
            resetDistrict();
            resetVillage();
            loadProvinces();
        });
    </script>

@endsection