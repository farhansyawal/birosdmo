<nav class="relative flex flex-wrap items-center justify-between px-0 py-2 mx-6 transition-all shadow-none duration-250 ease-soft-in rounded-2xl lg:flex-nowrap lg:justify-start"
    navbar-main navbar-scroll="true">
    <div class="flex items-center justify-between w-full px-4 py-1 mx-auto flex-wrap-inherit">

        <nav>
            <ol class="flex flex-wrap pt-1 mr-12 bg-transparent rounded-lg sm:mr-16">
                <li class="text-sm leading-normal">
                    <a class="opacity-50 text-slate-700" href="javascript:;">Pages</a>
                </li>
                <li class="text-sm pl-2 capitalize leading-normal text-slate-700 before:float-left before:pr-2 before:text-gray-600 before:content-['/']"
                    aria-current="page">Dashboard</li>
            </ol>
            <h6 class="mb-0 font-bold capitalize">Dashboard</h6>
        </nav>

        <div class="flex items-center mt-2 grow sm:mt-0 sm:mr-6 md:mr-0 lg:flex lg:basis-auto">

            <ul class="flex flex-row justify-end pl-0 mb-0 list-none w-full md-max:w-full">

                <li class="flex items-center pl-4 xl:hidden">
                    <a href="javascript:;" class="block p-0 text-sm transition-all ease-nav-brand text-slate-500"
                        id="sidenav-trigger-btn">
                        <div class="w-4.5 overflow-hidden">
                            <i class="fas fa-bars text-lg"></i>
                        </div>
                    </a>
                </li>

                <li class="relative flex items-center pl-4">
                    @auth
                        <button id="userDropdownButton"
                            class="flex items-center px-3 py-2 text-sm font-semibold text-slate-700 hover:text-slate-900 transition-all rounded-lg">
                            <i class="fa fa-user sm:mr-2"></i>
                            <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 ml-1 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div id="userDropdownMenu"
                            class="hidden absolute right-0 mt-2 w-44 bg-white border border-gray-100 rounded-xl shadow-lg z-50"
                            style="top: 100%;">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg">
                                    <i class="fa fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                            class="block px-3 py-2 text-sm font-semibold text-slate-500 hover:text-slate-700 transition-all">
                            <i class="fa fa-user sm:mr-1"></i>
                            <span class="hidden sm:inline">Sign In</span>
                        </a>
                    @endauth
                </li>

            </ul>
        </div>
    </div>
</nav>