<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png" />
    <link rel="icon" type="image/png" href="./assets/img/favicon.png" />
    <title>KEMENLH</title>
    <style>
        .swal2-popup {
            background: #fff !important;
        }

        /* Hide scrollbar for Chrome, Safari and Opera */
        /* CSS untuk menyembunyikan batang scroll tapi tetap bisa di-scroll */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }
    </style>


    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <!-- ApexCharts (digunakan oleh Flowbite Chart) -->

    <!-- Nucleo Icons -->
    <link href="{{ asset('template/build/assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('template/build/assets/css/nucleo-svg.css') }}" rel="stylesheet" />

    <!-- Popper -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Flowbite -->
    <link defer href="https://unpkg.com/flowbite@1.8.1/dist/flowbite.min.css" rel="stylesheet" />
    <script defer src="https://unpkg.com/flowbite@1.8.1/dist/flowbite.min.js"></script>

    <!-- Simple DataTables -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" />
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>

    <!-- Soft UI Dashboard CSS -->
    <link href="{{ asset('template/build/assets/css/soft-ui-dashboard-tailwind.css?v=1.0.5') }}" rel="stylesheet" />
</head>

<body
    class="m-0 font-sans text-base antialiased font-normal leading-default bg-gray-50 text-slate-500 overflow-x-hidden">

    <!-- Sidenav -->
    @include('admin.layouts.sidebar')

    <!-- Main -->
    <main class="ease-soft-in-out xl:ml-72 relative h-full min-h-screen rounded-xl transition-all duration-200">

        <div class="sticky top-0 z-30 bg-gray-50/90 backdrop-blur-md">
            @include('admin.layouts.navbar')
        </div>

        <div class="w-full flex justify-center my-6 px-3">
            <div class="w-full max-w-6xl">
                @yield('content')
            </div>
        </div>

        @include('admin.layouts.footer')
    </main>



    <!-- Plugin Scripts -->

    <script src="{{ asset('template/build/assets/js/plugins/perfect-scrollbar.min.js') }}" async></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="{{ asset('template/build/assets/js/soft-ui-dashboard-tailwind.js?v=1.0.5') }}" async></script>

    <!-- ✅ SweetAlert2 (Toast + Alert) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // === DataTable Init ===
            const tableEls = document.querySelectorAll("#datatable");
            tableEls.forEach(table => {
                new simpleDatatables.DataTable(table, {
                    searchable: true,
                    fixedHeight: true,
                    labels: {
                        placeholder: "Cari...",
                        perPage: "{select} entri per halaman",
                        noRows: "Tidak ada data",
                        info: "Menampilkan {start}–{end} dari {rows} data",
                    },
                    perPageSelect: [5, 10, 25, 50],
                    perPage: 10,
                });
            });

            // === SweetAlert Toast Setup ===
            window.Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: '#fff',
                color: '#333',
                iconColor: '#16a34a',
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
        });

        // === Fungsi Global Notifikasi Toast ===
        function showToast(type, message) {
            Toast.fire({
                icon: type,
                title: message ?? (type === 'success' ?
                    'Berhasil!' :
                    type === 'error' ?
                        'Terjadi Kesalahan' :
                        'Informasi')
            });
        }

        // === Fungsi Global Alert Konfirmasi ===
        async function confirmAction(message, actionFn) {
            const result = await Swal.fire({
                title: 'Yakin?',
                text: message,
                icon: 'question',
                background: '#fff',
                color: '#1e293b',
                customClass: {
                    popup: 'shadow-lg rounded-xl'
                },
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

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // --- 1. LOGIKA SIDEBAR MOBILE ---
            const sidenav = document.getElementById('sidenav-main');
            const triggerBtn = document.getElementById('sidenav-trigger-btn'); // Tombol Hamburger
            const closeBtn = document.getElementById('sidenav-close-btn');     // Tombol X
            const overlay = document.getElementById('sidenav-overlay');

            function toggleSidenav() {
                if (!sidenav) return;
                // Jika sidebar sedang sembunyi (-translate-x-full), maka munculkan
                if (sidenav.classList.contains('-translate-x-full')) {
                    sidenav.classList.remove('-translate-x-full');
                    sidenav.classList.add('translate-x-0');
                    if (overlay) overlay.classList.remove('hidden');
                } else {
                    // Jika sedang muncul, sembunyikan
                    sidenav.classList.add('-translate-x-full');
                    sidenav.classList.remove('translate-x-0');
                    if (overlay) overlay.classList.add('hidden');
                }
            }

            // Event Klik Hamburger
            if (triggerBtn) {
                triggerBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    toggleSidenav();
                });
            }

            // Event Klik Close (X)
            if (closeBtn) {
                closeBtn.addEventListener('click', toggleSidenav);
            }

            // Event Klik Overlay (Tutup jika klik di luar)
            if (overlay) {
                overlay.addEventListener('click', toggleSidenav);
            }

            // --- 2. LOGIKA DROPDOWN USER ---
            const userBtn = document.getElementById('userDropdownButton');
            const userMenu = document.getElementById('userDropdownMenu');

            if (userBtn && userMenu) {
                userBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    userMenu.classList.toggle('hidden');
                });

                // Tutup dropdown jika klik di mana saja di luar tombol
                window.addEventListener('click', function (e) {
                    if (!userBtn.contains(e.target) && !userMenu.contains(e.target)) {
                        userMenu.classList.add('hidden');
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>