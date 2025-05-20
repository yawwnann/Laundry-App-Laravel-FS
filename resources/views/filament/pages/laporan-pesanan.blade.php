<x-filament-panels::page>

    {{-- Section untuk Filter --}}
    <x-filament::section collapsible>
        <x-slot name="heading">
            Filter Laporan
        </x-slot>

        {{-- Indikator Loading Global untuk Form --}}
        <div wire:loading wire:target="dispatchReportUpdate, filterToday, filterThisMonth, filterLastMonth"
            class="mb-4 text-center">
            <x-filament::loading-indicator class="h-6 w-6 inline-block text-primary-500" />
            <span class="text-sm text-gray-500">Memproses filter...</span>
        </div>

        {{ $form }} {{-- Form filter tanggal --}}

        <div class="mt-4 flex flex-wrap items-center gap-2">
            <x-filament::button wire:click="filterToday" color="gray" size="sm" outlined wire:loading.attr="disabled"
                wire:target="filterToday, dispatchReportUpdate">
                Hari Ini
            </x-filament::button>
            <x-filament::button wire:click="filterThisMonth" color="primary" size="sm" wire:loading.attr="disabled"
                wire:target="filterThisMonth, dispatchReportUpdate">
                Bulan Ini
            </x-filament::button>
            <x-filament::button wire:click="filterLastMonth" color="secondary" size="sm" outlined
                wire:loading.attr="disabled" wire:target="filterLastMonth, dispatchReportUpdate">
                Bulan Lalu
            </x-filament::button>
        </div>
    </x-filament::section>

    {{-- Widget akan dirender otomatis oleh Filament jika didaftarkan di getHeaderWidgets() atau getFooterWidgets() --}}
    {{-- Tidak perlu memanggil @livewire secara manual di sini lagi untuk widget yang terdaftar di page class --}}

</x-filament-panels::page>