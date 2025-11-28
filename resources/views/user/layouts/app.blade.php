<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}" />
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}" />
    <title>KEMENLH</title>

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="{{ asset('template/build/assets/css/soft-ui-dashboard-tailwind.css?v=1.0.5') }}" rel="stylesheet" />

    <style>
        /* 1. Sembunyikan Scrollbar tapi tetap bisa scroll */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* 2. Navbar Sticky - Z-Index cukup 10 atau 20 (Jangan 1000) */
        [navbar-main] {
            position: sticky;
            top: 0;
            z-index: 10;
            /* Diturunkan agar di bawah Sidebar (z-50/999) */
            background-color: rgba(248, 249, 250, 0.8);
            backdrop-filter: blur(10px);
        }

        /* SweetAlert Fix */
        .swal2-popup {
            background: #fff !important;
        }
    </style>
</head>

<body class="m-0 font-sans text-base antialiased font-normal leading-default bg-gray-50 text-slate-500">

    @include('user.layouts.sidebar')

    <div id="sidenav-overlay" class="fixed inset-0 bg-black/50 z-[40] hidden xl:hidden transition-opacity duration-300">
    </div>

    <main class="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-200">

        @include('user.layouts.navbar')

        <div class="w-full flex justify-center my-6 px-3">
            <div class="w-full max-w-6xl">
                @yield('content')
            </div>
        </div>

        @include('user.layouts.footer')
    </main>

    <script src="{{ asset('template/build/assets/js/plugins/perfect-scrollbar.min.js') }}" async></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="{{ asset('template/build/assets/js/soft-ui-dashboard-tailwind.js?v=1.0.5') }}" async></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {

            // =================================================
            // 1. LOGIKA SIDEBAR & OVERLAY (MOBILE)
            // =================================================
            const sidenav = document.getElementById('sidenav-main');
            const triggerBtn = document.getElementById('sidenav-trigger-btn'); // Hamburger Navbar
            const closeBtn = document.getElementById('sidenav-close-btn');     // Close Sidebar
            const overlay = document.getElementById('sidenav-overlay');

            function toggleSidenav() {
                if (!sidenav) return;

                // Cek apakah sidebar sedang sembunyi (-translate-x-full)
                if (sidenav.classList.contains('-translate-x-full')) {
                    // BUKA
                    sidenav.classList.remove('-translate-x-full');
                    sidenav.classList.add('translate-x-0');
                    if (overlay) overlay.classList.remove('hidden');
                } else {
                    // TUTUP
                    sidenav.classList.add('-translate-x-full');
                    sidenav.classList.remove('translate-x-0');
                    if (overlay) overlay.classList.add('hidden');
                }
            }

            if (triggerBtn) triggerBtn.addEventListener('click', (e) => { e.stopPropagation(); toggleSidenav(); });
            if (closeBtn) closeBtn.addEventListener('click', toggleSidenav);
            if (overlay) overlay.addEventListener('click', toggleSidenav);


            // =================================================
            // 2. LOGIKA DROPDOWN USER
            // =================================================
            const userBtn = document.getElementById('userDropdownButton');
            const userMenu = document.getElementById('userDropdownMenu');

            if (userBtn && userMenu) {
                userBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    userMenu.classList.toggle('hidden');
                });
                window.addEventListener('click', (e) => {
                    if (!userBtn.contains(e.target) && !userMenu.contains(e.target)) {
                        userMenu.classList.add('hidden');
                    }
                });
            }


            // =================================================
            // 3. SETUP DATATABLES & SWEETALERT
            // =================================================

            // DataTable Init
            const tableEl = document.querySelector("#pengajuanTable, #flowbiteTable, #datatable");
            if (tableEl && typeof simpleDatatables !== 'undefined') {
                new simpleDatatables.DataTable(tableEl, {
                    searchable: true, fixedHeight: true, perPage: 10
                });
            }

            // SweetAlert Toast Config
            const Toast = Swal.mixin({
                toast: true, position: 'top-end', showConfirmButton: false,
                timer: 3000, timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });

            // Expose Global Functions
            window.showToast = (type, message) => Toast.fire({ icon: type, title: message });
            window.confirmAction = async (message, actionFn) => {
                const result = await Swal.fire({
                    title: 'Yakin?', text: message, icon: 'question',
                    showCancelButton: true, confirmButtonColor: '#2563eb', cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya', cancelButtonText: 'Batal'
                });
                if (result.isConfirmed && typeof actionFn === 'function') actionFn();
            };

            // Session Flash Messages
            @if(session('success'))
                Toast.fire({ icon: 'success', title: "{{ session('success') }}" });
            @elseif(session('error'))
                Toast.fire({ icon: 'error', title: "{{ session('error') }}" });
            @endif
        });
    </script>
    @stack('scripts')
</body>

</html>