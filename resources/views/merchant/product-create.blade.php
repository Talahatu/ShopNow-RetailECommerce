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
                    <form class="forms-sample">
                        <div class="form-group">
                            <label for="exampleInputName1">Name</label>
                            <input type="text" class="form-control text-light" id="exampleInputName1" placeholder="Name">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail3">Description</label>
                            <textarea class="form-control text-light" id="exampleInputEmail3" placeholder="Description" rows="4"></textarea>
                        </div>
                        <div class="form-group mb-4">
                            <label for="">Choose a category</label>
                            <select class="form-control text-light" id="selectCategory">
                                <option>Category1</option>
                                <option>Category2</option>
                                <option>Category3</option>
                                <option>Category4</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword4">Weight</label>
                            <div class="input-group">

                                <input type="number" class="form-control text-light" id="exampleInputPassword4"
                                    placeholder="Weight">
                                <div class="input-group-append">
                                    <span class="input-group-text text-light">gr</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword4">Stock</label>
                            <div class="input-group">
                                <input type="number" class="form-control text-light" id="exampleInputPassword4"
                                    placeholder="Stock">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword4">Price</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp.</span>
                                </div>
                                <input type="number" step="1000" class="form-control text-light"
                                    aria-label="Amount (to the nearest rupiah)">
                            </div>
                        </div>


                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                        <button class="btn btn-dark">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('js/product-create.js') }}"></script>
@endsection
