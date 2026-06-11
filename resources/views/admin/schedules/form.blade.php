<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

    <div>
        <label class="block text-sm font-medium mb-1">
            Dokter
        </label>

        <select name="doctor_id"
                class="w-full h-12 text-sm rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
            <option value="">Pilih dokter</option>

            @foreach ($doctors as $doctor)
                <option value="{{ $doctor->id }}"
                    @selected(old('doctor_id', $schedule->doctor_id ?? '') == $doctor->id)>
                    {{ $doctor->name }} - {{ $doctor->specialist ?? 'Dokter Klinik' }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">
            Hari
        </label>

        <select name="day"
                class="w-full h-12 text-sm rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
            <option value="">Pilih hari</option>

            @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $day)
                <option value="{{ $day }}"
                    @selected(old('day', $schedule->day ?? '') === $day)>
                    {{ $day }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">
            Jam Mulai
        </label>

        <input type="time"
               name="start_time"
               value="{{ old('start_time', isset($schedule) ? substr($schedule->start_time, 0, 5) : '') }}"
               class="w-full h-12 text-sm rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">
            Jam Selesai
        </label>

        <input type="time"
               name="end_time"
               value="{{ old('end_time', isset($schedule) ? substr($schedule->end_time, 0, 5) : '') }}"
               class="w-full h-12 text-sm rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">
            Status Jadwal
        </label>

        <select name="is_active"
                class="w-full h-12 text-sm rounded-lg border border-gray-400 bg-white px-4 py-2 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
            <option value="1" @selected(old('is_active', $schedule->is_active ?? 1) == 1)>
                Aktif
            </option>

            <option value="0" @selected(old('is_active', $schedule->is_active ?? 1) == 0)>
                Tidak Aktif
            </option>
        </select>
    </div>
</div>
