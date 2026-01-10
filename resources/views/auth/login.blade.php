<!DOCTYPE html>
<html lang="id" class="h-full bg-white">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="icon" type="image/png" href="{{ asset('template/build/assets/img/favicon.png') }}" />
    <title>Masuk Sistem - KEMENLH</title>

    {{-- Tailwind & Fonts --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        /* DEFINE BRAND COLORS LOCALLY FOR CONSISTENCY */
        :root {
            --brand-teal-dark: #112826;
            /* Warna Gelap Logo */
            --brand-teal-primary: #0F766E;
            /* Warna Utama Teal */
            --brand-orange: #F97316;
            /* Warna Aksen Oranye */
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #334155;
            /* Slate 700 */
        }

        .font-heading {
            font-family: 'Space Grotesk', sans-serif;
        }

        /* SUBTLE PATTERN UNTUK PANEL KIRI (Topographic lines - Lingkungan Hidup theme) */
        .bg-pattern-topo {
            background-color: var(--brand-teal-dark);
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zM32 63c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm57-13c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%230F766E' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
        }

        /* Custom Focus Ring Color */
        .focus-ring-teal:focus {
            border-color: var(--brand-teal-primary);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
        }
    </style>
</head>

<body class="h-full">
    <div class="min-h-full flex">

        {{-- ========================================== --}}
        {{-- BAGIAN KIRI: BRAND PANEL (Desktop Only) --}}
        {{-- ========================================== --}}
        <div class="hidden lg:flex flex-1 w-0 bg-pattern-topo relative justify-center items-center overflow-hidden">
            {{-- Overlay Gradient Halus --}}
            <div class="absolute inset-0 bg-gradient-to-t from-[#0F172A] via-transparent to-transparent opacity-40">
            </div>

            <div class="relative z-10 max-w-lg px-10 text-center">
                {{-- Logo Container --}}
                <div class="bg-white/10 p-4 rounded-2xl inline-block mb-8 backdrop-blur-sm border border-white/10">
                    <img src="{{ asset('img/logo.png') }}" class="w-24 h-24 object-contain mx-auto" alt="Logo KemenLH">
                </div>

                <h1 class="font-heading text-4xl font-bold text-white mb-4 tracking-tight leading-tight">
                    Sistem Informasi <br> Biro SDM & Organisasi
                </h1>
                <div class="w-20 h-1.5 bg-orange-500 mx-auto rounded-full mb-6"></div>
                <p class="text-teal-100 text-lg leading-relaxed font-medium">
                    Kementerian Lingkungan Hidup Republik Indonesia. <br>
                    Mewujudkan tata kelola yang transparan dan akuntabel.
                </p>
            </div>

            {{-- Footer Copyright (Desktop) --}}
            <div class="absolute bottom-6 text-teal-200/60 text-xs font-medium tracking-wider uppercase">
                © {{ date('Y') }} Pusdatin KemenLH
            </div>
        </div>


        {{-- ========================================== --}}
        {{-- BAGIAN KANAN: FORMULIR (Mobile & Desktop) --}}
        {{-- ========================================== --}}
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24 bg-white">
            <div class="mx-auto w-full max-w-sm lg:max-w-md">

                {{-- HEADER MOBILE (Hanya muncul di layar kecil) --}}
                <div class="lg:hidden text-center mb-10">
                    <img src="{{ asset('img/logo.png') }}" class="w-16 h-16 object-contain mx-auto mb-4" alt="Logo">
                    <h2 class="font-heading text-2xl font-bold text-slate-900">Selamat Datang</h2>
                    <p class="text-slate-500 text-sm mt-1">Silakan masuk ke sistem.</p>
                </div>

                {{-- HEADER DESKTOP --}}
                <div class="hidden lg:block mb-10">
                    <h2 class="font-heading text-3xl font-bold text-slate-900 mb-2">Halo, Rekan ASN!</h2>
                    <p class="text-slate-600">Masukkan kredensial dinas Anda untuk melanjutkan.</p>
                </div>

                {{-- FORM --}}
                <div class="mt-8">
                    <form id="loginForm" method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        {{-- Input Email --}}
                        <div>
                            <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Email / NIP
                            </label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="w-5 h-5 text-slate-400">
                                        <path fill-rule="evenodd"
                                            d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="email" name="email" id="email" required
                                    class="focus-ring-teal block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-lg leading-5 bg-white placeholder-slate-400 focus:outline-none focus:bg-white transition-all sm:text-sm font-medium text-slate-900"
                                    placeholder="nama@kemenlh.go.id">
                            </div>
                        </div>

                        {{-- Input Password --}}
                        <div>
                            <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Kata Sandi
                            </label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="w-5 h-5 text-slate-400">
                                        <path fill-rule="evenodd"
                                            d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="password" name="password" id="password" required
                                    class="focus-ring-teal block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-lg leading-5 bg-white placeholder-slate-400 focus:outline-none focus:bg-white transition-all sm:text-sm font-medium text-slate-900"
                                    placeholder="••••••••">
                            </div>
                        </div>

                        {{-- Remember & Forgot --}}
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember_me" name="remember" type="checkbox"
                                    class="h-4 w-4 text-teal-700 focus:ring-teal-500 border-slate-300 rounded transition-all">
                                <label for="remember_me" class="ml-2 block text-sm text-slate-600 font-medium">
                                    Ingat perangkat ini
                                </label>
                            </div>
                            @if (Route::has('password.request'))
                                <div class="text-sm">
                                    <a href="{{ route('password.request') }}"
                                        class="font-bold text-orange-600 hover:text-orange-700 transition-colors">
                                        Lupa kata sandi?
                                    </a>
                                </div>
                            @endif
                        </div>

                        {{-- Button Aksi Utama (Orange) --}}
                        <div>
                            <button type="submit"
                                class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all transform active:scale-[0.98] uppercase tracking-wider">
                                Masuk Dashboard
                            </button>
                        </div>
                    </form>

                    <div class="mt-6 text-center border-t border-slate-100 pt-4">
                        <p class="text-sm text-slate-600">
                            Belum punya akun?
                            <a href="{{ route('register') }}"
                                class="font-bold text-teal-700 hover:text-teal-900 transition-colors">Daftar di sini</a>
                        </p>
                    </div>

                    {{-- Footer Mobile --}}
                    <div class="mt-10 lg:hidden text-center text-xs text-slate-400 font-medium">
                        © {{ date('Y') }} Kementerian Lingkungan Hidup
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const form = this;
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;

            // 1. Loading State
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="animate-pulse">Memproses...</span>';

            const formData = new FormData(form);

            try {
                const response = await fetch("{{ route('login') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    // SUKSES
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil Masuk!',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 1500,
                        heightAuto: false, // PENTING: Mencegah layout lompat/ngangkat
                        customClass: {
                            popup: 'rounded-2xl shadow-2xl font-sans'
                        }
                    }).then(() => {
                        window.location.href = data.redirect_url;
                    });

                } else {
                    // GAGAL (Password Salah / Belum Verifikasi)
                    throw new Error(data.message || 'Terjadi kesalahan.');
                }

            } catch (error) {
                // ERROR ALERT YANG LEBIH CANTIK
                Swal.fire({
                    title: 'Gagal Masuk',
                    text: error.message,
                    icon: 'error',
                    // Styling Tombol
                    confirmButtonText: 'OK, Mengerti',
                    buttonsStyling: false, // Matikan style bawaan agar bisa pakai Tailwind
                    heightAuto: false,     // PENTING: Mencegah layout lompat/ngangkat
                    customClass: {
                        popup: 'rounded-2xl shadow-2xl font-sans border border-slate-100 p-6',
                        title: 'text-xl font-bold text-slate-900',
                        htmlContainer: 'text-slate-500 mt-2',
                        confirmButton: 'bg-orange-500 hover:bg-orange-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-orange-500/30 mt-4 focus:outline-none'
                    }
                });

                // Reset Tombol Login
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        });
    </script>

</body>

</html>