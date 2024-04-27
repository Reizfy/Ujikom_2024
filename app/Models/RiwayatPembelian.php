<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPembelian extends Model
{
    use HasFactory;

    protected $table = 'riwayat_pembelian';

    protected $fillable = ['id', 'invoice_id', 'tanggal', 'member_id', 'invoice_file_name', 'nama_member', 'subtotal', 'diskon', 'total_harga', 'pembayaran'];

    public function riwayatPembelian()
    {
        return $this->hasMany(RiwayatPembelian::class);
    }
}
