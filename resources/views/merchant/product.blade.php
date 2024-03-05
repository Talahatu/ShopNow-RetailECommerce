@extends('layouts.seller')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/product.css') }}">
@endsection
@section('content-wrapper')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">My Products</h3>
        </div>
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-pills nav-pills-success nav-pills-custom" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link product-type active" id="pills-live-tab" data-bs-toggle="pill"
                                href="#pills-live" role="tab" aria-controls="pills-live" aria-selected="false"
                                data-type="live">Live</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link product-type" id="pills-empty-tab" data-bs-toggle="pill" href="#pills-empty"
                                role="tab" aria-controls="pills-empty" aria-selected="false" data-type="empty">Empty</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link product-type" id="pills-archive-tab" data-bs-toggle="pill"
                                href="#pills-archive" role="tab" aria-controls="pills-archive" aria-selected="true"
                                data-type="archive">Archive</a>
                        </li>
                    </ul>
                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                        <a type="button" class="btn btn-success create-new-button" id="addNewProduct"
                            href="{{ route('product.create') }}"><i class="mdi mdi-plus"></i>Add new product</a>
                    </div>
                    <div class="tab-content tab-content-custom-pill" id="pills-tabContent">
                        <table id="myTable" class="display table table-hover responsive nowrap"
                            style="width: 100% !important">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>SKU</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('js/product.js') }}"></script>
@endsection
