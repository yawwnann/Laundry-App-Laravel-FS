<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-700 dark:text-gray-200 leading-tight">
                {{-- Sedikit menggelapkan teks header di light mode --}}
                {{ __('Dasbor Pelanggan') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Pesan Sukses atau Error --}}
            @if (session('success'))
                <div class="bg-green-50 dark:bg-green-900/60 border border-green-300 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded-md shadow-sm flex items-center"
                    role="alert">
                    <div class="mr-3">
                        <svg class="h-5 w-5 fill-current text-green-500 dark:text-green-400" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <strong class="font-bold">Sukses!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-50 dark:bg-red-900/60 border border-red-300 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-md shadow-sm flex items-center"
                    role="alert">
                    <div class="mr-3">
                        <svg class="h-5 w-5 fill-current text-red-500 dark:text-red-400" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <strong class="font-bold">Error!</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            {{-- Kartu Selamat Datang --}}
            <div
                class="bg-gradient-to-r from-indigo-500 to-purple-600 dark:from-indigo-700 dark:to-purple-800 text-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    <div class="flex flex-col sm:flex-row items-center">
                        <div class="flex-shrink-0 mb-4 sm:mb-0 sm:mr-6">
                            <svg class="h-20 w-20 text-indigo-300 dark:text-indigo-400"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold">
                                Halo, {{ Auth::user()->name }}!
                            </h3>
                            <p class="mt-2 text-indigo-100 dark:text-indigo-200 text-lg">
                                Selamat datang di dasbor laundry Anda. Kelola pesanan Anda dengan mudah!
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Menu Aksi Cepat --}}
            <div>
                <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-5">Apa yang ingin Anda lakukan?
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <a href="{{ route('customer.orders.create') }}"
                        class="group block p-6 bg-white dark:bg-gray-800/50 hover:bg-gray-50 dark:hover:bg-gray-700/70 ring-1 ring-gray-200 dark:ring-gray-700 hover:ring-indigo-500 dark:hover:ring-indigo-400 overflow-hidden shadow-lg hover:shadow-xl sm:rounded-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1">
                        <div class="flex items-start space-x-4">
                            <div
                                class="flex-shrink-0 p-3 bg-emerald-500 dark:bg-emerald-600 rounded-lg text-white group-hover:scale-110 transition-transform duration-300">
                                <x-heroicon-o-shopping-bag class="w-7 h-7" />
                            </div>
                            <div class="flex-1">
                                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Buat Pesanan Baru
                                </h4>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Mulai laundry Anda sekarang
                                    juga.</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('customer.orders.index') }}"
                        class="group block p-6 bg-white dark:bg-gray-800/50 hover:bg-gray-50 dark:hover:bg-gray-700/70 ring-1 ring-gray-200 dark:ring-gray-700 hover:ring-amber-500 dark:hover:ring-amber-400 overflow-hidden shadow-lg hover:shadow-xl sm:rounded-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1">
                        <div class="flex items-start space-x-4">
                            <div
                                class="flex-shrink-0 p-3 bg-amber-500 dark:bg-amber-600 rounded-lg text-white group-hover:scale-110 transition-transform duration-300">
                                <x-heroicon-o-list-bullet class="w-7 h-7" />
                            </div>
                            <div class="flex-1">
                                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Riwayat Pesanan</h4>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Cek status dan detail semua
                                    pesanan Anda.</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('customer.services.index') }}"
                        class="group block p-6 bg-white dark:bg-gray-800/50 hover:bg-gray-50 dark:hover:bg-gray-700/70 ring-1 ring-gray-200 dark:ring-gray-700 hover:ring-sky-500 dark:hover:ring-sky-400 overflow-hidden shadow-lg hover:shadow-xl sm:rounded-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1">
                        <div class="flex items-start space-x-4">
                            <div
                                class="flex-shrink-0 p-3 bg-sky-500 dark:bg-sky-600 rounded-lg text-white group-hover:scale-110 transition-transform duration-300">
                                <x-heroicon-o-clipboard-document-list class="w-7 h-7" />
                            </div>
                            <div class="flex-1">
                                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Layanan & Harga</h4>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Lihat semua jenis layanan dan
                                    daftar harga kami.</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800/50 ring-1 ring-gray-200 dark:ring-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                            Sekilas Layanan Kami
                        </h3>
                        <a href="{{ route('customer.services.index') }}"
                            class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                            Lihat Semua Layanan &rarr;
                        </a>
                    </div>
                    @if($services->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            @foreach ($services->take(4) as $service) {{-- Ambil beberapa layanan saja --}}
                                <div class="bg-gray-50 dark:bg-gray-700/60 p-4 rounded-lg shadow-sm">
                                    <h4 class="font-semibold text-gray-700 dark:text-gray-200">{{ $service->nama_layanan }}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Rp {{ number_format($service->harga, 0, ',', '.') }} / {{ $service->satuan }}
                                        <span
                                            class="text-xs text-gray-500 dark:text-gray-500">({{ $service->estimasi_durasi ?: '-' }})</span>
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">Belum ada layanan yang tersedia saat ini.</p>
                    @endif
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800/50 ring-1 ring-gray-200 dark:ring-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-2 md:mb-0">
                            Butuh Bantuan?
                        </h3>
                        <a href="{{ route('customer.help.index') }}"
                            class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                            Buka Halaman Bantuan & FAQ &rarr;
                        </a>
                    </div>
                    <div class="space-y-3 text-sm">
                        <div x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex justify-between items-center w-full text-left py-2 focus:outline-none">
                                <span class="font-medium text-gray-700 dark:text-gray-300">Bagaimana cara membuat
                                    pesanan baru?</span>
                                <svg :class="{'transform rotate-180': open}"
                                    class="w-4 h-4 text-gray-500 dark:text-gray-400 transition-transform duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="mt-1 px-1 text-gray-600 dark:text-gray-400">
                                <p>Klik "Buat Pesanan Baru", pilih layanan dan kuantitas, lalu konfirmasi pesanan Anda.
                                </p>
                            </div>
                        </div>
                        <div x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex justify-between items-center w-full text-left py-2 focus:outline-none">
                                <span class="font-medium text-gray-700 dark:text-gray-300">Di mana saya bisa melihat
                                    status pesanan?</span>
                                <svg :class="{'transform rotate-180': open}"
                                    class="w-4 h-4 text-gray-500 dark:text-gray-400 transition-transform duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="mt-1 px-1 text-gray-600 dark:text-gray-400">
                                <p>Anda dapat melihat semua status pesanan Anda di halaman "Riwayat Pesanan".</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Our Location Section -->
            <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        Lokasi Kami
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Kunjungi workshop kami di Yogyakarta. Kami siap melayani Anda!
                    </p>
                    <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden">
                        <iframe src="https://maps.google.com/maps?q=-7.797068,110.370529&hl=es;z=14&amp;output=embed"
                            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>