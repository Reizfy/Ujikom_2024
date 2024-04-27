<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanProduk;
use App\Exports\LaporanProdukExport;
use Maatwebsite\Excel\Facades\Excel;

class LaporanProdukController extends Controller
{
    public function index() {
        $laporan = LaporanProduk::select('id', 'tanggal', 'kode_produk', 'nama_produk', 'harga_produk', 'stok_awal', 'barang_masuk', 'barang_keluar', 'stok_akhir')->get();

        if (request()->expectsJson()) {
            return response()->json($laporan);
        }

        return view('laporan-produk')->with('laporan', $laporan);
    }

    public function export(Request $request) {

        $request->validate([
            'start_date' => 'required',
            'end_date' => 'required'
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $filename = 'laporan_produk_periode_' . $startDate . '_' . $endDate . '.xlsx';

        return Excel::download(new LaporanProdukExport($startDate, $endDate), $filename);
    }
}


