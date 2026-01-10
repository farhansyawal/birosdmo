<aside class="max-w-62.5 ease-nav-brand fixed inset-y-0 w-full 
           bg-white p-0 antialiased shadow-2xl transition-transform duration-300 
           
           {{-- Z-INDEX TINGGI (999) AGAR DI ATAS NAVBAR --}}
           z-[999]

           {{-- LOGIKA MOBILE: Full Screen, Kotak, Margin 0 --}}
           -translate-x-full h-screen m-0 rounded-none
           
           {{-- LOGIKA DESKTOP: Floating, Rounded, Ada Margin --}}
           xl:translate-x-0 xl:my-4 xl:ml-4 xl:h-[calc(100vh-2rem)] xl:rounded-2xl xl:shadow-none" id="sidenav-main">

    <div class="h-19.5 shrink-0 px-8 py-6 flex items-center justify-between">
        <a class="block m-0 text-sm whitespace-nowrap text-slate-700" href="javascript:;" target="_blank">
            <img src="{{ asset('img/logo.png') }}"
                class="inline h-full max-w-full transition-all duration-200 ease-nav-brand max-h-8" alt="main_logo" />
            <span class="ml-1 font-semibold transition-all duration-200 ease-nav-brand">KEMENLH</span>
        </a>

        <div class="xl:hidden cursor-pointer p-2" id="sidenav-close-btn">
            <i class="fas fa-times text-slate-400 text-xl"></i>
        </div>
    </div>

    <hr class="h-px mt-0 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent shrink-0" />

    <div class="items-center block w-auto grow basis-full overflow-y-auto no-scrollbar">
        <ul class="flex flex-col pl-0 mb-0 pb-4">

            <li class="mt-0.5 w-full">
                <a class="py-2.7 shadow-soft-xl text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg bg-white px-4 font-semibold text-slate-700 transition-colors"
                    href="{{ route('user.dashboard') }}">
                    <div
                        class="bg-gradient-to-tl from-purple-700 to-pink-500 shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                        <i class="fas fa-home text-white"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Dashboard</span>
                </a>
            </li>

            <li class="mt-0.5 w-full">
                <a class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors"
                    href="{{ route('user.pages.pengajuan') }}">
                    <div
                        class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                        <i class="fas fa-edit text-slate-800"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Pengajuan Layanan</span>
                </a>
            </li>

            <li class="mt-0.5 w-full">
                <a class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors"
                    href="{{ route('user.pages.riwayat-pengajuan') }}">
                    <div
                        class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                        <i class="fas fa-history text-slate-800"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Riwayat Pengajuan</span>
                </a>
            </li>

            <li class="mt-4 w-full">
                <h6 class="pl-6 ml-2 text-xs font-bold leading-tight uppercase opacity-60">Account pages</h6>
            </li>

            <li class="mt-0.5 w-full">
                <a class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors"
                    href="{{ route('user.profile.index') }}">
                    <div
                        class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                        <i class="fas fa-user text-slate-800"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Profile</span>
                </a>
            </li>

        </ul>
    </div>
</aside>

<div id="sidenav-overlay" class="fixed inset-0 bg-black/50 z-40 hidden xl:hidden transition-opacity"></div>