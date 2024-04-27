@extends('layouts.app')
@extends('layouts.navbar')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Edit Member') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('update-member', [$member->id]) }}" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="nama_member" class="form-label">{{ __('Nama Member') }}</label>
                                <input value="{{ $member->nama_member }}" type="text" class="form-control" id="nama_member" name="nama_member" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Email') }}</label>
                                <input value="{{ $member->email }}" type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">{{ __('Phone') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon2">+62</span>
                                    </div>
                                    <input min="12" max="13" value="{{ $member->phone }}" type="phone" class="form-control" id="phone" name="phone" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="alamat" class="form-label">{{ __('Alamat') }}</label>
                                <input value="{{ $member->alamat }}" type="text" class="form-control" id="alamat" name="alamat" required>
                            </div>

                            <div class="mb-3">
                                <a href="{{ route('management.member') }}" class="btn btn-secondary">{{ __('Back') }}</a>
                                <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
