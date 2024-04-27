<?php

namespace App\Exports;

use App\Models\LaporanProduk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Writer;

class LaporanProdukExport implements FromCollection, WithHeadings, WithEvents
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $subquery = LaporanProduk::select('kode_produk')
        ->selectRaw('MAX(nama_produk) as nama_produk')
        ->groupBy('kode_produk');
    
        $data = LaporanProduk::select('laporan_produk.kode_produk', 'subquery.nama_produk', 'harga_produk', 'stok_awal')
            ->selectRaw('COALESCE(SUM(barang_masuk), 0) as total_barang_masuk')
            ->selectRaw('COALESCE(SUM(barang_keluar), 0) as total_barang_keluar')
            ->selectRaw('COALESCE((stok_awal + SUM(barang_masuk) - SUM(barang_keluar)), 0) as stok_akhir')
            ->joinSub($subquery, 'subquery', function ($join) {
                $join->on('laporan_produk.kode_produk', '=', 'subquery.kode_produk');
            })
            ->groupBy('laporan_produk.kode_produk', 'subquery.nama_produk', 'harga_produk', 'stok_awal')
            ->get();
    
        return $data->map(function ($item, $key) {
            return [
                'No.' => $key + 1,
                'Kode Produk' => $item->kode_produk,
                'Nama Produk' => $item->nama_produk,
                'Harga Produk' => $item->harga_produk,
                'Stok Awal' => $item->stok_awal,
                'Barang Masuk' => $item->total_barang_masuk,
                'Barang Keluar' => $item->total_barang_keluar,
                'Stok Akhir' => $item->stok_akhir,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No.',
            'Kode Produk',
            'Nama Produk',
            'Harga Produk',
            'Stok Awal',
            'Barang Masuk',
            'Barang Keluar',
            'Stok Akhir',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->getColumnDimension('A')->setWidth(15);
                $sheet->getColumnDimension('B')->setWidth(15);
                $sheet->getColumnDimension('C')->setWidth(15);
                $sheet->getColumnDimension('D')->setWidth(15);
                $sheet->getColumnDimension('E')->setWidth(15);
                $sheet->getColumnDimension('F')->setWidth(15);
                $sheet->getColumnDimension('G')->setWidth(15);
                $sheet->getColumnDimension('H')->setWidth(15);
                $highestRow = $sheet->getHighestRow();
                $sheet->insertNewRowBefore(1, 2, 3);
                $sheet->setCellValue('A1', 'Laporan Stok Barang');
                $sheet->setCellValue('A2', "Periode Tanggal : {$this->startDate} - {$this->endDate}");
                $sheet->getStyle('A1')->getFont()->setSize(20);
                $sheet->getStyle('A2')->getFont()->setSize(16);
                $sheet->getStyle('A1:A2:A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->mergeCells('A1:H1');
                $sheet->mergeCells('A2:H2');
                $sheet->rangeToArray('A1:H' . $highestRow, null, true, true, true);
            },
        ];
    }
}