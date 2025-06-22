<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\User;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash; // Untuk hashing password di createOptionForm
use Illuminate\Support\Facades\Auth; // Import Facade Auth

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Manajemen Pesanan';
    protected static ?string $modelLabel = 'Pesanan';
    protected static ?string $pluralModelLabel = 'Daftar Pesanan';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['customer', 'processedByAdmin'])->orderBy('created_at', 'desc');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Pesanan')
                    ->schema([
                        TextInput::make('kode_pesanan')
                            ->default('INV-' . strtoupper(Str::random(4)) . '-' . date('YmdHis'))
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->columnSpanFull(),

                        Select::make('user_id')
                            ->label('Pelanggan')
                            ->relationship('customer', 'name', modifyQueryUsing: fn(Builder $query) => $query->where('role', 'pelanggan'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                TextInput::make('name')->required()->maxLength(255),
                                TextInput::make('email')->email()->required()->maxLength(255)->unique(User::class, 'email'),
                                TextInput::make('phone')->tel()->maxLength(255),
                                Textarea::make('address')->maxLength(65535)->columnSpanFull(),
                                TextInput::make('password')->password()->required()->dehydrateStateUsing(fn($state) => Hash::make($state))->minLength(8),
                                Forms\Components\Hidden::make('role')->default('pelanggan'),
                            ])
                            ->createOptionAction(function (Forms\Components\Actions\Action $action) {
                                return $action
                                    ->modalHeading('Buat Pelanggan Baru')
                                    ->modalSubmitActionLabel('Buat Pelanggan');
                            })
                            ->live()
                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                if ($state) {
                                    $user = User::find($state);
                                    $set('customer_phone', $user?->phone);
                                } else {
                                    $set('customer_phone', null);
                                }
                            }),

                        TextInput::make('customer_phone')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->disabled()
                            ->dehydrated(false),

                        DateTimePicker::make('order_date')
                            ->label('Tanggal Pesanan')
                            ->default(now())
                            ->required(),

                        Select::make('status_pesanan')
                            ->options([
                                'baru' => 'Baru',
                                'diproses' => 'Diproses',
                                'siap diambil' => 'Siap Diambil',
                                'selesai' => 'Selesai',
                                'dibatalkan' => 'Dibatalkan',
                            ])
                            ->default('baru')
                            ->required(),

                        Select::make('payment_status')
                            ->label('Status Pembayaran')
                            ->options([
                                'belum_bayar' => 'Belum Bayar',
                                'lunas' => 'Lunas',
                            ])
                            ->default('belum_bayar')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                if ($state === 'lunas') {
                                    $set('paid_at', now());
                                    // Menggunakan Auth Facade
                                    if (Auth::check() && is_null($get('processed_by_admin_id'))) {
                                        $set('processed_by_admin_id', Auth::id());
                                    }
                                } else {
                                    $set('paid_at', null);
                                }
                            }),

                        Select::make('delivery_option')
                            ->label('Opsi Pengambilan')
                            ->options([
                                'dijemput' => 'Di Jemput',
                                'diantar' => 'Diantar',
                            ])
                            ->default('dijemput')
                            ->required(),

                        Select::make('processed_by_admin_id')
                            ->label('Diproses Oleh Admin')
                            ->relationship('processedByAdmin', 'name', modifyQueryUsing: fn(Builder $query) => $query->where('role', 'admin'))
                            ->searchable()
                            ->preload()
                            ->helperText('Pilih admin yang memproses pengambilan/pembayaran.'),

                        DateTimePicker::make('paid_at')
                            ->label('Dibayar Pada')
                            ->nullable(),

                    ])->columns(2),

                Section::make('Detail Item Pesanan')
                    ->schema([
                        Repeater::make('orderDetails')
                            ->relationship()
                            ->schema([
                                Select::make('service_id')
                                    ->label('Layanan')
                                    ->relationship('service', 'nama_layanan')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                        if ($state) {
                                            $service = Service::find($state);
                                            if ($service) {
                                                $set('harga_saat_pesan', $service->harga);
                                                $quantity = $get('quantity');
                                                $set('sub_total', $quantity ? ($service->harga * (float) $quantity) : 0);
                                            }
                                        } else {
                                            $set('harga_saat_pesan', 0);
                                            $set('sub_total', 0);
                                        }
                                    })
                                    ->columnSpan(2),
                                TextInput::make('quantity')
                                    ->label('Kuantitas')
                                    ->numeric()
                                    ->minValue(0.1)
                                    ->step(0.1)
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                        $hargaSatuan = $get('harga_saat_pesan');
                                        $set('sub_total', ($state && $hargaSatuan) ? ((float) $hargaSatuan * (float) $state) : 0);
                                    }),
                                TextInput::make('harga_saat_pesan')
                                    ->label('Harga Satuan')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->disabled()
                                    ->dehydrated(),
                                TextInput::make('sub_total')
                                    ->label('Sub Total')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->disabled()
                                    ->dehydrated(),
                            ])
                            ->columns(5)
                            ->addActionLabel('Tambah Item Layanan')
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->itemLabel(fn(array $state): ?string => $state['service_id'] ? Service::find($state['service_id'])?->nama_layanan . ' (Qty: ' . ($state['quantity'] ?? 'N/A') . ')' : null)
                            ->deleteAction(
                                fn(Forms\Components\Actions\Action $action) => $action->requiresConfirmation(),
                            )
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set, array $state) {
                                $total = 0;
                                foreach ($state as $item) {
                                    $total += (float) ($item['sub_total'] ?? 0);
                                }
                                $set('total_amount', $total);
                            })
                            ->defaultItems(1),
                    ]),

                Section::make('Total & Catatan')
                    ->schema([
                        TextInput::make('total_amount')
                            ->label('Total Keseluruhan')
                            ->numeric()
                            ->prefix('Rp')
                            ->readOnly()
                            ->dehydrated(),
                        Textarea::make('catatan_pelanggan')
                            ->columnSpanFull(),
                        Textarea::make('catatan_internal')
                            ->label('Catatan Internal (Admin)')
                            ->columnSpanFull(),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_pesanan')->searchable()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('customer.name')->label('Pelanggan')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('customer.phone')->label('No. Telepon')->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('order_date')->label('Tgl Pesan')->dateTime('d M Y, H:i')->sortable(),
                Tables\Columns\TextColumn::make('total_amount')->label('Total Harga')->money('IDR')->sortable(),
                Tables\Columns\TextColumn::make('delivery_option')
                    ->label('Pengambilan')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'dijemput' => 'Di Jemput',
                        'diantar' => 'Diantar',
                        default => $state,
                    })
                    ->searchable()->sortable(),
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_status')->label('Pembayaran')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'belum_bayar' => 'danger',
                        'lunas' => 'success',
                        default => 'gray',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('processedByAdmin.name')->label('Diproses Oleh')->searchable()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('paid_at')->label('Tgl Bayar')->dateTime('d M Y, H:i')->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_pesanan')
                    ->options([
                        'baru' => 'Baru',
                        'diproses' => 'Diproses',
                        'siap diambil' => 'Siap Diambil',
                        'selesai' => 'Selesai',
                        'dibatalkan' => 'Dibatalkan',
                    ]),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'belum_bayar' => 'Belum Bayar',
                        'lunas' => 'Lunas',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('tandai_lunas')
                    ->label('Tandai Lunas')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Order $record) {
                        if ($record->payment_status !== 'lunas') {
                            $record->payment_status = 'lunas';
                            $record->paid_at = now();
                            // Menggunakan Auth Facade dan mengambil user yang sudah diautentikasi
                            $currentUser = Auth::user();
                            if (Auth::check() && $currentUser && $currentUser->role === 'admin' && is_null($record->processed_by_admin_id)) {
                                $record->processed_by_admin_id = $currentUser->id;
                            }
                            $record->save();
                            \Filament\Notifications\Notification::make()
                                ->title('Pembayaran berhasil ditandai lunas')
                                ->success()
                                ->send();
                        } else {
                            \Filament\Notifications\Notification::make()
                                ->title('Pesanan sudah lunas')
                                ->warning()
                                ->send();
                        }
                    })
                    ->visible(fn(Order $record): bool => $record->payment_status === 'belum_bayar'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}