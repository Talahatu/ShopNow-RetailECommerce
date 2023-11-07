@extends('layouts.app')

@section('content')
    <div class="container py-5 h-50">
        <div class="row d-flex justify-content-center align-items-center h-50">
            <div class="col col-xl-10">
                <div class="card" style="border-radius: 1rem;">
                    <div class=" d-flex align-items-center">
                        <div class="card-body p-4 p-lg-5 text-black">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }} <a href="/">Back to Login!</a>
                                </div>
                            @endif
                            <form method="POST" action="{{ route('change.email') }}">
                                @csrf
                                <div class="d-flex align-items-center mb-3 pb-1">
                                    <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                                    <span class="h1 fw-bold mb-0">{{ __('Change Email') }}</span>
                                </div>

                                <div class="form-outline mb-4">
                                    <input type="email" id="email"
                                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" required autofocus />
                                    <label class="form-label" for="email">Email Address</label>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="pt-1 mb-4">
                                    <button type="submit" class="btn btn-dark btn-lg btn-block">
                                        {{ __('Send Email Change Link') }}
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
