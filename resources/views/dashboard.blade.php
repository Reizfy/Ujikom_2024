@extends('layouts.app')
@extends('layouts.navbar')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">{{ __('Item Populer') }}</div>
                    <div class="card-body">
                        @php
                            $mostPurchasedItem = App\Models\DataProduk::orderBy('kali_dibeli', 'desc')->first();
                        @endphp
                        @if ($mostPurchasedItem)
                            <h4>{{ $mostPurchasedItem->nama_produk }}</h4>
                            <p>Total Purchases: {{ $mostPurchasedItem->kali_dibeli }}</p>
                        @else
                            <h4>Tidak Ada Data</h4>
                            <p>Total Purchases: 0</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">{{ __('Total Penjualan') }}</div>
                    <div class="card-body">
                        @php
                            $totalHarga = App\Models\Invoice::sum('total_harga');
                            $totalInvoice = App\Models\Invoice::count();
                        @endphp
                        <h4>Rp. {{ $totalHarga }}</h4>
                        <p>Jumlah Invoice Yang Ada : {{$totalInvoice}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3 justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">{{ __('Transaksi Terbaru') }}</div>
                    <div class="card-body">
                        @php
                            $invoices = App\Models\RiwayatPembelian::select('id', 'nama_member', 'total_harga', 'tanggal')->orderByDesc('created_at')->get();
                        @endphp
                        @foreach($invoices->take(5) as $invoice)
                        <div class="card mt-2">
                            <div class="card-body">
                                @if ($invoice)
                                <label for="invoice" style="font-size: x-large;">{{ $invoice->nama_member }}</label>
                                @else
                                <label for="invoice" style="font-size: x-large;">Guest</label>
                                @endif
                                <div id="invoice">{{ $invoice->tanggal }}</div>
                                <div id="invoice">Rp. {{ $invoice->total_harga }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Total Order') }}</div>
                    <div class="card-body">
                        {!! $OrderChart->container() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ $OrderChart->cdn() }}"></script>

{{ $OrderChart->script() }}
@endsection


