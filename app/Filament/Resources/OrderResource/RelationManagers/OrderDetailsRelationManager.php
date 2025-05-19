<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderDetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'orderDetails';

    // Bagian ini mungkin ada atau tidak, atau isinya berbeda tergantung jawaban Anda
    // protected static ?string $recordTitleAttribute = 'id'; // Atau kolom yang Anda isi, atau tidak ada sama sekali

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Biasanya Filament akan mencoba menambahkan satu field berdasarkan recordTitleAttribute
                // atau field pertama yang bisa ia deteksi. Jika tidak, ini akan kosong.
                // Contoh: Forms\Components\TextInput::make('service_id')->required(), // Ini hanya contoh jika dia menebak
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            // ->recordTitleAttribute('id') // Bisa di-override di sini juga
            ->columns([
                // Biasanya Filament akan mencoba menambahkan kolom berdasarkan recordTitleAttribute
                // atau beberapa kolom dasar dari model OrderDetail.
                // Contoh: Tables\Columns\TextColumn::make('service_id'), // Ini hanya contoh
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}