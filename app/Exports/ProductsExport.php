<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\Goods;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ProductsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $products;

    public function __construct($products = null)
    {
        $this->products = $products;
    }

    public function collection()
    {
        if ($this->products) {
            return $this->products;
        }
        return Product::where('status', 'active')->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Item ID',
            'Nama Produk',
            'Nama Barang (Master)',
            'Spesifikasi',
            'Bahan',
            'Warna',
            'Deskripsi',
            'Keterangan Tambahan',
            'Status',
            'URL Gambar',
            'Created At',
            'Updated At'
        ];
    }

    public function map($product): array
    {
        $goods = Goods::on('sqlsrv_master')->where('ItemID', $product->item_id)->first();

        // URL Gambar
        $imageUrl = '';
        if ($product->image && file_exists(storage_path('app/public/' . $product->image))) {
            $imageUrl = asset('storage/' . $product->image);
        } else {
            $imageUrl = 'https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=' . urlencode($product->name);
        }

        return [
            $product->id,
            $product->item_id,
            $product->name,
            $goods->ItemName ?? '-',
            $goods->Spec ?? '-',
            $goods->bahan ?? '-',
            $goods->warnac ?? '-',
            $product->description ?? '-',
            $product->additional_info ?? '-',
            $product->status == 'active' ? 'Aktif' : 'Tidak Aktif',
            $imageUrl,
            $product->created_at ? $product->created_at->format('Y-m-d H:i:s') : '-',
            $product->updated_at ? $product->updated_at->format('Y-m-d H:i:s') : '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }
}
