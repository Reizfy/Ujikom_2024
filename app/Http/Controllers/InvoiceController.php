<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoice = Invoice::select('id', 'invoice_id', 'tanggal', 'member_id', 'invoice_file_name', 'nama_member', 'total_harga', 'subtotal', 'diskon', 'pembayaran')->get();

        if (request()->expectsJson()) {
            return response()->json($invoice);
        }

        return view('invoice')->with('invoice', $invoice);
    }

    public function delete($id)
    {
        $invoice = Invoice::find($id);
        $pdfPath = public_path('assets/invoice/' . $invoice->invoice_file_name);
        if (file_exists($pdfPath)) {
            unlink($pdfPath);
        }
        $invoice->delete();

        return response()->json(['message' => 'Invoice deleted']);
    }

    public function stream($id)
    {
        $invoice = Invoice::find($id);
        $pdfPath = public_path('assets/invoice/' . $invoice->invoice_file_name);
        if (file_exists($pdfPath)) {
            return response()->file($pdfPath);
        }
        return response()->json(['message' => 'Invoice not found']);
    }

}
