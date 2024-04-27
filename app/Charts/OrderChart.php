<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Models\RiwayatPembelian;

class OrderChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\BarChart
    {
        $months = range(1, 12);
        $monthNames = [];

        foreach ($months as $month) {
            $monthNames[$month] = date('F', mktime(0, 0, 0, $month, 1));
        }

        $ordersByMonth = RiwayatPembelian::selectRaw('MONTH(tanggal) as month, COUNT(*) as total_orders')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $orderCounts = [];

        foreach ($months as $month) {
            $orderCounts[] = $ordersByMonth[$month]->total_orders ?? 0;
        }
        return $this->chart->barChart()
        ->setTitle('Total Orders by Month')
        ->setSubtitle('Monthly breakdown of orders')
        ->addData('Total Orders', $orderCounts)
        ->setXAxis(array_values($monthNames));
        }
}
