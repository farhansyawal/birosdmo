<!--
=========================================================
* Soft UI Dashboard Tailwind - v1.0.5
=========================================================

* Product Page: https://www.creative-tim.com/product/soft-ui-dashboard-tailwind
* Copyright 2023 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png" />
    <link rel="icon" type="image/png" href="./assets/img/favicon.png" />
    <title>KEMENLH</title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <!-- Nucleo Icons -->
    <link href="{{ asset('template/build/assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('template/build/assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Popper -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Data Tables -->
    <!-- Flowbite JS -->
    <script src="https://unpkg.com/flowbite@1.8.1/dist/flowbite.min.js"></script>

    <!-- Simple DataTables -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" />
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
    <!-- Main Styling -->
    <link href="{{ asset('template/build/assets/css/soft-ui-dashboard-tailwind.css?v=1.0.5') }}" rel="stylesheet" />
    <!-- Nepcha Analytics (nepcha.com) -->
    <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
    <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
</head>

<body class="m-0 font-sans text-base antialiased font-normal leading-default bg-gray-50 text-slate-500">
    <!-- sidenav  -->
    @include('operator.layouts.sidebar')

    <!-- end sidenav -->

    <main class="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-200">
        <!-- Navbar -->
        @include('operator.layouts.navbar')

        <!-- end Navbar -->

        <!-- cards -->
        <div class="w-full flex justify-center my-6 px-3">
            <div class="w-full max-w-6xl">
                @yield('content')
            </div>
        </div>
        @include('operator.layouts.footer')
        <!-- end cards -->
    </main>
    <div fixed-plugin>
        <a fixed-plugin-button
            class="bottom-7.5 right-7.5 text-xl z-990 shadow-soft-lg rounded-circle fixed cursor-pointer bg-white px-4 py-2 text-slate-700">
            <i class="py-2 pointer-events-none fa fa-cog"> </i>
        </a>

    </div>
</body>
<!-- plugin for charts  -->
<script src="{{ asset('template/build/assets/js/plugins/chartjs.min.js') }}" async></script>
<!-- plugin for scrollbar  -->
<script src="{{ asset('template/build/assets/js/plugins/perfect-scrollbar.min.js') }}" async></script>
<!-- github button -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- main script file  -->
<script src="{{ asset('template/build/assets/js/soft-ui-dashboard-tailwind.js?v=1.0.5') }}" async></script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const table = document.querySelector("#flowbiteTable");
        if (table) {
            new simpleDatatables.DataTable(table, {
                fixedHeight: true,
                labels: {
                    placeholder: "Cari...",
                    perPage: "{select} entri per halaman",
                    noRows: "Tidak ada data",
                    info: "Menampilkan {start}–{end} dari {rows} data",
                }
            });
        }
    });
</script>
{{-- ✅ SweetAlert2 (for alert & toast) --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        // === Inisialisasi DataTable ===
        const tableEl = document.querySelector("#pengajuanTable");
        if (tableEl) {
            new simpleDatatables.DataTable(tableEl, {
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
        }

        // === Setup SweetAlert Toast ===
        window.Toast = Swal.mixin({
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
    });

    // === Fungsi Global Notifikasi ===
    function showToast(type, message) {
        Toast.fire({
            icon: type,
            title: message
        });
    }

    // === Fungsi Global Alert Konfirmasi ===
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

</html>