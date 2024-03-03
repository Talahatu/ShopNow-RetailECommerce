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
                                <form method="POST" action="{{ route('courier.login.attempt') }}">
                                    @csrf
                                    <div class="d-flex align-items-center mb-3 pb-1">
                                        <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                                        <span class="h1 fw-bold mb-0">ShopNow</span>
                                    </div>

                                    <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Sign into your courier's
                                        account
                                    </h5>

                                    <div class="form-outline mb-4">
                                        <input type="text" id="username"
                                            class="form-control form-control-lg @error('username') is-invalid @enderror"
                                            name="username" value="{{ old('username') }}" required autofocus
                                            autocomplete="username" />
                                        <label class="form-label" for="username">Username</label>
                                        @error('username')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-outline mb-4">
                                        <input id="password" type="password"
                                            class="form-control form-control-lg @error('password') is-invalid @enderror"
                                            name="password" required autocomplete="current-password" />
                                        <label class="form-label" for="password">Password</label>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="pt-1 mb-4">
                                        <button class="btn btn-dark btn-lg btn-block" type="submit">Login</button>
                                    </div>
                                    <p class="mb-2 pb-lg-2" style="color: #393f81;">Doesn't have an account yet? Apply to a
                                        seller!</p>
                                    <p>Back to shopping? <a href="{{ route('home') }}" style="color: #393f81;">Start
                                            Shopping Now!</a></p>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
