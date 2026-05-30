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
            'NAMA BARANG',
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
            '', // Placeholder untuk gambar
            $product->created_at ? $product->created_at->format('d/m/Y H:i') : '-',
        ];
    }

    public function drawings()
    {
        $drawings = [];
        $currentRow = 2; // Mulai dari baris 2 (setelah header)

        foreach ($this->collection() as $index => $product) {
            $imagePath = null;

            // Cek gambar di berbagai lokasi
            if ($product->image) {
                if (file_exists(storage_path('app/public/' . $product->image))) {
                    $imagePath = storage_path('app/public/' . $product->image);
                } elseif (file_exists(storage_path('app/' . $product->image))) {
                    $imagePath = storage_path('app/' . $product->image);
                } elseif (file_exists(public_path($product->image))) {
                    $imagePath = public_path($product->image);
                }
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
                    $drawing->setOffsetX(15);
                    $drawing->setOffsetY(5);
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
            'A' => 6,   // NO
            'B' => 15,  // ITEM ID
            'C' => 30,  // NAMA PRODUK
            'D' => 25,  // NAMA BARANG
            'E' => 40,  // SPESIFIKASI
            'F' => 15,  // BAHAN
            'G' => 15,  // WARNA
            'H' => 35,  // DESKRIPSI
            'I' => 30,  // KETERANGAN
            'J' => 12,  // STATUS
            'K' => 15,  // GAMBAR (diperlebar untuk gambar)
            'L' => 18,  // CREATED AT
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();

        // Set tinggi baris untuk setiap baris data
        for ($i = 2; $i <= $highestRow; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(70);
        }

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

        // Border untuk semua cell
        $sheet->getStyle('A1:L' . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);

        // Wrap text untuk kolom spesifikasi dan deskripsi
        $sheet->getStyle('E:H')->getAlignment()->setWrapText(true);

        // Vertical center untuk semua data
        $sheet->getStyle('A2:L' . $highestRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // Horizontal center untuk kolom NO, ITEM ID, STATUS
        $sheet->getStyle('A2:A' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B2:B' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('J2:J' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [];
    }
}
