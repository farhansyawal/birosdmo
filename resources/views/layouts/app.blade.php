<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Biro SDM & Organisasi')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-white text-gray-800">

    <nav class="fixed top-0 left-0 w-full bg-white shadow z-50">
        <div class="container mx-auto px-6 py-3 flex items-center justify-between">

            <div class="flex items-center space-x-3 z-50">
                <img src="/img/logo.png" class="w-8 h-8" alt="Logo">
                <span class="font-semibold text-gray-800 text-base whitespace-nowrap">
                    Biro SDM & Organisasi
                </span>
            </div>

            <button id="menuBtn" class="md:hidden text-gray-700 text-3xl focus:outline-none">
                ☰
            </button>

            <ul id="menuList" class="hidden md:flex flex-col md:flex-row absolute md:static 
                   top-full left-0 w-full md:w-auto bg-white md:bg-transparent 
                   shadow-md md:shadow-none px-6 md:px-0 py-4 md:py-0 
                   space-y-4 md:space-y-0 md:space-x-8
                   text-left items-start md:items-center
                   md:ml-auto">
                <li><a href="/" class="block text-gray-700 hover:text-gray-900 font-medium">Beranda</a></li>

                <li><a href="/layanan" class="block text-gray-700 hover:text-gray-900 font-medium">Layanan</a></li>

                <li class="relative group w-full md:w-auto">
                    <button
                        class="flex items-center justify-between w-full md:w-auto text-gray-700 hover:text-gray-900 font-medium">
                        Publikasi
                        <span class="ml-1">▾</span>
                    </button>

                    <ul
                        class="hidden group-hover:block md:absolute md:left-0 md:mt-3 bg-white rounded-md md:shadow-md w-full md:w-48 py-2 
                               transition-all duration-200 text-left border-l-2 md:border-l-0 border-gray-100 md:border-none pl-4 md:pl-0">
                        <li><a href="{{ route('peraturan') }}" class="block px-4 py-2 hover:bg-gray-100">Peraturan /
                                Kebijakan</a></li>
                        <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">Data Statistik</a></li>
                        <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">Buku</a></li>
                    </ul>
                </li>

                <li><a href="/berita" class="block text-gray-700 hover:text-gray-900 font-medium">Berita</a></li>

                <li><a href="/kontak" class="block text-gray-700 hover:text-gray-900 font-medium">Kontak</a></li>

                <li class="w-full h-px bg-gray-200 md:hidden"></li>

                <li class="w-full md:w-auto" x-data="{ open: false }">

                    @auth
                        <div class="relative">
                            <button @click="open = ! open"
                                class="w-full md:w-auto px-4 py-2 bg-gray-800 text-white rounded-lg text-sm flex justify-between md:justify-center items-center hover:bg-gray-700 focus:outline-none transition">
                                <span class="font-bold mr-1">
                                    {{ Auth::user()->name }}
                                </span>
                                <svg class="w-4 h-4 ml-1 transform transition-transform duration-200"
                                    :class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>

                            <div x-show="open" @click.outside="open = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                class="md:absolute right-0 mt-2 w-full md:w-48 bg-white rounded-md shadow-lg py-1 z-20 border border-gray-100 md:border-none">

                                @php
                                    $role = Auth::user()->role ?? 'user';
                                    $dashboardRoute = match ($role) {
                                        'admin' => route('admin.dashboard'),
                                        'operator' => route('operator.dashboard'),
                                        default => route('user.dashboard'),
                                    };
                                @endphp

                                <a href="{{ $dashboardRoute }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Dashboard ({{ ucfirst($role) }})
                                </a>

                                <div class="border-t border-gray-100 my-1"></div>

                                <a href="{{ route('logout') }}"
                                    class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    @endauth

                    @guest
                        <a href="{{ route('login') }}"
                            class="block w-full md:w-auto text-center px-6 py-2 bg-gray-900 text-white rounded-lg text-sm font-semibold hover:bg-gray-700 transition shadow-md active:scale-95">
                            Login
                        </a>
                    @endguest

                </li>

            </ul>

        </div>
    </nav>

    <div class="h-16 md:h-20"></div>

    <main class="min-h-screen">
        @yield('content')
    </main>

    <footer class="bg-gray-100 text-center text-xs md:text-sm text-gray-700 py-8 px-4 mt-10 border-t">
        <div class="container mx-auto">
            <p class="font-bold text-gray-900 mb-1">Kementerian Lingkungan Hidup / Badan Pengendalian Lingkungan Hidup
            </p>
            <p class="mb-2">Biro Sumber Daya Manusia dan Organisasi</p>
            <p class="text-gray-500">Gedung C Lantai 2, Jalan D.I. Panjaitan, Kav 24, Kebon Nanas, Jakarta Timur 13410
            </p>
            <p class="text-gray-500">Telp: (021) 2806 7681</p>
        </div>
    </footer>

    <script>
        const menuBtn = document.getElementById("menuBtn");
        const menuList = document.getElementById("menuList");

        // Toggle menu mobile
        menuBtn.addEventListener("click", () => {
            menuList.classList.toggle("hidden");
            menuList.classList.toggle("flex");
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });

            @if(session('success'))
                Toast.fire({ icon: 'success', title: "{{ session('success') }}" });
            @elseif(session('error'))
                Toast.fire({ icon: 'error', title: "{{ session('error') }}" });
            @endif
        });
    </script>

</body>

</html>