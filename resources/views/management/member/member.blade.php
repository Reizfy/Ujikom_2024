@extends('layouts.app')
@extends('layouts.navbar')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h1 class="text-start mb-5 mt-4">
                {{ __("Management Member") }}
            </h1>
            <div class="card">
                <div class="card-body">
                    <table id="tableMember" class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Member</th>
                                <th>Email</th>
                                <th>Nomor Telepon</th>
                                <th>Alamat</th>
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
    </script>

    <script>
        $(document).ready(function() {
            $('#tableMember').DataTable({
                responsive: true,
                ajax: {
                    url: "{{ route('data-member') }}",
                    dataSrc: ""
                },
                "columns": [{
                        "data": "id"
                    },
                    {
                        "data": "nama_member"
                    },
                    {
                        "data": "email"
                    },
                    {
                        "data": "phone"
                    },
                    {
                        "data": "alamat"
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
                            text: 'Tambah Member',
                            action: function() {
                                window.location.href =
                                    "{{ route('management.member.create') }}";
                            }
                        }],
                    },

                }
            });

            $('#tableMember').on('click', '.edit-btn', function() {
                var id = $(this).data('id');
                window.location = "{{ route('management.member.edit', ['id' => ':id']) }}".replace(':id', id);
            });

            $('#tableMember').on('click', '.delete-btn', function() {
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
                            url: "{{ route('delete-member') }}",
                            method: 'DELETE',
                            data: {
                                _token: "{{ csrf_token() }}",
                                id: id
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Deleted!',
                                    'Member Berhasil Dihapus',
                                    'success'
                                );
                                $('#tableMember').DataTable().ajax.reload();
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
