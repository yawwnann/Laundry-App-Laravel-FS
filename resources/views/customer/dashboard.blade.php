<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dasbor Pelanggan') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8"> {{-- Menambah space-y agar lebih lega --}}

            {{-- ... (Pesan Sukses/Error dan Kartu Selamat Datang yang sudah ada) ... --}}
            @if (session('success'))
                <div class="bg-green-100 bg-opacity-75 dark:bg-green-800 dark:bg-opacity-75 border border-green-200 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded-md shadow-sm flex items-center"
                    role="alert">
                    <div class="mr-2">
                        <svg class="h-5 w-5 fill-current text-green-500" viewBox="0 0 20 20"
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
                <div class="bg-red-100 bg-opacity-75 dark:bg-red-800 dark:bg-opacity-75 border border-red-200 dark:border-red-600 text-red-700 dark:text-red-300 px-4 py-3 rounded-md shadow-sm flex items-center"
                    role="alert">
                    <div class="mr-2">
                        <svg class="h-5 w-5 fill-current text-red-500" viewBox="0 0 20 20"
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
            {{-- ... (Kartu navigasi yang sudah ada seperti Buat Pesanan, Riwayat Pesanan) ... --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Menu Navigasi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
                    <a href="{{ route('customer.orders.index') }}"
                        class="group block p-6 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 overflow-hidden shadow-md hover:shadow-lg sm:rounded-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1">
                        <div class="flex items-center space-x-4">
                            <div
                                class="flex-shrink-0 p-3 bg-amber-500 dark:bg-amber-600 rounded-full text-white group-hover:scale-110 transition-transform duration-300">
                                <x-heroicon-o-list-bullet class="w-7 h-7" />
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Riwayat Pesanan</h4>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Cek status dan detail semua
                                    pesanan Anda.</p>
                            </div>
                        </div>
                    </a>
                    {{-- Anda bisa menambahkan lebih banyak kartu navigasi di sini --}}
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-6">
                        Layanan Kami
                    </h3>
                    @if($services->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($services as $service)
                                <div
                                    class="bg-gray-50 dark:bg-gray-700/50 p-5 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                                    <div class="flex items-center mb-3">
                                        {{-- Ganti dengan ikon yang sesuai jika Anda punya --}}
                                        <div class="flex-shrink-0 p-2 bg-sky-500 dark:bg-sky-600 rounded-full text-white mr-3">
                                            <x-heroicon-o-sparkles class="w-5 h-5" />
                                            {{-- Contoh ikon, pastikan Heroicons terinstal atau ganti --}}
                                        </div>
                                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                            {{ $service->nama_layanan }}
                                        </h4>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                                        Satuan: {{ Str::ucfirst($service->satuan) }}
                                    </p>
                                    <p class="text-md font-bold text-indigo-600 dark:text-indigo-400">
                                        Rp {{ number_format($service->harga, 0, ',', '.') }}
                                    </p>
                                    @if($service->deskripsi)
                                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-500 italic">
                                            {{ Str::limit($service->deskripsi, 50) }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">Belum ada layanan yang tersedia saat ini.</p>
                    @endif
                </div>
            </div>

            {{-- Informasi Tambahan --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 sm:p-8 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-2">Informasi Tambahan</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Belum ada pengumuman saat ini.
                    </p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>