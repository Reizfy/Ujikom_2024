@extends('layouts.app')
@extends('layouts.navbar')

@section('content')
    <div class="container">
        <div class="row justify-content-center ">
            <div class="col-md-8">
                <h1 class="text-start mb-5 mt-4">
                    {{ __("Tambah Produk") }}
                </h1>
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('store-produk') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="photo_produk" class="form-label">{{ __('Photo Produk') }}</label>
                                <input type="file" class="form-control" id="photo_produk" name="photo_produk" required>
                            </div>

                            <div class="mb-3">
                                <label for="nama_produk" class="form-label">{{ __('Nama Produk') }}</label>
                                <input type="text" class="form-control" id="nama_produk" name="nama_produk" required>
                            </div>

                            <div class="mb-3">
                                <label for="kategori_produk" class="form-label">{{ __('Kategori Produk') }}</label>
                                <select class="form-control" id="kategori_produk" name="kategori_produk" required>
                                    <option value="" disabled selected>Select One</option>
                                    @foreach($kategori as $item)
                                        <option value="{{ $item->kategori_produk }}">{{ $item->kategori_produk }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="jumlah_stok" class="form-label">{{ __('Jumlah Stok') }}</label>
                                <input min="1" type="number" class="form-control" id="jumlah_stok" name="jumlah_stok" required>
                            </div>

                            <div class="mb-3">
                                <label for="harga_produk" class="form-label">{{ __('Harga') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon2">Rp.</span>
                                    </div>
                                    <input min="1" type="number" class="form-control" id="harga_produk" name="harga_produk" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <a href="{{ route('management.produk') }}" class="btn btn-secondary">{{ __('Back') }}</a>
                                <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
