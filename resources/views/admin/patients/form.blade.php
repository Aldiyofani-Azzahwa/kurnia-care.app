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

            <input type="text" name="child_name" value="{{ old('child_name', $patient->child_name ?? '') }}"
                class="w-full h-12 text-sm rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">
                Umur Anak
            </label>

            <input type="number" name="child_age" value="{{ old('child_age', $patient->child_age ?? '') }}" min="1"
                max="60" step="1" inputmode="numeric"
                class="w-full h-12 text-sm rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">
                Berat Badan
            </label>

            <input type="number" name="child_weight" value="{{ old('child_weight', $patient->child_weight ?? '') }}"
                min="1" max="200" step="1" inputmode="numeric"
                class="w-full h-12 text-sm rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
        </div>

        @if (!isset($patient))
            <div>
                <label class="block text-sm font-medium mb-1">
                    Foto Anak
                </label>

                <input type="file" name="child_photo"
                    class="w-full text-sm rounded-lg border border-gray-400 bg-white px-4 py-2">

                <p class="text-xs text-gray-500 mt-1">
                    JPG/PNG maksimal 5MB.
                </p>
            </div>
        @endif

        <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-1">
                Punya Alergi Obat?
            </label>

            <textarea name="drug_allergy" rows="2"
                class="w-full text-sm rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">{{ old('drug_allergy', $patient->drug_allergy ?? '') }}</textarea>
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-1">
                Pernah Luka / Perdarahan Sulit Berhenti?
            </label>

            <textarea name="bleeding_history" rows="2"
                class="w-full text-sm rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">{{ old('bleeding_history', $patient->bleeding_history ?? '') }}</textarea>
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-1">
                Pernah Operasi?
            </label>

            <textarea name="surgery_history" rows="2"
                class="w-full text-sm rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">{{ old('surgery_history', $patient->surgery_history ?? '') }}</textarea>
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-1">
                Riwayat Penyakit
            </label>

            <textarea name="disease_history" rows="2"
                class="w-full text-sm rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">{{ old('disease_history', $patient->disease_history ?? '') }}</textarea>
        </div>
    </div>
</div>

{{-- ALAMAT PASIEN --}}
<div class="bg-white rounded-xl shadow-sm p-6">
    <h3 class="text-lg font-semibold mb-4 text-emerald-700">
        Alamat Pasien
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- PROVINSI --}}
        <div class="relative">
            <label class="block text-sm font-medium mb-1">
                Provinsi
            </label>

            <input type="text" id="province_search" value="{{ old('province_name', $patient->province_name ?? '') }}"
                placeholder="Ketik provinsi, contoh: Jawa Timur" autocomplete="off"
                class="region-search-input w-full h-12 text-sm rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">

            <div id="province_dropdown"
                class="region-dropdown hidden absolute z-50 mt-1 w-full bg-white border border-emerald-200 rounded-xl shadow-lg max-h-60 overflow-y-auto">
            </div>

            <input type="hidden" name="province_code" id="province_code"
                value="{{ old('province_code', $patient->province_code ?? '') }}">

            <input type="hidden" name="province_name" id="province_name"
                value="{{ old('province_name', $patient->province_name ?? '') }}">
        </div>

        {{-- KABUPATEN / KOTA --}}
        <div class="relative">
            <label class="block text-sm font-medium mb-1">
                Kabupaten / Kota
            </label>

            <input type="text" id="city_search" value="{{ old('city_name', $patient->city_name ?? '') }}"
                placeholder="Pilih provinsi dulu" autocomplete="off" disabled
                class="region-search-input w-full h-12 text-sm rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none disabled:bg-gray-100 disabled:text-gray-400">

            <div id="city_dropdown"
                class="region-dropdown hidden absolute z-50 mt-1 w-full bg-white border border-emerald-200 rounded-xl shadow-lg max-h-60 overflow-y-auto">
            </div>

            <input type="hidden" name="city_code" id="city_code"
                value="{{ old('city_code', $patient->city_code ?? '') }}">

            <input type="hidden" name="city_name" id="city_name"
                value="{{ old('city_name', $patient->city_name ?? '') }}">
        </div>

        {{-- KECAMATAN --}}
        <div class="relative">
            <label class="block text-sm font-medium mb-1">
                Kecamatan
            </label>

            <input type="text" id="district_search" value="{{ old('district_name', $patient->district_name ?? '') }}"
                placeholder="Pilih kabupaten/kota dulu" autocomplete="off" disabled
                class="region-search-input w-full h-12 text-sm rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none disabled:bg-gray-100 disabled:text-gray-400">

            <div id="district_dropdown"
                class="region-dropdown hidden absolute z-50 mt-1 w-full bg-white border border-emerald-200 rounded-xl shadow-lg max-h-60 overflow-y-auto">
            </div>

            <input type="hidden" name="district_code" id="district_code"
                value="{{ old('district_code', $patient->district_code ?? '') }}">

            <input type="hidden" name="district_name" id="district_name"
                value="{{ old('district_name', $patient->district_name ?? '') }}">
        </div>

        {{-- DESA / KELURAHAN --}}
        <div class="relative">
            <label class="block text-sm font-medium mb-1">
                Desa / Kelurahan
            </label>

            <input type="text" id="village_search" value="{{ old('village_name', $patient->village_name ?? '') }}"
                placeholder="Pilih kecamatan dulu" autocomplete="off" disabled
                class="region-search-input w-full h-12 text-sm rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none disabled:bg-gray-100 disabled:text-gray-400">

            <div id="village_dropdown"
                class="region-dropdown hidden absolute z-50 mt-1 w-full bg-white border border-emerald-200 rounded-xl shadow-lg max-h-60 overflow-y-auto">
            </div>

            <input type="hidden" name="village_code" id="village_code"
                value="{{ old('village_code', $patient->village_code ?? '') }}">

            <input type="hidden" name="village_name" id="village_name"
                value="{{ old('village_name', $patient->village_name ?? '') }}">
        </div>
    </div>

    <p class="text-xs text-gray-500 mt-3">
        Ketik nama wilayah lalu pilih dari daftar yang muncul agar data alamat tersimpan.
    </p>
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

            <input type="text" name="father_name" value="{{ old('father_name', $patient->father_name ?? '') }}"
                class="w-full h-12 text-sm rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">
                Nama Ibu
            </label>

            <input type="text" name="mother_name" value="{{ old('mother_name', $patient->mother_name ?? '') }}"
                class="w-full h-12 text-sm rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">
                No HP / WA
            </label>

            <input type="text" name="phone" value="{{ old('phone', $patient->phone ?? '') }}"
                class="w-full h-12 text-sm rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">
                Instagram Opsional
            </label>

            <input type="text" name="instagram" value="{{ old('instagram', $patient->instagram ?? '') }}"
                placeholder="Boleh dikosongkan"
                class="w-full h-12 text-sm rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">
                Facebook Opsional
            </label>

            <input type="text" name="facebook" value="{{ old('facebook', $patient->facebook ?? '') }}"
                placeholder="Boleh dikosongkan"
                class="w-full h-12 text-sm rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">
                Dapat Informasi Dari
            </label>

            <select name="information_source"
                class="w-full h-12 text-sm rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
                <option value="">Pilih sumber informasi</option>

                @foreach (['Instagram', 'Facebook', 'Google', 'Lainnya'] as $source)
                    <option value="{{ $source }}" @selected(old('information_source', $patient->information_source ?? '') === $source)>
                        {{ $source }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>