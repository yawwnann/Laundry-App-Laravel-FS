<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use App\Filament\Widgets\LaporanStatsOverviewWidget; // Import widget
use App\Filament\Widgets\LaporanOrderTableWidget;  // Import widget tabel juga

class LaporanPesanan extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'Laporan Pesanan';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $title = 'Laporan Pesanan';
    protected static string $view = 'filament.pages.laporan-pesanan'; // Blade view kita

    public ?string $startDate = null;
    public ?string $endDate = null;

    // Tidak perlu properti statistik di sini lagi ($totalOrders, dll.)

    public function mount(): void
    {
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->endOfMonth()->toDateString();
        $this->dispatchReportUpdate();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('startDate')
                    ->label('Tanggal Mulai')
                    ->live()
                    ->afterStateUpdated(function () {
                        $this->dispatchReportUpdate();
                    }),
                DatePicker::make('endDate')
                    ->label('Tanggal Selesai')
                    ->live()
                    ->afterStateUpdated(function () {
                        $this->dispatchReportUpdate();
                    }),
            ])
            ->columns(2)
            ->fill([
                'startDate' => $this->startDate,
                'endDate' => $this->endDate,
            ]);
    }

    protected function dispatchReportUpdate(): void
    {
        // Kirim event ke kedua widget (Stats dan Tabel)
        $filters = ['startDate' => $this->startDate, 'endDate' => $this->endDate];
        $this->dispatch('updateLaporanStats', filters: $filters); // Untuk StatsOverviewWidget
        $this->dispatch('dateRangeUpdatedForTable', filters: $filters); // Untuk LaporanOrderTableWidget
    }

    public function filterToday(): void
    {
        $this->startDate = Carbon::now()->toDateString();
        $this->endDate = Carbon::now()->toDateString();
        $this->form->fill(['startDate' => $this->startDate, 'endDate' => $this->endDate]);
        $this->dispatchReportUpdate();
    }

    public function filterThisMonth(): void
    {
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->endOfMonth()->toDateString();
        $this->form->fill(['startDate' => $this->startDate, 'endDate' => $this->endDate]);
        $this->dispatchReportUpdate();
    }

    public function filterLastMonth(): void
    {
        $this->startDate = Carbon::now()->subMonthNoOverflow()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->subMonthNoOverflow()->endOfMonth()->toDateString();
        $this->form->fill(['startDate' => $this->startDate, 'endDate' => $this->endDate]);
        $this->dispatchReportUpdate();
    }

    // Mendaftarkan widget yang akan ditampilkan di halaman ini
    protected function getHeaderWidgets(): array
    {
        return [
            LaporanStatsOverviewWidget::class, // Widget statistik kita
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            LaporanOrderTableWidget::class, // Widget tabel kita
        ];
    }
}