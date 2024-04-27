@extends('layouts.app')
@extends('layouts.navbar')

@section('content')
@if (auth()->user()->role != 'Admin')
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
                {{ __("Management Petugas") }}
            </h1>
            <div class="card">
                <div class="card-body">
                    <table id="tablePetugas" class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Petugas</th>
                                <th>Email</th>
                                <th>Nomor Telepon</th>
                                <th>Password</th>
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
            $('#tablePetugas').DataTable({
                responsive: true,
                ajax: {
                    url: "{{ route('data-petugas') }}",
                    dataSrc: ""
                },
                "columns": [{
                        "data": "id"
                    },
                    {
                        "data": "nama_petugas"
                    },
                    {
                        "data": "email"
                    },
                    {
                        "data": "phone"
                    },
                    {
                        "data": "password"
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
                            text: 'Tambah Petugas',
                            action: function() {
                                window.location.href =
                                    "{{ route('management.petugas.create') }}";
                            }
                        }],
                    },
                }
            });

            $('#tablePetugas').on('click', '.edit-btn', function() {
                var id = $(this).data('id');
                window.location = "{{ route('management.petugas.edit', ['id' => ':id']) }}".replace(':id', id);
            });

            $('#tablePetugas').on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Yakin Untuk Menghapus?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('delete-petugas') }}",
                            method: 'DELETE',
                            data: {
                                _token: "{{ csrf_token() }}",
                                id: id
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Deleted!',
                                    'Petugas Berhasil Dihapus.',
                                    'success'
                                );
                                $('#tablePetugas').DataTable().ajax.reload();
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

