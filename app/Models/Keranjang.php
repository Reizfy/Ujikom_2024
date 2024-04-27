<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    use HasFactory;

    protected $table = 'keranjang';
    protected $fillable = ['id', 'kode_produk', 'nama_produk', 'kategori_produk', 'harga_produk', 'jumlah_beli', 'total_harga'];
}
