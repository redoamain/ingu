<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KindOfGoods extends Model
{
    protected $table = 'taKindofGoods';
    protected $connection = 'sqlsrv_master';
    protected $primaryKey = 'KodeJenis';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'KodeJenis',
        'NamaJenis'
    ];

    public $timestamps = false;

    // Relasi balik ke Goods
    public function goods()
    {
        return $this->hasMany(Goods::class, 'KodeJenis', 'KodeJenis');
    }
}
