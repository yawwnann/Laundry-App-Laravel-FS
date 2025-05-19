<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class LaporanOrderTableWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 'full';

    public ?string $startDate = null;
    public ?string $endDate = null;

    protected $listeners = ['dateRangeUpdatedForTable' => 'applyDateRangeFilter'];

    public function mount(): void
    {
        $this->startDate = $this->startDate ?? Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = $this->endDate ?? Carbon::now()->endOfMonth()->toDateString();
    }

    public function applyDateRangeFilter(array $filters): void
    {
        $this->startDate = $filters['startDate'] ?? $this->startDate;
        $this->endDate = $filters['endDate'] ?? $this->endDate;
        $this->resetTable();
    }

    protected function getTableQuery(): Builder
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        return Order::query()
            ->with('customer:id,name', 'processedByAdmin:id,name') // Eager load dengan kolom spesifik
            ->whereBetween('orders.created_at', [$start, $end])
            // Default order by bisa dihapus atau dibiarkan, Filament akan menimpanya
            // ->orderBy('orders.order_date', 'desc'); 
        ; // Biarkan Filament yang menangani default sorting awal jika tidak ada preferensi kuat
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('kode_pesanan')
                ->searchable()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            // OPSI 1 untuk customer.name (coba ini dulu)
            Tables\Columns\TextColumn::make('customer.name')
                ->label('Pelanggan')
                ->searchable()
                ->sortable(),

            /*
            // OPSI 2 untuk customer.name (jika OPSI 1 tidak berfungsi untuk sorting)
            Tables\Columns\TextColumn::make('customer.name')
                ->label('Pelanggan')
                ->searchable()
                ->sortable(query: function (Builder $query, string $direction): Builder {
                    // Pastikan alias 'customers' tidak bentrok jika ada join lain
                    return $query
                        ->leftJoin('users as customers', 'orders.user_id', '=', 'customers.id')
                        ->orderBy('customers.name', $direction);
                }),
            */

            Tables\Columns\TextColumn::make('order_date')
                ->label('Tgl Pesan')
                ->dateTime('d M Y, H:i')
                ->sortable(),

            Tables\Columns\TextColumn::make('total_amount')
                ->label('Total Harga')
                ->money('IDR')
                ->sortable(),

            Tables\Columns\TextColumn::make('status_pesanan')
                ->badge()
                ->color(fn(string $state): string => match ($state) {
                    'baru' => 'gray',
                    'diproses' => 'warning',
                    'siap diambil' => 'info',
                    'selesai' => 'success',
                    'dibatalkan' => 'danger',
                    default => 'primary',
                })
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('payment_status')
                ->label('Pembayaran')
                ->badge()
                ->color(fn(string $state): string => match ($state) {
                    'belum_bayar' => 'danger',
                    'lunas' => 'success',
                    default => 'gray',
                })
                ->searchable()
                ->sortable(),

            // OPSI 1 untuk processedByAdmin.name (coba ini dulu)
            Tables\Columns\TextColumn::make('processedByAdmin.name')
                ->label('Diproses Oleh')
                ->searchable()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            /*
            // OPSI 2 untuk processedByAdmin.name (jika OPSI 1 tidak berfungsi untuk sorting)
            Tables\Columns\TextColumn::make('processedByAdmin.name')
                ->label('Diproses Oleh')
                ->searchable()
                ->sortable(query: function (Builder $query, string $direction): Builder {
                    // Pastikan alias 'admins' tidak bentrok
                    return $query
                        ->leftJoin('users as admins', 'orders.processed_by_admin_id', '=', 'admins.id')
                        ->orderBy('admins.name', $direction);
                })
                ->toggleable(isToggledHiddenByDefault: true),
            */

            Tables\Columns\TextColumn::make('paid_at')
                ->label('Tgl Bayar')
                ->dateTime('d M Y, H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    protected function isTablePaginationEnabled(): bool
    {
        return true;
    }
    protected function getTableFilters(): array
    {
        return [];
    }
    protected function getTableHeaderActions(): array
    {
        return [];
    }
    protected function getTableActions(): array
    {
        return [];
    }
    protected function getTableBulkActions(): array
    {
        return [];
    }
}