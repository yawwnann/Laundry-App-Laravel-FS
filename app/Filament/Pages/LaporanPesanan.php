<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form; // Import Form

class LaporanPesanan extends Page implements HasForms // Implementasikan HasForms
{
    use InteractsWithForms; // Gunakan trait ini

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'Laporan Pesanan';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $title = 'Laporan Pesanan';
    protected static string $view = 'filament.pages.laporan-pesanan'; // Menunjuk ke file blade kita

    // Properti untuk menyimpan data laporan
    public ?int $totalOrders = 0;
    public ?float $totalRevenue = 0.0;
    public ?int $newCustomers = 0;
    public array $ordersData = [];

    // Properti untuk filter tanggal
    public ?string $startDate = null;
    public ?string $endDate = null;

    public function mount(): void
    {
        // Default: tampilkan laporan untuk bulan ini
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->endOfMonth()->toDateString();
        $this->loadReportData();
    }

    // Mendefinisikan form untuk filter tanggal
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('startDate')
                    ->label('Tanggal Mulai')
                    ->default($this->startDate)
                    ->reactive()
                    ->afterStateUpdated(fn() => $this->loadReportData()),
                DatePicker::make('endDate')
                    ->label('Tanggal Selesai')
                    ->default($this->endDate)
                    ->reactive()
                    ->afterStateUpdated(fn() => $this->loadReportData()),
            ])
            ->columns(2)
            ->statePath('filterData'); // Menyimpan state form filter
    }

    // Untuk menyimpan data filter dari form ke properti kelas
    // (Tidak perlu jika sudah menggunakan statePath dan properti kelas langsung)
    // public array $filterData = []; 

    public function loadReportData(): void
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        $query = Order::whereBetween('created_at', [$start, $end]);

        $this->totalOrders = (clone $query)->count();
        $this->totalRevenue = (clone $query)->where('payment_status', 'lunas')->sum('total_amount');

        // Ambil daftar pesanan untuk tabel (contoh sederhana)
        $this->ordersData = (clone $query)
            ->with('customer') // Eager load relasi customer
            ->select('kode_pesanan', 'user_id', 'order_date', 'total_amount', 'status_pesanan', 'payment_status')
            ->orderBy('order_date', 'desc')
            ->take(100) // Batasi jumlah data yang diambil untuk tabel
            ->get()
            ->toArray();

        // Contoh menghitung pelanggan baru (yang order pertama kali dalam periode ini)
        $this->newCustomers = User::where('role', 'pelanggan')
            ->whereHas('orders', function ($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end]);
            })
            ->whereDoesntHave('orders', function ($q) use ($start) {
                $q->where('created_at', '<', $start);
            })
            ->count();
    }

    // Metode untuk filter cepat
    public function filterToday(): void
    {
        $this->startDate = Carbon::now()->toDateString();
        $this->endDate = Carbon::now()->toDateString();
        $this->loadReportData();
    }

    public function filterThisMonth(): void
    {
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->endOfMonth()->toDateString();
        $this->loadReportData();
    }

    public function filterLastMonth(): void
    {
        $this->startDate = Carbon::now()->subMonth()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->subMonth()->endOfMonth()->toDateString();
        $this->loadReportData();
    }
}