<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use App\Models\Goods;

class ProductsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Pilih Barang
                Select::make('item_id')
                    ->label('Pilih Barang dari Master')
                    ->placeholder('Cari berdasarkan Item ID atau Nama Barang...')
                    ->options(function () {
                        return Goods::on('sqlsrv_master')->get()->mapWithKeys(function ($goods) {
                            $warna = $goods->warnac ? " [Warna: {$goods->warnac}]" : '';
                            return [
                                $goods->ItemID => $goods->ItemID . ' - ' . $goods->ItemName . $warna
                            ];
                        });
                    })
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $goods = Goods::on('sqlsrv_master')->find($state);
                            if ($goods) {
                                $set('name', $goods->ItemName);
                                $set('description', $goods->Spec);
                            }
                        }
                    }),

                // Nama Produk
                TextInput::make('name')
                    ->label('Nama Produk')
                    ->required()
                    ->maxLength(200),

                // Deskripsi
                Textarea::make('description')
                    ->label('Deskripsi Produk')
                    ->rows(3),

                // Spesifikasi (disabled)
                TextInput::make('spec_display')
                    ->label('Spesifikasi')
                    ->disabled()
                    ->dehydrated(false)
                    ->formatStateUsing(function ($record) {
                        return $record?->goods?->Spec ?? '-';
                    }),

                // Bahan (disabled)
                TextInput::make('bahan_display')
                    ->label('Bahan')
                    ->disabled()
                    ->dehydrated(false)
                    ->formatStateUsing(function ($record) {
                        return $record?->goods?->bahan ?? '-';
                    }),

                // Warna (disabled)
                TextInput::make('warna_display')
                    ->label('Warna')
                    ->disabled()
                    ->dehydrated(false)
                    ->formatStateUsing(function ($record) {
                        return $record?->goods?->warnac ?? '-';
                    }),

                // Status
                ToggleButtons::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Aktif',
                        'inactive' => 'Tidak Aktif'
                    ])
                    ->colors([
                        'active' => 'success',
                        'inactive' => 'danger'
                    ])
                    ->default('active')
                    ->inline(),

                // Foto
                FileUpload::make('image')
                    ->label('Foto Produk')
                        ->disk('public')  // Tambahkan ini
                        ->directory('products')
                        ->visibility('public')
                    ->image()
                    ->directory('products')
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('1:1')
                    ->helperText('Upload gambar untuk produk ini'),

                // Keterangan Tambahan
                Textarea::make('additional_info')
                    ->label('Keterangan Tambahan')
                    ->rows(2)
                    ->placeholder('Info tambahan untuk produk...'),
            ]);
    }
}
