<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Bantuan & Pertanyaan Umum (FAQ)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8 text-gray-900 dark:text-gray-100 space-y-8">

                    {{-- Bagian Alur Pemesanan --}}
                    <div x-data="{ open: false }">
                        <button @click="open = !open" class="flex justify-between items-center w-full text-left py-3 px-4 bg-gray-100 dark:bg-gray-700/50 hover:bg-gray-200 dark:hover:bg-gray-600/50 rounded-md focus:outline-none">
                            <span class="font-semibold text-lg text-gray-800 dark:text-gray-200">Alur Cara Melakukan Pemesanan</span>
                            <svg :class="{'transform rotate-180': open}" class="w-5 h-5 text-gray-500 dark:text-gray-400 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-collapse class="mt-3 px-4 py-2 border-l-4 border-indigo-500 dark:border-indigo-400 bg-gray-50 dark:bg-gray-700/30 rounded-b-md space-y-3 text-gray-700 dark:text-gray-300">
                            <p>Berikut adalah langkah-langkah mudah untuk melakukan pemesanan laundry di layanan kami:</p>
                            <ol class="list-decimal list-inside space-y-2">
                                <li><strong>Login atau Register:</strong> Jika Anda pengguna baru, silakan lakukan registrasi. Jika sudah memiliki akun, silakan login.</li>
                                <li><strong>Masuk ke Dashboard:</strong> Setelah login, Anda akan diarahkan ke dashboard pelanggan.</li>
                                <li><strong>Buat Pesanan Baru:</strong> Klik tombol "Buat Pesanan Baru" yang tersedia di dashboard atau menu navigasi.</li>
                                <li><strong>Pilih Layanan:</strong> Pilih satu atau lebih layanan laundry yang Anda inginkan dari daftar yang tersedia.</li>
                                <li><strong>Tentukan Kuantitas:</strong> Masukkan jumlah atau berat (sesuai satuan) untuk setiap layanan yang dipilih.</li>
                                <li><strong>Catatan Tambahan (Opsional):</strong> Jika ada instruksi khusus (misalnya, "Jangan disetrika terlalu panas"), Anda bisa menuliskannya di kolom catatan.</li>
                                <li><strong>Periksa Total Estimasi:</strong> Sistem akan menghitung total estimasi harga berdasarkan layanan dan kuantitas yang Anda pilih.</li>
                                <li><strong>Konfirmasi Pesanan:</strong> Klik tombol "Buat Pesanan" untuk mengirimkan pesanan Anda.</li>
                                <li><strong>Cek Riwayat Pesanan:</strong> Anda dapat melihat status dan detail pesanan Anda di halaman "Riwayat Pesanan".</li>
                            </ol>
                            <p>Jika ada pertanyaan lebih lanjut, jangan ragu untuk menghubungi kami!</p>
                        </div>
                    </div>

                    {{-- Bagian FAQ --}}
                    <div>
                        <h3 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-6 text-center">Pertanyaan Umum (FAQ)</h3>
                        <div class="space-y-6">
                            @if(isset($faqs) && count($faqs) > 0)
                                @foreach($faqs as $faq)
                                <div x-data="{ open: false }" class="bg-gray-50 dark:bg-gray-700/50 rounded-lg shadow">
                                    <button @click="open = !open" class="flex justify-between items-center w-full text-left py-4 px-5 focus:outline-none">
                                        <span class="font-medium text-gray-800 dark:text-gray-200">{{ $faq['question'] }}</span>
                                        <svg :class="{'transform rotate-180': open}" class="w-5 h-5 text-gray-500 dark:text-gray-400 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                    <div x-show="open" x-collapse class="px-5 pb-4 text-gray-600 dark:text-gray-400 text-sm">
                                        <p>{{ $faq['answer'] }}</p>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <p class="text-center text-gray-500 dark:text-gray-400">Belum ada FAQ yang tersedia.</p>
                            @endif
                        </div>
                    </div>

                    {{-- Informasi Kontak --}}
                     <div class="mt-10 pt-6 border-t dark:border-gray-700 text-center">
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Masih Butuh Bantuan?</h4>
                        <p class="text-gray-600 dark:text-gray-400">
                            Jika Anda tidak menemukan jawaban atas pertanyaan Anda, jangan ragu untuk menghubungi kami melalui:
                        </p>
                        <p class="mt-2">
                            <a href="mailto:kontak@laundryapp.com" class="text-indigo-600 dark:text-indigo-400 hover:underline">kontak@laundryapp.com</a>
                            <span class="mx-2 text-gray-400 dark:text-gray-600">|</span>
                            <span class="text-gray-700 dark:text-gray-300">Telepon/WA: 0812-3456-7890</span>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>