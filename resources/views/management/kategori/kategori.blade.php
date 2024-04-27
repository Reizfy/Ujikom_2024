@extends('layouts.app')
@extends('layouts.navbar')

@section('content')
  <div class="container">
        <div class="row justify-content-center">
            <h1 class="text-start mb-5 mt-4">
                {{ __("Management Kategori") }}
            </h1>
            <div class="card">
                <div class="card-body">
                    <table id="tableKategori" class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Kategori</th>
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
            $('#tableKategori').DataTable({
                responsive: true,
                "processing": true,
                ajax: {
                    url: "{{ route('data-kategori') }}",
                    dataSrc: ""
                },
                "columns": [{
                        "data": "id"
                    },
                    {
                        "data": "kategori_produk"
                    },
                    {
                        "data": "id",
                        "render": function(data, type, row) {
                            return `
                            <button class="btn btn-primary btn-edit" data-id="${data}">Edit</button>
                            <button class="btn btn-danger btn-delete" data-id="${data}">Delete</button>
                            `;
                        }
                    }
                ],
                layout: {
                    top2End: {
                        buttons: [{
                            text: 'Tambah Kategori',
                            action: function(e, dt, node, config) {
                                window.location = "{{ route('management.kategori.create') }}";
                            }
                        }]
                    }
                }

            })

            $('#tableKategori').on('click', '.btn-edit', function() {
                var id = $(this).data('id');
                window.location = "{{ route('management.kategori.edit', ['id' => ':id']) }}".replace(':id', id);
            });


            $('#tableKategori').on('click', '.btn-delete', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Confirmation',
                    text: 'Yakin untuk menghapus kategori?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('delete-kategori') }}",
                            method: 'DELETE',
                            data: {
                                _token: "{{ csrf_token() }}",
                                id: id
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Deleted!',
                                    'Kategori berhasil dihapus.',
                                    'success'
                                );
                                $('#tableKategori').DataTable().ajax.reload();
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });
                    }
                });
            })
        })
    </script>
@endsection
