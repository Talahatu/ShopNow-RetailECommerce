@extends('layouts.seller')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/product-create.css') }}">
@endsection
@section('content-wrapper')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Add New Product</h3>
        </div>
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Please Fill The Form!</h4>
                    <p class="card-description"> Product's Information </p>
                    <form class="forms-sample" action="{{ route('product.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="shopPhotoContainer mb-4">
                            {{-- Carousel --}}
                            <div class="mb-4 d-flex justify-content-center img-container">
                                <div id="carouselExampleIndicators" class="carousel slide w-50 mb-4"
                                    data-bs-ride="carousel">
                                    <div class="carousel-indicators">
                                        <button type="button" data-bs-target="#carouselExampleIndicators"
                                            data-bs-slide-to="0" class="active" aria-current="true"
                                            aria-label="Slide 1"></button>
                                    </div>
                                    <div class="carousel-inner">
                                        <div class="carousel-item active">
                                            <img src="https://mdbootstrap.com/img/Photos/Others/placeholder.jpg"
                                                class="d-block w-100" style="object-fit: cover" alt="...">
                                        </div>
                                    </div>
                                    <button class="carousel-control-prev" type="button"
                                        data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button"
                                        data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                </div>
                            </div>
                            {{-- Button Choose Image --}}
                            <div class="d-flex justify-content-center">
                                <div class="btn btn-dark btn-rounded">
                                    <label class="form-label text-white m-1" for="image">Choose
                                        Image</label>
                                    <input type="file" name="image[]" class="form-control d-none" id="image"
                                        multiple />
                                    @error('image')
                                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName">Name</label>
                            <input type="text" class="form-control text-light @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" id="inputName" name="name" placeholder="Name">
                            @error('name')
                                <label id="name-error" class="error mt-2 text-danger" for="name">
                                    {{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="inputDesc">Description</label>
                            <textarea class="form-control text-light @error('desc') is-invalid @enderror" id="inputDesc" value="{{ old('desc') }}"
                                name="desc" placeholder="Description" rows="4"></textarea>
                            @error('desc')
                                <label id="desc-error" class="error mt-2 text-danger" for="desc">
                                    {{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group mb-4">
                            <label for="">Choose a category</label>
                            <select class="form-control text-light @error('category') is-invalid @enderror" name="category"
                                id="selectCategory"></select>
                            @error('category')
                                <label id="category-error" class="error mt-2 text-danger" for="category">
                                    {{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group mb-4">
                            <label for="">Choose a brand</label>
                            <select class="form-control text-light  @error('brand') is-invalid @enderror" name="brand"
                                id="selectBrand"></select>
                            @error('brand')
                                <label id="brand-error" class="error mt-2 text-danger" for="brand">
                                    {{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="inputWeight">Weight</label>
                            <div class="input-group">
                                <input type="number" class="form-control text-light @error('weight') is-invalid @enderror"
                                    value="{{ old('weight') }}" id="inputWeight" name="weight" placeholder="Weight"
                                    min="1">
                                <div class="input-group-append">
                                    <span class="input-group-text text-light">gr</span>
                                </div>
                            </div>
                            @error('weight')
                                <label id="weight-error" class="error mt-2 text-danger" for="weight">
                                    {{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="inputStock">Stock</label>
                            <div class="input-group">
                                <input type="number" class="form-control text-light @error('stock') is-invalid @enderror"
                                    value="{{ old('stock') }}" id="inputStock" name="stock" placeholder="Stock"
                                    min="1">
                            </div>
                            @error('stock')
                                <label id="stock-error" class="error mt-2 text-danger" for="stock">
                                    {{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="inputPrice">Price</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp.</span>
                                </div>
                                <input type="text" class="form-control text-light @error('price') is-invalid @enderror"
                                    aria-label="Amount (to the nearest rupiah)" value="{{ old('price') }}"
                                    id="inputPrice" name="price">
                            </div>
                            @error('price')
                                <label id="price-error" class="error mt-2 text-danger" for="price">
                                    {{ $message }}</label>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                        <a class="btn btn-dark" href="{{ route('product.index') }}">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('js/product-create.js') }}"></script>
@endsection
