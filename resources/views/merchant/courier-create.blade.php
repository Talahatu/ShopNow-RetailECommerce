@extends('layouts.seller')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/courier-create.css') }}">
@endsection
@section('content-wrapper')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Tambahkan Kurir Baru</h3>
        </div>
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Mohon isi form berikut!</h4>
                    <p class="card-description">Informasi Kurir </p>
                    <form class="forms-sample" action="{{ route('courier.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="inputName">Nama Kurir</label>
                            <input type="text" class="form-control text-light @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" id="inputName" name="name" placeholder="Nama">
                            @error('name')
                                <label id="name-error" class="error mt-2 text-danger" for="name">
                                    {{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group mb-4">
                            <label for="">Alamat Email Kurir</label>
                            <input type="text" class="form-control text-light @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" id="inputEmail" name="email" placeholder="Alamat Email">
                            @error('email')
                                <label id="email-error" class="error mt-2 text-danger" for="email">
                                    {{ $message }}</label>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary me-2">Tambahkan</button>
                        <a class="btn btn-dark" href="{{ route('courier.index') }}">Batalkan</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('js/courier-create.js') }}"></script>
@endsection
