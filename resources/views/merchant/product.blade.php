@extends('layouts.seller')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/product.css') }}">
@endsection
@section('content-wrapper')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Daftar Produk</h3>
        </div>
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-pills nav-pills-success nav-pills-custom" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link product-type active" id="pills-live-tab" data-bs-toggle="pill"
                                href="#pills-live" role="tab" aria-controls="pills-live" aria-selected="false"
                                data-type="live">Dijual</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link product-type" id="pills-empty-tab" data-bs-toggle="pill" href="#pills-empty"
                                role="tab" aria-controls="pills-empty" aria-selected="false" data-type="empty">Habis</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link product-type" id="pills-archive-tab" data-bs-toggle="pill"
                                href="#pills-archive" role="tab" aria-controls="pills-archive" aria-selected="true"
                                data-type="archive">Arsip</a>
                        </li>
                    </ul>
                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                        <a type="button" class="btn btn-success create-new-button" id="addNewProduct"
                            href="{{ route('product.create') }}"><i class="mdi mdi-plus"></i>Tambahkan Produk Baru</a>
                    </div>
                    <div class="tab-content tab-content-custom-pill" id="pills-tabContent">
                        <table id="myTable" class="display table table-hover responsive nowrap"
                            style="width: 100% !important">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>SKU</th>
                                    <th>Harga</th>
                                    <th>Jumlah Tersedia</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Riwayat Stok Barang</h4>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th> Gambar</th>
                                        <th> Nama </th>
                                        <th> Tanggal </th>
                                        <th> Bertambah </th>
                                        <th> Berkurang </th>
                                        <th> Total Stok </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stockHistories as $item)
                                        <tr>
                                            <td>
                                                <img src="{{ asset('productimages/' . $item->product->images[0]->name) }}"
                                                    alt="image">
                                            </td>
                                            <td>
                                                {{ $item->product->name }}
                                            </td>
                                            <td> {{ strftime('%A, %d %B %Y', strtotime($item->date)) }} </td>
                                            <td class="text-success"> +{{ $item->addition }}
                                            </td>
                                            <td class="text-danger">
                                                -{{ abs($item->substraction) }}
                                            </td>
                                            <td>{{ $item->total_stock }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('js/product.js') }}"></script>
@endsection
