@extends('layouts.app')
@extends('layouts.navbar')

@section('content')
@if (auth()->user()->role != 'Admin' && (auth()->user()->role != 'Petugas'))
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        {{ __("You Don't Have Access To This Page") }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="container">
        <div class="row justify-content-center">
            <h1 class="text-start mb-5 mt-4">
                {{ __("Management Produk") }}
            </h1>
            <div class="card">
                <div class="card-body">
                    <table id="tableBarang" class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode Produk</th>
                                <th>Photo Produk</th>
                                <th>Nama Produk</th>
                                <th>Kategori Produk</th>
                                <th>Stok</th>
                                <th>Harga</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var message = "{{ Session::get('success') }}";
            if (message) {
                Swal.fire('Success', message, 'success');
            }
        })

        document.addEventListener('DOMContentLoaded', function () {
            var message = "{{ Session::get('error') }}";
            if (message) {
                Swal.fire('Error', message, 'Error')
            }
        })
    </script>

    <script>
        $(document).ready(function() {
            $('#tableBarang').DataTable({
                responsive: true,
                ajax: {
                    url: "{{ route('data-produk') }}",
                    dataSrc: ""
                },
                "columns": [{
                        "data": "id"
                    },
                    {
                        "data": "kode_produk"
                    },
                    {
                        "data": "photo_produk",
                        "render": function(data, type, row) {
                            return '<img src="{{ asset('assets/img/uploaded') }}/' + data +
                                '" alt="Product Photo" width="100">';
                        }
                    },
                    {
                        "data": "nama_produk"
                    },
                    {
                        "data": "kategori_produk"
                    },
                    {
                        "data": "jumlah_stok"
                    },
                    {
                        "data": "harga_produk"
                    },
                    {
                        "data": "id",
                        "render": function(data, type, row) {
                            return `
                                <button class="btn btn-primary edit-btn" data-id="${data}">Edit</button>
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
                            text: 'Tambah Produk',
                            action: function() {
                                window.location.href =
                                    "{{ route('management.produk.create') }}";
                            },
                        }],
                    },
                }
            });

            $('#tableBarang').on('click', '.edit-btn', function() {
                var id = $(this).data('id');
                window.location = "{{ route('management.produk.edit', ['id' => ':id']) }}".replace(':id', id);
            });

            $('#tableBarang').on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Confirmation',
                    text: 'Yakin Untuk Menghapus Produk?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('delete-produk') }}",
                            method: 'DELETE',
                            data: {
                                _token: "{{ csrf_token() }}",
                                id: id
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Deleted!',
                                    'Produk Berhasil Dihapus',
                                    'success'
                                );
                                $('#tableBarang').DataTable().ajax.reload();
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
@endif
@endsection
