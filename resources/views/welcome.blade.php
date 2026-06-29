@php
    $logo = asset('images/logo-kurnia-care.png');

    $loginUrl = Route::has('login') ? route('login') : url('/login');
    $registerUrl = Route::has('register') ? route('register') : url('/register');

    $bookingUrl = auth()->check()
        ? (
            in_array(auth()->user()->role, ['user', 'pasien'], true)
            ? route('user.appointments.create')
            : route('dashboard')
        )
        : $registerUrl;

    $navItems = [
        ['label' => 'Beranda', 'target' => '#beranda'],
        ['label' => 'Layanan', 'target' => '#layanan'],
        ['label' => 'Dokter', 'target' => '#dokter'],
        ['label' => 'Galeri', 'target' => '#galeri'],
        ['label' => 'Testimoni', 'target' => '#testimoni'],
        ['label' => 'FAQ', 'target' => '#faq'],
        ['label' => 'Kontak', 'target' => '#kontak'],
    ];

    $whyCards = [
        [
            'title' => 'Dokter Profesional',
            'description' => 'Tindakan ditangani oleh dokter berpengalaman dalam layanan khitan modern.',
            'icon' => 'M12 14c3.866 0 7 2.239 7 5v1H5v-1c0-2.761 3.134-5 7-5Zm0-2a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z',
        ],
        [
            'title' => 'Proses Aman',
            'description' => 'Alur layanan dibuat rapi dari pendaftaran, pembayaran, verifikasi, hingga tindakan.',
            'icon' => 'M12 3 5 6v5c0 5 3.5 8.5 7 10 3.5-1.5 7-5 7-10V6l-7-3Z',
        ],
        [
            'title' => 'Ramah Anak',
            'description' => 'Pelayanan dibuat tenang, humanis, dan nyaman untuk anak serta keluarga.',
            'icon' => 'M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78Z',
        ],
        [
            'title' => 'Pendaftaran Mudah',
            'description' => 'Orang tua dapat mendaftar online dan memantau status melalui dashboard.',
            'icon' => 'M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 0 1 1 1v14H4V6a1 1 0 0 1 1-1Z',
        ],
    ];

    $steps = [
        'Daftar Akun',
        'Lengkapi Data',
        'Pilih Jadwal',
        'Pembayaran',
        'Verifikasi',
        'Tindakan',
    ];

    $faqs = [
        [
            'question' => 'Berapa lama proses khitan?',
            'answer' => 'Durasi layanan bergantung pada paket dan kondisi anak. Estimasi durasi dapat dilihat pada kartu layanan saat pendaftaran.',
        ],
        [
            'question' => 'Apakah harus puasa?',
            'answer' => 'Kebutuhan puasa dapat berbeda sesuai kondisi pasien. Orang tua disarankan konsultasi terlebih dahulu sebelum jadwal tindakan.',
        ],
        [
            'question' => 'Bagaimana proses pendaftaran?',
            'answer' => 'Orang tua membuat akun, mengisi data anak dan keluarga, memilih layanan, memilih jadwal, lalu mengunggah bukti pembayaran.',
        ],
        [
            'question' => 'Apakah bisa konsultasi terlebih dahulu?',
            'answer' => 'Bisa. Orang tua dapat menghubungi WhatsApp Kurnia Care melalui tombol konsultasi yang tersedia di halaman ini.',
        ],
        [
            'question' => 'Berapa lama masa pemulihan?',
            'answer' => 'Masa pemulihan dapat berbeda pada setiap anak. Dokter akan memberikan arahan perawatan setelah tindakan selesai.',
        ],
    ];
