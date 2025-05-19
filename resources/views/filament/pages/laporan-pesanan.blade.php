<x-filament-panels::page>

    {{-- Section untuk Filter --}}
    <x-filament::section collapsible> {{-- Tambahkan collapsible jika ingin bisa disembunyikan --}}
        <x-slot name="heading">
            Filter Laporan
        </x-slot>

        {{ $this->form }} {{-- Ini akan merender form (DatePicker) dari kelas PHP --}}

        <div class="mt-4 flex flex-wrap items-center gap-2">
            <x-filament::button wire:click="filterToday" color="gray" size="sm" outlined>
                Hari Ini
            </x-filament::button>
            <x-filament::button wire:click="filterThisMonth" color="primary" size="sm">
                Bulan Ini
            </x-filament::button>
            <x-filament::button wire:click="filterLastMonth" color="secondary" size="sm" outlined>
                Bulan Lalu
            </x-filament::button>
        </div>
    </x-filament::section>

    {{-- Section untuk Statistik Utama (Stats Cards) --}}
    {{-- Menggunakan grid untuk tata letak kartu yang lebih baik --}}
    <div class="grid grid-cols-1 gap-4 py-6 filament-stats md:grid-cols-3 lg:gap-8">
        {{-- Card Total Pesanan --}}
        <x-filament::stats.card label="Total Pesanan" :value="$this->totalOrders" icon="heroicon-o-shopping-cart"
            color="primary" />

        {{-- Card Total Pendapatan --}}
        <x-filament::stats.card label="Total Pendapatan (Lunas)"
            value="Rp {{ number_format($this->totalRevenue, 0, ',', '.') }}" icon="heroicon-o-currency-dollar"
            color="success" />

        {{-- Card Pelanggan Baru --}}
        <x-filament::stats.card label="Pelanggan Baru" :value="$this->newCustomers" icon="heroicon-o-user-plus"
            color="info" />
    </div>


    {{-- Section untuk Tabel Daftar Pesanan --}}
    <x-filament::section collapsible>
        <x-slot name="heading">
            Daftar Pesanan (Max 100 Terbaru dalam Periode)
        </x-slot>

        @if(!empty($this->ordersData))
            <x-filament-tables::table class="w-full">
                <x-slot name="header">
                    <x-filament-tables::header-cell>Kode Pesanan</x-filament-tables::header-cell>
                    <x-filament-tables::header-cell>Pelanggan</x-filament-tables::header-cell>
                    <x-filament-tables::header-cell>Tgl Pesan</x-filament-tables::header-cell>
                    <x-filament-tables::header-cell class="text-right">Total</x-filament-tables::header-cell>
                    <x-filament-tables::header-cell class="text-center">Status</x-filament-tables::header-cell>
                    <x-filament-tables::header-cell class="text-center">Pembayaran</x-filament-tables::header-cell>
                </x-slot>

                @foreach($this->ordersData as $order)
                    <x-filament-tables::row wire:key="order-{{ $order['kode_pesanan'] }}">
                        <x-filament-tables::cell>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $order['kode_pesanan'] }}
                            </span>
                        </x-filament-tables::cell>
                        <x-filament-tables::cell>
                            <span class="text-sm text-gray-900 dark:text-white">
                                {{ $order['customer']['name'] ?? 'N/A' }}
                            </span>
                        </x-filament-tables::cell>
                        <x-filament-tables::cell>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $order['order_date'] ? \Carbon\Carbon::parse($order['order_date'])->isoFormat('D MMM YY, HH:mm') : 'N/A' }}
                            </span>
                        </x-filament-tables::cell>
                        <x-filament-tables::cell class="text-right">
                            <span class="text-sm text-gray-900 dark:text-white">
                                Rp {{ number_format($order['total_amount'], 0, ',', '.') }}
                            </span>
                        </x-filament-tables::cell>
                        <x-filament-tables::cell class="text-center">
                            <x-filament::badge :color="match ($order['status_pesanan']) {
                        'baru' => 'gray',
                        'diproses' => 'warning',
                        'siap diambil' => 'info',
                        'selesai' => 'success',
                        'dibatalkan' => 'danger',
                        default => 'primary',
                    }">
                                {{ Str::ucfirst($order['status_pesanan']) }}
                            </x-filament::badge>
                        </x-filament-tables::cell>
                        <x-filament-tables::cell class="text-center">
                            <x-filament::badge :color="$order['payment_status'] == 'lunas' ? 'success' : 'danger'">
                                {{ Str::ucfirst(str_replace('_', ' ', $order['payment_status'])) }}
                            </x-filament::badge>
                        </x-filament-tables::cell>
                    </x-filament-tables::row>
                @endforeach
            </x-filament-tables::table>
        @else
            <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                Tidak ada data pesanan untuk periode ini.
            </div>
        @endif
    </x-filament::section>

</x-filament-panels::page>