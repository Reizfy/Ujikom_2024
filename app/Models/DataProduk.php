<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataProduk extends Model
{
    use HasFactory;

    protected $table = 'data_produk';
    protected $fillable = ['id', 'kode_produk', 'photo_produk', 'nama_produk', 'kategori_produk', 'jumlah_stok', 'harga_produk', 'jumlah_dibeli', 'kali_dibeli'];

    protected static function booted()
    {
        static::deleting(function ($produk) {
            Keranjang::where('kode_produk', $produk->kode_produk)->delete();
        });
    }
    // /**
    //  * Mengurangi jumlah stok produk.
    //  *
    //  * @param int $jumlah
    //  * @return bool
    //  */
    // public function kurangiStok($jumlah)
    // {
    //     if ($this->jumlah_stok >= $jumlah) {
    //         $this->jumlah_stok -= $jumlah;
    //         $this->save();
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    // /**
    //  * Menambah jumlah stok produk.
    //  *
    //  * @param int $jumlah
    //  */
    // public function tambahStok($jumlah)
    // {
    //     $this->jumlah_stok += $jumlah;
    //     $this->save();
    // }
}
