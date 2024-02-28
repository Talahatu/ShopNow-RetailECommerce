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
                                data-type="accepted">Processed</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link order-type" id="pills-sent-tab" data-bs-toggle="pill" href="#pills-sent"
                                role="tab" aria-controls="pills-sent" aria-selected="true" data-type="sent">Sent</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link order-type" id="pills-finish-tab" data-bs-toggle="pill" href="#pills-finish"
                                role="tab" aria-controls="pills-finish" aria-selected="true"
                                data-type="done">Finished</a>
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
                    <div class="table-responsive">
                        <h3>Couriers current status</h3>
                        <table class="table table-hover sortable-table">
                            <thead>
                                <tr>
                                    <td>No.</td>
                                    <td>Courier Name</td>
                                    <td>Orders Handled</td>
                                    <td>Status</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form mt-4">
                        <label for="courier">Pick a courier</label>
                        <select class="form-control text-light" name="courier" id="selectCourier"></select>
                    </div>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('js/order.js') }}"></script>
@endsection
