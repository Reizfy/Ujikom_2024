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
        Schema::create('checkout', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id')->default('')->nullable();
            $table->string('kode_produk')->default('');
            $table->string('photo_produk')->nullable();
            $table->string('nama_produk');
            $table->string('kategori_produk');
            $table->integer('harga_produk');
            $table->integer('jumlah_beli');
            $table->integer('total');
            $table->integer('nominal_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkout');
    }
};
