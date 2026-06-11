<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasien - Kurnia Care</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Tom Select CDN untuk searchable dropdown wilayah --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        .ts-wrapper {
            width: 100% !important;
        }

        .ts-wrapper.region-select {
            padding: 0 !important;
            border: none !important;
            background: transparent !important;
        }

        .ts-control {
            min-height: 48px !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            padding: 0.5rem 1rem !important;
            font-size: 0.875rem !important;
            background-color: #ffffff !important;
            box-shadow: none !important;
        }

        .ts-control:hover {
            border-color: #cbd5e1 !important;
        }

        .ts-control.focus {
            border-color: #10b981 !important;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.15) !important;
        }

        .ts-wrapper.single .ts-control {
            background-image: none !important;
        }

        .ts-dropdown {
            z-index: 9999 !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 0.75rem !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
            margin-top: 6px !important;
        }

        .ts-dropdown .content {
            max-height: 260px;
            overflow-y: auto;
        }

        .ts-dropdown .option {
            padding: 10px 14px !important;
            font-size: 0.875rem !important;
        }

        .ts-dropdown .active {
            background-color: #ecfdf5 !important;
            color: #047857 !important;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800 overflow-x-hidden">

    <div
        x-data="{
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
        }"
        class="min-h-screen flex"
    >

        {{-- Overlay Mobile --}}
        <div
            x-cloak
            x-show="sidebarOpen"
            x-transition.opacity.duration.200ms
            @click="sidebarOpen = false"
            class="fixed inset-0 bg-black/40 z-40 md:hidden"
        ></div>

        {{-- Sidebar --}}
        <aside
            x-cloak
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-emerald-700 text-white transform -translate-x-full transition-transform duration-300 ease-in-out md:translate-x-0"
        >
            <div class="p-6 border-b border-emerald-600 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">Kurnia Care</h1>
                    <p class="text-sm text-emerald-100">Pasien Panel</p>
                </div>

                <button
                    type="button"
                    @click="sidebarOpen = false"
                    class="md:hidden text-white text-2xl"
                >
                    ×
                </button>
            </div>

            <nav class="p-4 space-y-2">

                <a
                    href="{{ route('user.dashboard') }}"
                    @click.prevent="goTo('{{ route('user.dashboard') }}')"
                    class="block px-4 py-3 rounded-lg hover:bg-emerald-600 transition"
                >
                    Dashboard
                </a>

                <a
                    href="{{ route('user.appointments.create') }}"
                    @click.prevent="goTo('{{ route('user.appointments.create') }}')"
                    class="block px-4 py-3 rounded-lg hover:bg-emerald-600 transition"
                >
                    Pendaftaran Online
                </a>

                <a
                    href="{{ route('user.appointments.index') }}"
                    @click.prevent="goTo('{{ route('user.appointments.index') }}')"
                    class="block px-4 py-3 rounded-lg hover:bg-emerald-600 transition"
                >
                    Status Pendaftaran
                </a>

                <a
                    href="{{ route('user.appointments.index') }}"
                    @click.prevent="goTo('{{ route('user.appointments.index') }}')"
                    class="block px-4 py-3 rounded-lg hover:bg-emerald-600 transition"
                >
                    Pembayaran
                </a>

                <a
                    href="{{ route('user.appointments.index') }}"
                    @click.prevent="goTo('{{ route('user.appointments.index') }}')"
                    class="block px-4 py-3 rounded-lg hover:bg-emerald-600 transition"
                >
                    Riwayat
                </a>

            </nav>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col min-w-0 md:ml-64">

            {{-- Topbar --}}
            <header class="bg-white shadow-sm px-4 md:px-6 py-4 flex justify-between items-center gap-4">

                <div class="flex items-center gap-3 min-w-0">

                    <button
                        type="button"
                        @click="sidebarOpen = true"
                        class="md:hidden text-2xl text-emerald-700"
                    >
                        ☰
                    </button>

                    <div class="min-w-0">
                        <h2 class="text-lg md:text-xl font-semibold truncate">
                            @yield('title', 'Dashboard Pasien')
                        </h2>

                        <p class="text-xs md:text-sm text-gray-500 truncate">
                            Kelola pendaftaran sunat online Anda
                        </p>
                    </div>

                </div>

                <div class="flex items-center gap-2 md:gap-4 shrink-0">

                    <span class="hidden sm:block text-sm text-gray-600 max-w-[140px] truncate">
                        {{ auth()->user()->name }}
                    </span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button
                            type="submit"
                            class="px-3 md:px-4 py-2 bg-red-500 text-white text-sm rounded-lg hover:bg-red-600"
                        >
                            Logout
                        </button>
                    </form>

                </div>

            </header>

            {{-- Page Content --}}
            <main class="p-4 md:p-6 pb-28 min-h-screen overflow-y-auto">
                @yield('content')
            </main>

        </div>

    </div>

    @stack('scripts')

</body>

</html>