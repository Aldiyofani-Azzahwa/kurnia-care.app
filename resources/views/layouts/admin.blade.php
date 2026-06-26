<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kurnia Care</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    @include('partials.vite-assets')
    <link rel="stylesheet" href="/build/assets/app-BOyNhecd.css">
    <script type="module" src="/build/assets/app-BjMeHjpC.js"></script>

</head>

<body class="bg-gray-100 text-gray-800">

    <div x-data="{
            sidebarOpen: false,

            goTo(url) {
                if (window.innerWidth < 768) {
                    this.sidebarOpen = false;

                    setTimeout(() => {
                        window.location.href = url;
                    }, 320);
                } else {
                    window.location.href = url;
                }
            }
        }" class="min-h-screen flex">

        {{-- Mobile Overlay --}}
        <div x-cloak x-show="sidebarOpen" x-transition.opacity.duration.200ms @click="sidebarOpen = false"
            class="fixed inset-0 bg-black/40 z-40 md:hidden"></div>

        {{-- Sidebar --}}
        <aside x-cloak :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-emerald-700 text-white transform -translate-x-full transition-transform duration-300 ease-in-out md:translate-x-0">
            <div class="p-6 border-b border-emerald-600 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">Kurnia Care</h1>
                    <p class="text-sm text-emerald-100">Admin Panel</p>
                </div>

                <button type="button" @click="sidebarOpen = false" class="md:hidden text-white text-2xl">
                    ×
                </button>
            </div>

            <nav class="p-4 space-y-2">

                <a href="{{ route('admin.dashboard') }}" @click.prevent="goTo('{{ route('admin.dashboard') }}')"
                    class="block px-4 py-3 rounded-lg hover:bg-emerald-600 transition">
                    Dashboard
                </a>

                <a href="{{ route('admin.patients.index') }}"
                    @click.prevent="goTo('{{ route('admin.patients.index') }}')"
                    class="block px-4 py-3 rounded-lg transition
                    {{ request()->routeIs('admin.patients.*') ? 'bg-emerald-600 text-white font-semibold' : 'hover:bg-emerald-600' }}">
                    Pasien
                </a>

                <a href="{{ route('admin.doctors.index') }}" @click.prevent="goTo('{{ route('admin.doctors.index') }}')"
                    class="block px-4 py-3 rounded-lg transition
                    {{ request()->routeIs('admin.doctors.*') ? 'bg-emerald-600 text-white font-semibold' : 'hover:bg-emerald-600' }}">
                    Dokter
                </a>

                <a href="{{ route('admin.services.index') }}"
                    class="block px-4 py-3 rounded-lg transition
                 {{ request()->routeIs('admin.services.*') ? 'bg-emerald-600 text-white font-semibold' : 'hover:bg-emerald-600' }}">
                    Layanan
                </a>
                <a href="{{ route('admin.galleries.index') }}"
                    class="block px-4 py-3 rounded-lg transition
                   {{ request()->routeIs('admin.galleries.*') ? 'bg-emerald-600 text-white font-semibold' : 'hover:bg-emerald-600' }}">
                    Dokumentasi
                </a>
                <a href="{{ route('admin.testimonials.index') }}"
                    class="block px-4 py-3 rounded-lg transition
                    {{ request()->routeIs('admin.testimonials.*') ? 'bg-emerald-600 text-white font-semibold' : 'hover:bg-emerald-600' }}">
                    Testimoni
                </a>

                <a href="{{ route('admin.schedules.index') }}"
                    @click.prevent="goTo('{{ route('admin.schedules.index') }}')"
                    class="block px-4 py-3 rounded-lg transition
                    {{ request()->routeIs('admin.schedules.*') ? 'bg-emerald-600 text-white font-semibold' : 'hover:bg-emerald-600' }}">
                    Jadwal
                </a>

                <a href="{{ route('admin.payments.index') }}"
                    @click.prevent="goTo('{{ route('admin.payments.index') }}')"
                    class="block px-4 py-3 rounded-lg hover:bg-emerald-600 transition">
                    Pembayaran
                </a>

                <a href="{{ route('admin.reports.index') }}" @click.prevent="goTo('{{ route('admin.reports.index') }}')"
                    class="block px-4 py-3 rounded-lg transition
                    {{ request()->routeIs('admin.reports.*') ? 'bg-emerald-600 text-white font-semibold' : 'hover:bg-emerald-600' }}">
                    Laporan
                </a>

            </nav>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col min-w-0 md:ml-64">

            {{-- Topbar --}}
            <header class="bg-white shadow-sm px-4 md:px-6 py-4 flex justify-between items-center gap-4">

                <div class="flex items-center gap-3 min-w-0">

                    {{-- Hamburger Mobile --}}
                    <button type="button" @click="sidebarOpen = true" class="md:hidden text-2xl text-emerald-700">
                        ☰
                    </button>

                    <div class="min-w-0">
                        <h2 class="text-lg md:text-xl font-semibold truncate">
                            @yield('title', 'Dashboard Admin')
                        </h2>

                        <p class="text-xs md:text-sm text-gray-500 truncate">
                            Sistem Klinik Sunat Modern Kurnia Care
                        </p>
                    </div>

                </div>

                <div class="flex items-center gap-2 md:gap-4 shrink-0">

                    <span class="hidden sm:block text-sm text-gray-600 max-w-[140px] truncate">
                        {{ auth()->user()->name }}
                    </span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit"
                            class="px-3 md:px-4 py-2 bg-red-500 text-white text-sm rounded-lg hover:bg-red-600">
                            Logout
                        </button>
                    </form>

                </div>

            </header>

            {{-- Page Content --}}
            <main class="p-4 md:p-6">
                @yield('content')
            </main>

        </div>

    </div>

    @stack('scripts')

</body>

</html>