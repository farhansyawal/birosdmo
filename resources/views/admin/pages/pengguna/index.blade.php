@extends('admin.layouts.app')
@section('title', 'Data Pengguna')

@section('content')
    {{-- Import Font Inter agar lebih modern --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>

    <div class="w-full px-6 py-6 mx-auto">
        
        {{-- Header Section --}}
        <div class="flex flex-wrap items-center justify-between mb-6 gap-4">
            <div>
                <h3 class="text-2xl font-bold text-slate-800 tracking-tight">Data Pengguna</h3>
                <p class="text-sm text-slate-500 mt-1">Kelola akses dan status verifikasi pengguna sistem.</p>
            </div>
            <div class="flex items-center gap-2">
                <div class="bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm text-sm font-medium text-slate-600">
                    Total: <span class="text-slate-900 font-bold">{{ count($users) }}</span> Pengguna
                </div>
            </div>
        </div>

        {{-- Card Table --}}
        <div class="relative flex flex-col w-full min-w-0 mb-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                <h6 class="font-bold text-slate-700">Tabel Daftar Pengguna</h6>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-0 overflow-x-auto">
                    <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500" id="datatable">
                        <thead class="align-bottom">
                            <tr>
                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Pengguna
                                </th>
                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Status Verifikasi
                                </th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Akses Login
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr class="hover:bg-slate-50 transition-colors duration-200">
                                    {{-- Kolom 1: Avatar + Nama + Email (Digabung biar rapi) --}}
                                    <td class="p-4 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <div class="flex px-2 py-1">
                                            <div>
                                                {{-- Avatar Inisial --}}
                                                <div class="inline-flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-soft-in-out h-10 w-10 rounded-xl bg-gradient-to-tl from-slate-600 to-slate-300 shadow-md">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                            </div>
                                            <div class="flex flex-col justify-center">
                                                <h6 class="mb-0 text-sm leading-normal font-semibold text-slate-700">{{ $user->name }}</h6>
                                                <p class="mb-0 text-xs leading-tight text-slate-400">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Kolom 2: Status Verifikasi (Badge Modern) --}}
                                    <td class="p-4 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        @if ($user->is_verified)
                                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-100">
                                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                                <span class="text-xs font-semibold text-emerald-600">Terverifikasi</span>
                                            </div>
                                        @else
                                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50 border border-amber-100">
                                                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                                <span class="text-xs font-semibold text-amber-600">Menunggu</span>
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Kolom 3: Toggle Switch (Aksi) --}}
                                    <td class="p-4 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <div class="flex justify-center items-center">
                                            <label class="relative inline-flex items-center cursor-pointer group">
                                                <input type="checkbox" 
                                                       class="sr-only peer" 
                                                       onchange="toggleUserStatus({{ $user->id }}, this)"
                                                       {{ $user->is_verified ? 'checked' : '' }}>
                                                
                                                {{-- Switch Base --}}
                                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all after:duration-300 peer-checked:bg-blue-600 shadow-inner"></div>
                                                
                                                {{-- Label Status Kecil --}}
                                                <span class="ml-3 text-xs font-medium transition-colors duration-200 status-text-{{ $user->id }} {{ $user->is_verified ? 'text-blue-600' : 'text-slate-400' }}">
                                                    {{ $user->is_verified ? 'Aktif' : 'Non-Aktif' }}
                                                </span>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Javascript Logic (Sama seperti sebelumnya tapi lebih rapi alert-nya) --}}
    <script>
        function toggleUserStatus(id, checkbox) {
            let isActive = checkbox.checked;
            
            // Styling Alert Modern
            const alertConfig = {
                title: isActive ? 'Aktifkan Akun?' : 'Nonaktifkan Akun?',
                text: isActive ? "Pengguna akan diizinkan login kembali." : "Pengguna tidak akan bisa mengakses sistem.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: isActive ? '#3b82f6' : '#ef4444', // Blue vs Red
                cancelButtonColor: '#94a3b8',
                confirmButtonText: isActive ? 'Ya, Aktifkan' : 'Ya, Matikan',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl', // Membuat popup rounded modern
                    confirmButton: 'rounded-xl px-4 py-2',
                    cancelButton: 'rounded-xl px-4 py-2'
                }
            };

            Swal.fire(alertConfig).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/pages/pengguna/${id}/toggle-status`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                            "Accept": "application/json",
                        },
                        body: JSON.stringify({ status: isActive })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true,
                                customClass: { popup: 'rounded-xl' }
                            });
                            
                            Toast.fire({
                                icon: 'success',
                                title: data.message
                            });

                            // Update warna teks label secara realtime
                            let label = document.querySelector(`.status-text-${id}`);
                            if (label) {
                                label.innerText = isActive ? 'Aktif' : 'Non-Aktif';
                                if(isActive) {
                                    label.classList.remove('text-slate-400');
                                    label.classList.add('text-blue-600');
                                } else {
                                    label.classList.remove('text-blue-600');
                                    label.classList.add('text-slate-400');
                                }
                            }

                        } else {
                            checkbox.checked = !isActive;
                            Swal.fire("Gagal!", "Terjadi kesalahan sistem.", "error");
                        }
                    })
                    .catch(() => {
                        checkbox.checked = !isActive;
                        Swal.fire("Error!", "Gagal terhubung ke server.", "error");
                    });
                } else {
                    checkbox.checked = !isActive;
                }
            });
        }
    </script>
@endsection