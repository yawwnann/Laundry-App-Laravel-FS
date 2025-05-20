<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dasbor Pelanggan') }}
            </h2>
            {{-- Tombol Aksi Cepat di Header (Opsional) --}}
            {{-- <a href="{{ route('customer.orders.create') }}"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold
            text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none
            focus:border-indigo-700 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
            Buat Pesanan Baru
            </a> --}}
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6"> {{-- Menambahkan space-y-6 untuk jarak antar blok --}}

            {{-- Pesan Sukses atau Error --}}
            @if (session('success'))
                <div class="bg-green-100 dark:bg-green-900 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-4 shadow-md rounded-md"
                    role="alert">
                    <div class="flex">
                        <div class="py-1"><svg class="fill-current h-6 w-6 text-green-500 mr-4"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path
                                    d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z" />
                            </svg></div>
                        <div>
                            <p class="font-bold">Sukses!</p>
                            <p class="text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 dark:bg-red-900 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-4 shadow-md rounded-md"
                    role="alert">
                    <div class="flex">
                        <div class="py-1"><svg class="fill-current h-6 w-6 text-red-500 mr-4"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path
                                    d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 5v6h2V5H9zm0 8h2v2H9v-2z" />
                            </svg></div>
                        <div>
                            <p class="font-bold">Error!</p>
                            <p class="text-sm">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Kartu Selamat Datang --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 sm:p-8 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col sm:flex-row items-center">
                        <div class="flex-shrink-0 mb-4 sm:mb-0 sm:mr-6 text-indigo-600 dark:text-indigo-400">
                            <svg class="h-16 w-16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold">
                                Halo, {{ Auth::user()->name }}!
                            </h3>
                            <p class="mt-1 text-gray-600 dark:text-gray-400">
                                Selamat datang di dasbor laundry Anda.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Menu Aksi Cepat (Kartu-kartu Navigasi) --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Menu Navigasi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {{-- Kartu Buat Pesanan Baru --}}
                    <a href="{{ route('customer.orders.create') }}"
                        class="group block p-6 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 overflow-hidden shadow-md hover:shadow-lg sm:rounded-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1">
                        <div class="flex items-center space-x-4">
                            <div
                                class="flex-shrink-0 p-3 bg-emerald-500 dark:bg-emerald-600 rounded-full text-white group-hover:scale-110 transition-transform duration-300">
                                <x-heroicon-o-shopping-bag class="w-7 h-7" />
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Buat Pesanan Baru
                                </h4>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Mulai laundry Anda sekarang
                                    juga.</p>
                            </div>
                        </div>
                    </a>

                    {{-- Kartu Lihat Layanan --}}
                    {{-- <a href="#" 
                       class="group block p-6 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 overflow-hidden shadow-md hover:shadow-lg sm:rounded-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 p-3 bg-sky-500 dark:bg-sky-600 rounded-full text-white group-hover:scale-110 transition-transform duration-300">
                                <x-heroicon-o-sparkles class="w-7 h-7"/>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Layanan Kami</h4>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Lihat semua layanan yang tersedia.</p>
                            </div>
                        </div>
                    </a> --}}

                    {{-- Kartu Riwayat Pesanan --}}
                    {{-- <a href="#"
                       class="group block p-6 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 overflow-hidden shadow-md hover:shadow-lg sm:rounded-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 p-3 bg-amber-500 dark:bg-amber-600 rounded-full text-white group-hover:scale-110 transition-transform duration-300">
                                <x-heroicon-o-list-bullet class="w-7 h-7"/>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Riwayat Pesanan</h4>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Cek status dan detail pesanan Anda.</p>
                            </div>
                        </div>
                    </a> --}}
                </div>
            </div>

            {{-- Anda bisa menambahkan section lain di sini, misalnya "Pesanan Aktif Anda" --}}

        </div>
    </div>
</x-app-layout>