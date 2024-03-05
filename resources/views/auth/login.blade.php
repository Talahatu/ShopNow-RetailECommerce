@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col col-lg-10 col-md-10">
                <div class="card" style="border-radius: 1rem;">
                    <div class="row g-0">
                        <div class="col-md-6 col-lg-5 d-none d-md-block">
                            <img src="{{ asset('images/shoppingVector.jpg') }}" alt="login form" class="img-fluid"
                                style="border-radius: 1rem 0 0 1rem;object-fit:cover;height:100%" />
                        </div>
                        <div class="col-md-6 col-lg-7 d-flex align-items-center">
                            <div class="card-body p-4 p-lg-5 text-black">
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="d-flex align-items-center mb-3 pb-1">
                                        <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                                        <span class="h1 fw-bold mb-0">ShopNow</span>
                                    </div>

                                    <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Masuk ke akun
                                    </h5>

                                    <div class="form-outline mb-4">
                                        <input type="text" id="email"
                                            class="form-control form-control-lg @error('email') is-invalid @enderror"
                                            name="email" value="{{ old('email') }}" required autofocus
                                            autocomplete="email" />
                                        <label class="form-label" for="email">Alamat email atau nama alias</label>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-outline mb-4">
                                        <input id="password" type="password"
                                            class="form-control form-control-lg @error('password') is-invalid @enderror"
                                            name="password" required autocomplete="current-password" />
                                        <label class="form-label" for="password">Kata Sandi</label>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="pt-1 mb-4">
                                        <button class="btn btn-dark btn-lg btn-block" type="submit">Masuk Akun</button>
                                    </div>

                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link small text-muted" href="{{ route('password.request') }}">
                                            {{ __('Lupa Kata Sandi?') }}
                                        </a>
                                    @endif
                                    <p class="mb-2 pb-lg-2" style="color: #393f81;">Belum Punya Akun? <a
                                            href="{{ route('register') }}" style="color: #393f81;">Buat akun baru
                                            disini!</a></p>
                                    <p>Apakah kamu kurir? <a href="{{ route('courier.show.login') }}"
                                            style="color: #393f81;">Masuk akun disini!</a></p>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
