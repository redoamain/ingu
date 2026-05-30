<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductsTemplateExport
{
    public static function download()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header
        $headers = ['item_id', 'name', 'description', 'status', 'additional_info'];
        $columnLetters = ['A', 'B', 'C', 'D', 'E'];

        foreach ($headers as $index => $header) {
            $sheet->setCellValue($columnLetters[$index] . '1', $header);
        }

        // Style header
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);

        // Set lebar kolom
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(35);
        $sheet->getColumnDimension('D')->setWidth(12);
        $sheet->getColumnDimension('E')->setWidth(30);

        // Contoh data
        $sheet->setCellValue('A2', '00A001NI-S');
        $sheet->setCellValue('B2', 'TES IMPORT');
        $sheet->setCellValue('C2', 'TAT');
        $sheet->setCellValue('D2', 'active');
        $sheet->setCellValue('E2', 'Warna hitam');

        $sheet->setCellValue('A3', '00A01NI-S');
          $sheet->setCellValue('B2', 'TES IMPORT');
        $sheet->setCellValue('C2', 'TAT');
        $sheet->setCellValue('D2', 'active');
        $sheet->setCellValue('E2', 'IO');

        // Style untuk contoh data
        $exampleStyle = [
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E6F0FA']],
        ];
        $sheet->getStyle('A2:E3')->applyFromArray($exampleStyle);

        // Download file
        $writer = new Xlsx($spreadsheet);

        return new StreamedResponse(function() use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="products_template.xlsx"',
        ]);
    }
}
