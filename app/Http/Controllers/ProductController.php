<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Goods;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;
use App\Exports\ProductsWithImageExport;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('status', 'active');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('products.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $goods = Goods::on('sqlsrv_master')->where('ItemID', $product->item_id)->first();
        return view('products.show', compact('product', 'goods'));
    }

    // Export ke Excel (tanpa gambar)
    public function export()
    {
        $products = Product::where('status', 'active')->get();
        return Excel::download(new ProductsExport($products), 'products_' . date('Y-m-d_His') . '.xlsx');
    }

    // Export ke Excel (dengan gambar embed)
    public function exportWithImage()
    {
        $products = Product::where('status', 'active')->get();
        return Excel::download(new ProductsWithImageExport($products), 'products_with_image_' . date('Y-m-d_His') . '.xlsx');
    }

    // Export ke CSV
    public function exportCsv()
    {
        $products = Product::where('status', 'active')->get();

        $filename = 'products_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($products) {
            $file = fopen('php://output', 'w');

            // Header CSV
            fputcsv($file, [
                'ID', 'Item ID', 'Nama Produk', 'Nama Barang', 'Spesifikasi',
                'Bahan', 'Warna', 'Deskripsi', 'Keterangan', 'Status', 'URL Gambar'
            ]);

            // Data CSV
            foreach ($products as $product) {
                $goods = Goods::on('sqlsrv_master')->where('ItemID', $product->item_id)->first();

                $imageUrl = '';
                if ($product->image && file_exists(storage_path('app/public/' . $product->image))) {
                    $imageUrl = asset('storage/' . $product->image);
                }

                fputcsv($file, [
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
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
