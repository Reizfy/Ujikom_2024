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
            <div class="col-md-8">
                <h1 class="text-start mb-5 mt-4">
                    {{ __('Tambah Petugas') }}
                </h1>
                <div class="card">
                    <div class="card-header">{{ __('Tambah Petugas') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('store-petugas') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="nama_petugas" class="form-label">{{ __('Nama Petugas') }}</label>
                                <input type="text" class="form-control" id="nama_petugas" name="nama_petugas" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Email') }}</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">{{ __('Phone') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon2">+62</span>
                                    </div>
                                    <input min="12" max="13" type="phone" class="form-control" id="phone" name="phone" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <div class="mb-3">
                                <a href="{{ route('management.petugas') }}"
                                    class="btn btn-secondary">{{ __('Back') }}</a>
                                <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
