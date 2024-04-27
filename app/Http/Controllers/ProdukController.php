<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DataProduk;
use App\Models\LaporanProduk;
use App\Models\Kategori;
use App\Models\Keranjang;
use Throwable;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = DataProduk::select('id', 'kode_produk', 'photo_produk', 'nama_produk', 'kategori_produk', 'jumlah_stok', 'harga_produk')->get();

        if (request()->expectsJson()) {
            return response()->json($produk);
        }

        return redirect('/management/produk')->with('produk', $produk);
    }

    public function create()
    {
        $kategori = Kategori::select('id', 'kategori_produk')->get();

        return view('management.produk.create')->with('kategori', $kategori);
    }

    public function store(Request $request)
    {

        $request->validate([
            'photo_produk' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'nama_produk' => 'required',
            'kategori_produk' => 'required',
            'jumlah_stok' => 'required|numeric',
            'harga_produk' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            $produk = new DataProduk;
            $produk->kode_produk = "BRG" . rand(100, 999);
            if ($request->hasFile('photo_produk')) {
                $photo_produk = $request->file('photo_produk');
                $photoName = time() . '.' . $photo_produk->getClientOriginalExtension();

                $photo_produk->move(public_path('assets/img/uploaded/'), $photoName);
                $produk->photo_produk = $photoName;
            }
            $produk->nama_produk = $request->nama_produk;
            $produk->kategori_produk = $request->kategori_produk;
            $produk->jumlah_stok = $request->jumlah_stok;
            $produk->harga_produk = $request->harga_produk;
            $produk->save();

            $laporan = new LaporanProduk;
            $laporan->tanggal = date('Y-m-d');
            $laporan->kode_produk = $produk->kode_produk;
            $laporan->nama_produk = $produk->nama_produk;
            $laporan->harga_produk = $produk->harga_produk;
            $laporan->stok_awal = $produk->jumlah_stok;
            $laporan->barang_masuk = 0;
            $laporan->barang_keluar = 0;
            $laporan->stok_akhir = $produk->jumlah_stok;
            $laporan->save();

            DB::commit();

            session()->flash('success', 'Tambah Produk Sukses');
        } catch (Throwable $th) {
            DB::rollBack();

            session()->flash('error', 'Tambah Produk Gagal');
        }


        if (request()->expectsJson()) {
            return response()->json($produk);
        }
        return redirect('/management/produk');
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');
        $produk = DataProduk::findOrFail($id);

        if ($produk->photo_produk) {
            $photoPath = public_path('assets/img/uploaded/' . $produk->photo_produk);
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }
        }

        $produk->delete();

        LaporanProduk::where('kode_produk', $produk->kode_produk)->delete();

        return response()->json(['message' => 'Product Berhasil Dihapus'], 200);
    }

    public function update(Request $request)
    {
        $request->validate([
            'photo_produk' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'nama_produk' => 'required',
            'kategori_produk' => 'required',
            'jumlah_stok' => 'required|numeric',
            'harga_produk' => 'required|numeric',
        ]);

        $produk = DataProduk::findOrFail($request->id);
        $laporan = LaporanProduk::where('kode_produk', $produk->kode_produk)->latest()->first();
        $stok_awal = $laporan->stok_awal;

        DB::beginTransaction();

        try {
            $laporan = new LaporanProduk;
            $laporan->tanggal = date('Y-m-d');
            $laporan->kode_produk = $produk->kode_produk;
            $laporan->nama_produk = $request->nama_produk;
            $laporan->harga_produk = $request->harga_produk;
            $laporan->stok_awal = $stok_awal;
            $laporan->barang_masuk = $request->jumlah_stok > $produk->jumlah_stok ? $request->jumlah_stok - $produk->jumlah_stok : 0;
            $laporan->barang_keluar = $request->jumlah_stok < $produk->jumlah_stok ? $produk->jumlah_stok - $request->jumlah_stok : 0;
            $laporan->stok_akhir = $request->jumlah_stok;
            $laporan->save();

            if ($request->hasFile('photo_produk')) {
                $photo_produk = $request->file('photo_produk');
                $photoName = time() . '.' . $photo_produk->getClientOriginalExtension();

                if ($produk->photo_produk) {
                    $oldPhotoPath = public_path('assets/img/uploaded/' . $produk->photo_produk);
                    if (file_exists($oldPhotoPath)) {
                        unlink($oldPhotoPath);
                    }
                }

                $photo_produk->move(public_path('assets/img/uploaded/'), $photoName);
                $produk->photo_produk = $photoName;
            }

            $produk->nama_produk = $request->nama_produk;
            $produk->kategori_produk = $request->kategori_produk;
            $produk->jumlah_stok = $request->jumlah_stok;
            $produk->harga_produk = $request->harga_produk;

            $produk->save();

            Keranjang::where('kode_produk', $produk->kode_produk)
            ->update([
                'nama_produk' => $request->nama_produk,
                'harga_produk' => $request->harga_produk,
            ]);

            DB::commit();

            session()->flash('success', 'Update Produk Sukses');
        } catch (Throwable $th) {
            DB::rollBack();
            session()->flash('error', 'Update Produk Gagal');
        }

        return redirect('/management/produk');
    }

    public function edit(Request $request, $id)
    {

        $produk = DataProduk::findOrFail($id);
        $kategori = Kategori::select('id', 'kategori_produk')->get();

        $data = [$produk, $kategori];

        if ($request->expectsJson()) {
            return response()->json($data);
        }

        return view('management.produk.edit', ['produk' => $produk, 'kategori' => $kategori]);
    }
}
