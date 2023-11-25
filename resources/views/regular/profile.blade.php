@extends('layouts.index')

@section('content')
    <div class="container mt-4">
        <div class="row w-100">
            <div class="col-12 card p-3 shadow-2-strong mb-4 d-block d-md-none">
                <!-- Tab navs -->
                <ul class="nav nav-tabs nav-justified mb-3" id="ex1" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="v-tabs-profile-tab" data-mdb-toggle="tab" href="#v-tabs-profile"
                            role="tab" aria-controls="v-tabs-profile" aria-selected="true">Profile</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link tabs-order" id="v-tabs-order-tab" data-mdb-toggle="tab" href="#v-tabs-order"
                            role="tab" aria-controls="v-tabs-order" aria-selected="false">Orders</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="v-tabs-notif-tab" data-mdb-toggle="tab" href="#v-tabs-notif" role="tab"
                            aria-controls="v-tabs-notif" aria-selected="false">Notifications</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row w-100 ">
            <div class="col-3 card p-3 shadow-2-strong overflow-auto d-none d-md-block" style="height:30vh;">
                <!-- Tab navs -->
                <div class="nav flex-column nav-tabs text-center" id="v-tabs-tab" role="tablist"
                    aria-orientation="vertical">
                    <a class="nav-link active" id="v-tabs-profile-tab" data-mdb-toggle="tab" href="#v-tabs-profile"
                        role="tab" aria-controls="v-tabs-profile" aria-selected="true">Profile</a>
                    <a class="nav-link tabs-order" id="v-tabs-order-tab-lg" data-mdb-toggle="tab" href="#v-tabs-order"
                        role="tab" aria-controls="v-tabs-order" aria-selected="false">Orders</a>
                    <a class="nav-link" id="v-tabs-notif-tab" data-mdb-toggle="tab" href="#v-tabs-notif" role="tab"
                        aria-controls="v-tabs-notif" aria-selected="false">Notifications</a>
                </div>
                <!-- Tab navs -->
            </div>

            <div class="col-md-9 col-sm-12 ms-auto">
                <!-- Tab content -->
                <div class="tab-content" id="v-tabs-tabContent">
                    <div class="tab-pane fade show active" id="v-tabs-profile" role="tabpanel"
                        aria-labelledby="v-tabs-profile-tab">

                        @include('regular.tabs.profile-tab')
                    </div>
                    <div class="tab-pane fade" id="v-tabs-order" role="tabpanel" aria-labelledby="v-tabs-profile-tab">

                        @include('regular.tabs.order-tab')
                    </div>
                    <div class="tab-pane fade" id="v-tabs-notif" role="tabpanel" aria-labelledby="v-tabs-notif-tab">

                        @include('regular.tabs.notif-tab')
                    </div>
                </div>
                <!-- Tab content -->
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" modal-tags="">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Modal title</h5>
                    <button type="button" class="btn-close btnCloseModal" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">

                </div>
                <div class="modal-footer" id="modalFooter">
                    <button type="button" class="btn btn-secondary btnCloseModal" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btnSave" attr-dia="">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('js/profile.js') }}"></script>
@endsection
