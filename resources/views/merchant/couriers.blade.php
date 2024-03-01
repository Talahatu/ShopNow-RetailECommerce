@extends('layouts.seller')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/courier.css') }}">
@endsection
@section('content-wrapper')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">My Couriers</h3>
        </div>
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <div class="tab-content tab-content-custom-pill" id="pills-tabContent">
                        <table id="myTable" class="display table table-hover responsive nowrap"
                            style="width: 100% !important">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Operational Fee</th>
                                    <th>Delivery Status</th>
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
    <script src="{{ asset('js/courier.js') }}"></script>
@endsection
