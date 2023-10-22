@extends('layouts.app')

@section('content')
    <div class="container py-5 h-50">
        <div class="row d-flex justify-content-center align-items-center h-50">
            <div class="col col-xl-10">
                <div class="card" style="border-radius: 1rem;">
                    <div class=" d-flex align-items-center">
                        <div class="card-body p-4 p-lg-5 text-black">
                            <div class="d-flex align-items-center mb-3 pb-1">
                                <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                                <span class="h1 fw-bold mb-0">{{ __('Verify Your Email Address') }}</span>
                            </div>
                            @if (session('resent'))
                                <div class="alert alert-success" role="alert">
                                    {{ __('A fresh verification link has been sent to your email address.') }}
                                </div>
                            @endif
                            <div class="d-flex align-items-center mb-3 pb-1">
                                <span
                                    class="h5 mb-0">{{ __('Before proceeding, please check your email for a verification link.') }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-3 pb-1">
                                <span class="h5 mb-0">{{ __('If you did not receive the email') }},</span>
                            </div>
                            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                                @csrf
                                <button type="submit"
                                    class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
