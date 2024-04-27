<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataProduk;
use App\Models\Keranjang;

class KeranjangController extends Controller
{

    public function index()
    {
        $keranjang = Keranjang::select('id', 'kode_produk', 'nama_produk', 'kategori_produk', 'harga_produk', 'jumlah_beli', 'total_harga')->get();

        if (request()->expectsJson()) {
            return response()->json($keranjang);
        }

        return view('transaksi')->with('keranjang', $keranjang);
    }

    public function delete(Request $request)
    {
        $keranjang = Keranjang::find($request->id);
        if ($keranjang) {
            $produk = DataProduk::where('kode_produk', $keranjang->kode_produk)->first();
            if ($produk) {
                $produk->jumlah_stok += $request->quantity;
                $produk->save();
            }
            if ($keranjang->jumlah_beli > 0) {
                $keranjang->jumlah_beli -= $request->quantity;
                $keranjang->save();
            }
            if ($keranjang->jumlah_beli == 0) {
                $keranjang->delete();
            }
        }
        return response()->json(['message' => 'Item(s) removed successfully.']);
    }


    public function addToCart(Request $request)
    {
        $produk = DataProduk::find($request->id);

        $existingKeranjang = Keranjang::where('kode_produk', $produk->kode_produk)->first();
        if ($existingKeranjang) {
            $produk->jumlah_stok -= $request->jumlah_beli;
            $produk->save();

            $existingKeranjang->jumlah_beli += $request->jumlah_beli;
            $existingKeranjang->total_harga = $existingKeranjang->harga_produk * $existingKeranjang->jumlah_beli;
            $existingKeranjang->save();
        } else {
            Keranjang::create([
                'kode_produk' => $produk->kode_produk,
                'nama_produk' => $produk->nama_produk,
                'kategori_produk' => $produk->kategori_produk,
                'harga_produk' => $produk->harga_produk,
                'jumlah_beli' => $request->jumlah_beli,
                'total_harga' => $produk->harga_produk * $request->jumlah_beli
            ]);
        }

        session()->flash('success', 'Tambah Sukses');

        return redirect('/transaksi');
    }
}
