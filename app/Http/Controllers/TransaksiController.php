<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataProduk;
use App\Models\Keranjang;
use App\Models\RiwayatPembelian;
use App\Models\LaporanProduk;
use App\Models\Member;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;



class TransaksiController extends Controller
{
    public function index()
    {
        $produk = DataProduk::select('id', 'kode_produk', 'photo_produk', 'nama_produk', 'kategori_produk', 'jumlah_stok', 'harga_produk')->get();
        $keranjang = Keranjang::select('id', 'kode_produk', 'nama_produk', 'kategori_produk', 'harga_produk', 'jumlah_beli', 'total_harga')->get();
        $member = Member::select('id', 'nama_member', 'email', 'phone', 'alamat')->get();


        if (request()->expectsJson()) {
            return response()->json($produk);
        }


        return view('transaksi')->with(['produk' => $produk, 'keranjang' => $keranjang, 'member' => $member]);
    }

    public function checkout()
    {
        $keranjang = Keranjang::select('id', 'kode_produk', 'nama_produk', 'kategori_produk', 'harga_produk', 'jumlah_beli', 'total_harga')->get();

        if ($keranjang->isEmpty()) {
            return redirect()->back()->with('error', 'No items in the cart');
        }

        $totalHarga = 0;

        foreach ($keranjang as $item) {
            $totalHarga += $item->total_harga;
        }

        $member = request()->input('member');
        $member = Member::select('id', 'nama_member')->where('nama_member', $member)->first();
        $discountPercent = 0;
        if ($member && $totalHarga >= 50000 && $totalHarga < 100000) {
            $discountPercent = 10;
        } else if ($member && $totalHarga >= 100000) {
            $discountPercent = 15;
        } else if ($member && $totalHarga < 50000) {
            $discountPercent = 0;
        }

        $discount = $totalHarga * ($discountPercent / 100);
        $total = $totalHarga - $discount;

        $uang = request()->input('uang');


        if ($uang < $total) {
            return redirect()->back()->with('error', 'Insufficient funds');
        }

        $kembalian = $uang - $total;

        $invoice_id = Str::random(10);

        $pdf = Pdf::loadView('invoice', compact('keranjang', 'total', 'member', 'uang', 'invoice_id', 'kembalian', 'discountPercent'));


        $pdf->save(public_path('assets/invoice/' . $invoice_id . '.pdf'));
        $riwayatPembelian = new RiwayatPembelian;
        $riwayatPembelian->invoice_id = $invoice_id;
        $riwayatPembelian->invoice_file_name = $invoice_id . '.pdf';
        $riwayatPembelian->tanggal = now();
        if ($member) {
            $riwayatPembelian->member_id = $member->id;
            $riwayatPembelian->nama_member = $member->nama_member;
        } else {
            $riwayatPembelian->member_id = '-';
            $riwayatPembelian->nama_member = 'Non-member';
        }
        $riwayatPembelian->subtotal = $totalHarga;
        $riwayatPembelian->diskon = $discountPercent;
        $riwayatPembelian->total_harga = $total;
        $riwayatPembelian->pembayaran = $uang;
        $riwayatPembelian->save();

        foreach ($keranjang as $item) {
            $produk = DataProduk::where('kode_produk', $item->kode_produk)->first();
            $produk->kali_dibeli += $item->jumlah_beli;
            $produk->save();
        }

        foreach ($keranjang as $item) {
            $produk = DataProduk::where('kode_produk', $item->kode_produk)->first();
            $laporan = LaporanProduk::where('kode_produk', $produk->kode_produk)->latest()->first();
            $stok_awal = $laporan->stok_awal;

            $laporanProduk = new LaporanProduk;
            $laporanProduk->tanggal = now();
            $laporanProduk->kode_produk = $item->kode_produk;
            $laporanProduk->nama_produk = $item->nama_produk;
            $laporanProduk->harga_produk = $item->harga_produk;
            $laporanProduk->stok_awal = $stok_awal;
            $laporanProduk->barang_keluar += $item->jumlah_beli;
            $laporanProduk->stok_akhir = $laporan->stok_akhir - $item->jumlah_beli;
            $laporanProduk->save();
        }

        Keranjang::truncate();

        return $pdf->stream('invoice.pdf',);
    }
}
