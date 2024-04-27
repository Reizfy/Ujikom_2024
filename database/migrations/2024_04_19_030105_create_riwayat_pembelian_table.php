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
        Schema::create('riwayat_pembelian', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id');
            $table->string('invoice_file_name');
            $table->date('tanggal');
            $table->string('member_id');
            $table->string('nama_member');
            $table->string('subtotal');
            $table->string('diskon');
            $table->string('total_harga');
            $table->string('pembayaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_pembelian');
    }
};
