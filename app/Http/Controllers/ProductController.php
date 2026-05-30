<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Goods;
use Illuminate\Http\Request;

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

        // Ambil data dari taGoods
        $goods = Goods::on('sqlsrv_master')->where('ItemID', $product->item_id)->first();

        return view('products.show', compact('product', 'goods'));
    }
}
