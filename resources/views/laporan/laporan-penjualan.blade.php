@extends('layouts.app')
@extends('layouts.navbar')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h1 class="text-start mb-5 mt-4">
                {{ __('Laporan Penjualan') }}
            </h1>
            <div class="card">
                <div class="card-body">
                    <table id="tableInvoice" class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Invoice ID</th>
                                <th>Tanggal Pembelian</th>
                                <th>Member ID</th>
                                <th>Nama Member</th>
                                <th>Subtotal</th>
                                <th>Diskon</th>
                                <th>Total Harga</th>
                                <th>Pembayaran</th>
                                <th>Action</th>
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
                    <form id="exportForm" action="{{ route('laporan-penjualan.export') }}" method="POST">
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
    </div>
        <script>
            $(document).ready(function() {
                $('#tableInvoice').DataTable({
                    responsive: true,
                    ajax: {
                        url: "{{ route('data-laporan-penjualan') }}",
                        dataSrc: ""
                    },
                    "columns": [{
                            "data": "id"
                        },
                        {
                            "data": "invoice_id"
                        },
                        {
                            "data": "tanggal"
                        },
                        {
                            "data": "member_id"
                        },
                        {
                            "data": "nama_member"
                        },
                        {
                            "data": "subtotal"
                        },
                        {
                            "data": "diskon"
                        },
                        {
                            "data": "total_harga"
                        },
                        {
                            "data": "pembayaran"
                        },
                        {
                            "data": "id",
                            "render": function(data, type, row) {
                                return `
                                <button class="btn btn-primary stream-btn" data-id="${data}">View</button>
                                <button class="btn btn-danger delete-btn" data-id="${data}">Delete</button>
                            `;
                            }
                        }
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
                });

                $('#exportForm').on('submit', function(e) {
                    var itemCount = $('#tableInvoice').DataTable().data().count();
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

                    console.log('Export form submitted...');
                });

                $('#closeButton').click(function() {
                    $('#exportModal').modal('hide');
                });

                $('#tableInvoice').on('click', '.stream-btn', function() {
                    var id = $(this).data('id');
                    window.open("{{ route('stream-invoice', ['id' => ':id']) }}".replace(':id', id));
                });

                $('#tableInvoice').on('click', '.delete-btn', function() {
                    var id = $(this).data('id');
                    Swal.fire({
                        title: 'Confirmation',
                        text: 'Are you sure you want to delete this invoice?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('delete-invoice', ['id' => ':id']) }}".replace(
                                    ':id', id),
                                method: 'DELETE',
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    id: id
                                },
                                success: function(response) {
                                    Swal.fire(
                                        'Deleted!',
                                        'Product deleted successfully.',
                                        'success'
                                    );
                                    $('#tableInvoice').DataTable().ajax.reload();
                                },
                                error: function(xhr, status, error) {
                                    console.error(xhr.responseText);
                                }
                            });
                        }
                    });
                });
            });
        </script>
    @endsection
