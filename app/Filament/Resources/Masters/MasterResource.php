<?php

namespace App\Filament\Resources\Masters;

use App\Filament\Resources\Masters\Pages\CreateMaster;
use App\Filament\Resources\Masters\Pages\EditMaster;
use App\Filament\Resources\Masters\Pages\ListMasters;
use App\Filament\Resources\Masters\Pages\ViewMaster;
use App\Filament\Resources\Masters\Schemas\MasterForm;
use App\Filament\Resources\Masters\Schemas\MasterInfolist;
use App\Filament\Resources\Masters\Tables\MastersTable;
use App\Models\Goods;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MasterResource extends Resource
{
    protected static ?string $model = Goods::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Master';
     protected static ?string $pluralLabel = 'Master Barang Wincp';

    // public static function form(Schema $schema): Schema
    // {
    //     return MasterForm::configure($schema);
    // }

    public static function infolist(Schema $schema): Schema
    {
        return MasterInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MastersTable::configure($table);
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
            'index' => ListMasters::route('/'),
            // 'create' => CreateMaster::route('/create'),
            'view' => ViewMaster::route('/{record}'),
            // 'edit' => EditMaster::route('/{record}/edit'),
        ];
    }
}
