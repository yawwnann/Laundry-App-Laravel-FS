<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Str;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-scissors';
    protected static ?string $navigationGroup = 'Manajemen Laundry';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('tipe_paket')
                    ->options([
                        'reguler' => 'Reguler',
                        'kilat' => 'Kilat',
                    ])
                    ->required()
                    ->live() // Menggantikan reactive() di Filament v3
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                        self::updateFieldsBasedOnSelection($get, $set);
                    }),

                Select::make('satuan')
                    ->options([
                        'kg' => 'Kg',
                        'pcs' => 'Pcs (Buah)',
                    ])
                    ->required()
                    ->default('kg')
                    ->live() // Menggantikan reactive() di Filament v3
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                        self::updateFieldsBasedOnSelection($get, $set);
                    }),

                TextInput::make('nama_layanan')
                    ->maxLength(255)
                    ->helperText('Akan terisi otomatis berdasarkan paket dan satuan.')
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('harga')
                    ->numeric()
                    ->prefix('Rp')
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('estimasi_durasi')
                    ->maxLength(255)
                    ->helperText('Akan terisi otomatis berdasarkan paket.')
                    ->disabled()
                    ->dehydrated(),
            ]);
    }

    // Fungsi helper untuk update field secara dinamis
    protected static function updateFieldsBasedOnSelection(Get $get, Set $set): void
    {
        $tipePaket = $get('tipe_paket');
        $satuan = $get('satuan');

        if ($tipePaket && $satuan) {
            $harga = 0;
            $namaLayanan = "Paket " . Str::ucfirst($tipePaket); // Menggunakan Str::ucfirst
            $estimasi = Service::ESTIMASI_DURASI_PAKET[$tipePaket] ?? 'N/A';

            if ($satuan === 'kg' && isset(Service::HARGA_PAKET_KG[$tipePaket])) {
                $harga = Service::HARGA_PAKET_KG[$tipePaket];
                $namaLayanan .= " (Kg)";
            } elseif ($satuan === 'pcs' && isset(Service::HARGA_PAKET_PCS[$tipePaket])) {
                $harga = Service::HARGA_PAKET_PCS[$tipePaket]; // Menggunakan HARGA_PAKET_PCS
                $namaLayanan .= " (Pcs)";
            }
            // Tambahkan logika lain jika ada satuan lain atau kondisi khusus

            $set('nama_layanan', $namaLayanan);
            $set('harga', $harga);
            $set('estimasi_durasi', $estimasi);
        } else {
            // Jika salah satu (tipe_paket atau satuan) belum dipilih, kosongkan field terkait
            $set('nama_layanan', null);
            $set('harga', null);
            $set('estimasi_durasi', null);
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_layanan')->searchable(),
                Tables\Columns\TextColumn::make('tipe_paket')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'reguler' => 'gray',
                        'kilat' => 'success',
                        default => 'primary',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('harga')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('satuan')->searchable(),
                Tables\Columns\TextColumn::make('estimasi_durasi'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}