@endphp

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kurnia Care - Klinik Khitan Modern</title>

    <meta name="description"
        content="Kurnia Care adalah klinik khitan modern dengan pendaftaran online, pelayanan profesional, dan pendekatan ramah anak.">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('partials.vite-inline')

    <style>
        html {
            scroll-behavior: smooth;
        }

        [x-cloak] {
            display: none !important;
        }

        .kc-section {
            scroll-margin-top: 92px;
        }

        .kc-hero-bg {
            background:
                radial-gradient(circle at 12% 16%, rgba(4, 120, 87, .28), transparent 28%),
                radial-gradient(circle at 86% 14%, rgba(245, 158, 11, .20), transparent 30%),
                radial-gradient(circle at 48% 100%, rgba(16, 185, 129, .16), transparent 36%),
                linear-gradient(135deg, #ecfdf5 0%, #ffffff 46%, #fffbeb 100%);
        }

        .kc-hero-pattern {
            background-image:
                radial-gradient(rgba(4, 120, 87, .14) 1px, transparent 1px),
                radial-gradient(rgba(245, 158, 11, .10) 1px, transparent 1px);
            background-size: 30px 30px, 46px 46px;
            background-position: 0 0, 18px 18px;
        }

        .kc-gold-line {
            background: linear-gradient(90deg, #047857, #f59e0b, #047857);
        }

        .kc-hero-panel {
            background: rgba(255, 255, 255, .82);
            border: 1px solid rgba(255, 255, 255, .78);
            box-shadow: 0 26px 70px rgba(15, 23, 42, .12);
            backdrop-filter: blur(20px);
        }

        .kc-premium-card {
            background: rgba(255, 255, 255, .9);
            border: 1px solid rgba(229, 231, 235, .9);
            box-shadow: 0 18px 45px rgba(15, 23, 42, .06);
            backdrop-filter: blur(18px);
        }

        .kc-gold-glow {
            box-shadow: 0 22px 55px rgba(245, 158, 11, .16);
        }

        .kc-emerald-glow {
            box-shadow: 0 22px 55px rgba(4, 120, 87, .14);
        }

        .reveal {
            opacity: 0;
            transform: translateY(18px);
            transition: all .7s ease;
        }

        .reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>

<body class="kc-page-bg overflow-x-hidden text-gray-800 antialiased">
    <div x-data="{
            mobileOpen: false,
            scrolled: false,
            init() {
                this.scrolled = window.scrollY > 24;
                window.addEventListener('scroll', () => {
                    this.scrolled = window.scrollY > 24;
                });
            }
        }" class="min-h-screen">
        <div class="pointer-events-none fixed inset-0 z-0 overflow-hidden">
            <div class="kc-pattern absolute inset-0 opacity-40"></div>
            <div class="absolute -left-24 top-28 h-80 w-80 rounded-full bg-emerald-200/40 blur-3xl"></div>
            <div class="absolute -right-24 top-40 h-96 w-96 rounded-full bg-amber-200/35 blur-3xl"></div>
            <div class="absolute bottom-0 left-1/3 h-96 w-96 rounded-full bg-emerald-100/50 blur-3xl"></div>
        </div>
        <header class="fixed inset-x-0 top-0 z-50 transition-all duration-300"
            :class="scrolled ? 'border-b border-gray-100 bg-white/85 shadow-sm backdrop-blur-xl' : 'bg-transparent'">
            <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
                <a href="#beranda" class="flex items-center gap-3">
                    <img src="{{ $logo }}" alt="Logo Kurnia Care"
                        class="h-12 w-12 rounded-full bg-white object-contain shadow-sm ring-1 ring-gray-200">
                    <div>
                        <p class="text-base font-bold leading-tight text-gray-900">Kurnia Care</p>
                        <p class="text-xs font-semibold text-emerald-700">Klinik Khitan Modern</p>
                    </div>
                </a>

                <div class="hidden items-center gap-7 lg:flex">
                    @foreach ($navItems as $item)
                        <a href="{{ $item['target'] }}"
                            class="text-sm font-semibold text-gray-600 transition hover:text-emerald-700">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>

                <div class="hidden items-center gap-3 lg:flex">
                    <a href="{{ $loginUrl }}"
                        class="rounded-xl px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-100">
                        Masuk
                    </a>
                    <a href="{{ $bookingUrl }}"
                        class="rounded-xl bg-emerald-700 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 hover:shadow-md">
                        Daftar Sekarang
                    </a>
                </div>

                <button type="button" @click="mobileOpen = !mobileOpen"
                    class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-white/90 text-emerald-700 shadow-sm ring-1 ring-gray-200 lg:hidden"
                    aria-label="Menu">
                    <span x-show="!mobileOpen" class="text-2xl">☰</span>
                    <span x-show="mobileOpen" class="text-2xl">×</span>
                </button>
            </nav>

            <div x-cloak x-show="mobileOpen" x-transition
                class="border-t border-gray-100 bg-white/95 px-4 py-4 shadow-lg lg:hidden">
                <div class="space-y-1">
                    @foreach ($navItems as $item)
                        <a href="{{ $item['target'] }}" @click="mobileOpen = false"
                            class="block rounded-xl px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-emerald-50 hover:text-emerald-700">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>

                <div class="mt-4 grid grid-cols-2 gap-3">
                    <a href="{{ $loginUrl }}"
                        class="rounded-xl border border-gray-200 px-4 py-3 text-center text-sm font-semibold text-gray-700">
                        Masuk
                    </a>
                    <a href="{{ $bookingUrl }}"
                        class="rounded-xl bg-emerald-700 px-4 py-3 text-center text-sm font-semibold text-white">
                        Daftar
                    </a>
                </div>
            </div>
        </header>

        <main class="relative z-10">
            {{-- BERANDA / HERO UTAMA --}}
            <section id="beranda"
                class="kc-section kc-hero-bg relative isolate overflow-hidden pt-28 sm:pt-32 lg:pt-36">
                <div class="pointer-events-none absolute inset-0 -z-10">
                    <div class="kc-hero-pattern absolute inset-0 opacity-40"></div>

                    <div class="absolute -left-24 top-20 h-80 w-80 rounded-full bg-emerald-300/30 blur-3xl"></div>
                    <div class="absolute -right-24 top-24 h-96 w-96 rounded-full bg-amber-300/25 blur-3xl"></div>
                    <div class="absolute bottom-0 left-1/3 h-96 w-96 rounded-full bg-emerald-200/40 blur-3xl"></div>

                    <div class="absolute left-8 top-32 h-24 w-24 rounded-full border border-amber-300/50"></div>
                    <div class="absolute right-16 top-44 h-16 w-16 rounded-full border border-emerald-300/50"></div>
                    <div class="absolute left-10 top-1/2 h-2 w-28 rounded-full bg-amber-300/70"></div>
                    <div class="absolute right-20 bottom-36 h-2 w-24 rounded-full bg-emerald-300/70"></div>
                </div>

                <div
                    class="mx-auto grid max-w-7xl items-center gap-12 px-4 pb-20 sm:px-6 lg:grid-cols-2 lg:px-8 lg:pb-28">
                    <div class="reveal">
                        <div
                            class="inline-flex items-center gap-2 rounded-full border border-amber-200 bg-white/85 px-4 py-2 text-sm font-semibold text-emerald-800 shadow-sm backdrop-blur">
                            <span class="h-2 w-2 rounded-full bg-amber-400"></span>
                            Pendaftaran khitan modern lebih mudah
                        </div>

                        <h1
                            class="mt-7 max-w-3xl text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl lg:text-6xl">
                            Khitan Aman, Nyaman dan Profesional
                        </h1>

                        <div class="kc-gold-line mt-6 h-1 w-28 rounded-full"></div>

                        <p class="mt-6 max-w-2xl text-base leading-8 text-gray-600 sm:text-lg">
                            Pendaftaran online yang mudah dengan pelayanan profesional dan pendekatan yang ramah
                            terhadap anak dan keluarga.
                        </p>

                        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                            <a href="{{ $bookingUrl }}"
                                class="inline-flex items-center justify-center rounded-2xl bg-emerald-700 px-6 py-3.5 text-sm font-bold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-emerald-800 hover:shadow-lg">
                                Daftar Sekarang
                            </a>

                            <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener"
                                class="inline-flex items-center justify-center rounded-2xl border border-amber-200 bg-white px-6 py-3.5 text-sm font-bold text-gray-800 shadow-sm transition hover:-translate-y-0.5 hover:border-amber-300 hover:bg-amber-50 hover:text-emerald-700 hover:shadow-md">
                                Konsultasi
                            </a>
                        </div>

                        <div class="mt-8 grid max-w-xl grid-cols-2 gap-4 sm:grid-cols-3">
                            <div class="kc-premium-card rounded-2xl p-4">
                                <p class="text-2xl font-bold text-emerald-700">10</p>
                                <p class="mt-1 text-xs font-medium text-gray-500">Kuota harian</p>
                            </div>

                            <div class="kc-premium-card rounded-2xl p-4">
                                <p class="text-2xl font-bold text-amber-600">Online</p>
                                <p class="mt-1 text-xs font-medium text-gray-500">Pendaftaran</p>
                            </div>

                            <div class="kc-premium-card col-span-2 rounded-2xl p-4 sm:col-span-1">
                                <p class="text-2xl font-bold text-emerald-700">Aman</p>
                                <p class="mt-1 text-xs font-medium text-gray-500">Ramah keluarga</p>
                            </div>
                        </div>
                    </div>

                    <div class="reveal">
                        <div class="kc-hero-panel kc-gold-glow mx-auto max-w-lg rounded-[2rem] p-5">
                            <div
                                class="relative overflow-hidden rounded-[1.5rem] bg-gradient-to-br from-emerald-900 via-emerald-700 to-amber-500 p-6 text-white">
                                <div
                                    class="pointer-events-none absolute right-0 top-0 h-32 w-32 rounded-full bg-white/10 blur-2xl">
                                </div>
                                <div
                                    class="pointer-events-none absolute bottom-0 left-0 h-28 w-28 rounded-full bg-amber-200/20 blur-2xl">
                                </div>

                                <div class="relative flex items-center gap-4">
                                    <img src="{{ $logo }}" alt="Logo Kurnia Care"
                                        class="h-16 w-16 rounded-full bg-white object-contain p-1 shadow-md">

                                    <div>
                                        <p class="text-sm text-emerald-50">Kurnia Care</p>
                                        <p class="text-xl font-bold">Klinik Khitan Modern</p>
                                    </div>
                                </div>

                                <div class="relative mt-8 grid grid-cols-2 gap-4">
                                    <div class="rounded-2xl bg-white/15 p-4 backdrop-blur">
                                        <p class="text-sm text-emerald-50">Kuota hari ini</p>
                                        <p class="mt-2 text-3xl font-bold">{{ $remainingQuota }}</p>
                                    </div>

                                    <div class="rounded-2xl bg-white/15 p-4 backdrop-blur">
                                        <p class="text-sm text-emerald-50">Booking aktif</p>
                                        <p class="mt-2 text-3xl font-bold">{{ $todayBookings }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5 space-y-3">
                                @foreach (['Daftar akun', 'Lengkapi data anak', 'Upload bukti pembayaran'] as $index => $item)
                                    <div
                                        class="flex items-center justify-between rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-100 text-sm font-bold text-amber-700">
                                                {{ $index + 1 }}
                                            </div>

                                            <p class="text-sm font-semibold text-gray-700">
                                                {{ $item }}
                                            </p>
                                        </div>

                                        <span class="text-emerald-600">✓</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="bg-gradient-to-b from-white via-emerald-50/40 to-white py-16">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div
                            class="reveal kc-premium-card rounded-3xl p-6 transition hover:-translate-y-1 hover:shadow-md">
                            <p class="text-4xl font-bold text-emerald-700">
                                <span>1000</span>+
                            </p>
                            <p class="mt-2 font-semibold text-gray-900">Pasien Ditangani</p>
                        </div>
                        <div
                            class="reveal kc-premium-card rounded-3xl p-6 transition hover:-translate-y-1 hover:shadow-md">
                            <p class="text-3xl font-bold text-emerald-700">Dokter Profesional</p>
                            <p class="mt-2 font-semibold text-gray-900">Berpengalaman</p>
                        </div>
                        <div
                            class="reveal kc-premium-card rounded-3xl p-6 transition hover:-translate-y-1 hover:shadow-md">
                            <p class="text-4xl font-bold text-amber-600"><span data-counter="98">0</span>%</p>
                            <p class="mt-2 font-semibold text-gray-900">Kepuasan Orang Tua</p>
                        </div>
                        <div
                            class="reveal kc-premium-card rounded-3xl p-6 transition hover:-translate-y-1 hover:shadow-md">
                            <p class="text-3xl font-bold text-emerald-700">Layanan</p>
                            <p class="mt-2 font-semibold text-gray-900">Ramah Anak</p>
                        </div>
                    </div>
                </div>
            </section>

            <section id="mengapa" class="kc-section bg-gradient-to-br from-white via-gray-50 to-emerald-50/50 py-20">
                <div
                    class="pointer-events-none absolute right-0 top-10 h-64 w-64 rounded-full bg-emerald-100/70 blur-3xl">
                </div>
                <div
                    class="pointer-events-none absolute bottom-10 left-0 h-52 w-52 rounded-full bg-amber-100/70 blur-3xl">
                </div>

                <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="reveal max-w-2xl">
                        <p class="text-sm font-bold uppercase tracking-wide text-amber-600">Mengapa Kurnia Care</p>
                        <h2 class="mt-3 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                            Pelayanan yang dirancang untuk rasa aman orang tua.
                        </h2>
                        <div class="kc-gold-line mt-5 h-1 w-24 rounded-full"></div>
                    </div>

                    <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach ($whyCards as $card)
                            <div
                                class="reveal group rounded-3xl border border-gray-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-amber-600 transition group-hover:bg-emerald-700 group-hover:text-white">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="{{ $card['icon'] }}" />
                                    </svg>
                                </div>
                                <h3 class="mt-5 text-lg font-bold text-gray-900">{{ $card['title'] }}</h3>
                                <p class="mt-3 text-sm leading-6 text-gray-600">{{ $card['description'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section id="dokter"
                class="kc-section bg-gradient-to-br from-emerald-50/80 via-white to-emerald-100/40 py-20">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="reveal max-w-2xl">
                        <p class="text-sm font-bold uppercase tracking-wide text-amber-600">
                            Profil Dokter
                        </p>

                        <h2 class="mt-3 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                            Dokter profesional dengan pendekatan ramah keluarga.
                        </h2>

                        <div class="kc-gold-line mt-5 h-1 w-24 rounded-full"></div>
                    </div>

                    <div
                        class="reveal mt-10 rounded-[2rem] border border-gray-100 bg-white p-4 shadow-sm sm:p-5 lg:p-6">
                        <div class="grid gap-6 lg:grid-cols-[340px_1fr] lg:items-stretch">
                            <div
                                class="overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-50 via-white to-amber-50 shadow-sm ring-1 ring-gray-100">
                                @if ($featuredDoctor && $featuredDoctor->photo)
                                    <img src="{{ asset('storage/' . $featuredDoctor->photo) }}"
                                        alt="{{ $featuredDoctor->name }}"
                                        class="h-72 w-full object-cover sm:h-80 lg:h-full">
                                @else
                                    <div class="flex h-72 w-full items-center justify-center p-6 sm:h-80 lg:h-full">
                                        <img src="{{ $logo }}" alt="Logo Kurnia Care"
                                            class="h-24 w-24 rounded-full bg-white object-contain p-1 shadow-md sm:h-28 sm:w-28">
                                    </div>
                                @endif
                            </div>

                            <div
                                class="flex flex-col justify-center rounded-3xl bg-gradient-to-br from-white via-white to-emerald-50/60 p-5 sm:p-7 lg:p-8">
                                <div
                                    class="inline-flex w-fit rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                                    Dokter Khitan Modern
                                </div>

                                <h3 class="mt-4 text-2xl font-bold text-gray-900 sm:text-3xl">
                                    {{ $featuredDoctor?->name ?? 'Dokter Kurnia Care' }}
                                </h3>

                                <p class="mt-2 text-sm font-semibold text-emerald-700">
                                    {{ $featuredDoctor?->specialist ?? 'Dokter Khitan Modern' }}
                                </p>

                                <div class="mt-5 grid gap-3 sm:grid-cols-2">
                                    <div class="rounded-2xl bg-emerald-50 p-4">
                                        <p class="text-xs font-medium text-emerald-700">SIP</p>
                                        <p class="mt-1 truncate text-sm font-bold text-gray-900">
                                            {{ $featuredDoctor?->sip_number ?? 'Belum diatur' }}
                                        </p>
                                    </div>

                                    <div class="rounded-2xl bg-amber-50 p-4">
                                        <p class="text-xs font-medium text-amber-700">Pengalaman</p>
                                        <p class="mt-1 text-sm font-bold text-gray-900">
                                            Berpengalaman
                                        </p>
                                    </div>
                                </div>

                                <p class="mt-5 text-sm leading-7 text-gray-600 sm:text-base">
                                    {{ $featuredDoctor?->bio ?? 'Profil dokter akan tampil otomatis dari data dokter aktif pada dashboard admin. Deskripsi ini dapat berisi pengalaman, pendekatan pelayanan, dan fokus dokter dalam memberikan tindakan yang aman serta nyaman bagi anak dan keluarga.' }}
                                </p>

                                
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="layanan"
                class="kc-section relative overflow-hidden bg-gradient-to-br from-white via-amber-50/45 to-white py-20">
                <div
                    class="pointer-events-none absolute left-0 top-20 h-64 w-64 rounded-full bg-emerald-100/70 blur-3xl">
                </div>
                <div
                    class="pointer-events-none absolute bottom-24 right-0 h-72 w-72 rounded-full bg-amber-100/70 blur-3xl">
                </div>

                <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="reveal flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                        <div>
                            <p class="text-sm font-bold uppercase tracking-wide text-amber-600">
                                Layanan Khitan
                            </p>

                            <h2 class="mt-3 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                                Paket Layanan Sunat Modern
                            </h2>

                            <p class="mt-4 max-w-2xl leading-7 text-gray-600">
                                Pilih layanan sesuai kebutuhan keluarga. Klik gambar untuk melihat preview foto layanan.
                            </p>

                            <div class="kc-gold-line mt-5 h-1 w-24 rounded-full"></div>
                        </div>

                        <a href="{{ $bookingUrl }}"
                            class="inline-flex rounded-2xl bg-emerald-700 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-800">
                            Daftar Sekarang
                        </a>
                    </div>

                    <div x-data="{ showAll: false, previewImage: null, previewTitle: '' }" class="mt-10">
                        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            @forelse ($services as $index => $service)
                                <article x-show="showAll || {{ $index < 3 ? 'true' : 'false' }}" x-transition @if ($index >= 3) x-cloak @endif
                                    class="reveal overflow-hidden rounded-[1.75rem] border border-gray-100 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                                    <div class="relative overflow-hidden bg-emerald-50">
                                        @if ($service->image)
                                            <button type="button"
                                                @click="previewImage = @js(asset('storage/' . $service->image)); previewTitle = @js($service->name)"
                                                class="group block w-full">
                                                <div class="aspect-[4/3] w-full bg-gradient-to-br from-emerald-50 to-amber-50">
                                                    <img src="{{ asset('storage/' . $service->image) }}"
                                                        alt="{{ $service->name }}"
                                                        class="h-full w-full object-cover object-center transition duration-500 group-hover:scale-105">
                                                </div>
                                            </button>
                                        @else
                                            <div
                                                class="aspect-[4/3] w-full bg-gradient-to-br from-emerald-700 via-emerald-600 to-amber-400">
                                                <div
                                                    class="flex h-full w-full items-center justify-center p-6 text-center text-white">
                                                    <div>
                                                        <img src="{{ $logo }}" alt="Kurnia Care"
                                                            class="mx-auto h-16 w-16 rounded-full bg-white object-contain p-1 shadow-md">
                                                        <p class="mt-4 text-sm font-semibold">
                                                            Gambar layanan belum tersedia
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div
                                            class="absolute left-4 top-4 rounded-full bg-white/90 px-3 py-1 text-xs font-bold text-emerald-700 shadow-sm">
                                            Layanan Khitan
                                        </div>
                                    </div>

                                    <div class="p-6">
                                        <h3 class="text-xl font-bold text-gray-900">
                                            {{ $service->name }}
                                        </h3>

                                        <p class="mt-3 line-clamp-3 text-sm leading-6 text-gray-600">
                                            {{ $service->description ?? 'Deskripsi layanan belum tersedia.' }}
                                        </p>

                                        <div class="mt-6 border-t border-gray-100 pt-5">
                                            <p class="text-xs font-medium text-gray-500">
                                                Harga
                                            </p>

                                            <p class="text-lg font-bold text-emerald-700">
                                                Rp {{ number_format($service->price, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                </article>
                            @empty
                                <div
                                    class="reveal rounded-3xl border border-dashed border-gray-200 bg-white p-8 text-center md:col-span-2 lg:col-span-3">
                                    <p class="font-bold text-gray-900">
                                        Data layanan belum tersedia.
                                    </p>

                                    <p class="mt-2 text-sm text-gray-500">
                                        Tambahkan layanan dari Dashboard Admin agar tampil di homepage.
                                    </p>
                                </div>
                            @endforelse
                        </div>

                        @if ($services->count() > 3)
                            <div class="mt-10 flex justify-center">
                                <button type="button" @click="showAll = !showAll"
                                    class="rounded-2xl border border-amber-200 bg-white px-6 py-3 text-sm font-bold text-gray-800 shadow-sm transition hover:border-amber-300 hover:bg-amber-50 hover:text-emerald-700">
                                    <span x-show="!showAll">Lihat Selengkapnya ↓</span>
                                    <span x-show="showAll">Lihat Lebih Sedikit ↑</span>
                                </button>
                            </div>
                        @endif

                        <div x-cloak x-show="previewImage" x-transition.opacity
                            @keydown.escape.window="previewImage = null"
                            class="fixed inset-0 z-[90] flex items-center justify-center bg-gray-900/70 px-4 py-6 backdrop-blur-sm">
                            <div @click.away="previewImage = null"
                                class="relative max-h-[92vh] w-full max-w-5xl overflow-auto rounded-[2rem] bg-white p-3 shadow-2xl">
                                <button type="button" @click="previewImage = null"
                                    class="absolute right-5 top-20 z-10 flex h-11 w-11 items-center justify-center rounded-full bg-white text-gray-700 shadow-md hover:bg-gray-100 md:top-20">
                                    ×
                                </button>
                                <div class="rounded-[1.5rem] bg-gray-100 p-3">
                                    <img :src="previewImage" :alt="previewTitle"
                                        class="mx-auto max-h-[82vh] w-auto rounded-xl object-contain">

                                    <p x-text="previewTitle" class="mt-3 text-center text-sm font-bold text-gray-800">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>



            <section id="alur" class="kc-section bg-gradient-to-b from-white via-amber-50/30 to-emerald-50/30 py-20">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="reveal max-w-2xl">
                        <p class="text-sm font-bold uppercase tracking-wide text-amber-600">Alur Pendaftaran</p>
                        <h2 class="mt-3 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                            Langkah jelas dari daftar sampai tindakan.
                        </h2>
                        <div class="kc-gold-line mt-5 h-1 w-24 rounded-full"></div>
                    </div>

                    <div class="mt-12 grid gap-4 md:grid-cols-3 lg:grid-cols-6">
                        @foreach ($steps as $index => $step)
                            <div
                                class="reveal rounded-3xl border border-gray-100 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                                <div
                                    class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-700 text-sm font-bold text-white">
                                    {{ $index + 1 }}
                                </div>
                                <h3 class="mt-5 font-bold text-gray-900">{{ $step }}</h3>
                                <p class="mt-2 text-sm leading-6 text-gray-500">
                                    {{ $index === 0 ? 'Mulai dari akun pasien.' : 'Dipantau melalui sistem.' }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section id="jadwal" class="kc-section bg-gradient-to-br from-emerald-50/80 via-white to-amber-50/50 py-20">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div
                        class="reveal rounded-[2rem] bg-gradient-to-br from-emerald-800 via-emerald-700 to-emerald-600 p-6 text-white shadow-lg ring-1 ring-amber-300/20 sm:p-8 lg:p-10">
                        <div class="grid gap-8 lg:grid-cols-[1fr_1.2fr] lg:items-center">
                            <div>
                                <p class="text-sm font-bold uppercase tracking-wide text-amber-200">Kuota dan Jadwal
                                    Hari Ini</p>
                                <h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">
                                    Pantau ketersediaan sebelum mendaftar.
                                </h2>
                                <p class="mt-4 leading-7 text-emerald-50">
                                    Data kuota dan booking hari ini diambil langsung dari database appointment.
                                </p>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-3">
                                <div class="rounded-3xl bg-white/15 p-5 backdrop-blur">
                                    <p class="text-sm text-emerald-50">Kuota Tersedia</p>
                                    <p class="mt-2 text-4xl font-bold">{{ $remainingQuota }}</p>
                                </div>
                                <div class="rounded-3xl bg-white/15 p-5 backdrop-blur">
                                    <p class="text-sm text-emerald-50">Booking Hari Ini</p>
                                    <p class="mt-2 text-4xl font-bold">{{ $todayBookings }}</p>
                                </div>
                                <div class="rounded-3xl bg-white/15 p-5 backdrop-blur">
                                    <p class="text-sm text-emerald-50">Jam Operasional</p>
                                    <p class="mt-2 text-lg font-bold leading-7">{{ $operationalHours }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="galeri" class="kc-section bg-gradient-to-br from-emerald-50 via-white to-emerald-100/60 py-20">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="reveal">
                        <p class="text-sm font-bold uppercase tracking-wide text-amber-600">
                            Dokumentasi
                        </p>

                        <h2 class="mt-3 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                            Momen Bahagia Bersama Kurnia Care
                        </h2>

                        <p class="mt-4 max-w-3xl leading-7 text-gray-600">
                            Dokumentasi pasien setelah tindakan, foto bersama keluarga, foto bersama dokter, dan
                            dokumentasi kegiatan.
                        </p>

                        <div class="kc-gold-line mt-5 h-1 w-24 rounded-full"></div>
                    </div>

                    <div x-data="{
                showAllGallery: false,
                galleryImage: null,
                galleryTitle: '',
                galleryDescription: ''
            }" x-effect="document.body.classList.toggle('overflow-hidden', !!galleryImage)" class="mt-10">
                        <div class="grid grid-cols-2 gap-2 sm:grid-cols-3 md:gap-3 lg:grid-cols-4">
                            @forelse ($galleries as $index => $gallery)
                                <button type="button" x-show="showAllGallery || {{ $index < 4 ? 'true' : 'false' }}"
                                    x-transition @if ($index >= 4) x-cloak @endif @click="
                                                                            if (window.innerWidth < 768) {
                                                                                galleryImage = @js(asset('storage/' . $gallery->image));
                                                                                galleryTitle = @js($gallery->title);
                                                                                galleryDescription = @js($gallery->description ?? '');
                                                                            }
                                                                        "
                                    class="reveal group relative aspect-[3/4] overflow-hidden rounded-2xl bg-gray-100 shadow-sm ring-1 ring-gray-100 transition hover:-translate-y-1 hover:shadow-xl md:pointer-events-none md:cursor-default">
                                    <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->title }}"
                                        class="h-full w-full object-cover object-center transition duration-500 group-hover:scale-105">

                                    <div class="absolute inset-0 bg-black/0 transition group-hover:bg-black/25 md:hidden">
                                    </div>

                                    <div
                                        class="absolute inset-0 flex items-center justify-center opacity-0 transition group-hover:opacity-100 md:hidden">
                                        <div
                                            class="flex h-12 w-12 items-center justify-center rounded-full bg-white/90 text-emerald-700 shadow-lg">
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 10l4.553 4.553a1 1 0 0 1 0 1.414L16 19.52M9 14l-4.553-4.553a1 1 0 0 1 0-1.414L8 4.48M14 4h6m0 0v6m0-6L10 14M10 20H4m0 0v-6m0 6 10-10" />
                                            </svg>
                                        </div>
                                    </div>
                                </button>
                            @empty
                                <div
                                    class="rounded-3xl border border-dashed border-gray-200 bg-white p-8 text-center sm:col-span-3 lg:col-span-4">
                                    <p class="font-bold text-gray-900">
                                        Dokumentasi belum tersedia.
                                    </p>

                                    <p class="mt-2 text-sm text-gray-500">
                                        Tambahkan dokumentasi dari Dashboard Admin agar tampil di homepage.
                                    </p>
                                </div>
                            @endforelse
                        </div>

                        @if ($galleries->count() > 4)
                            <div class="mt-10 flex justify-center">
                                <button type="button" @click="showAllGallery = !showAllGallery"
                                    class="rounded-2xl border border-amber-200 bg-white px-6 py-3 text-sm font-bold text-gray-800 shadow-sm transition hover:border-amber-300 hover:bg-amber-50 hover:text-emerald-700">
                                    <span x-show="!showAllGallery">Lihat Selengkapnya ↓</span>
                                    <span x-cloak x-show="showAllGallery">Lihat Lebih Sedikit ↑</span>
                                </button>
                            </div>
                        @endif

                        <div x-cloak x-show="galleryImage" x-transition.opacity
                            @keydown.escape.window="galleryImage = null" @click.self="galleryImage = null"
                            class="fixed inset-0 z-[9999] flex items-center justify-center overflow-hidden bg-gray-900/85 px-4 py-6 backdrop-blur-sm">
                            <button type="button" @click="galleryImage = null"
                                class="fixed right-5 top-6 z-[10000] flex h-11 w-11 items-center justify-center rounded-full bg-white text-2xl font-bold leading-none text-gray-700 shadow-lg ring-1 ring-gray-200 transition hover:bg-red-50 hover:text-red-600 md:top-24"
                                aria-label="Tutup galeri">
                                ×
                            </button>

                            <div x-transition @click.stop
                                class="flex max-h-[92vh] max-w-[90vw] flex-col overflow-hidden rounded-[1.5rem] bg-white shadow-2xl">
                                <div class="flex items-center justify-center bg-gray-100 p-3">
                                    <img :src="galleryImage" :alt="galleryTitle"
                                        class="block max-h-[85vh] max-w-[90vw] rounded-xl object-contain">
                                </div>

                                <div class="max-w-[90vw] bg-white px-5 py-4 text-center">
                                    <h3 x-show="galleryTitle" x-text="galleryTitle"
                                        class="text-base font-bold text-gray-900"></h3>

                                    <p x-show="galleryDescription" x-text="galleryDescription"
                                        class="mt-2 text-sm leading-6 text-gray-600"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="testimoni" class="kc-section bg-gradient-to-b from-white via-gray-50 to-white py-20">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="reveal max-w-2xl">
                        <p class="text-sm font-bold uppercase tracking-wide text-amber-600">Testimoni</p>
                        <h2 class="mt-3 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                            Cerita orang tua pasien.
                        </h2>
                        <div class="kc-gold-line mt-5 h-1 w-24 rounded-full"></div>
                    </div>

                    <div class="mt-10 flex snap-x gap-5 overflow-x-auto pb-4">
                        @forelse ($testimonials as $testimonial)
                            <article
                                class="reveal min-w-[300px] snap-start rounded-3xl border border-gray-100 bg-white p-6 shadow-sm sm:min-w-[380px]">
                                <div class="flex gap-1 text-amber-400">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span>{{ $i <= $testimonial->rating ? '★' : '☆' }}</span>
                                    @endfor
                                </div>

                                <p class="mt-5 leading-7 text-gray-600">
                                    “{{ $testimonial->content }}”
                                </p>

                                <div class="mt-6 flex items-center gap-3">
                                    @if ($testimonial->image)
                                        <img src="{{ asset('storage/' . $testimonial->image) }}" alt="{{ $testimonial->name }}"
                                            class="h-11 w-11 rounded-full object-cover">
                                    @else
                                        <div
                                            class="flex h-11 w-11 items-center justify-center rounded-full bg-emerald-100 font-bold text-emerald-700">
                                            {{ strtoupper(substr($testimonial->name, 0, 1)) }}
                                        </div>
                                    @endif

                                    <div>
                                        <p class="font-bold text-gray-900">{{ $testimonial->name }}</p>
                                        <p class="text-sm text-gray-500">Orang tua pasien</p>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div
                                class="reveal w-full rounded-3xl border border-dashed border-gray-200 bg-white p-8 text-center">
                                <p class="font-bold text-gray-900">Testimoni belum tersedia.</p>
                                <p class="mt-2 text-sm text-gray-500">
                                    Tambahkan data testimoni agar slider ini menampilkan cerita orang tua pasien.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </section>

            <section id="faq" class="kc-section bg-gradient-to-br from-white via-emerald-50/25 to-white py-20">
                <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                    <div class="reveal text-center">
                        <p class="text-sm font-bold uppercase tracking-wide text-amber-600">FAQ</p>
                        <h2 class="mt-3 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                            Pertanyaan yang sering diajukan.
                        </h2>
                    </div>

                    <div class="mt-10 space-y-4">
                        @foreach ($faqs as $index => $faq)
                            <div x-data="{ open: {{ $index === 0 ? 'true' : 'false' }} }"
                                class="reveal rounded-3xl border border-gray-100 bg-white shadow-sm">
                                <button type="button" @click="open = !open"
                                    class="flex w-full items-center justify-between gap-4 px-6 py-5 text-left">
                                    <span class="font-bold text-gray-900">{{ $faq['question'] }}</span>
                                    <span
                                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-amber-50 text-amber-700"
                                        x-text="open ? '−' : '+'"></span>
                                </button>
                                <div x-show="open" x-transition class="px-6 pb-5 text-sm leading-7 text-gray-600">
                                    {{ $faq['answer'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section id="kontak" class="kc-section bg-gradient-to-b from-gray-50 via-emerald-50/40 to-white py-20">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div
                        class="reveal relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-gray-950 via-emerald-950 to-gray-900 shadow-xl ring-1 ring-amber-300/20">
                        <div
                            class="pointer-events-none absolute right-8 top-8 h-28 w-28 rounded-full bg-amber-300/10 blur-2xl">
                        </div>
                        <div
                            class="pointer-events-none absolute left-10 bottom-10 h-32 w-32 rounded-full bg-emerald-300/10 blur-2xl">
                        </div>

                        <div class="relative grid gap-8 p-6 sm:p-8 lg:grid-cols-[1.15fr_0.85fr] lg:p-10">
                            <div class="p-8 sm:p-10 lg:p-12 text-white">
                                <div class="flex items-center gap-4">
                                    <img src="{{ asset('images/logo-kurnia-care.png') }}" alt="Kurnia Care"
                                        class="h-16 w-16 rounded-full bg-white p-1 shadow-lg">

                                    <div>
                                        <p class="text-sm font-semibold text-emerald-300">
                                            Informasi Klinik
                                        </p>

                                        <h1 class="mt-1 text-3xl sm:text-4xl font-extrabold leading-tight">
                                            Kurnia Care
                                        </h1>

                                        <p class="mt-1 text-sm text-gray-300">
                                            Klinik Khitan Modern
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div class="rounded-2xl border border-white/10 bg-white/10 p-5">
                                        <p class="text-sm text-gray-300">Alamat</p>
                                        <p class="mt-2 font-semibold text-white">
                                            {{ $clinicAddress }}
                                        </p>
                                    </div>

                                    <div class="rounded-2xl border border-white/10 bg-white/10 p-5">
                                        <p class="text-sm text-gray-300">WhatsApp</p>
                                        <p class="mt-2 font-semibold text-white">
                                            {{ $whatsappNumber }}
                                        </p>
                                    </div>

                                    <div class="rounded-2xl border border-white/10 bg-white/10 p-5">
                                        <p class="text-sm text-gray-300">Email</p>
                                        <p class="mt-2 font-semibold text-white">
                                            {{ $clinicEmail }}
                                        </p>
                                    </div>

                                    <div class="rounded-2xl border border-white/10 bg-white/10 p-5">
                                        <p class="text-sm text-gray-300">Jam Operasional</p>
                                        <p class="mt-2 font-semibold text-white">
                                            {{ $operationalHours }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener"
                                        class="inline-flex items-center justify-center rounded-xl bg-emerald-500 px-6 py-3 text-sm font-bold text-white hover:bg-emerald-600">
                                        Hubungi WhatsApp
                                    </a>

                                    @if (!empty($googleMapsDirectionUrl ?? null))
                                        <a href="{{ $googleMapsDirectionUrl }}" target="_blank" rel="noopener"
                                            class="inline-flex items-center justify-center rounded-xl bg-white px-6 py-3 text-sm font-bold text-gray-900 hover:bg-gray-100">
                                            Buka Google Maps
                                        </a>
                                    @endif
                                </div>
                            </div>

                            {{-- KANAN: KONTAK DAN GOOGLE MAPS --}}
                            <div id="kontak"
                                class="rounded-[1.75rem] bg-white/10 border border-white/10 p-6 sm:p-8 shadow-xl">

                                {{-- HEADER KONTAK --}}



                                {{-- GOOGLE MAPS --}}
                                <div class="mt-6">


                                    @if (!empty($googleMapsEmbedUrl ?? null))
                                        <div class="overflow-hidden rounded-2xl bg-white shadow-lg">
                                            <iframe src="{{ $googleMapsEmbedUrl }}" width="100%" height="300"
                                                style="border:0;" allowfullscreen loading="lazy"
                                                referrerpolicy="strict-origin-when-cross-origin">
                                            </iframe>
                                        </div>

                                        @if (!empty($googleMapsDirectionUrl ?? null))
                                            <a href="{{ $googleMapsDirectionUrl }}" target="_blank" rel="noopener"
                                                class="mt-4 inline-flex w-full items-center justify-center rounded-xl bg-emerald-500 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-600">
                                                Buka Lokasi di Google Maps
                                            </a>
                                        @endif
                                    @else
                                        <div class="rounded-2xl border border-yellow-300/30 bg-yellow-400/10 p-5">
                                            <p class="text-sm font-semibold text-yellow-200">
                                                Peta belum dikonfigurasi.
                                            </p>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="relative z-10 border-t border-gray-100 bg-white py-10">
            <div
                class="mx-auto flex max-w-7xl flex-col gap-8 px-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
                <div class="flex items-center gap-3">
                    <img src="{{ $logo }}" alt="Logo Kurnia Care"
                        class="h-12 w-12 rounded-full object-contain ring-1 ring-gray-200">
                    <div>
                        <p class="font-bold text-gray-900">Kurnia Care</p>
                        <p class="text-sm text-gray-500">Klinik Khitan Modern</p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-x-5 gap-y-2 text-sm font-semibold text-gray-600">
                    @foreach ($navItems as $item)
                        <a href="{{ $item['target'] }}" class="hover:text-emerald-700">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>

                <p class="text-sm text-gray-500">
                    © {{ date('Y') }} Kurnia Care. All rights reserved.
                </p>
            </div>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const revealItems = document.querySelectorAll('.reveal');

            const revealObserver = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        revealObserver.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.12
            });

            revealItems.forEach((item) => revealObserver.observe(item));

            const counters = document.querySelectorAll('[data-counter]');

            const counterObserver = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) {
                        return;
                    }

                    const element = entry.target;
                    const target = parseInt(element.dataset.counter, 10);
                    const duration = 1200;
                    const startTime = performance.now();

                    const animate = (currentTime) => {
                        const progress = Math.min((currentTime - startTime) / duration, 1);
                        const value = Math.floor(progress * target);

                        element.textContent = value.toLocaleString('id-ID');

                        if (progress < 1) {
                            requestAnimationFrame(animate);
                        } else {
                            element.textContent = target.toLocaleString('id-ID');
                        }
                    };

                    requestAnimationFrame(animate);
                    counterObserver.unobserve(element);
                });
            }, {
                threshold: 0.7
            });

            counters.forEach((counter) => counterObserver.observe(counter));
        });
    </script>
</body>

</html>