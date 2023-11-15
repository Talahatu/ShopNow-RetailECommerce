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
                    <form class="forms-sample" action="{{ route('product.store') }}" method="POST">
                        @csrf
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
                            <textarea class="form-control text-light" id="inputDesc" value="{{ old('desc') }}" name="desc"
                                placeholder="Description" rows="4"></textarea>
                        </div>
                        <div class="form-group mb-4">
                            <label for="">Choose a category</label>
                            <select class="form-control text-light" name="category" id="selectCategory"></select>
                        </div>
                        <div class="form-group mb-4">
                            <label for="">Choose a brand</label>
                            <select class="form-control text-light" name="brand" id="selectBrand"></select>
                        </div>
                        <div class="form-group">
                            <label for="inputWeight">Weight</label>
                            <div class="input-group">
                                <input type="number" class="form-control text-light" value="{{ old('weight') }}"
                                    id="inputWeight" name="weight" placeholder="Weight">
                                <div class="input-group-append">
                                    <span class="input-group-text text-light">gr</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputStock">Stock</label>
                            <div class="input-group">
                                <input type="number" class="form-control text-light" value="{{ old('stock') }}"
                                    id="inputStock" name="stock" placeholder="Stock">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPrice">Price</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp.</span>
                                </div>
                                <input type="text" class="form-control text-light"
                                    aria-label="Amount (to the nearest rupiah)" value="{{ old('price') }}" id="inputPrice"
                                    name="price">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('js/product-create.js') }}"></script>
@endsection
