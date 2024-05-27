@extends('layouts.seller')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/order.css') }}">
@endsection
@section('content-wrapper')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Daftar Pesanan</h3>
        </div>
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-pills nav-pills-success nav-pills-custom" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link order-type active" id="pills-new-tab" data-bs-toggle="pill" href="#pills-new"
                                role="tab" aria-controls="pills-new" aria-selected="false" data-type="new">Baru</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link order-type" id="pills-process-tab" data-bs-toggle="pill"
                                href="#pills-process" role="tab" aria-controls="pills-process" aria-selected="false"
                                data-type="accepted">Diterima</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link order-type" id="pills-sent-tab" data-bs-toggle="pill" href="#pills-sent"
                                role="tab" aria-controls="pills-sent" aria-selected="true" data-type="sent">Dikirim</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link order-type" id="pills-finish-tab" data-bs-toggle="pill" href="#pills-finish"
                                role="tab" aria-controls="pills-finish" aria-selected="true"
                                data-type="done">Selesai</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link order-type" id="pills-cancel-tab" data-bs-toggle="pill" href="#pills-cancel"
                                role="tab" aria-controls="pills-cancel" aria-selected="true"
                                data-type="cancel">Ditolak</a>
                        </li>
                    </ul>
                    <div class="tab-content tab-content-custom-pill" id="pills-tabContent">
                        <table id="myTable" class="display table table-hover responsive nowrap"
                            style="width: 100% !important">
                            <thead>
                                <tr>
                                    <th>Nomor Pesanan</th>
                                    <th>Tanggal Pesanan</th>
                                    <th>Alamat Tujuan</th>
                                    <th>Tipe Pembayaran</th>
                                    <th>Jarak Tempuh</th>
                                    <th>Aksi/Keterangan</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <hr style="margin-top: 0px">
                <div class="modal-body">
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('js/order.js') }}"></script>
@endsection
