<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png" />
    <link rel="icon" type="image/png" href="{{ asset('template/build/assets/img/favicon.png') }}" />
    <title>KEMENLH - Operator</title>
    
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    
    <link href="{{ asset('template/build/assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('template/build/assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://unpkg.com/flowbite@1.8.1/dist/flowbite.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" />
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
    
    <link href="{{ asset('template/build/assets/css/soft-ui-dashboard-tailwind.css?v=1.0.5') }}" rel="stylesheet" />
    
    <style>
        /* CSS Tambahan untuk sembunyikan scrollbar sidebar agar rapi */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="m-0 font-sans text-base antialiased font-normal leading-default bg-gray-50 text-slate-500">
    
    @include('operator.layouts.sidebar')

    <main class="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-200">
        
        @include('operator.layouts.navbar')

        <div class="w-full flex justify-center my-6 px-3">
            <div class="w-full max-w-6xl">
                @yield('content')
            </div>
        </div>
        
        @include('operator.layouts.footer')
    </main>

    <script src="{{ asset('template/build/assets/js/plugins/chartjs.min.js') }}" async></script>
    <script src="{{ asset('template/build/assets/js/plugins/perfect-scrollbar.min.js') }}" async></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="{{ asset('template/build/assets/js/soft-ui-dashboard-tailwind.js?v=1.0.5') }}" async></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            
            // ==========================================
            // 1. LOGIKA SIDEBAR MOBILE (HAMBURGER MENU)
            // ==========================================
            const sidenav = document.getElementById('sidenav-main');
            const triggerBtn = document.getElementById('sidenav-trigger-btn'); // Tombol Hamburger di Navbar
            const closeBtn = document.getElementById('sidenav-close-btn');     // Tombol X di Sidebar
            const overlay = document.getElementById('sidenav-overlay');        // Layar hitam (jika ada)

            function toggleSidenav() {
                if (!sidenav) return;

                // Cek apakah sidebar sedang sembunyi (-translate-x-full)
                if (sidenav.classList.contains('-translate-x-full')) {
                    // MUNCULKAN
                    sidenav.classList.remove('-translate-x-full');
                    sidenav.classList.add('translate-x-0');
                    sidenav.classList.add('shadow-soft-xl');
                    if(overlay) overlay.classList.remove('hidden');
                } else {
                    // SEMBUNYIKAN
                    sidenav.classList.add('-translate-x-full');
                    sidenav.classList.remove('translate-x-0');
                    sidenav.classList.remove('shadow-soft-xl');
                    if(overlay) overlay.classList.add('hidden');
                }
            }

            // Pasang Event Listener
            if (triggerBtn) {
                triggerBtn.addEventListener('click', (e) => {
                    e.stopPropagation(); // Mencegah klik tembus
                    toggleSidenav();
                });
            }

            if (closeBtn) {
                closeBtn.addEventListener('click', toggleSidenav);
            }

            if (overlay) {
                overlay.addEventListener('click', toggleSidenav);
            }


            // ==========================================
            // 2. LOGIKA USER DROPDOWN (NAVBAR)
            // ==========================================
            const userBtn = document.getElementById('userDropdownButton');
            const userMenu = document.getElementById('userDropdownMenu');

            if (userBtn && userMenu) {
                userBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    userMenu.classList.toggle('hidden');
                });

                // Tutup dropdown jika klik di luar
                window.addEventListener('click', (e) => {
                    if (!userBtn.contains(e.target) && !userMenu.contains(e.target)) {
                        userMenu.classList.add('hidden');
                    }
                });
            }


            // ==========================================
            // 3. LOGIKA DATATABLE & SWEETALERT
            // ==========================================
            const table = document.querySelector("#flowbiteTable"); // Cek ID tabel Anda
            if (table) {
                new simpleDatatables.DataTable(table, {
                    fixedHeight: true,
                    searchable: true,
                    perPageSelect: [5, 10, 25, 50],
                    labels: {
                        placeholder: "Cari...",
                        perPage: "{select} baris per halaman",
                        noRows: "Tidak ada data",
                        info: "Menampilkan {start}–{end} dari {rows} data",
                    }
                });
            }

            // Setup Toast Notifikasi
            window.Toast = Swal.mixin({
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
        });

        // Fungsi Global Helper
        function showToast(type, message) {
            Toast.fire({ icon: type, title: message });
        }

        async function confirmAction(message, actionFn) {
            const result = await Swal.fire({
                title: 'Yakin?',
                text: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Lanjutkan',
                cancelButtonText: 'Batal'
            });
            if (result.isConfirmed && typeof actionFn === 'function') {
                actionFn();
            }
        }
    </script>
    @stack('scripts')
</body>
</html>