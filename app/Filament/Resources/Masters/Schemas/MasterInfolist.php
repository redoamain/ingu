<?php

namespace App\Filament\Resources\Masters\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;

class MasterInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Barang')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('ItemID')->label('Item ID'),
                                TextEntry::make('ItemName')->label('Item Name'),
                                TextEntry::make('ItemName2')->label('Item Name 2'),
                                TextEntry::make('warnac')->label('Warna'),
                                TextEntry::make('Mark')->label('Departemen'),
                                TextEntry::make('KodeJenis')->label('Kode Jenis'),
                                TextEntry::make('SatuanKecil')->label('Satuan'),
                                TextEntry::make('bahan')->label('Bahan'),
                                TextEntry::make('Spec')->label('Spesifikasi')->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
