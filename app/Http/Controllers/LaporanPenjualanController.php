<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RiwayatPembelian;
use App\Exports\LaporanPenjualanExport;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPenjualanController extends Controller
{
    public function index()
    {
        $riwayatPembelian = RiwayatPembelian::select('id', 'invoice_id', 'tanggal', 'member_id', 'invoice_file_name', 'nama_member', 'subtotal', 'diskon', 'total_harga', 'pembayaran')->get();

        if (request()->expectsJson()) {
            return response()->json($riwayatPembelian);
        }

        return view('laporan-penjualan')->with('riwayatPembelian', $riwayatPembelian);
    }

    public function export(Request $request) {

        $request->validate([
            'start_date' => 'required',
            'end_date' => 'required'
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $filename = 'laporan_penjualan_periode_' . $startDate . '_' . $endDate . '.xlsx';
        return Excel::download(new LaporanPenjualanExport($startDate, $endDate), $filename);
    }
}
