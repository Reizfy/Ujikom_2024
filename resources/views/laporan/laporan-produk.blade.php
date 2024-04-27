@extends('layouts.app')
@extends('layouts.navbar')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h1 class="text-start mb-5 mt-4">
                {{ __('Laporan Produk') }}
            </h1>
            <div class="card">
                <div class="card-body">
                    <table id="tableLaporanProduk" class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Stok Awal</th>
                                <th>Masuk</th>
                                <th>Keluar</th>
                                <th>Stok Akhir</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">Export Excel</h5>
                </div>
                <div class="modal-body">
                    <form id="exportForm" action="{{ route('laporan-produk.export') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="startDate">From Date</label>
                            <input type="date" class="form-control" id="startDate" name="start_date">
                        </div>
                        <div class="form-group">
                            <label for="endDate">To Date</label>
                            <input type="date" class="form-control" id="endDate" name="end_date">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                id="closeButton">Close</button>
                            <button type="submit" class="btn btn-primary" id="exportButton">Export</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var message = "{{ Session::get('success') }}";
                if (message) {
                    Swal.fire('Success', message, 'success');
                }
            })

            document.addEventListener('DOMContentLoaded', function() {
                var message = "{{ Session::get('error') }}";
                if (message) {
                    Swal.fire('Error', message, 'Error')
                }
            })
        </script>

        <script>
            $(document).ready(function() {
                $('#tableLaporanProduk').DataTable({
                    responsive: true,
                    "processing": true,
                    ajax: {
                        url: "{{ route('data-laporan-produk') }}",
                        dataSrc: ""
                    },
                    "columns": [{
                            "data": "id"
                        },
                        {
                            "data": "tanggal"
                        },
                        {
                            "data": "kode_produk"
                        },
                        {
                            "data": "nama_produk"
                        },
                        {
                            "data": "harga_produk"
                        },
                        {
                            "data": "stok_awal"
                        },
                        {
                            "data": "barang_masuk"
                        },
                        {
                            "data": "barang_keluar"
                        },
                        {
                            "data": "stok_akhir"
                        },
                    ],
                    "serverSide": false,
                    "processing": true,
                    "layout": {
                        top2End: {
                            buttons: [{
                                text: 'Export Excel',
                                action: function() {
                                    $('#exportModal').modal('show');
                                }
                            }],
                        },
                    }
                })

                $('#exportForm').on('submit', function(e) {
                    var itemCount = $('#tableLaporanProduk').DataTable().data().count();
                    var startDate = $('#startDate').val();
                    var endDate = $('#endDate').val();

                    if (itemCount === 0) {
                        Swal.fire({
                            title: 'Error',
                            text: 'There are no Data.',
                            icon: 'error'
                        });
                        event.preventDefault();
                    }

                    if (startDate === '' || endDate === '') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Both start date and end date are required!'
                        });
                        e.preventDefault();
                    }

                });

                $('#closeButton').click(function() {
                    $('#exportModal').modal('hide');
                });
            })
        </script>
    @endsection
