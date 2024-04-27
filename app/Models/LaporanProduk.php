<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanProduk extends Model
{
    use HasFactory;

    protected $table='laporan_produk';

    protected $fillable=[
        'kode_produk',
        'nama_produk',
        'stok_awal',
        'barang_masuk',
        'barang_keluar',
        'stok_akhir',
        'harga_produk',
        'nilai_stok'
    ];
}
