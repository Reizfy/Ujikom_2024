<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    Protected $table = 'kategori';
    Protected $fillable = ['id', 'kategori_produk'];

    protected static function booted()
    {
        static::deleting(function ($kategori) {
            DataProduk::where('kategori_produk', $kategori->kategori_produk)->delete();
        });
    }
}
