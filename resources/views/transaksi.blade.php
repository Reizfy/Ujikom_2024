@extends('layouts.app')
@extends('layouts.navbar')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="row">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-start" style="font-size: xx-large;">{{ __('Produk') }}</div>
                            <div class="col-md-12 mb-4 mt-3">
                                <input type="text" id="searchInput" class="form-control"
                                    placeholder="Search for products..." onchange="searchProducts()">
                            </div>
                            @if ($produk->isEmpty())
                                <p>No data available.</p>
                            @else
                                <div class="row">
                                    @foreach ($produk as $item)
                                        <div class="col-md-6">
                                            <div class="card card-produk mb-2">
                                                <img src="{{ asset('assets/img/uploaded/' . $item->photo_produk) }}"
                                                    class="card-img-top" alt="..." style="height: 200px;">
                                                <div class="card-body">
                                                    <h5 class="card-title-produk text-center">{{ $item->nama_produk }}</h5>
                                                    <p class="card-text text-center">Stok : {{ $item->jumlah_stok }}</p>
                                                    <p class="card-text text-center">Rp. {{ $item->harga_produk }}</p>
                                                    @if ($item->jumlah_stok == 0)
                                                        <p class="text-center text-danger">Out of Stock</p>
                                                    @else
                                                        <a href="#"
                                                            class="btn btn-primary d-flex justify-content-center text-center"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#addModal{{ $item->id }}">Add to Cart</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="text-start" style="font-size: xx-large;">{{ __('Keranjang') }}</div>
                        <table id="tableKeranjang" class="table table-striped mt-4">
                            <thead>
                                <tr>
                                    <th>Kode Produk</th>
                                    <th>Nama Produk</th>
                                    <th>Kategori Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Total Harga</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-body">
                        <div>
                            <label for="subtotal">Subtotal</label>
                            <p id="subtotal"></p>
                        </div>
                        <div>
                            <label for="diskon">Diskon</label>
                            <p id="diskon"></p>
                        </div>
                        <div>
                            <label for="total">Total</label>
                            <p id="total"></p>
                        </div>
                        <form id="cart-form" method="POST" action="{{ route('checkout') }}" target="blank_">
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
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon2">Rp.</span>
                                    </div>
                                    <input type="number" id="uang" name="uang" class="form-control">
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary"
                                    onclick="validationForm(event)">Checkout</button>
                            </div>
                        </form>
                    </div>
                </div>
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
                                <input min="1" type="number" class="form-control"
                                    id="jumlah_beli{{ $product->id }}" name="jumlah_beli"
                                    data-stok="{{ $product->jumlah_stok }}">
                            </div>
                            <button type="submit" class="btn btn-primary"
                                onclick="validateQuantity(event, '{{ $product->id }}')">Add to
                                Cart</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Product</h5>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-3">
                            <label for="deleteQuantity">Number of items to delete</label>
                            <input type="number" class="form-control" id="deleteQuantity" min="1">
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            id="closeModal">Close</button>
                        <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
                    <button class="btn btn-danger delete-btn" data-id="${data}" data-jumlah_beli="${row.jumlah_beli}">-</button>
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

        function searchProducts() {
            var input, filter, cards, card, title, i, txtValue;
            input = document.getElementById('searchInput');
            filter = input.value.toUpperCase();
            cards = document.getElementsByClassName('card-produk');
            for (i = 0; i < cards.length; i++) {
                card = cards[i];
                title = card.querySelector('.card-title-produk');
                txtValue = title.textContent || title.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            }
        }

        document.getElementById('searchInput').addEventListener('input', searchProducts);


        function validateQuantity(event, productId) {

            var jumlahBeli = parseInt($('#jumlah_beli' + productId).val());
            var jumlahStok = parseInt($('#jumlah_beli' + productId).data('stok'));

            if (isNaN(jumlahBeli) || jumlahBeli === '') {
                Swal.fire({
                    title: 'Error',
                    text: 'Tolong Input Jumlah Beli.',
                    icon: 'error'
                });
                event.preventDefault();
            } else if (jumlahBeli > jumlahStok) {
                Swal.fire({
                    title: 'Error',
                    text: 'Jumlah Beli Melebihi Stok.',
                    icon: 'error'
                });
                event.preventDefault();
            }
        }

        function resetInputField(modalId) {
            var inputField = document.getElementById("" + modalId);
            if (inputField) {
                inputField.value = "";
            }
        }

        $('.modal').on('hidden.bs.modal', function(e) {
            var modalId = $(this).attr('id').replace('addModal', '');
            resetInputField(modalId);
        });

        function validationForm(event, formId) {
            event.preventDefault();

            var itemCount = $('#tableKeranjang').DataTable().data().count();
            var totalText = $('#total').text().replace('Rp. ', '');
            var total = parseInt(totalText);
            var uang = parseInt($('#uang').val());

            var hasError = false;

            if (itemCount === 0) {
                Swal.fire({
                    title: 'Error',
                    text: 'Tidak Ada Item Di Keranjang.',
                    icon: 'error'
                });
                hasError = true;
            }

            if (isNaN(uang) || uang === '') {
                Swal.fire({
                    title: 'Error',
                    text: 'Tolong Input Jumlah Uang.',
                    icon: 'error'
                });
                hasError = true;
            }

            if (uang < total) {
                Swal.fire({
                    title: 'Error',
                    text: 'Jumlah Uang Kurang.',
                    icon: 'error'
                });
                hasError = true;
            }

            if (!hasError) {
                Swal.fire({
                    title: 'Confirmation',
                    text: 'Yakin Untuk Checkout?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#cart-form').submit();
                    }
                });
            }
        }

        $('#closeModal').on('click', function() {
            $('#deleteModal').modal('hide');
        });

        $('#tableKeranjang').on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            var jumlah_beli = $(this).data('jumlah_beli');
            $('#deleteModal').modal('show');

            $('#confirmDelete').on('click', function() {
                var quantity = $('#deleteQuantity').val();
                if (quantity > jumlah_beli) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Kuantitas Hapus Lebih Besar Dari Jumlah Beli.',
                        icon: 'error'
                    });
                    return;
                }
                Swal.fire({
                    title: 'Confirmation',
                    text: 'Yakin Untuk Menghapus ' + quantity + ' Produk?',
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
                                id: id,
                                quantity: quantity
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Deleted!',
                                    'Produk Berhasil Di Hapus.',
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
        });

        var table = $('#tableKeranjang').DataTable();

        table.on('draw', function() {
            var data = table.data().toArray();
            var subtotal = data.reduce(function(sum, row) {
                var harga = typeof row.total_harga === 'string' ? row.total_harga.replace('Rp. ', '') : row
                    .total_harga.toString();
                return sum + parseInt(harga);
            }, 0);
            var discount = 0;
            $('#subtotal').text('Rp. ' + subtotal.toFixed(0));
            $('#diskon').text(discount + '%');
            $('#total').text('Rp. ' + subtotal.toFixed(0));
        });

        $('#member').on('change', function() {
            var isMember = $(this).val() !== '';
            var subtotal = parseInt($('#subtotal').text().replace('Rp. ', ''));
            var total = subtotal;
            var discountPercent = 0;
            if (isMember) {
                if (total >= 100000) {
                    discountPercent = 15;
                } else if (total >= 50000) {
                    discountPercent = 10;
                } else {
                    discountPercent = 0;
                }
                var discount = total * (discountPercent / 100);
                total -= discount;
            }
            $('#total').text('Rp. ' + parseInt(total).toFixed(0));
            $('#diskon').text(discountPercent + '%');
        });
    </script>
@endsection
