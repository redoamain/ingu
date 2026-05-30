<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    protected $table = 'product_details';
    protected $connection = 'sqlsrv'; // Ganti ke kiwgu
    protected $fillable = [
        'product_id',
        'item_id',
        'additional_info',
        'image',
        'sort_order'
    ];

    public $timestamps = true;

    // Relasi ke Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    // Relasi ke Goods (dari database sqlsrv_master)
    public function goods()
    {
        return $this->belongsTo(Goods::class, 'item_id', 'ItemID');
    }
}
