<?php

namespace App\Exports;

use App\Models\RiwayatPembelian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Writer;

class LaporanPenjualanExport implements FromCollection, WithHeadings, WithEvents
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
        $data = RiwayatPembelian::select('id', 'invoice_id', 'tanggal', 'member_id', 'invoice_file_name', 'nama_member', 'subtotal', 'diskon', 'total_harga', 'pembayaran')->get();
        return $data->map(function ($item, $key) {
            return [
                'No.' => $key + 1,
                'Invoice ID' => $item->invoice_id,
                'Tanggal Pembelian' => $item->tanggal,
                'Member ID' => $item->member_id,
                'Nama Member' => $item->nama_member,
                'Subtotal' => $item->subtotal,
                'Diskon' => $item->diskon,
                'Total Harga' => $item->total_harga,
                'Pembayaran' => $item->pembayaran,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No.',
            'Invoice ID',
            'Tanggal Pembelian',
            'Member ID',
            'Nama Member',
            'Subtotal',
            'Diskon',
            'Total Harga',
            'Pembayaran',
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
                $sheet->getColumnDimension('I')->setWidth(15);
                $highestRow = $sheet->getHighestRow();
                $sheet->insertNewRowBefore(1, 2);
                $sheet->setCellValue('A1', 'Laporan Penjualan');
                $sheet->setCellValue('A2', "Periode Tanggal : {$this->startDate} - {$this->endDate}");
                $sheet->getStyle('A1')->getFont()->setSize(20);
                $sheet->getStyle('A2')->getFont()->setSize(16);
                $sheet->getStyle('A1:A2:A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->mergeCells('A1:I1');
                $sheet->mergeCells('A2:I2');
                $sheet->rangeToArray('A1:I' . $highestRow, null, true, true, true);
            },
        ];
    }
}
