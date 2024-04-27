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
        Schema::create('keranjang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_produk')->default('');
            $table->string('nama_produk');
            $table->string('kategori_produk')->default('')->nullable();
            $table->integer('harga_produk');
            $table->integer('jumlah_beli');
            $table->integer('total_harga');
            $table->timestamps();
        });

        \Illuminate\Support\Facades\DB::unprepared('
            CREATE TRIGGER update_produk_stok AFTER INSERT ON keranjang
            FOR EACH ROW
            BEGIN
                DECLARE stok INT;

                -- Mengambil jumlah stok produk
                SELECT jumlah_stok INTO stok FROM data_produk WHERE kode_produk = NEW.kode_produk;

                -- Mengurangi stok berdasarkan jumlah beli
                UPDATE data_produk SET jumlah_stok = jumlah_stok - NEW.jumlah_beli WHERE kode_produk = NEW.kode_produk;

            END;
        ');
    }

    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keranjang');

        \Illuminate\Support\Facades\DB::unprepared('DROP TRIGGER IF EXISTS update_produk_stok');
    }
};
