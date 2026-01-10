<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Biro SDM & Organisasi')</title>

    {{-- CSS & AlpineJS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Style Tambahan --}}
    <style>
        [x-cloak] {
            display: none !important;
        }

        .nav-transition {
            transition: background-color 0.3s ease, padding 0.3s ease, box-shadow 0.3s ease;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 font-sans antialiased">

    {{-- Cek Halaman Home --}}
    @php $isHome = Request::is('/'); @endphp

    {{-- NAVBAR UTAMA --}}
    <nav class="fixed top-0 left-0 w-full z-50 nav-transition" x-data="{ 
            scrolled: false, 
            mobileOpen: false,
            isHome: {{ $isHome ? 'true' : 'false' }}
         }" @scroll.window="scrolled = (window.pageYOffset > 20)" :class="{
            'bg-white shadow-md py-3': scrolled || !isHome,
            'bg-transparent py-5': !scrolled && isHome
         }">

        <div class="container mx-auto px-6 flex items-center justify-between">

            {{-- 1. LOGO & JUDUL --}}
            <div class="flex items-center space-x-3 z-50">
                <img src="/img/logo.png" class="w-10 h-10 object-contain drop-shadow-md" alt="Logo">
                <span class="font-bold text-base whitespace-nowrap tracking-wide transition-colors duration-300"
                    :class="(scrolled || !isHome) ? 'text-gray-800' : 'text-white'">
                    Biro SDM & Organisasi
                </span>
            </div>

            {{-- 2. TOMBOL BURGER (MOBILE) --}}
            <button @click="mobileOpen = !mobileOpen"
                class="md:hidden text-2xl focus:outline-none transition-colors duration-300"
                :class="(scrolled || !isHome) ? 'text-gray-800' : 'text-white'">
                <span x-show="!mobileOpen">☰</span>
                <span x-show="mobileOpen">✕</span>
            </button>

            {{-- 3. MENU ITEMS --}}
            <ul :class="{'hidden': !mobileOpen, 'flex': mobileOpen}" class="hidden md:flex flex-col md:flex-row absolute md:static 
                       top-full left-0 w-full md:w-auto 
                       bg-white md:bg-transparent 
                       shadow-lg md:shadow-none 
                       px-6 md:px-0 py-6 md:py-0 
                       space-y-4 md:space-y-0 md:space-x-8 
                       text-left items-start md:items-center 
                       md:ml-auto transition-all">

                {{-- BERANDA --}}
                <li>
                    <a href="/" class="font-medium transition-colors duration-300"
                        :class="(scrolled || !isHome) ? 'text-gray-700 hover:text-blue-600' : 'text-gray-700 md:text-white hover:opacity-80'">
                        Beranda
                    </a>
                </li>

                {{-- LAYANAN --}}
                <li>
                    <a href="/layanan" class="font-medium transition-colors duration-300"
                        :class="(scrolled || !isHome) ? 'text-gray-700 hover:text-blue-600' : 'text-gray-700 md:text-white hover:opacity-80'">
                        Layanan
                    </a>
                </li>

                {{-- PUBLIKASI (DROPDOWN) --}}
                <li class="relative group w-full md:w-auto" x-data="{ dropOpen: false }">
                    <button @click="dropOpen = !dropOpen" @mouseenter="dropOpen = true" @mouseleave="dropOpen = false"
                        class="flex items-center justify-between w-full md:w-auto font-medium transition-colors duration-300"
                        :class="(scrolled || !isHome) ? 'text-gray-700 hover:text-blue-600' : 'text-gray-700 md:text-white hover:opacity-80'">
                        Publikasi
                        <span class="ml-1 text-xs">▼</span>
                    </button>

                    <ul x-show="dropOpen" @mouseenter="dropOpen = true" @mouseleave="dropOpen = false"
                        x-transition.opacity
                        class="md:absolute md:left-0 md:mt-2 bg-white rounded-xl md:shadow-xl w-full md:w-48 py-2 text-gray-700 text-sm border border-gray-100 hidden md:block group-hover:block">
                        <li><a href="{{ route('peraturan') }}"
                                class="block px-4 py-2 hover:bg-gray-50 hover:text-blue-600">Peraturan</a></li>
                        <li><a href="#" class="block px-4 py-2 hover:bg-gray-50 hover:text-blue-600">Data Statistik</a>
                        </li>
                        <li><a href="#" class="block px-4 py-2 hover:bg-gray-50 hover:text-blue-600">Buku</a></li>
                    </ul>
                </li>

                {{-- BERITA --}}
                <li>
                    <a href="/berita" class="font-medium transition-colors duration-300"
                        :class="(scrolled || !isHome) ? 'text-gray-700 hover:text-blue-600' : 'text-gray-700 md:text-white hover:opacity-80'">
                        Berita
                    </a>
                </li>

                {{-- KONTAK --}}
                <li>
                    <a href="/kontak" class="font-medium transition-colors duration-300"
                        :class="(scrolled || !isHome) ? 'text-gray-700 hover:text-blue-600' : 'text-gray-700 md:text-white hover:opacity-80'">
                        Kontak
                    </a>
                </li>

                {{-- DIVIDER MOBILE --}}
                <li class="w-full h-px bg-gray-200 md:hidden"></li>

                {{-- 4. USER / LOGIN --}}
                <li class="w-full md:w-auto" x-data="{ userOpen: false }">
                    @auth
                        <div class="relative">
                            <button @click="userOpen = !userOpen" @click.outside="userOpen = false"
                                class="flex items-center gap-2 font-bold focus:outline-none transition px-3 py-2 rounded-lg"
                                :class="(scrolled || !isHome) ? 'bg-gray-100 text-gray-800' : 'bg-gray-100 md:bg-white/20 text-gray-800 md:text-white hover:bg-white/30'">
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4 transition-transform" :class="{'rotate-180': userOpen}"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>

                            {{-- Dropdown User --}}
                            <div x-show="userOpen" x-transition
                                class="md:absolute right-0 mt-2 w-full md:w-56 bg-white rounded-xl shadow-xl py-2 z-50 border border-gray-100 text-gray-800">

                                @php
                                    $role = Auth::user()->role ?? 'user';
                                    $dashboardRoute = match ($role) {
                                        'admin' => route('admin.dashboard'),
                                        'operator' => route('operator.dashboard'),
                                        default => route('user.dashboard'),
                                    };
                                @endphp

                                <div class="px-4 py-2 border-b border-gray-100 bg-gray-50">
                                    <p class="text-xs text-gray-500 uppercase font-bold">Login sebagai</p>
                                    <p class="text-sm font-semibold truncate">{{ Auth::user()->email }}</p>
                                </div>

                                <a href="{{ $dashboardRoute }}"
                                    class="block px-4 py-2 text-sm hover:bg-blue-50 hover:text-blue-600 transition">
                                    Dashboard ({{ ucfirst($role) }})
                                </a>

                                <a href="{{ route('logout') }}"
                                    class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf
                                </form>
                            </div>
                        </div>
                    @endauth

                    @guest
                        <a href="{{ route('login') }}"
                            class="block w-full md:w-auto px-6 py-2.5 rounded-full text-sm font-bold shadow-lg transform active:scale-95 transition-all"
                            :class="(scrolled || !isHome) ? 'bg-gray-900 text-white hover:bg-gray-800' : 'bg-gray-900 md:bg-white text-white md:text-gray-900 hover:bg-gray-800 md:hover:bg-gray-100'">
                            Login
                        </a>
                    @endguest
                </li>
            </ul>
        </div>
    </nav>

    {{-- SPACER LOGIC: Hapus spacer di Home agar slider naik --}}
    @if(!$isHome)
        <div class="h-20 md:h-24"></div>
    @endif

    {{-- MAIN CONTENT --}}
    <main class="min-h-screen">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-gray-900 text-white text-center py-10 px-4 mt-auto">
        <div class="container mx-auto">
            <h3 class="font-bold text-lg mb-2">Kementerian Lingkungan Hidup / BPLH</h3>
            <p class="text-gray-400 mb-4">Biro Sumber Daya Manusia dan Organisasi</p>
            <div class="text-sm text-gray-500 space-y-1">
                <p>Gedung C Lantai 2, Jalan D.I. Panjaitan, Kav 24, Kebon Nanas, Jakarta Timur 13410</p>
                <p>Telp: (021) 2806 7681</p>
            </div>
        </div>
    </footer>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
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