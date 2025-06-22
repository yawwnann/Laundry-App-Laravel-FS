<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Layanan & Harga Kami') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8 text-gray-900 dark:text-gray-100">
                    <p class="text-gray-600 dark:text-gray-400 mb-8 text-center">
                        Temukan berbagai layanan laundry berkualitas yang kami tawarkan untuk memenuhi kebutuhan Anda.
                    </p>

                    @if($services->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($services as $service)
                                <div
                                    class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 flex flex-col">
                                    <div class="flex items-center mb-3">
                                        <div
                                            class="flex-shrink-0 p-3 {{ $service->tipe_paket === 'kilat' ? 'bg-red-500 dark:bg-red-600' : 'bg-sky-500 dark:bg-sky-600' }} rounded-full text-white mr-4">
                                            @if($service->tipe_paket === 'kilat')
                                                <x-heroicon-o-bolt class="w-6 h-6" />
                                            @else
                                                <x-heroicon-o-sparkles class="w-6 h-6" />
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                                {{ $service->nama_layanan }}</h4>
                                            <span
                                                class="text-xs px-2 py-0.5 rounded-full font-medium
                                                        {{ $service->tipe_paket === 'kilat' ? 'bg-red-200 text-red-700 dark:bg-red-600 dark:text-red-200' : 'bg-sky-200 text-sky-700 dark:bg-sky-600 dark:text-sky-200' }}">
                                                {{ Str::ucfirst($service->tipe_paket) }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="text-sm text-gray-700 dark:text-gray-300 space-y-1 mb-4 flex-grow">
                                        <p><span class="font-medium">Harga:</span> Rp
                                            {{ number_format($service->harga, 0, ',', '.') }} / {{ $service->satuan }}</p>
                                        <p><span class="font-medium">Estimasi:</span> {{ $service->estimasi_durasi ?: '-' }}</p>
                                        {{-- Jika Anda punya kolom deskripsi di model Service --}}
                                        {{-- @if($service->deskripsi)
                                                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400 italic">
                                                        {{ $service->deskripsi }}
                                        </p>
                                        @endif --}}
                                    </div>

                                    <div class="mt-auto pt-4 border-t dark:border-gray-600">
                                        <a href="{{ route('customer.orders.create', ['service_id' => $service->id]) }}"
                                            {{-- Mengirim service_id ke halaman create --}}
                                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                            Pesan Layanan Ini
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-gray-500 dark:text-gray-400 py-10">
                            Belum ada layanan yang tersedia saat ini. Silakan kembali lagi nanti.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>