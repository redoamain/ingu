<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Goods;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;

class ProductsImport
{
    protected $filePath;
    protected $successCount = 0;
    protected $errors = [];

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function import()
    {
        return $this->importDataFromExcel();
    }

    protected function importDataFromExcel()
    {
        try {
            // Load Excel file
            $spreadsheet = IOFactory::load($this->filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Remove header row
            $header = array_shift($rows);

            Log::info('Products Import - Header: ', $header);
            Log::info('Products Import - Total rows: ' . count($rows));

            foreach ($rows as $rowIndex => $row) {
                // Skip empty rows
                if (empty($row[0]) && empty($row[1])) {
                    continue;
                }

                $itemId = !empty($row[0]) ? trim($row[0]) : null;

                if (empty($itemId)) {
                    $this->errors[] = "Baris " . ($rowIndex + 2) . ": Item ID kosong";
                    continue;
                }

                // Cek apakah item_id ada di taGoods
                $goods = Goods::on('sqlsrv_master')->where('ItemID', $itemId)->first();

                if (!$goods) {
                    $this->errors[] = "Baris " . ($rowIndex + 2) . ": Item ID '{$itemId}' tidak ditemukan di master barang";
                    continue;
                }

                // Prepare data
                $data = [
                    'item_id' => $itemId,
                    'name' => !empty($row[1]) ? trim($row[1]) : $goods->ItemName,
                    'description' => !empty($row[2]) ? trim($row[2]) : $goods->Spec,
                    'status' => !empty($row[3]) ? trim($row[3]) : 'active',
                    'additional_info' => !empty($row[4]) ? trim($row[4]) : null,
                ];

                // Validasi status
                if (!in_array($data['status'], ['active', 'inactive'])) {
                    $data['status'] = 'active';
                }

                try {
                    Product::create($data);
                    $this->successCount++;
                    Log::info("✓ Product created: {$data['name']} (Item ID: {$itemId})");
                } catch (\Exception $e) {
                    $this->errors[] = "Baris " . ($rowIndex + 2) . ": " . $e->getMessage();
                    Log::error("Failed to create product: " . $e->getMessage());
                }
            }

            Log::info("=== IMPORT COMPLETED: {$this->successCount} products imported ===");
            if (!empty($this->errors)) {
                Log::warning("Errors: " . implode(", ", $this->errors));
            }

            return $this->successCount;

        } catch (\Exception $e) {
            Log::error("Import error: " . $e->getMessage());
            $this->errors[] = "Error membaca file: " . $e->getMessage();
            return 0;
        }
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
