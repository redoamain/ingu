<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    protected $table = 'taGoods';
    protected $connection = 'sqlsrv_master'; // Sesuaikan dengan nama koneksi SQL Server Anda
    protected $primaryKey = 'ItemID';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ItemID',
        'ItemName',
        'ItemName2',
        'warnac',
        'Mark',
        'KodeJenis',
        'SatuanKecil',
        'Spec',
        'bahan'
    ];

    // Relasi ke taKindofGoods
    public function KindOfGoods()
    {
        return $this->belongsTo(KindOfGoods::class, 'KodeJenis', 'KodeJenis');
    }

    // Accessor untuk menampilkan NamaJenis
    public function getNamaJenisAttribute()
    {
        return $this->KindOfGoods?->NamaJenis;
    }

    // Scope untuk query dengan join (jika diperlukan)
    public function scopeWithJoin($query)
    {
        return $query->join('taKindofGoods as b', 'taGoods.KodeJenis', '=', 'b.KodeJenis')
                     ->select([
                         'taGoods.ItemID',
                         'taGoods.ItemName',
                         'taGoods.ItemName2 as ItemName2',
                         'taGoods.warnac as warna',
                         'taGoods.Mark as Departemen',
                         'taGoods.KodeJenis',
                         'taGoods.SatuanKecil',
                         'taGoods.Spec',
                         'taGoods.bahan',
                         'b.NamaJenis'
                     ]);
    }
}
