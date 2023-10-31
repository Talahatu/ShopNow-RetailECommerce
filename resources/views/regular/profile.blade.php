@extends('layouts.index')

@section('content')
    <div class="container mt-4">
        <div class="row w-100">
            <div class="col-3 card p-3 shadow-2-strong overflow-auto" style="height:50vh;">
                <!-- Tab navs -->
                <div class="nav flex-column nav-tabs text-center" id="v-tabs-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link active" id="v-tabs-profile-tab" data-mdb-toggle="tab" href="#v-tabs-profile"
                        role="tab" aria-controls="v-tabs-profile" aria-selected="true">Profile</a>
                    <a class="nav-link" id="v-tabs-order-tab" data-mdb-toggle="tab" href="#v-tabs-order" role="tab"
                        aria-controls="v-tabs-order" aria-selected="false">Orders</a>
                    <a class="nav-link" id="v-tabs-notif-tab" data-mdb-toggle="tab" href="#v-tabs-notif" role="tab"
                        aria-controls="v-tabs-notif" aria-selected="false">Notifications</a>
                </div>
                <!-- Tab navs -->
            </div>

            <div class="col-9 ms-auto">
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
@endsection

@section('js')
    <script src="{{ asset('js/profile.js') }}"></script>
@endsection
