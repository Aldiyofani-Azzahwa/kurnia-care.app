<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <!-- WAJIB buat responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kurnia Care</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <!-- NAVBAR -->
    <nav class="bg-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            
            <!-- Logo / Title -->
            <h1 class="text-lg md:text-xl font-bold">
                Kurnia Care
            </h1>

            <!-- Menu (Desktop) -->
            <div class="hidden md:flex gap-6">
                <a href="#" class="hover:underline">Home</a>
                <a href="#" class="hover:underline">Layanan</a>
                <a href="#" class="hover:underline">Kontak</a>
            </div>

            <!-- Menu Button (Mobile) -->
            <button id="menuBtn" class="md:hidden text-2xl">
                ☰
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden px-4 pb-4 md:hidden">
            <a href="#" class="block py-2">Home</a>
            <a href="#" class="block py-2">Layanan</a>
            <a href="#" class="block py-2">Kontak</a>
        </div>
    </nav>

    <!-- CONTENT -->
    <div class="max-w-7xl mx-auto px-4 mt-6">
        @yield('content')
    </div>

    <!-- SCRIPT MOBILE MENU -->
    <script>
        const btn = document.getElementById('menuBtn');
        const menu = document.getElementById('mobileMenu');

        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    </script>

</body>
</html>