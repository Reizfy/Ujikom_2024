@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="row">
                    @foreach ($produk as $item)
                        <div class="col-md-6">
                            <div class="card mb-2">
                                <img src="{{ asset('assets/img/uploaded/' . $item->photo_produk) }}" class="card-img-top"
                                    alt="..." style="height: 200px;">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $item->nama_produk }}</h5>
                                    <p class="card-text">{{ $item->jumlah_stok }}</p>
                                    <p class="card-text">{{ $item->harga_produk }}</p>
                                    <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#addModal{{ $item->id }}">Add to Cart</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-8">
                <table id="tableKeranjang" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Kode Produk</th>
                            <th>Photo Produk</th>
                            <th>Nama Produk</th>
                            <th>Kategori Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Total Harga</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
                <div class="mt-4">
                    <label for="subtotal">Subtotal</label>
                    <p id="subtotal"></p>
                </div>
                <div class="mt-4">
                    <label for="total">Total</label>
                    <p id="total"></p>
                </div>
                <form method="POST" action="{{ route('checkout') }}">
                    @csrf
                    <div class="mt-4">
                        <label for="member">Member</label>
                        <select id="member" name="member" class="form-control">
                            <option value="">Non Member</option>
                            @foreach ($member as $members)
                                <option value="{{ $members->nama_member }}">{{ $members->nama_member }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-4">
                        <label for="uang">Uang</label>
                        <input type="number" id="uang" name="uang" class="form-control">
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary" onclick="validationForm(event)">Checkout</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($produk as $product)
        <div class="modal fade" id="addModal{{ $product->id }}" tabindex="-1"
            aria-labelledby="addModalLabel{{ $product->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel{{ $product->id }}">Add to Cart</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('tambah.produk') }}">
                            @csrf
                            <input type="hidden" name="id" value="{{ $product->id }}">
                            <div class="mb-3">
                                <label for="jumlah_beli" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="jumlah_beli" name="jumlah_beli">
                            </div>
                            <button type="submit" class="btn btn-primary" onclick="validateQuantity(event)">Add to
                                Cart</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach


    <script>
        $('#tableKeranjang').DataTable({
            responsive: true,
            ajax: {
                url: "{{ route('data-keranjang') }}",
                dataSrc: ""
            },
            "columns": [{
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
                    "data": "harga_produk"
                },
                {
                    "data": "jumlah_beli"
                },
                {
                    "data": "total_harga"
                },
                {
                    "data": "id",
                    "render": function(data, type, row) {
                        return `
                    <button class="btn btn-danger delete-btn" data-id="${data}">Cancel</button>
                    `;
                    }
                }
            ],
            "serverSide": false,
            "processing": true,
            "layout": {
                topEnd: 'search',
            }
        });

        function validateQuantity() {

            var jumlahBeli = parseInt($('#jumlah_beli').val());
            var jumlahStok = parseInt('{{ $product->jumlah_stok }}');

            if (jumlahBeli > jumlahStok) {
                Swal.fire({
                    title: 'Error',
                    text: 'The quantity exceeds the available stock.',
                    icon: 'error'
                });
                event.preventDefault();
            }
        }

        function validationForm() {

            var itemCount = $('#tableKeranjang').DataTable().data().count();
            var total = parseFloat($('#total').text());
            var uang = parseFloat($('#uang').val());

            if (itemCount === 0) {
                Swal.fire({
                    title: 'Error',
                    text: 'There are no items in the cart.',
                    icon: 'error'
                });
                return false;
            } else if (isNaN(uang) || uang === '') {
                Swal.fire({
                    title: 'Error',
                    text: 'Please enter a valid amount of money.',
                    icon: 'error'
                });
                return false;
            } else if (uang < total) {
                Swal.fire({
                    title: 'Error',
                    text: 'The amount of money is not enough.',
                    icon: 'error'
                });
                event.preventDefault();
            }
        }

        $('#tableKeranjang').on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Confirmation',
                text: 'Are you sure you want to Cancel this product?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('delete-keranjang') }}",
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
                            ).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        });
        var table = $('#tableKeranjang').DataTable();
        table.on('draw', function() {
            var data = table.data().toArray();
            var subtotal = data.reduce(function(sum, row) {
                return sum + parseFloat(row.total_harga);
            }, 0);
            $('#subtotal').text(subtotal.toFixed(2));
            $('#total').text(subtotal.toFixed(2));
        });

        $('#member').on('change', function() {
            var isMember = $(this).val() !== '';
            var subtotal = parseFloat($('#subtotal').text());
            var total = subtotal;
            if (isMember) {
                if (total >= 100000) {
                    var discount = total * 0.15;
                } else if (total >= 50000) {
                    var discount = total * 0.10;
                } else if (total < 50000) {
                    var discount = total * 0;
                }
                total -= discount;
            }
            $('#total').text(total.toFixed(2));
        });
    </script>
@endsection
