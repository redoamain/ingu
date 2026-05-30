<?php

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Products\Pages\CreateProducts;
use App\Filament\Resources\Products\Pages\EditProducts;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Filament\Resources\Products\Schemas\ProductsForm;
use App\Filament\Resources\Products\Tables\ProductsTable;
use App\Models\Product;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use App\Imports\ProductsImport;
use App\Exports\ProductsTemplateExport;
use Illuminate\Support\Facades\Storage;
use App\Exports\ProductsExport;
use App\Exports\ProductsWithImageExport;
use Maatwebsite\Excel\Facades\Excel; 
class ProductsResource extends Resource
{
    protected static ?string $model = Product::class;

    public static function getNavigationLabel(): string
    {
        return 'Products';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Products';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Product Management';
    }

    public static function form(Schema $schema): Schema
    {
        return ProductsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductsTable::configure($table)
            ->headerActions([
                 Action::make('export_excel')
                    ->label('Export Excel (with URL)')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function () {
                        return Excel::download(new ProductsExport(), 'products_' . date('Y-m-d_His') . '.xlsx');
                    }),

                // Tombol Export Excel (dengan gambar embed)
                Action::make('export_excel_image')
                    ->label('Export Excel (with Image)')
                    ->icon('heroicon-o-photo')
                    ->color('warning')
                    ->action(function () {
                        return Excel::download(new ProductsWithImageExport(), 'products_with_image_' . date('Y-m-d_His') . '.xlsx');
                    }),
                // Tombol Download Template
                Action::make('download_template')
                    ->label('Download Template')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('gray')
                    ->action(function () {
                        return ProductsTemplateExport::download();
                    }),

                // Tombol Import
 Action::make('import')
    ->label('Import Excel')
    ->icon('heroicon-o-document-arrow-up')
    ->color('success')
    ->form([
        FileUpload::make('file')
            ->label('File Excel')
            // ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
            ->required()
            ->disk('local')
            ->directory('imports')
            ->preserveFilenames(true),
    ])
    ->action(function (array $data) {
        // Dapatkan nama file dari data
        $fileName = $data['file'];

        // Cek di berbagai kemungkinan lokasi
        $possiblePaths = [
            storage_path('app/livewire-tmp/' . $fileName),
            storage_path('app/livewire-tmp/' . basename($fileName)),
            storage_path('app/public/imports/' . $fileName),
            storage_path('app/imports/' . $fileName),
            storage_path('framework/livewire-tmp/' . $fileName),
            sys_get_temp_dir() . '/' . $fileName,
        ];

        $filePath = null;
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                $filePath = $path;
                break;
            }
        }

        // Jika tidak ditemukan, cari semua file xlsx di storage
        if (!$filePath) {
            $files = glob(storage_path('app/**/*.xlsx'));
            $files = array_merge($files, glob(storage_path('app/**/*.xls')));

            if (!empty($files)) {
                // Ambil file terakhir yang diupload
                $filePath = end($files);
            }
        }

        if (!$filePath || !file_exists($filePath)) {
            Notification::make()
                ->title('File tidak ditemukan')
                ->body("Tidak dapat menemukan file. Coba upload ulang.")
                ->danger()
                ->send();
            return;
        }

        // Proses import
        $import = new \App\Imports\ProductsImport($filePath);
        $result = $import->import();

        if ($result > 0) {
            Notification::make()
                ->title("✅ Berhasil import {$result} produk")
                ->success()
                ->send();
        } else {
            $errors = $import->getErrors();
            if (!empty($errors)) {
                Notification::make()
                    ->title('❌ Gagal import')
                    ->body(implode("\n", array_slice($errors, 0, 5)))
                    ->danger()
                    ->send();
            } else {
                Notification::make()
                    ->title('❌ Gagal import')
                    ->body('Tidak ada data yang diimport')
                    ->danger()
                    ->send();
            }
        }

        // Hapus file
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
    }),


            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
            'create' => CreateProducts::route('/create'),
            'edit' => EditProducts::route('/{record}/edit'),
        ];
    }
}
