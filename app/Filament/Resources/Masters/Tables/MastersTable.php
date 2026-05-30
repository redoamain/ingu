<?php

namespace App\Filament\Resources\Masters\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
class MastersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                //
                  TextColumn::make('ItemID')
                    ->label('Item ID')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('ItemName')
                    ->label('Item Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('ItemName2')
                    ->label('Item Name 2')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('warnac')
                    ->label('Warna')
                    ->searchable()
                    ->badge()
                    ->color('warning'),

                TextColumn::make('Mark')
                    ->label('Departemen')
                    ->searchable(),

                TextColumn::make('KodeJenis')
                    ->label('Kode Jenis')
                    ->searchable(),

                // TextColumn::make('nama_jenis')
                //     ->label('Nama Jenis')
                //     ->searchable()
                //     ->sortable(),

                TextColumn::make('SatuanKecil')
                    ->label('Satuan')
                    ->searchable(),

                TextColumn::make('bahan')
                    ->label('Bahan')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('Spec')
                    ->label('Spesifikasi')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                // EditAction::make(),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
