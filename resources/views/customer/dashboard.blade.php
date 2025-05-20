<x-app-layout>
    {{-- Header Halaman --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-900 leading-tight">
            {{ __('Dasbor Pelanggan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Kartu Selamat Datang --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg mb-6">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col md:flex-row items-center">
                        <div class="flex-shrink-0 mb-4 md:mb-0 md:mr-6">
                            {{-- Ganti dengan ikon atau gambar yang lebih menarik jika ada --}}
                            <svg class="h-16 w-16 text-indigo-500 dark:text-indigo-400"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold">
                                {{ __("Selamat Datang Kembali, ") }} {{ Auth::user()->name }}!
                            </h3>
                            <p class="mt-2 text-gray-600 dark:text-gray-400">
                                Senang melihat Anda lagi. Mari kelola pesanan laundry Anda dengan mudah.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Menu Aksi Cepat (Contoh Kartu-kartu Navigasi) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- Kartu Lihat Layanan --}}
                <a href="#" {{-- Ganti # dengan route yang sesuai nanti --}}
                    class="block p-6 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 overflow-hidden shadow-md sm:rounded-lg transition duration-150 ease-in-out">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0 p-3 bg-sky-500 dark:bg-sky-600 rounded-full text-white">
                            <x-heroicon-o-sparkles class="w-6 h-6" /> {{-- Menggunakan ikon dari Breeze --}}
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Lihat Layanan</h4>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Cek daftar layanan laundry yang
                                kami tawarkan.</p>
                        </div>
                    </div>
                </a>

                {{-- Kartu Buat Pesanan Baru --}}
                <a href="#" {{-- Ganti # dengan route yang sesuai nanti --}}
                    class="block p-6 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 overflow-hidden shadow-md sm:rounded-lg transition duration-150 ease-in-out">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0 p-3 bg-emerald-500 dark:bg-emerald-600 rounded-full text-white">
                            <x-heroicon-o-shopping-bag class="w-6 h-6" />
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Buat Pesanan Baru</h4>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Siap untuk laundry? Buat pesanan
                                Anda sekarang.</p>
                        </div>
                    </div>
                </a>

                {{-- Kartu Riwayat Pesanan --}}
                <a href="#" {{-- Ganti # dengan route yang sesuai nanti --}}
                    class="block p-6 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 overflow-hidden shadow-md sm:rounded-lg transition duration-150 ease-in-out">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0 p-3 bg-amber-500 dark:bg-amber-600 rounded-full text-white">
                            <x-heroicon-o-list-bullet class="w-6 h-6" />
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Riwayat Pesanan</h4>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Lihat status dan detail semua
                                pesanan Anda.</p>
                        </div>
                    </div>
                </a>

                {{-- Anda bisa menambahkan kartu lain di sini --}}

            </div>

            {{-- Bagian lain bisa ditambahkan di bawah ini jika perlu --}}
            {{-- Contoh: Pengumuman atau Pesanan Terbaru --}}
            <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-3">Informasi Tambahan</h3>
                    <p>Belum ada pengumuman saat ini.</p>
                    {{-- Di sini bisa menampilkan ringkasan pesanan aktif, dll. --}}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>