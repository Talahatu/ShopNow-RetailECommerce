@extends('layouts.seller')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/product-create.css') }}">
@endsection
@section('content-wrapper')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Tambahkan Produk Baru</h3>
        </div>
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Mohon isi form berikut!</h4>
                    <p class="card-description">Informasi Produk</p>
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
                                        <span class="visually-hidden">Sebelum</span>
                                    </button>
                                    <button class="carousel-control-next" type="button"
                                        data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Selanjutnya</span>
                                    </button>
                                </div>
                            </div>
                            {{-- Button Choose Image --}}
                            <div class="d-flex justify-content-center">
                                <div class="btn btn-dark btn-rounded">
                                    <label class="form-label text-white m-1" for="image">Pilih Gambar</label>
                                    <input type="file" name="image[]" class="form-control d-none" id="image"
                                        multiple />
                                    @error('image')
                                        <div class="alert alert-danger mt-1 mb-1">{{ 'Mohon pilih gambar produk' }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName">Nama Produk</label>
                            <input type="text" class="form-control text-light @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" id="inputName" name="name" placeholder="Nama Produk">
                            @error('name')
                                <label id="name-error" class="error mt-2 text-danger" for="name">
                                    {{ 'Mohon isikan terlebih dahulu' }}</label>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="inputDesc">Deskripsi</label>
                            <textarea class="form-control text-light @error('desc') is-invalid @enderror" id="inputDesc" value="{{ old('desc') }}"
                                name="desc" placeholder="Deskripsi" rows="4"></textarea>
                            @error('desc')
                                <label id="desc-error" class="error mt-2 text-danger" for="desc">
                                    {{ 'Mohon isikan terlebih dahulu' }}</label>
                            @enderror
                        </div>
                        <div class="form-group mb-4">
                            <label for="">Pilih Kategori</label>
                            <select class="form-control text-light @error('category') is-invalid @enderror" name="category"
                                id="selectCategory"></select>
                            @error('category')
                                <label id="category-error" class="error mt-2 text-danger" for="category">
                                    {{ 'Mohon isikan terlebih dahulu' }}</label>
                            @enderror
                        </div>
                        <div class="form-group mb-4">
                            <label for="">Pilih Merek</label>
                            <select class="form-control text-light  @error('brand') is-invalid @enderror" name="brand"
                                id="selectBrand"></select>
                            @error('brand')
                                <label id="brand-error" class="error mt-2 text-danger" for="brand">
                                    {{ 'Mohon isikan terlebih dahulu' }}</label>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="inputWeight">Berat</label>
                            <div class="input-group">
                                <input type="number" class="form-control text-light @error('weight') is-invalid @enderror"
                                    value="{{ old('weight') }}" id="inputWeight" name="weight" placeholder="Berat"
                                    min="1">
                                <div class="input-group-append">
                                    <span class="input-group-text text-light">gr</span>
                                </div>
                            </div>
                            @error('weight')
                                <label id="weight-error" class="error mt-2 text-danger" for="weight">
                                    {{ 'Mohon isikan terlebih dahulu' }}</label>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="inputStock">Jumlah Tersedia</label>
                            <div class="input-group">
                                <input type="number" class="form-control text-light @error('stock') is-invalid @enderror"
                                    value="{{ old('stock') }}" id="inputStock" name="stock"
                                    placeholder="Jumlah Tersedia" min="1">
                            </div>
                            @error('stock')
                                <label id="stock-error" class="error mt-2 text-danger" for="stock">
                                    {{ 'Mohon isikan terlebih dahulu' }}</label>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="inputPrice">Harga</label>
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
                                    {{ 'Mohon isikan terlebih dahulu' }}</label>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary me-2">Buat</button>
                        <a class="btn btn-dark" href="{{ route('product.index') }}">Batalkan</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('js/product-create.js') }}"></script>
@endsection
