<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detail Pesanan: ') }} {{ $order->kode_pesanan }}
            </h2>
            <a href="{{ route('customer.orders.index') }}"
                class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                &larr; Kembali ke Riwayat Pesanan
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Pesan Sukses atau Error --}}
            @if (session('success'))
                <div class="mb-6 bg-green-100 bg-opacity-75 dark:bg-green-800 dark:bg-opacity-75 border border-green-200 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded-md shadow-sm flex items-center"
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
                <div class="mb-6 bg-red-100 bg-opacity-75 dark:bg-red-800 dark:bg-opacity-75 border border-red-200 dark:border-red-600 text-red-700 dark:text-red-300 px-4 py-3 rounded-md shadow-sm flex items-center"
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

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 md:p-8 space-y-6">
                    {{-- Informasi Umum Pesanan --}}
                    <div class="border-b dark:border-gray-700 pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Informasi Umum</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400">Kode Pesanan:</p>
                                <p class="font-medium text-gray-800 dark:text-gray-200">{{ $order->kode_pesanan }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 dark:text-gray-400">Tanggal Pesan:</p>
                                <p class="font-medium text-gray-800 dark:text-gray-200">
                                    {{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->isoFormat('dddd, D MMMM YYYY, HH:mm') : 'N/A' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-600 dark:text-gray-400">Status Pesanan:</p>
                                <p class="font-medium">
                                    {{-- Menggunakan komponen badge --}}
                                    <x-status-badge :status="$order->status_pesanan" type="order" />
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-600 dark:text-gray-400">Status Pembayaran:</p>
                                <p class="font-medium">
                                    {{-- Menggunakan komponen badge --}}
                                    <x-status-badge :status="$order->payment_status" type="payment" />
                                </p>
                            </div>
                            @if($order->paid_at)
                                <div>
                                    <p class="text-gray-600 dark:text-gray-400">Tanggal Bayar:</p>
                                    <p class="font-medium text-gray-800 dark:text-gray-200">
                                        {{ \Carbon\Carbon::parse($order->paid_at)->isoFormat('dddd, D MMMM YYYY, HH:mm') }}
                                    </p>
                                </div>
                            @endif
                            @if($order->catatan_pelanggan)
                                <div class="md:col-span-2">
                                    <p class="text-gray-600 dark:text-gray-400">Catatan Anda:</p>
                                    <p class="font-medium text-gray-800 dark:text-gray-200 whitespace-pre-line">
                                        {{ $order->catatan_pelanggan }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Detail Item Pesanan --}}
                    <div class="border-b dark:border-gray-700 pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-2">Item Pesanan</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Layanan</th>
                                        <th
                                            class="px-4 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Kuantitas</th>
                                        <th
                                            class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Harga Satuan</th>
                                        <th
                                            class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($order->orderDetails as $detail)
                                        <tr>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                {{ $detail->service->nama_layanan ?? 'N/A' }}
                                            </td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-center text-gray-600 dark:text-gray-400">
                                                {{ $detail->quantity }} {{ $detail->service->satuan ?? '' }}
                                            </td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-600 dark:text-gray-400">
                                                Rp {{ number_format($detail->harga_saat_pesan, 0, ',', '.') }}</td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-right font-semibold text-gray-800 dark:text-gray-200">
                                                Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3"
                                            class="px-4 py-3 text-right text-sm font-semibold text-gray-800 dark:text-gray-200 uppercase">
                                            Total Keseluruhan:</td>
                                        <td
                                            class="px-4 py-3 text-right text-sm font-bold text-indigo-600 dark:text-indigo-400">
                                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    {{-- Aksi Pesanan --}}
                    <div class="mt-6 flex justify-end space-x-3">
                        @if($order->status_pesanan === 'baru')
                            <form action="{{ route('customer.orders.cancel', $order) }}" method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');">
                                @csrf
                                <x-danger-button type="submit">
                                    Batalkan Pesanan
                                </x-danger-button>
                            </form>
                        @endif
                        {{-- Tombol Cetak Struk bisa ditambahkan di sini nanti --}}
                        {{-- <x-secondary-button>
                            Cetak Struk
                        </x-secondary-button> --}}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>