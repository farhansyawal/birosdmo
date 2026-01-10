<!DOCTYPE html>
<html lang="id" class="h-full bg-white">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="icon" type="image/png" href="{{ asset('template/build/assets/img/favicon.png') }}" />
    <title>Daftar Akun - KEMENLH</title>
    
    {{-- Tailwind & Fonts --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* BRAND COLORS */
        :root {
            --brand-teal-dark: #112826; 
            --brand-teal-primary: #0F766E;
            --brand-orange: #F97316;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            color: #334155;
            overflow: hidden; /* Mencegah scrollbar ganda di body */
        }
        .font-heading { font-family: 'Space Grotesk', sans-serif; }

        /* PATTERN */
        .bg-pattern-topo {
            background-color: var(--brand-teal-dark);
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zM32 63c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm57-13c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%230F766E' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
        }

        /* HIDE SCROLLBAR (Trik Utama) */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }

        .focus-ring-teal:focus {
            border-color: var(--brand-teal-primary);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.15);
        }
    </style>
</head>

<body class="h-full">
    <div class="min-h-full flex flex-row-reverse">
        
        {{-- ========================================== --}}
        {{-- BAGIAN KANAN: BRAND PANEL (Fixed)          --}}
        {{-- ========================================== --}}
        <div class="hidden lg:flex flex-1 w-0 bg-pattern-topo relative justify-center items-center overflow-hidden h-screen">
            <div class="absolute inset-0 bg-gradient-to-b from-[#0F172A]/80 via-transparent to-transparent opacity-60"></div>
            
            <div class="relative z-10 max-w-lg px-10 text-center">
                <div class="bg-white/10 p-5 rounded-3xl inline-block mb-6 backdrop-blur-sm border border-white/10 shadow-2xl">
                    <img src="{{ asset('img/logo.png') }}" class="w-24 h-24 object-contain mx-auto drop-shadow-lg" alt="Logo">
                </div>
                
                <h1 class="font-heading text-3xl font-bold text-white mb-4 tracking-tight leading-snug">
                    Bergabung dengan <br> <span class="text-orange-500">Ekosistem Digital</span> ASN
                </h1>
                
                <div class="space-y-3 text-teal-100/80 text-base font-medium">
                    <div class="flex items-center gap-3 justify-center">
                        <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Akses Data Kepegawaian</span>
                    </div>
                    <div class="flex items-center gap-3 justify-center">
                        <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Layanan Administrasi Cepat</span>
                    </div>
                </div>

                <div class="absolute bottom-6 left-0 w-full text-center text-teal-200/40 text-xs font-medium tracking-wider uppercase">
                    © {{ date('Y') }} Pusdatin KemenLH
                </div>
            </div>
        </div>


        {{-- ========================================== --}}
        {{-- BAGIAN KIRI: FORM (Scrollable if needed)   --}}
        {{-- ========================================== --}}
        <div class="flex-1 flex flex-col justify-center h-screen bg-white relative">
            
            {{-- Container Form (Dengan no-scrollbar class) --}}
            <div class="flex-1 overflow-y-auto no-scrollbar flex items-center justify-center py-6 px-4 sm:px-6 lg:px-20 xl:px-24">
                <div class="mx-auto w-full max-w-sm lg:max-w-md">
                    
                    {{-- HEADER MOBILE --}}
                    <div class="lg:hidden text-center mb-6">
                        <img src="{{ asset('img/logo.png') }}" class="w-12 h-12 object-contain mx-auto mb-2" alt="Logo">
                        <h2 class="font-heading text-xl font-bold text-slate-900">Buat Akun Baru</h2>
                    </div>

                    {{-- HEADER DESKTOP --}}
                    <div class="hidden lg:block mb-6">
                        <h2 class="font-heading text-2xl font-bold text-slate-900 mb-1">Registrasi Akun</h2>
                        <p class="text-slate-500 text-sm">Lengkapi data diri Anda di bawah ini.</p>
                    </div>

                    {{-- FORM COMPACT (Jarak diperkecil agar muat) --}}
                    <form role="form" method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf

                        {{-- Nama --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1 uppercase tracking-wide">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="focus-ring-teal block w-full px-4 py-2.5 border border-slate-300 rounded-lg bg-white placeholder-slate-400 focus:outline-none transition-all text-sm font-medium text-slate-900"
                                placeholder="Contoh: Budi Santoso">
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1 uppercase tracking-wide">Email Dinas</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="focus-ring-teal block w-full px-4 py-2.5 border border-slate-300 rounded-lg bg-white placeholder-slate-400 focus:outline-none transition-all text-sm font-medium text-slate-900"
                                placeholder="nama@kemenlh.go.id">
                        </div>

                        {{-- Unit Kerja --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1 uppercase tracking-wide">Unit Kerja</label>
                            <div class="relative">
                                <select name="unit_kerja" id="unit_kerja" required
                                    class="focus-ring-teal block w-full pl-4 pr-10 py-2.5 border border-slate-300 rounded-lg bg-white text-slate-900 focus:outline-none transition-all text-sm appearance-none cursor-pointer">
                                    <option value="">-- Sedang Memuat Data... --</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </div>
                        </div>

                        {{-- Password Grid --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1 uppercase tracking-wide">Kata Sandi</label>
                                <input type="password" name="password" required
                                    class="focus-ring-teal block w-full px-4 py-2.5 border border-slate-300 rounded-lg bg-white placeholder-slate-400 focus:outline-none transition-all text-sm font-medium text-slate-900"
                                    placeholder="••••••••">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1 uppercase tracking-wide">Ulangi Sandi</label>
                                <input type="password" name="password_confirmation" required
                                    class="focus-ring-teal block w-full px-4 py-2.5 border border-slate-300 rounded-lg bg-white placeholder-slate-400 focus:outline-none transition-all text-sm font-medium text-slate-900"
                                    placeholder="••••••••">
                            </div>
                        </div>

                        {{-- Term Text --}}
                        <p class="text-[10px] text-slate-400 leading-tight">
                            Dengan mendaftar, Anda menyetujui <a href="#" class="text-teal-700 hover:underline">Syarat & Ketentuan</a>.
                        </p>

                        {{-- Button --}}
                        <div class="pt-2">
                            <button type="submit"
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-bold text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all transform active:scale-[0.98] uppercase tracking-wider">
                                BUAT AKUN
                            </button>
                        </div>
                    </form>

                    <div class="mt-6 text-center border-t border-slate-100 pt-4">
                        <p class="text-sm text-slate-600">
                            Sudah punya akun? 
                            <a href="{{ route('login') }}" class="font-bold text-teal-700 hover:text-teal-900 transition-colors">Masuk di sini</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script JS Sama Seperti Sebelumnya --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const select = document.getElementById('unit_kerja');
            select.disabled = true;
            
            fetch('/api/unit-kerja')
                .then(res => res.json())
                .then(data => {
                    select.innerHTML = '<option value="">-- Pilih Unit Kerja --</option>';
                    data.forEach(u => {
                        let val = u.value || u.id;
                        let lbl = u.label || u.nama;
                        select.innerHTML += `<option value="${val}">${lbl}</option>`;
                    });
                    select.disabled = false;
                })
                .catch(() => {
                    select.innerHTML = '<option value="">Gagal memuat data</option>';
                });
        });

        const Toast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
            customClass: { popup: 'rounded-xl shadow-xl border border-slate-100 m-4 font-medium' },
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        @if($errors->any())
            Toast.fire({ icon: 'error', title: 'Gagal', text: '{{ $errors->first() }}' });
        @endif

        @if(session('success'))
            Toast.fire({ icon: 'success', title: 'Berhasil', text: '{{ session('success') }}' });
        @endif
    </script>

</body>
</html>