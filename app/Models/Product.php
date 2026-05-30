<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';
    protected $connection = 'sqlsrv';

    protected $fillable = [
        'item_id',
        'name',
        'description',
        'status',
        'image',
        'additional_info'
    ];

    public function goods()
    {
        return $this->belongsTo(Goods::class, 'item_id', 'ItemID');
    }
}
