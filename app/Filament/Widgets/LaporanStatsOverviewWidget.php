<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat; // Di v3, ini adalah Stat
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

class LaporanStatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = -3; // Agar muncul di atas widget lain jika di dashboard
    protected int|string|array $columnSpan = 'full';

    // Properti untuk menerima filter tanggal
    public ?string $startDate = null;
    public ?string $endDate = null;

    // Listener untuk event dari halaman kustom
    // Nama listener harus sesuai dengan nama metode tanpa 'on' dan huruf pertama kecil
    protected $listeners = ['updateLaporanStats' => 'updateFilters'];


    public function mount(): void
    {
        $this->startDate = $this->startDate ?? Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = $this->endDate ?? Carbon::now()->endOfMonth()->toDateString();
    }

    public function updateFilters(array $filters): void
    {
        $this->startDate = $filters['startDate'] ?? $this->startDate;
        $this->endDate = $filters['endDate'] ?? $this->endDate;
        // Widget akan otomatis me-refresh datanya karena perubahan properti yang di-track Livewire
    }

    protected function getStats(): array // Di v3 tetap getStats()
    {
        // Pastikan startDate dan endDate tidak null sebelum di-parse
        $start = $this->startDate ? Carbon::parse($this->startDate)->startOfDay() : Carbon::now()->startOfMonth()->startOfDay();
        $end = $this->endDate ? Carbon::parse($this->endDate)->endOfDay() : Carbon::now()->endOfMonth()->endOfDay();

        $query = Order::whereBetween('created_at', [$start, $end]);

        $totalOrders = (clone $query)->count();
        $totalRevenue = (clone $query)->where('payment_status', 'lunas')->sum('total_amount');

        $newCustomers = User::where('role', 'pelanggan')
            ->whereHas('orders', function ($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end]);
            })
            ->whereDoesntHave('orders', function ($q) use ($start) {
                $q->where('created_at', '<', $start);
            })
            ->count();

        return [
            Stat::make('Total Pesanan', $this->totalOrders ?? $totalOrders) // Ambil dari properti jika sudah di-set
                ->description('Pesanan dalam periode')
                ->descriptionIcon('heroicon-o-shopping-cart')
                ->color('primary')
                ->chart($this->generateDummyChartData()) // Contoh chart kecil
                ->extraAttributes([
                    'class' => 'cursor-pointer', // Contoh atribut tambahan
                ]),
            Stat::make('Total Pendapatan (Lunas)', 'Rp ' . number_format($this->totalRevenue ?? $totalRevenue, 0, ',', '.'))
                ->description('Pendapatan dari pesanan lunas')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success')
                ->chart($this->generateDummyChartData(true)),
            Stat::make('Pelanggan Baru', $this->newCustomers ?? $newCustomers)
                ->description('Pelanggan order pertama kali')
                ->descriptionIcon('heroicon-o-user-plus')
                ->color('info')
                ->chart($this->generateDummyChartData()),
        ];
    }

    // Fungsi dummy untuk data chart kecil di kartu stat
    protected function generateDummyChartData(bool $reverse = false): array
    {
        $data = collect(range(1, 7))->map(fn() => random_int(5, 20))->toArray();
        return $reverse ? array_reverse($data) : $data;
    }
}