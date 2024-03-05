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
                                <span class="h1 fw-bold mb-0">{{ __('Periksa Kembali Alamat Email Anda') }}</span>
                            </div>
                            @if (session('resent'))
                                <div class="alert alert-success" role="alert">
                                    {{ __('Link Perubahan baru telah dikirim ke alamat email anda') }}
                                </div>
                            @endif
                            <div class="d-flex align-items-center mb-3 pb-1">
                                <span
                                    class="h5 mb-0">{{ __('Sebelum lanjut, mohon periksa link verifikasi pada email anda.') }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-3 pb-1">
                                <span class="h5 mb-0">{{ __('Bila link verifikasi belum diterima pada email') }},</span>
                            </div>
                            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                                @csrf
                                <button type="submit"
                                    class="btn btn-link p-0 m-0 align-baseline">{{ __('Klik disini untuk mengirim ulang') }}</button>.
                            </form><br>
                            <a href="{{ route('change.email.show') }}">Alamat email salah? Ubah alamat email disini!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
