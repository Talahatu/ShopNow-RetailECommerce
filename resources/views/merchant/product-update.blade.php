@extends('layouts.seller')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/product-create.css') }}">
@endsection
@section('content-wrapper')
    <input type="hidden" name="dia" id="dia" value="{{ $data->category_id }}-{{ $data->brand_id }}">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Update Existing Product</h3>
        </div>
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Please Fill The Form!</h4>
                    <p class="card-description"> {{ $data->name }}'s Information </p>
                    <form class="forms-sample" action="{{ route('product.update', $data->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="inputName">Name</label>
                            <input type="text" class="form-control text-light @error('name') is-invalid @enderror"
                                value="{{ $data->name }}" id="inputName" name="name" placeholder="Name">
                            @error('name')
                                <label id="name-error" class="error mt-2 text-danger" for="name">
                                    {{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="inputDesc">Description</label>
                            <textarea class="form-control text-light @error('desc') is-invalid @enderror" id="inputDesc" name="desc"
                                placeholder="Description" rows="4">{{ $data->description }}</textarea>
                            @error('desc')
                                <label id="desc-error" class="error mt-2 text-danger" for="desc">
                                    {{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group mb-4">
                            <label for="">Choose a category</label>
                            <select class="form-control text-light @error('category') is-invalid @enderror" name="category"
                                id="selectCategory">
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            </select>
                            @error('category')
                                <label id="category-error" class="error mt-2 text-danger" for="category">
                                    {{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group mb-4">
                            <label for="">Choose a brand</label>
                            <select class="form-control text-light  @error('brand') is-invalid @enderror" name="brand"
                                id="selectBrand">
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            </select>
                            @error('brand')
                                <label id="brand-error" class="error mt-2 text-danger" for="brand">
                                    {{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="inputWeight">Weight</label>
                            <div class="input-group">
                                <input type="number" class="form-control text-light @error('weight') is-invalid @enderror"
                                    value="{{ $data->weight }}" id="inputWeight" name="weight" placeholder="Weight"
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
                                    value="{{ $data->stock }}" id="inputStock" name="stock" placeholder="Stock"
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
                                    aria-label="Amount (to the nearest rupiah)" value="{{ $data->price }}" id="inputPrice"
                                    name="price">
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
    <script src="{{ asset('js/product-update.js') }}"></script>
@endsection
