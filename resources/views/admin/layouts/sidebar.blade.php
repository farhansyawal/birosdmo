<aside class="max-w-62.5 ease-nav-brand fixed inset-y-0 z-50 
            flex flex-col w-full 
            bg-gray-50 border-0 p-0 antialiased shadow-none 
            transition-transform duration-300 
            -translate-x-full xl:translate-x-0 
            xl:left-0 xl:h-screen xl:rounded-none" id="sidenav-main">

    <div class="h-19.5 shrink-0 px-8 py-6 flex items-center justify-between">
        <a class="block m-0 text-sm whitespace-nowrap text-slate-700" href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('img/logo.png') }}"
                class="inline h-full max-w-full transition-all duration-200 ease-nav-brand max-h-8" alt="main_logo" />
            <span class="ml-1 font-semibold transition-all duration-200 ease-nav-brand">KEMENLH</span>
        </a>

        {{-- Close Button (Mobile) --}}
        <div class="xl:hidden cursor-pointer p-2" id="sidenav-close-btn" sidenav-close>
            <i class="fas fa-times text-slate-400 text-xl"></i>
        </div>
    </div>

    <hr class="h-px mt-0 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent shrink-0" />

    <div class="items-center block w-auto grow basis-full overflow-y-auto no-scrollbar">
        <ul class="flex flex-col pl-0 mb-0 pb-4">

            {{-- Logic PHP untuk Active State --}}
            @php
                $route = Route::currentRouteName();
            @endphp

            {{-- 1. DASHBOARD --}}
            <li class="mt-0.5 w-full">
                <a class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors rounded-lg
                    {{ request()->routeIs('admin.dashboard') ? 'bg-white shadow-soft-xl font-semibold text-slate-700' : 'text-slate-500 hover:text-slate-700' }}"
                    href="{{ route('admin.dashboard') }}">

                    <div
                        class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5
                        {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-tl from-purple-700 to-pink-500' : 'bg-white' }}">
                        <i
                            class="fas fa-tv text-{{ request()->routeIs('admin.dashboard') ? 'white' : 'slate-700' }}"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Dashboard</span>
                </a>
            </li>

            <li class="w-full mt-4">
                <h6 class="pl-6 ml-2 text-xs font-bold leading-tight uppercase opacity-60">Pages</h6>
            </li>

            {{-- 2. DATA PENGGUNA --}}
            <li class="mt-0.5 w-full">
                <a class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors rounded-lg
                    {{ request()->routeIs('admin.pengguna.*') ? 'bg-white shadow-soft-xl font-semibold text-slate-700' : 'text-slate-500 hover:text-slate-700' }}"
                    href="{{ route('admin.pengguna.index') }}">

                    <div
                        class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5
                        {{ request()->routeIs('admin.pengguna.*') ? 'bg-gradient-to-tl from-purple-700 to-pink-500' : 'bg-white' }}">
                        <i
                            class="fas fa-users text-{{ request()->routeIs('admin.pengguna.*') ? 'white' : 'slate-700' }}"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Manajemen Pengguna</span>
                </a>
            </li>

            {{-- 3. MANAJEMEN PENGAJUAN --}}
            <li class="mt-0.5 w-full">
                <a class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors rounded-lg
                    {{ request()->routeIs('admin.pengajuan.*') ? 'bg-white shadow-soft-xl font-semibold text-slate-700' : 'text-slate-500 hover:text-slate-700' }}"
                    href="{{ route('admin.pengajuan.index') }}">

                    <div
                        class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5
                        {{ request()->routeIs('admin.pengajuan.*') ? 'bg-gradient-to-tl from-purple-700 to-pink-500' : 'bg-white' }}">
                        <i
                            class="fas fa-file-invoice text-{{ request()->routeIs('admin.pengajuan.*') ? 'white' : 'slate-700' }}"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Manajemen Pengajuan</span>
                </a>
            </li>

            {{-- 4. LAYANAN --}}
            <li class="mt-0.5 w-full">
                <a class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors rounded-lg
                    {{ request()->routeIs('admin.layanan.*') ? 'bg-white shadow-soft-xl font-semibold text-slate-700' : 'text-slate-500 hover:text-slate-700' }}"
                    href="{{ route('admin.layanan.index') }}">

                    <div
                        class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5
                        {{ request()->routeIs('admin.layanan.*') ? 'bg-gradient-to-tl from-purple-700 to-pink-500' : 'bg-white' }}">
                        <i
                            class="fas fa-briefcase text-{{ request()->routeIs('admin.layanan.*') ? 'white' : 'slate-700' }}"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Manajemen Layanan</span>
                </a>
            </li>



            {{-- 5. MANAJEMEN BERANDA --}}
            <li class="mt-0.5 w-full">
                <a class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors rounded-lg
                    {{ request()->routeIs('admin.beranda.*') ? 'bg-white shadow-soft-xl font-semibold text-slate-700' : 'text-slate-500 hover:text-slate-700' }}"
                    href="{{ route('admin.beranda.edit') }}">

                    <div
                        class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5
                        {{ request()->routeIs('admin.beranda.*') ? 'bg-gradient-to-tl from-purple-700 to-pink-500' : 'bg-white' }}">
                        <i
                            class="fas fa-home text-{{ request()->routeIs('admin.beranda.*') ? 'white' : 'slate-700' }}"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Manajemen Beranda</span>
                </a>
            </li>

            {{-- 6. MANAJEMEN BERITA --}}
            <li class="mt-0.5 w-full">
                <a class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors rounded-lg
                    {{ request()->routeIs('admin.berita.*') ? 'bg-white shadow-soft-xl font-semibold text-slate-700' : 'text-slate-500 hover:text-slate-700' }}"
                    href="{{ route('admin.berita.index') }}">

                    <div
                        class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5
                        {{ request()->routeIs('admin.berita.*') ? 'bg-gradient-to-tl from-purple-700 to-pink-500' : 'bg-white' }}">
                        <i
                            class="fas fa-newspaper text-{{ request()->routeIs('admin.berita.*') ? 'white' : 'slate-700' }}"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Manajemen Berita</span>
                </a>
            </li>

            <li class="w-full mt-4">
                <h6 class="pl-6 ml-2 text-xs font-bold leading-tight uppercase opacity-60">Account Pages</h6>
            </li>

            {{-- 7. PROFILE UPDATE --}}
            <li class="mt-0.5 w-full">
                <a class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors rounded-lg
                    {{ request()->routeIs('admin.profile.*') ? 'bg-white shadow-soft-xl font-semibold text-slate-700' : 'text-slate-500 hover:text-slate-700' }}"
                    href="{{ route('admin.profile.index') }}">

                    <div
                        class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5
                        {{ request()->routeIs('admin.profile.*') ? 'bg-gradient-to-tl from-purple-700 to-pink-500' : 'bg-white' }}">
                        <i
                            class="fas fa-newspaper text-{{ request()->routeIs('admin.profile.*') ? 'white' : 'slate-700' }}"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Profile</span>
                </a>
            </li>

        </ul>
    </div>
</aside>

<div id="sidenav-overlay" class="fixed inset-0 bg-black/50 z-40 hidden xl:hidden transition-opacity"></div>