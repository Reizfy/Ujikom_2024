<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('data_produk', function (Blueprint $table) {
            $table->id();
            $table->string('kode_produk');
            $table->string('photo_produk')->nullable();
            $table->string('nama_produk');
            $table->string('kategori_produk');
            $table->integer('jumlah_stok');
            $table->integer('kali_dibeli')->default(0);
            $table->integer('harga_produk');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_produk');
    }
};

