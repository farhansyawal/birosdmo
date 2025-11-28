<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png" />
    <link rel="icon" type="image/png" href="../assets/img/favicon.png" />
    <title>Register - KEMENLH</title>

    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

    <!-- Nucleo Icons -->
    <link href="{{ asset('template/build/assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('template/build/assets/css/nucleo-svg.css') }}" rel="stylesheet" />

    <!-- Main Styling -->
    <link href="{{ asset('template/build/assets/css/soft-ui-dashboard-tailwind.css?v=1.0.5') }}" rel="stylesheet" />
</head>

<body class="m-0 font-sans antialiased font-normal bg-white text-start text-base leading-default text-slate-500">

    <main class="mt-0 transition-all duration-200 ease-soft-in-out">
        <section class="min-h-screen mb-32">
            <div class="relative flex items-start pt-12 pb-56 m-4 overflow-hidden bg-center bg-cover min-h-50-screen rounded-xl"
                style="background-image: url('{{ asset('template/build/assets/img/curved-images/curved14.jpg') }}')">
                <span
                    class="absolute top-0 left-0 w-full h-full bg-center bg-cover bg-gradient-to-tl from-gray-900 to-slate-800 opacity-60"></span>
                <div class="container z-10">
                    <div class="flex flex-wrap justify-center -mx-3">
                        <div class="w-full max-w-full px-3 mx-auto mt-0 text-center lg:w-5/12">
                            <h1 class="mt-12 mb-2 text-white">Welcome!</h1>
                            <p class="text-white">Gunakan form di bawah untuk membuat akun baru.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="flex flex-wrap -mx-3 -mt-48 md:-mt-56 lg:-mt-48">
                    <div class="w-full max-w-full px-3 mx-auto md:w-7/12 lg:w-5/12 xl:w-4/12">
                        <div
                            class="relative z-0 flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                            <div class="p-6 mb-0 text-center bg-white border-b-0 rounded-t-2xl">
                                <h5>Buat Akun Baru</h5>
                            </div>

                            <div class="flex-auto p-6">
                                <form role="form" method="POST" action="{{ route('register') }}">
                                    @csrf

                                    <div class="mb-4">
                                        <input type="text" name="name" value="{{ old('name') }}"
                                            class="text-sm focus:shadow-soft-primary-outline leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-gray-300 bg-white py-2 px-3 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none"
                                            placeholder="Nama Lengkap" required />
                                    </div>

                                    <div class="mb-4">
                                        <input type="email" name="email" value="{{ old('email') }}"
                                            class="text-sm focus:shadow-soft-primary-outline leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-gray-300 bg-white py-2 px-3 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none"
                                            placeholder="Email" required />
                                    </div>

                                    <div class="mb-4">
                                        <select name="unit_kerja" id="unit_kerja"
                                            class="text-sm focus:shadow-soft-primary-outline leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-gray-300 bg-white py-2 px-3 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none"
                                            required>
                                            <option value="">-- Pilih Unit Kerja --</option>
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <input type="password" name="password"
                                            class="text-sm focus:shadow-soft-primary-outline leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-gray-300 bg-white py-2 px-3 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none"
                                            placeholder="Password" required />
                                    </div>

                                    <div class="mb-4">
                                        <input type="password" name="password_confirmation"
                                            class="text-sm focus:shadow-soft-primary-outline leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-gray-300 bg-white py-2 px-3 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none"
                                            placeholder="Konfirmasi Password" required />
                                    </div>

                                    <div class="text-center">
                                        <button type="submit"
                                            class="inline-block w-full px-6 py-3 mt-6 mb-2 font-bold text-center text-white uppercase transition-all bg-gradient-to-tl from-gray-900 to-slate-800 rounded-lg shadow-soft-md hover:scale-102">
                                            Daftar
                                        </button>
                                    </div>

                                    <p class="mt-4 mb-0 text-sm text-center">
                                        Sudah punya akun?
                                        <a href="{{ route('login') }}" class="font-bold text-slate-700">Masuk</a>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer class="py-12">
            <div class="container">
                <div class="flex flex-wrap -mx-3">
                    <div class="w-8/12 max-w-full px-3 mx-auto mt-1 text-center flex-0">
                        <p class="mb-0 text-slate-400">
                            Copyright ©
                            <script>
                                document.write(new Date().getFullYear());
                            </script>
                            Soft UI Dashboard by Creative Tim.
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </main>
    <script>
        // === Load Unit Kerja dari Excel ===
        document.addEventListener('DOMContentLoaded', () => {
            fetch('/api/unit-kerja')
                .then(res => res.json())
                .then(data => {
                    const select = document.getElementById('unit_kerja');
                    select.innerHTML = '<option value="">-- Pilih Unit Kerja --</option>';
                    data.forEach(u => select.innerHTML += `<option value="${u.value}">${u.label}</option>`);
                })
                .catch(() => {
                    document.getElementById('unit_kerja').innerHTML = '<option>Gagal memuat data</option>';
                });
        });
    </script>

    <!-- Scripts -->
    <script src="{{ asset('template/build/assets/js/plugins/perfect-scrollbar.min.js') }}" async></script>
    <script src="{{ asset('template/build/assets/js/soft-ui-dashboard-tailwind.js?v=1.0.5') }}" async></script>
</body>

</html>