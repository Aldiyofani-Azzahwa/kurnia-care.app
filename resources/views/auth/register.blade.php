@php
    $logo = asset('images/logo-kurnia-care.png');
    $homeUrl = Route::has('home') ? route('home') : url('/');
    $loginUrl = Route::has('login') ? route('login') : url('/login');
@endphp

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun - Kurnia Care</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }

        .kc-register-bg {
            background:
                radial-gradient(circle at 12% 18%, rgba(4, 120, 87, .28), transparent 30%),
                radial-gradient(circle at 88% 12%, rgba(245, 158, 11, .20), transparent 32%),
                radial-gradient(circle at 48% 100%, rgba(16, 185, 129, .18), transparent 36%),
                linear-gradient(135deg, #ecfdf5 0%, #ffffff 48%, #fffbeb 100%);
        }

        .kc-pattern {
            background-image:
                radial-gradient(rgba(4, 120, 87, .12) 1px, transparent 1px),
                radial-gradient(rgba(245, 158, 11, .10) 1px, transparent 1px);
            background-size: 30px 30px, 46px 46px;
            background-position: 0 0, 18px 18px;
        }

        .kc-auth-card {
            background: rgba(255, 255, 255, .88);
            border: 1px solid rgba(255, 255, 255, .78);
            box-shadow: 0 28px 80px rgba(15, 23, 42, .13);
            backdrop-filter: blur(22px);
        }

        .kc-gold-line {
            background: linear-gradient(90deg, #047857, #f59e0b, #047857);
        }
    </style>
</head>

<body class="kc-register-bg min-h-screen overflow-x-hidden font-sans text-gray-900 antialiased">
    <main class="relative min-h-screen">
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="kc-pattern absolute inset-0 opacity-40"></div>
            <div class="absolute -left-24 top-20 h-80 w-80 rounded-full bg-emerald-300/30 blur-3xl"></div>
            <div class="absolute -right-24 top-24 h-96 w-96 rounded-full bg-amber-300/25 blur-3xl"></div>
            <div class="absolute bottom-0 left-1/3 h-96 w-96 rounded-full bg-emerald-200/40 blur-3xl"></div>
            <div class="absolute left-8 top-32 h-24 w-24 rounded-full border border-amber-300/50"></div>
            <div class="absolute right-16 top-44 h-16 w-16 rounded-full border border-emerald-300/50"></div>
        </div>

        <div class="relative z-10 flex min-h-screen items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
            <div class="grid w-full max-w-6xl overflow-hidden rounded-[2rem] bg-white/55 shadow-2xl ring-1 ring-white/70 backdrop-blur-xl lg:grid-cols-[0.95fr_1.05fr]">

                <section class="relative hidden overflow-hidden bg-gradient-to-br from-emerald-900 via-emerald-700 to-amber-500 p-10 text-white lg:flex lg:flex-col lg:justify-between">
                    <div class="pointer-events-none absolute right-0 top-0 h-48 w-48 rounded-full bg-white/10 blur-3xl"></div>
                    <div class="pointer-events-none absolute bottom-0 left-0 h-52 w-52 rounded-full bg-amber-200/20 blur-3xl"></div>

                    <div class="relative">
                        <a href="{{ $homeUrl }}" class="inline-flex items-center gap-4">
                            <img
                                src="{{ $logo }}"
                                alt="Logo Kurnia Care"
                                class="h-16 w-16 rounded-full bg-white object-contain p-1 shadow-md"
                            >

                            <div>
                                <p class="text-sm font-semibold text-emerald-50">
                                    Kurnia Care
                                </p>
                                <h1 class="text-2xl font-bold">
                                    Klinik Khitan Modern
                                </h1>
                            </div>
                        </a>

                        <div class="mt-14 max-w-lg">
                            <p class="inline-flex rounded-full bg-white/15 px-4 py-2 text-sm font-semibold text-emerald-50 backdrop-blur">
                                Pendaftaran Akun Pasien
                            </p>

                            <h2 class="mt-6 text-4xl font-bold leading-tight">
                                Buat akun untuk mengakses layanan Kurnia Care.
                            </h2>

                            <p class="mt-5 text-base leading-8 text-emerald-50">
                                Daftar akun pasien untuk melakukan pendaftaran layanan, memilih jadwal, mengunggah pembayaran, dan memantau status layanan.
                            </p>
                        </div>
                    </div>

                    <div class="relative grid grid-cols-3 gap-4">
                        <div class="rounded-2xl bg-white/15 p-4 backdrop-blur">
                            <p class="text-2xl font-bold">Daftar</p>
                            <p class="mt-1 text-xs text-emerald-50">
                                Buat akun
                            </p>
                        </div>

                        <div class="rounded-2xl bg-white/15 p-4 backdrop-blur">
                            <p class="text-2xl font-bold">Pilih</p>
                            <p class="mt-1 text-xs text-emerald-50">
                                Jadwal layanan
                            </p>
                        </div>

                        <div class="rounded-2xl bg-white/15 p-4 backdrop-blur">
                            <p class="text-2xl font-bold">Pantau</p>
                            <p class="mt-1 text-xs text-emerald-50">
                                Status pasien
                            </p>
                        </div>
                    </div>
                </section>

                <section class="kc-auth-card p-6 sm:p-8 lg:p-10">
                    <div class="mx-auto max-w-md">
                        <div class="flex items-center justify-between gap-4">
                            <a href="{{ $homeUrl }}" class="inline-flex items-center gap-3">
                                <img
                                    src="{{ $logo }}"
                                    alt="Logo Kurnia Care"
                                    class="h-12 w-12 rounded-full bg-white object-contain p-1 shadow-sm ring-1 ring-gray-200"
                                >

                                <div>
                                    <p class="font-bold leading-tight text-gray-900">
                                        Kurnia Care
                                    </p>
                                    <p class="text-xs font-semibold text-emerald-700">
                                        Klinik Khitan Modern
                                    </p>
                                </div>
                            </a>

                            <a
                                href="{{ $homeUrl }}"
                                class="rounded-xl bg-white px-4 py-2 text-xs font-bold text-gray-700 shadow-sm ring-1 ring-gray-200 transition hover:bg-emerald-50 hover:text-emerald-700"
                            >
                                Beranda
                            </a>
                        </div>

                        <div class="mt-10">
                            <p class="text-sm font-bold uppercase tracking-wide text-amber-600">
                                Daftar Akun
                            </p>

                            <h2 class="mt-3 text-3xl font-bold tracking-tight text-gray-900">
                                Buat Akun Pasien
                            </h2>

                            <div class="kc-gold-line mt-5 h-1 w-24 rounded-full"></div>

                            <p class="mt-5 text-sm leading-6 text-gray-600">
                                Isi data akun untuk mulai menggunakan layanan pendaftaran online Kurnia Care.
                            </p>
                        </div>

                        @if ($errors->any())
                            <div class="mt-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                <p class="font-semibold">
                                    Pendaftaran gagal.
                                </p>

                                <ul class="mt-2 list-inside list-disc space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-5">
                            @csrf

                            <div>
                                <label for="name" class="mb-2 block text-sm font-semibold text-gray-700">
                                    Nama Lengkap
                                </label>

                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14c3.866 0 7 2.239 7 5v1H5v-1c0-2.761 3.134-5 7-5Zm0-2a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" />
                                        </svg>
                                    </div>

                                    <input
                                        id="name"
                                        type="text"
                                        name="name"
                                        value="{{ old('name') }}"
                                        required
                                        autofocus
                                        autocomplete="name"
                                        placeholder="Masukkan nama lengkap"
                                        class="h-12 w-full rounded-2xl border border-gray-200 bg-white pl-12 pr-4 text-sm text-gray-800 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100"
                                    >
                                </div>

                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="mb-2 block text-sm font-semibold text-gray-700">
                                    Email
                                </label>

                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l9 6 9-6M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2Z" />
                                        </svg>
                                    </div>

                                    <input
                                        id="email"
                                        type="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required
                                        autocomplete="username"
                                        placeholder="Masukkan email"
                                        class="h-12 w-full rounded-2xl border border-gray-200 bg-white pl-12 pr-4 text-sm text-gray-800 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100"
                                    >
                                </div>

                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="mb-2 block text-sm font-semibold text-gray-700">
                                    Nomor WhatsApp
                                </label>

                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 0 1 2-2h3l2 5-2 1c1 2 3 4 5 5l1-2 5 2v3a2 2 0 0 1-2 2h-1C9.925 19 5 14.075 5 8V7a2 2 0 0 1-2-2Z" />
                                        </svg>
                                    </div>

                                    <input
                                        id="phone"
                                        type="text"
                                        name="phone"
                                        value="{{ old('phone') }}"
                                        autocomplete="tel"
                                        placeholder="Contoh: 082285662642"
                                        class="h-12 w-full rounded-2xl border border-gray-200 bg-white pl-12 pr-4 text-sm text-gray-800 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100"
                                    >
                                </div>

                                @error('phone')
                                    <p class="mt-2 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div x-data="{ showPassword: false, showConfirm: false }" class="space-y-5">
                                <div>
                                    <label for="password" class="mb-2 block text-sm font-semibold text-gray-700">
                                        Password
                                    </label>

                                    <div class="relative">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2Zm10-10V7a4 4 0 0 0-8 0v4h8Z" />
                                            </svg>
                                        </div>

                                        <input
                                            id="password"
                                            :type="showPassword ? 'text' : 'password'"
                                            name="password"
                                            required
                                            autocomplete="new-password"
                                            placeholder="Masukkan password"
                                            class="h-12 w-full rounded-2xl border border-gray-200 bg-white pl-12 pr-12 text-sm text-gray-800 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100"
                                        >

                                        <button
                                            type="button"
                                            @click="showPassword = !showPassword"
                                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-sm font-semibold text-emerald-700"
                                        >
                                            <span x-show="!showPassword">Lihat</span>
                                            <span x-cloak x-show="showPassword">Tutup</span>
                                        </button>
                                    </div>

                                    @error('password')
                                        <p class="mt-2 text-sm text-red-600">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-gray-700">
                                        Konfirmasi Password
                                    </label>

                                    <div class="relative">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7 21h10a2 2 0 0 0 2-2v-7a7 7 0 1 0-14 0v7a2 2 0 0 0 2 2Z" />
                                            </svg>
                                        </div>

                                        <input
                                            id="password_confirmation"
                                            :type="showConfirm ? 'text' : 'password'"
                                            name="password_confirmation"
                                            required
                                            autocomplete="new-password"
                                            placeholder="Ulangi password"
                                            class="h-12 w-full rounded-2xl border border-gray-200 bg-white pl-12 pr-12 text-sm text-gray-800 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100"
                                        >

                                        <button
                                            type="button"
                                            @click="showConfirm = !showConfirm"
                                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-sm font-semibold text-emerald-700"
                                        >
                                            <span x-show="!showConfirm">Lihat</span>
                                            <span x-cloak x-show="showConfirm">Tutup</span>
                                        </button>
                                    </div>

                                    @error('password_confirmation')
                                        <p class="mt-2 text-sm text-red-600">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <button
                                type="submit"
                                class="flex h-12 w-full items-center justify-center rounded-2xl bg-emerald-700 px-5 text-sm font-bold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-emerald-800 hover:shadow-lg"
                            >
                                Daftar Akun Pasien
                            </button>
                        </form>

                        <div class="mt-6 rounded-2xl border border-amber-100 bg-amber-50/70 px-4 py-4">
                            <p class="text-sm font-semibold text-gray-800">
                                Sudah punya akun?
                            </p>

                            <p class="mt-1 text-sm leading-6 text-gray-600">
                                Masuk untuk melanjutkan pendaftaran atau memantau status layanan.
                            </p>

                            <a
                                href="{{ $loginUrl }}"
                                class="mt-4 inline-flex w-full items-center justify-center rounded-xl border border-amber-200 bg-white px-4 py-3 text-sm font-bold text-gray-800 shadow-sm transition hover:bg-amber-100 hover:text-emerald-700"
                            >
                                Masuk Akun
                            </a>
                        </div>

                        <p class="mt-6 text-center text-xs leading-5 text-gray-500">
                            © {{ date('Y') }} Kurnia Care. Sistem Klinik Sunat Modern.
                        </p>
                    </div>
                </section>
            </div>
        </div>
    </main>
</body>
</html>