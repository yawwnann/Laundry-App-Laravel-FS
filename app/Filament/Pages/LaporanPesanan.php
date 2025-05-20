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
use App\Models\Order;
use App\Models\User;
use Filament\Actions\Action; // Import Action
use Barryvdh\DomPDF\Facade\Pdf; // Import Facade PDF
use Dompdf\Options;
use Illuminate\Support\Facades\Blade;

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
    protected function getHeaderActions(): array
    {
        return [
            Action::make('printPdf')
                ->label('Cetak PDF')
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->action(function () {
                    $start = Carbon::parse($this->startDate)->startOfDay();
                    $end = Carbon::parse($this->endDate)->endOfDay();

                    $baseQueryPdf = Order::whereBetween('created_at', [$start, $end]);
                    $pdfTotalOrders = (clone $baseQueryPdf)->count();
                    $pdfTotalRevenue = (clone $baseQueryPdf)->where('payment_status', 'lunas')->sum('total_amount');
                    $pdfNewCustomers = User::where('role', 'pelanggan')
                        ->whereHas('orders', function ($q) use ($start, $end) {
                            $q->whereBetween('created_at', [$start, $end]);
                        })
                        ->whereDoesntHave('orders', function ($q) use ($start) {
                            $q->where('created_at', '<', $start);
                        })
                        ->count();

                    $pdfOrdersData = (clone $baseQueryPdf)
                        ->with('customer:id,name')
                        ->select('kode_pesanan', 'user_id', 'order_date', 'total_amount', 'status_pesanan', 'payment_status')
                        ->orderBy('order_date', 'desc')
                        ->get()
                        ->map(function ($order) {
                            return $order->toArray();
                        })
                        ->all();

                    $dataForPdf = [
                        'startDate' => $this->startDate,
                        'endDate' => $this->endDate,
                        'totalOrders' => $pdfTotalOrders,
                        'totalRevenue' => $pdfTotalRevenue,
                        'newCustomers' => $pdfNewCustomers,
                        'ordersData' => $pdfOrdersData,
                    ];

                    $fileName = 'laporan-pesanan-' . Carbon::parse($this->startDate)->format('Ymd') . '-' . Carbon::parse($this->endDate)->format('Ymd') . '.pdf';

                    // Menggunakan array untuk setOptions
                    $pdf = Pdf::setOptions([
                        'isHtml5ParserEnabled' => true,
                        'isRemoteEnabled' => true, // Untuk gambar/CSS eksternal jika ada
                        'defaultCharset' => 'UTF-8', // Opsi untuk charset
                    ])->loadView('pdf.laporan-pesanan', $dataForPdf)
                        ->setPaper('a4', 'portrait');

                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->output();
                    }, $fileName);
                }),
        ];
    }
}