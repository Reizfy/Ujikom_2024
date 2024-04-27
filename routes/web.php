<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/dashboard');
})->middleware('auth');


Auth::routes();

Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

//produk

Route::get('data-produk', [App\Http\Controllers\ProdukController::class, 'index'])->name('data-produk');
Route::get('management/produk/create', [App\Http\Controllers\ProdukController::class, 'create'])->name('management.produk.create');
Route::post('store-produk', [App\Http\Controllers\ProdukController::class, 'store'])->name('store-produk');
Route::delete('delete-produk', [App\Http\Controllers\ProdukController::class, 'delete'])->name('delete-produk');
Route::post('update-produk/{id}', [App\Http\Controllers\ProdukController::class, 'update'])->name('update-produk');
Route::get('management/produk/edit/{id}', [App\Http\Controllers\ProdukController::class, 'edit'])->name('management.produk.edit');

//kategori

Route::get('data-kategori', [App\Http\Controllers\KategoriController::class, 'index'])->name('data-kategori');
Route::post('store-kategori', [App\Http\Controllers\KategoriController::class, 'store'])->name('store-kategori');
Route::delete('delete-kategori', [App\Http\Controllers\KategoriController::class, 'delete'])->name('delete-kategori');
Route::post('update-kategori/{id}', [App\Http\Controllers\KategoriController::class, 'update'])->name('update-kategori');
Route::get('management/kategori/edit/{id}', [App\Http\Controllers\KategoriController::class, 'edit'])->name('management.kategori.edit');

//petugas
Route::get('data-petugas', [App\Http\Controllers\PetugasController::class, 'index'])->name('data-petugas');
Route::post('store-petugas', [App\Http\Controllers\PetugasController::class, 'store'])->name('store-petugas');
Route::delete('delete-petugas', [App\Http\Controllers\PetugasController::class, 'delete'])->name('delete-petugas');
Route::post('update-petugas/{id}', [App\Http\Controllers\PetugasController::class, 'update'])->name('update-petugas');
Route::get('management/petugas/edit/{id}', [App\Http\Controllers\PetugasController::class, 'edit'])->name('management.petugas.edit');

//member
Route::get('data-member', [App\Http\Controllers\MemberController::class, 'index'])->name('data-member');
Route::get('management/member/create', [App\Http\Controllers\MemberController::class, 'create'])->name('management.member.create');
Route::post('store-member', [App\Http\Controllers\MemberController::class, 'store'])->name('store-member');
Route::delete('delete-member', [App\Http\Controllers\MemberController::class, 'delete'])->name('delete-member');
Route::post('update-member/{id}', [App\Http\Controllers\MemberController::class, 'update'])->name('update-member');
Route::get('management/member/edit/{id}', [App\Http\Controllers\MemberController::class, 'edit'])->name('management.member.edit');

//transaksi
Route::get('transaksi', [App\Http\Controllers\TransaksiController::class, 'index'])->name('transaksi');
Route::get('data-keranjang', [App\Http\Controllers\KeranjangController::class, 'index'])->name('data-keranjang');
Route::delete('delete-keranjang', [App\Http\Controllers\KeranjangController::class, 'delete'])->name('delete-keranjang');
Route::post('/tambah_produk', [App\Http\Controllers\KeranjangController::class, 'addToCart'])->name('tambah.produk');
Route::post('checkout', [App\Http\Controllers\TransaksiController::class, 'checkout'])->name('checkout');

//invoice
Route::get('invoice/view/{id}', [App\Http\Controllers\InvoiceController::class, 'stream'])->name('stream-invoice');
Route::delete('invoice/delete/{id}', [App\Http\Controllers\InvoiceController::class, 'delete'])->name('delete-invoice');
Route::get('/order-per-day', [App\Http\Controllers\InvoiceController::class, 'getOrderPerDay']);

//laporan produk
Route::get('laporan/produk', function() {
    return view('laporan.laporan-produk');
})->name('laporan.produk');
Route::get('data-laporan-produk', [App\Http\Controllers\LaporanProdukController::class, 'index'])->name('data-laporan-produk');
Route::post('laporan-produk/export', [App\Http\Controllers\LaporanProdukController::class, 'export'])->name('laporan-produk.export');

//laporan penjualan
Route::get('laporan/penjualan', function() {
    return view('laporan.laporan-penjualan');
})->name('laporan.penjualan');
Route::get('data-laporan-penjualan', [App\Http\Controllers\LaporanPenjualanController::class, 'index'])->name('data-laporan-penjualan');
Route::post('laporan-penjualan/export', [App\Http\Controllers\LaporanPenjualanController::class, 'export'])->name('laporan-penjualan.export');


//base routing
Route::prefix('management')->middleware('auth')->group(function ($router) {

    route::get('/petugas', function() {
        return view('management.petugas.petugas');
    })->name('management.petugas');

    Route::get('/petugas/create', function() {
        return view('management.petugas.create');
    })->name('management.petugas.create');

    route::get('/member', function() {
        return view('management.member.member');
    })->name('management.member');

    route::get('/kategori', function() {
        return view('management.kategori.kategori');
    })->name('management.kategori');

    route::get('/kategori/create', function() {
        return view('management.kategori.create');
    })->name('management.kategori.create');

    route::get('/produk', function() {
        return view('management.produk.produk');
    })->name('management.produk');
});
