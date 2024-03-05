@extends('layouts.app')

@section('content')
    <div class="container py-5 h-50">
        <div class="row d-flex justify-content-center align-items-center h-50">
            <div class="col col-xl-10">
                <div class="card" style="border-radius: 1rem;">
                    <div class=" d-flex align-items-center">
                        <div class="card-body p-4 p-lg-5 text-black">
                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">
                                <div class="d-flex align-items-center mb-3 pb-1">
                                    <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                                    <span class="h1 fw-bold mb-0">{{ __('Ganti Kata Sandi') }}</span>
                                </div>
                                <div class="form-outline mb-4">
                                    <input type="email" id="email"
                                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" required autofocus />
                                    <label class="form-label" for="email">Alamat Email</label>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-outline mb-4">
                                    <input type="password" id="password"
                                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                                        name="password" required autocomplete="new-password" />
                                    <label class="form-label" for="password">{{ __('Kata Sandi') }}</label>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-outline mb-4">
                                    <input type="password" id="password-confirm" class="form-control form-control-lg"
                                        name="password_confirmation" required autocomplete="new-password" />
                                    <label class="form-label" for="password-confirm">{{ __('Kata Sandi Sesuai') }}</label>
                                </div>
                                <div class="pt-1 mb-4">
                                    <button type="submit" class="btn btn-dark btn-lg btn-block">
                                        {{ __('Ganti Password') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
