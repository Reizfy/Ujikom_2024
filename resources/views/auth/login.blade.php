@extends('layouts.app')

@section('login')
    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="d-flex justify-content-center align">
                    <img class="p-3" style="width: 70%;" src="{{ asset('assets/img/kasir.png') }}" alt="">
                </div>
            </div>
            <div class="col-md-6 d-flex justify-content-end ">
                <div>
                    <div class="d-flex justify-content-center">
                        <h1>{{ __('Login') }}</h1>
                    </div>
                    <div>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-12 col-form-label text-md-start">{{ __('Email Address') }}</label>

                                <div class="col-md-12">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-4">
                                <label for="password"
                                    class="col-md-12 col-form-label text-md-start">{{ __('Password') }}</label>

                                <div class="col-md-12">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-block" style="width: 100%;">
                                        {{ __('Login') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
