<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

    <div>
        <label class="block text-sm font-medium mb-1">
            Nama Dokter
        </label>

        <input type="text"
               name="name"
               value="{{ old('name', $doctor->name ?? '') }}"
               class="w-full h-12 text-sm rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">
            Email Login Dokter
        </label>

        <input type="email"
               name="email"
               value="{{ old('email', $doctor->user->email ?? '') }}"
               class="w-full h-12 text-sm rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">
            Password
        </label>

        <input type="password"
               name="password"
               placeholder="{{ isset($doctor) ? 'Kosongkan jika tidak ingin mengubah password' : 'Minimal 6 karakter' }}"
               class="w-full h-12 text-sm rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">

        @isset($doctor)
            <p class="text-xs text-gray-500 mt-1">
                Kosongkan jika password tidak ingin diubah.
            </p>
        @endisset
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">
            Nomor HP / WA
        </label>

        <input type="text"
               name="phone"
               value="{{ old('phone', $doctor->phone ?? $doctor->user->phone ?? '') }}"
               class="w-full h-12 text-sm rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">
            Spesialis / Keahlian
        </label>

        <input type="text"
               name="specialist"
               value="{{ old('specialist', $doctor->specialist ?? '') }}"
               placeholder="Contoh: Dokter Khitan Modern"
               class="w-full h-12 text-sm rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">
            Nomor SIP
        </label>

        <input type="text"
               name="sip_number"
               value="{{ old('sip_number', $doctor->sip_number ?? '') }}"
               placeholder="Contoh: SIP-001/KC/2026"
               class="w-full h-12 text-sm rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">
            Status Dokter
        </label>

        <select name="is_active"
                class="w-full h-12 text-sm rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
            <option value="1" @selected(old('is_active', $doctor->is_active ?? 1) == 1)>
                Aktif
            </option>
            <option value="0" @selected(old('is_active', $doctor->is_active ?? 1) == 0)>
                Tidak Aktif
            </option>
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">
            Foto Dokter
        </label>

        <input type="file"
               name="photo"
               class="w-full text-sm rounded-lg border border-gray-400 bg-white px-4 py-2">

        <p class="text-xs text-gray-500 mt-1">
            Format JPG/PNG, maksimal 2MB.
        </p>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium mb-1">
            Bio Dokter
        </label>

        <textarea name="bio"
                  rows="4"
                  class="w-full text-sm rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">{{ old('bio', $doctor->bio ?? '') }}</textarea>
    </div>
</div>

@isset($doctor)
    @if ($doctor->photo)
        <div class="mt-6">
            <p class="text-sm font-medium mb-2">Foto Saat Ini</p>

            <img src="{{ asset('storage/' . $doctor->photo) }}"
                 alt="{{ $doctor->name }}"
                 class="w-28 h-28 rounded-xl object-cover border">
        </div>
    @endif
@endisset