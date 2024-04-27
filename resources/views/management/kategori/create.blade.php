@extends('layouts.app')
@extends('layouts.navbar')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1 class="text-start mb-5 mt-4">
                    {{ __("Tambah Kategori") }}
                </h1>
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('store-kategori') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="kategori_produk" class="form-label">{{ __('Kategori') }}</label>
                                <input type="text" class="form-control" id="kategori_produk" name="kategori_produk" required>
                            </div>

                            <div class="mb-3">
                                <a href="{{ route('management.kategori') }}" class="btn btn-secondary">{{ __('Back') }}</a>
                                <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

