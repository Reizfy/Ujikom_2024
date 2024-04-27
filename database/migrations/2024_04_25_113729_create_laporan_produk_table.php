<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laporan_produk', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('kode_produk');
            $table->string('nama_produk');
            $table->integer('harga_produk');
            $table->integer('stok_awal')->default(0);
            $table->integer('barang_masuk')->default(0);
            $table->integer('barang_keluar')->default(0);
            $table->integer('stok_akhir')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_produk');
    }
};
