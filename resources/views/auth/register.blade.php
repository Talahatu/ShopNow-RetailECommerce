@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col col-lg-10 col-md-10">
                <div class="card" style="border-radius: 1rem;">
                    <div class="row">
                        <div class="col-md-12 col-lg-12 d-none d-md-block">
                            <img src="{{ asset('images/shoppingVector.jpg') }}" alt="login form" class="img-fluid"
                                style="border-radius: 1rem 1rem 0 1rem;object-fit:cover;height:30vh;width:100%" />
                        </div>
                    </div>
                    <div class="row g-0">
                        <div class="col-md-12 col-lg-12 d-flex align-items-center">
                            <div class="card-body p-4 p-lg-5 text-black">
                                <form method="POST" action="{{ route('register') }}">
                                    @csrf
                                    <div class="d-flex align-items-center mb-3 pb-1">
                                        <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                                        <span class="h1 fw-bold mb-0">ShopNow</span>
                                    </div>
                                    <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;"> Buat Akun Baru!
                                    </h5>

                                    @if (session('verifyfailed'))
                                        <div class="alert alert-success" role="alert">
                                            {{ __('Mohon buat akun ulang!') }}
                                        </div>
                                    @endif
                                    <div class="form-outline mb-4">
                                        <input type="text" id="name"
                                            class="form-control form-control-lg @error('name') is-invalid @enderror"
                                            name="name" value="{{ old('name') }}" autocomplete="name" />
                                        <label class="form-label" for="email">{{ __('Nama Anda') }}</label>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ 'Mohon diisi terlebih dahulu!' }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-outline mb-4">
                                        <input type="text" id="username"
                                            class="form-control form-control-lg @error('username') is-invalid @enderror"
                                            name="username" value="{{ old('username') }}" autocomplete="username" />
                                        <label class="form-label" for="username">{{ __('Nama Alias') }}</label>
                                        @error('username')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ 'Mohon diisi terlebih dahulu!' }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-outline mb-4">
                                        <input type="text" id="address"
                                            class="form-control form-control-lg @error('address') is-invalid @enderror"
                                            name="address" value="{{ old('address') }}" autocomplete="address" />
                                        <input type="hidden" name="latlng" id="ll">
                                        <label class="form-label" for="address">{{ __('Alamat Rumah') }}</label>
                                        @error('address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ 'Mohon diisi terlebih dahulu!' }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div id="map" style="height: 180px" class="mb-4"></div>

                                    <div class="form-outline mb-4">
                                        <input type="text" id="email"
                                            class="form-control form-control-lg @error('email') is-invalid @enderror"
                                            name="email" value="{{ old('email') }}" autocomplete="email" />
                                        <label class="form-label" for="email">Alamat Email</label>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ 'Mohon diisi dengan format alamat email yang benar!' }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-outline mb-4">
                                        <input type="password" id="password"
                                            class="form-control form-control-lg @error('password') is-invalid @enderror"
                                            name="password" autocomplete="new-password" />
                                        <label class="form-label" for="password">{{ __('Kata Sandi') }}</label>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                @if ($message = 'The password confirmation does not match.')
                                                    <strong>Konfirmasi kata sandi tidak sesuai dengan kata sandi</strong>
                                                @else
                                                    <strong>Kata sandi minimal 8 karakter</strong>
                                                @endif
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-outline mb-4">
                                        <input type="password" id="password-confirm" class="form-control form-control-lg"
                                            name="password_confirmation" autocomplete="new-password" />
                                        <label class="form-label"
                                            for="password-confirm">{{ __('Konfirmasi Kata Sandi') }}</label>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="pt-1 mb-4">
                                        <button class="btn btn-dark btn-lg btn-block"
                                            type="submit">{{ __('Buat Akun') }}</button>
                                    </div>
                                </form>
                                <h6><a href="{{ route('login') }}">Sudah punya akun? Masuk sekarang!</a></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('js/register.js') }}"></script>
@endsection
