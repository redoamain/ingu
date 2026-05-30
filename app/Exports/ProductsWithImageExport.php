<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\Goods;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ProductsWithImageExport implements FromCollection, WithHeadings, WithMapping, WithDrawings, WithColumnWidths, WithStyles
{
    protected $products;
    protected $rowIndex = 2; // Mulai dari baris 2 (setelah header)

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
            'NO',
            'ITEM ID',
            'NAMA PRODUK',
            'NAMA BARANG WINCP',
            'SPESIFIKASI',
            'BAHAN',
            'WARNA',
            'DESKRIPSI',
            'KETERANGAN',
            'STATUS',
            'GAMBAR',
            'CREATED AT'
        ];
    }

    public function map($product): array
    {
        static $no = 1;

        $goods = Goods::on('sqlsrv_master')->where('ItemID', $product->item_id)->first();

        return [
            $no++,
            $product->item_id,
            $product->name,
            $goods->ItemName ?? '-',
            $goods->Spec ?? '-',
            $goods->bahan ?? '-',
            $goods->warnac ?? '-',
            $product->description ?? '-',
            $product->additional_info ?? '-',
            $product->status == 'active' ? 'Aktif' : 'Tidak Aktif',
            ' ', // Placeholder untuk gambar
            $product->created_at ? $product->created_at->format('d/m/Y H:i') : '-',
        ];
    }

    public function drawings()
    {
        $drawings = [];
        $currentRow = 2;

        foreach ($this->collection() as $index => $product) {
            // Cek gambar
            $imagePath = null;
            if ($product->image && file_exists(storage_path('app/public/' . $product->image))) {
                $imagePath = storage_path('app/public/' . $product->image);
            } elseif ($product->image && file_exists(storage_path('app/' . $product->image))) {
                $imagePath = storage_path('app/' . $product->image);
            }

            if ($imagePath && file_exists($imagePath)) {
                try {
                    $drawing = new Drawing();
                    $drawing->setName('Gambar ' . $product->name);
                    $drawing->setDescription($product->name);
                    $drawing->setPath($imagePath);
                    $drawing->setHeight(80);
                    $drawing->setWidth(80);
                    $drawing->setCoordinates('K' . $currentRow);
                    $drawing->setOffsetX(10);
                    $drawing->setOffsetY(10);
                    $drawings[] = $drawing;
                } catch (\Exception $e) {
                    // Skip jika gambar corrupt
                }
            }

            $currentRow++;
        }

        return $drawings;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,      // NO
            'B' => 15,     // ITEM ID
            'C' => 25,     // NAMA PRODUK
            'D' => 25,     // NAMA BARANG
            'E' => 35,     // SPESIFIKASI
            'F' => 15,     // BAHAN
            'G' => 15,     // WARNA
            'H' => 30,     // DESKRIPSI
            'I' => 25,     // KETERANGAN
            'J' => 12,     // STATUS
            'K' => 15,     // GAMBAR
            'L' => 20,     // CREATED AT
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style header
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2E75B6'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Set tinggi baris header
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Style untuk baris data
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A2:L' . $lastRow)->applyFromArray([
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);

        // Set tinggi baris untuk data (agar gambar muat)
        for ($i = 2; $i <= $lastRow; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(70);
        }

        // Wrap text untuk kolom spesifikasi dan deskripsi
        $sheet->getStyle('E:H')->getAlignment()->setWrapText(true);

        return [];
    }
}
