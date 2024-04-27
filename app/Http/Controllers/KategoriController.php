<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index () {
        $kategori = Kategori::select('id', 'kategori_produk')->get();

        if (request()->expectsJson()) {
            return response()->json($kategori);
        }

        return redirect('/management/kategori')->with('kategori', $kategori);
    }

    public function store (Request $request) {
        $request->validate([
            'kategori_produk' => 'required',
        ]);

        $kategori = new Kategori;
        $kategori->kategori_produk = $request->kategori_produk;
        $kategori->save();

        if (request()->expectsJson()) {
            return response()->json($kategori);
        }

        session()->flash('success', 'Tambah Kategori Sukses');

        return redirect('/management/kategori');
    }

    public function delete (Request $request) {
        $id = $request->input('id');
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return response()->json(['message' => 'Kategori berhasil dihapus']);
    }

    public function update (Request $request) {

        $request->validate([
            'kategori_produk' => 'required',
        ]);

        $kategori = Kategori::findOrFail($request->id);
        $kategori->kategori_produk = $request->kategori_produk;
        $kategori->save();

        if (request()->expectsJson()) {
            return response()->json($kategori);
        }

        session()->flash('success', 'Update Kategori Sukses');

        return redirect('/management/kategori');
    }

    public function edit(Request $request, $id) {
        $kategori = Kategori::findOrFail($id);

        if ($request->expectsJson()) {
            return response()->json($kategori);
        }
        return view('management.kategori.edit', ['kategori' => $kategori]);
    }

}
