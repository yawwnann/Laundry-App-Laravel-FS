<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ Auth::check() ? (Auth::user()->role === 'admin' ? route('filament.admin.pages.dashboard') : route('customer.dashboard')) : url('/') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-700 dark:text-gray-200" />
                    </a>
                </div>

                @auth
                    @if(Auth::user()->role === 'pelanggan')
                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <x-nav-link :href="route('customer.dashboard')" :active="request()->routeIs('customer.dashboard')"
                                class="text-gray-700 hover:text-indigo-700 dark:text-gray-300 dark:hover:text-indigo-400">
                                {{ __('Dasbor') }}
                            </x-nav-link>
                            <x-nav-link :href="route('customer.orders.create')" :active="request()->routeIs('customer.orders.create')"
                                class="text-gray-700 hover:text-indigo-700 dark:text-gray-300 dark:hover:text-indigo-400">
                                {{ __('Buat Pesanan') }}
                            </x-nav-link>
                            <x-nav-link :href="route('customer.orders.index')" :active="request()->routeIs('customer.orders.index')"
                                class="text-gray-700 hover:text-indigo-700 dark:text-gray-300 dark:hover:text-indigo-400">
                                {{ __('Riwayat Pesanan') }}
                            </x-nav-link>
                        </div>
                    @endif
                @endauth
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <button @click="darkMode = !darkMode" type="button"
                        class="p-2 mr-3 rounded-md text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition duration-150 ease-in-out">
                    <svg x-show="!darkMode" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg x-show="darkMode" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m8.66-15.66l-.707.707M5.05 18.95l-.707.707M21 12h-1M4 12H3m15.66 8.66l-.707-.707M6.76 5.05l-.707-.707" />
                    </svg>
                </button>

                @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 hover:text-gray-800 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                    class="text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
                @else
                <div class="hidden space-x-4 sm:flex">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-700 dark:text-gray-300 dark:hover:text-indigo-400">Log In</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-700 dark:text-gray-300 dark:hover:text-indigo-400">Register</a>
                    @endif
                </div>
                @endauth
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        @auth
            <div class="pt-2 pb-3 space-y-1">
                @if(Auth::user()->role === 'pelanggan')
                    <x-responsive-nav-link :href="route('customer.dashboard')" :active="request()->routeIs('customer.dashboard')"
                        class="text-gray-700 hover:text-indigo-700 dark:text-gray-300 dark:hover:text-indigo-400">
                        {{ __('Dasbor') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('customer.orders.create')" :active="request()->routeIs('customer.orders.create')"
                        class="text-gray-700 hover:text-indigo-700 dark:text-gray-300 dark:hover:text-indigo-400">
                        {{ __('Buat Pesanan') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('customer.orders.index')" :active="request()->routeIs('customer.orders.index')"
                        class="text-gray-700 hover:text-indigo-700 dark:text-gray-300 dark:hover:text-indigo-400">
                        {{ __('Riwayat Pesanan') }}
                    </x-responsive-nav-link>
                @endif
            </div>

            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')"
                        class="text-gray-700 hover:text-indigo-700 dark:text-gray-300 dark:hover:text-indigo-400">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <button @click="darkMode = !darkMode" type="button" class="w-full flex items-center ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-700 dark:text-gray-300 hover:text-indigo-700 dark:hover:text-indigo-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 focus:outline-none transition duration-150 ease-in-out">
                        <svg x-show="!darkMode" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                             <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                         <svg x-show="darkMode" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m8.66-15.66l-.707.707M5.05 18.95l-.707.707M21 12h-1M4 12H3m15.66 8.66l-.707-.707M6.76 5.05l-.707-.707" />
                        </svg>
                        <span x-text="darkMode ? 'Light Mode' : 'Dark Mode'"></span>
                    </button>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();"
                                class="text-gray-700 hover:text-indigo-700 dark:text-gray-300 dark:hover:text-indigo-400">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else {{-- Jika belum login, tampilkan link Login & Register di menu responsif --}}
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('login')"
                    class="text-gray-700 hover:text-indigo-700 dark:text-gray-300 dark:hover:text-indigo-400">
                    {{ __('Log In') }}
                </x-responsive-nav-link>
                @if (Route::has('register'))
                    <x-responsive-nav-link :href="route('register')"
                        class="text-gray-700 hover:text-indigo-700 dark:text-gray-300 dark:hover:text-indigo-400">
                        {{ __('Register') }}
                    </x-responsive-nav-link>
                @endif
            </div>
        @endauth
    </div>
</nav>