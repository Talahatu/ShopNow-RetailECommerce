@extends('layouts.seller')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/order.css') }}">
@endsection
@section('content-wrapper')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">My Orders</h3>
        </div>
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-pills nav-pills-success nav-pills-custom" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link order-type active" id="pills-new-tab" data-bs-toggle="pill" href="#pills-new"
                                role="tab" aria-controls="pills-new" aria-selected="false" data-type="new">New</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link order-type" id="pills-process-tab" data-bs-toggle="pill"
                                href="#pills-process" role="tab" aria-controls="pills-process" aria-selected="false"
                                data-type="process">Processed</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link order-type" id="pills-sent-tab" data-bs-toggle="pill" href="#pills-sent"
                                role="tab" aria-controls="pills-sent" aria-selected="true" data-type="sent">Sent</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link order-type" id="pills-finish-tab" data-bs-toggle="pill" href="#pills-finish"
                                role="tab" aria-controls="pills-finish" aria-selected="true"
                                data-type="finish">Finished</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link order-type" id="pills-cancel-tab" data-bs-toggle="pill" href="#pills-cancel"
                                role="tab" aria-controls="pills-cancel" aria-selected="true"
                                data-type="cancel">Cancelled</a>
                        </li>
                    </ul>
                    <div class="tab-content tab-content-custom-pill" id="pills-tabContent">
                        <table id="myTable" class="display table table-hover responsive nowrap"
                            style="width: 100% !important">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Destination</th>
                                    <th>Distance</th>
                                    <th>Payment Type</th>
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
    <script src="{{ asset('js/order.js') }}"></script>
@endsection
