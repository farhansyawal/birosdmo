@extends('user.layouts.app')

@section('title', 'Pengaturan Akun')

@section('content')
    @push('head')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <style>
            /* Modern Clean Input */
            .modern-input {
                background-color: #f8fafc; /* Slate-50 */
                border: 1px solid #e2e8f0; /* Slate-200 */
                transition: all 0.2s ease-in-out;
            }
            .modern-input:focus {
                background-color: #ffffff;
                border-color: #0f172a; /* Slate-900 (Hitam) */
                box-shadow: 0 0 0 2px rgba(15, 23, 42, 0.1); /* Ring Hitam Halus */
                outline: none;
            }
            
            /* Password Meter Bar */
            .meter-container {
                height: 4px;
                background-color: #e2e8f0;
                border-radius: 99px;
                overflow: hidden;
                margin-top: 8px;
            }
            .meter-fill {
                height: 100%;
                width: 0%;
                background-color: #ef4444; /* Default Merah */
                transition: width 0.3s ease, background-color 0.3s ease;
            }
            
            /* Syarat Checklist */
            .req-item { font-size: 0.7rem; color: #94a3b8; display: flex; align-items: center; gap: 4px; transition: color 0.2s; }
            .req-item.valid { color: #10b981; font-weight: 600; }
            .req-item.valid i { color: #10b981; }
        </style>
    @endpush

    <div class="w-full px-6 py-8 mx-auto">

        {{-- PAGE HEADER --}}
        <div class="flex items-end justify-between mb-10">
            <div>
                <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">Akun Saya</h3>
                <p class="text-slate-500 font-medium mt-1">Kelola profil dan keamanan akun Anda.</p>
            </div>
        </div>

        <form action="{{ route('user.profile.update') }}" method="POST" id="profileForm">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                {{-- KOLOM KIRI: PROFILE CARD (STICKY) --}}
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 text-center sticky top-6">
                        
                        {{-- Avatar --}}
                        <div class="relative w-28 h-28 mx-auto mb-6">
                            <div class="w-full h-full rounded-full bg-slate-900 flex items-center justify-center text-4xl font-bold text-white uppercase shadow-xl ring-4 ring-slate-50">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="absolute bottom-1 right-1 bg-white p-1 rounded-full">
                                <div class="bg-emerald-500 w-4 h-4 rounded-full border-2 border-white" title="Active"></div>
                            </div>
                        </div>

                        <h4 class="text-xl font-bold text-slate-800">{{ Auth::user()->name }}</h4>
                        <p class="text-sm font-medium text-slate-500 mb-6">{{ Auth::user()->email }}</p>

                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 text-left space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Role</span>
                                <span class="font-bold text-slate-800 uppercase">{{ Auth::user()->role ?? 'User' }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Bergabung</span>
                                <span class="font-bold text-slate-800">{{ Auth::user()->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: FORM EDIT --}}
                <div class="lg:col-span-8 space-y-8">

                    {{-- 1. INFORMASI DASAR --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="px-8 py-5 border-b border-slate-100 bg-slate-50/50">
                            <h5 class="font-bold text-slate-800">Informasi Dasar</h5>
                        </div>
                        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" class="modern-input w-full rounded-xl px-4 py-3 text-slate-700 text-sm font-medium" required>
                                @error('name') <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Alamat Email</label>
                                <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="modern-input w-full rounded-xl px-4 py-3 text-slate-700 text-sm font-medium" required>
                                @error('email') <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- 2. KEAMANAN PASSWORD (CORE FEATURE) --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="px-8 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                            <h5 class="font-bold text-slate-800">Ubah Password</h5>
                            <span class="text-xs bg-slate-200 text-slate-600 px-2 py-1 rounded font-bold">Opsional</span>
                        </div>
                        
                        <div class="p-8 space-y-6">
                            
                            {{-- Password Lama --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Password Saat Ini</label>
                                <div class="relative">
                                    <input type="password" name="current_password" placeholder="Masukan password lama..." class="modern-input w-full rounded-xl px-4 py-3 text-slate-700 text-sm">
                                    <i class="fas fa-lock absolute right-4 top-3.5 text-slate-400 text-sm"></i>
                                </div>
                                @error('current_password') <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                                {{-- Password Baru --}}
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Password Baru</label>
                                    <input type="password" name="new_password" id="newPassword" placeholder="Buat password baru..." class="modern-input w-full rounded-xl px-4 py-3 text-slate-700 text-sm">
                                    
                                    {{-- PASSWORD METER UI --}}
                                    <div class="mt-3" id="passwordMeter" style="opacity: 0.5; transition: opacity 0.3s;">
                                        <div class="flex justify-between items-end mb-1">
                                            <span class="text-[10px] font-bold text-slate-400 uppercase">Kekuatan</span>
                                            <span class="text-[10px] font-bold text-slate-500" id="strengthText">Lemah</span>
                                        </div>
                                        <div class="meter-container">
                                            <div class="meter-fill" id="strengthBar"></div>
                                        </div>
                                        {{-- Syarat Text --}}
                                        <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1">
                                            <span id="reqMin" class="req-item"><i class="fas fa-circle text-[4px]"></i> 8+ Karakter</span>
                                            <span id="reqCap" class="req-item"><i class="fas fa-circle text-[4px]"></i> 1 Huruf Besar</span>
                                            <span id="reqSym" class="req-item"><i class="fas fa-circle text-[4px]"></i> 1 Simbol (!@#)</span>
                                        </div>
                                    </div>

                                    @error('new_password') <p class="text-xs text-rose-500 mt-2 font-bold">{{ $message }}</p> @enderror
                                </div>

                                {{-- Konfirmasi --}}
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Ulangi Password</label>
                                    <input type="password" name="new_password_confirmation" id="confirmPassword" placeholder="Ketik ulang password..." class="modern-input w-full rounded-xl px-4 py-3 text-slate-700 text-sm">
                                    <p class="text-[10px] text-slate-400 mt-2 ml-1" id="matchText"></p>
                                </div>
                            </div>

                        </div>

                        {{-- FOOTER ACTION --}}
                        <div class="bg-slate-50 px-8 py-5 border-t border-slate-200 flex justify-end">
                            {{-- BUTTON HITAM YANG DIMINTA --}}
                            <button type="submit" class="bg-slate-900 hover:bg-black text-white text-sm font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-2 group">
                                <span>Simpan Perubahan</span>
                                <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. SWEETALERT TOAST
            const Toast = Swal.mixin({
                toast: true, position: 'top-end', showConfirmButton: false,
                timer: 3000, timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            @if(session('success'))
                Toast.fire({ icon: 'success', title: '{{ session('success') }}' });
            @endif

            @if($errors->any())
                Toast.fire({ icon: 'error', title: 'Validasi Gagal', text: 'Mohon periksa form kembali.' });
            @endif

            // 2. LOGIKA PASSWORD METER & VALIDASI VISUAL
            const passwordInput = document.getElementById('newPassword');
            const confirmInput = document.getElementById('confirmPassword');
            const meterBox = document.getElementById('passwordMeter');
            const bar = document.getElementById('strengthBar');
            const text = document.getElementById('strengthText');
            const matchText = document.getElementById('matchText');

            // Elements syarat
            const reqMin = document.getElementById('reqMin');
            const reqCap = document.getElementById('reqCap');
            const reqSym = document.getElementById('reqSym');

            passwordInput.addEventListener('input', function() {
                const val = passwordInput.value;
                meterBox.style.opacity = val.length > 0 ? '1' : '0.5';

                // Cek Kriteria
                const isLength = val.length >= 8;
                const isCapital = /[A-Z]/.test(val);
                const isSymbol = /[!@#$%^&*(),.?":{}|<>_+-]/.test(val);

                // Update Warna Text Syarat
                updateReq(reqMin, isLength);
                updateReq(reqCap, isCapital);
                updateReq(reqSym, isSymbol);

                // Hitung Kekuatan (0 - 100)
                let strength = 0;
                if(val.length > 0) strength += 10;
                if(isLength) strength += 30;
                if(isCapital) strength += 30;
                if(isSymbol) strength += 30;

                // Update Bar UI
                bar.style.width = strength + '%';

                if (strength < 40) {
                    bar.style.backgroundColor = '#ef4444'; // Merah
                    text.innerText = 'Lemah'; text.style.color = '#ef4444';
                } else if (strength < 100) {
                    bar.style.backgroundColor = '#f59e0b'; // Kuning
                    text.innerText = 'Sedang'; text.style.color = '#f59e0b';
                } else {
                    bar.style.backgroundColor = '#10b981'; // Hijau
                    text.innerText = 'Sangat Kuat'; text.style.color = '#10b981';
                }
            });

            // Cek Kesamaan Password
            confirmInput.addEventListener('input', function() {
                if (passwordInput.value === confirmInput.value && confirmInput.value !== '') {
                    matchText.innerHTML = '<span class="text-emerald-500 font-bold"><i class="fas fa-check"></i> Password Cocok</span>';
                } else if (confirmInput.value !== '') {
                    matchText.innerHTML = '<span class="text-rose-500 font-bold">Tidak Cocok</span>';
                } else {
                    matchText.innerHTML = '';
                }
            });

            function updateReq(el, valid) {
                if (valid) {
                    el.classList.add('valid');
                    el.querySelector('i').className = 'fas fa-check-circle';
                } else {
                    el.classList.remove('valid');
                    el.querySelector('i').className = 'fas fa-circle text-[4px]';
                }
            }
        });
    </script>
@endpush