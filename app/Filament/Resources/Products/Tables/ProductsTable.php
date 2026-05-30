<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
               ImageColumn::make('image')
    ->label('Foto')
    ->circular()
    ->defaultImageUrl(function ($record) {
        if ($record->image) {
            return asset('storage/' . $record->image);
        }
        return 'https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=' . urlencode($record->name);
    }),

                TextColumn::make('item_id')
                    ->label('Item ID')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('goods.ItemName')
                    ->label('Nama Barang wincp')
                    ->getStateUsing(function ($record) {
                        return $record->goods?->ItemName;
                    }),

                TextColumn::make('goods.warnac')
                    ->label('Warna')
                    ->badge()
                    ->color('warning')
                    ->getStateUsing(function ($record) {
                        return $record->goods?->warnac ?? '-';
                    }),

                TextColumn::make('goods.Spec')
                    ->label('Spesifikasi')
                    ->limit(50)
                    ->getStateUsing(function ($record) {
                        return $record->goods?->Spec ?? '-';
                    })
                    ->toggleable(),

                TextColumn::make('goods.bahan')
                    ->label('Bahan')
                    ->getStateUsing(function ($record) {
                        return $record->goods?->bahan ?? '-';
                    })
                    ->badge()
                    ->color('info')
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                    }),

                TextColumn::make('additional_info')
                    ->label('Keterangan')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                // \Filament\Tables\Actions\CreateAction::make(),
            ]);
    }
}
