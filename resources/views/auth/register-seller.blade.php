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
                                <form method="POST" action="{{ route('seller.store') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="d-flex align-items-center mb-3 pb-1">
                                        <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                                        <span class="h1 fw-bold mb-0">ShopNow</span>
                                    </div>
                                    <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;"> Open a seller account!
                                    </h5>


                                    <div class="shopPhotoContainer mb-4">
                                        <div class="mb-4 d-flex justify-content-center">
                                            <img id="selectedImage"
                                                src="https://mdbootstrap.com/img/Photos/Others/placeholder.jpg"
                                                alt="example placeholder" style="width: 300px;" />
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <div class="btn btn-dark btn-rounded">
                                                <label class="form-label text-white m-1" for="image">Choose
                                                    Image</label>
                                                <input type="file" name="image" class="form-control d-none changeCheck"
                                                    id="image" />
                                                @error('image')
                                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-outline mb-4">
                                        <input type="text" id="name"
                                            class="form-control form-control-lg @error('name') is-invalid @enderror changeCheck"
                                            name="name" value="{{ old('name') }}" required autofocus
                                            autocomplete="name" />
                                        <label class="form-label" for="name">{{ __('Shop Name') }}</label>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-outline mb-4">
                                        <input type="tel" id="phoneNumber"
                                            class="form-control form-control-lg @error('phoneNumber') is-invalid @enderror changeCheck"
                                            name="phoneNumber" value="{{ old('phoneNumber') }}" autofocus
                                            autocomplete="phoneNumber">
                                        <label class="form-label" for="desc">{{ __('phoneNumber') }}</label>
                                        @error('phoneNumber')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-outline mb-4">
                                        <input type="text" id="address"
                                            class="form-control form-control-lg @error('address') is-invalid @enderror changeCheck"
                                            name="address" value="{{ old('address') }}" required autofocus
                                            autocomplete="address" />
                                        <input type="hidden" name="latlng" id="ll">
                                        <label class="form-label" for="address">{{ __('Shop Address') }}</label>
                                    </div>
                                    @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div id="map" style="height: 180px" class="mb-4"></div>

                                    <div class="pt-1 mb-4">
                                        <button class="btn btn-dark btn-lg btn-block" disabled type="submit"
                                            id="btnsbmt">{{ __('Open Account') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('js/register-seller.js') }}"></script>
@endsection
