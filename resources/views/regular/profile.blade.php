@extends('layouts.index')

@section('content')
    <div class="container mt-4">
        <div class="row w-100">
            <div class="col-12 card p-3 shadow-2-strong mb-4 d-block d-md-none">
                <!-- Tab navs -->
                <ul class="nav nav-tabs nav-justified mb-3" id="ex1" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link nav-link-profile active" id="v-tabs-profile-tab" data-mdb-toggle="tab"
                            href="#v-tabs-profile" role="tab" aria-controls="v-tabs-profile"
                            aria-selected="true">Profil</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link nav-link-profile tabs-order" id="v-tabs-order-tab" data-mdb-toggle="tab"
                            href="#v-tabs-order" role="tab" aria-controls="v-tabs-order"
                            aria-selected="false">Pesanan</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link nav-link-profile" id="v-tabs-notif-tab" href="{{ route('profile.notif') }}"
                            aria-selected="false">Notifikasi</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row w-100 ">
            <div class="col-3 card p-3 shadow-2-strong overflow-auto d-none d-md-block" style="height:30vh;">
                <!-- Tab navs -->
                <div class="nav flex-column nav-tabs text-center" id="v-tabs-tab" role="tablist"
                    aria-orientation="vertical">
                    <a class="nav-link nav-link-profile active" id="v-tabs-profile-tab-lg"
                        href="{{ route('profile.bio') }}">Profil</a>
                    <a class="nav-link nav-link-profile tabs-order" id="v-tabs-order-tab-lg"
                        href="{{ route('profile.order') }}">Pesanan</a>
                    <a class="nav-link nav-link-profile" id="v-tabs-notif-tab-lg"
                        href="{{ route('profile.notif') }}">Notifikasi</a>
                </div>
                <!-- Tab navs -->
            </div>

            <div class="col-md-9 col-sm-12 ms-auto">
                <!-- Tab content -->
                <div class="tab-content" id="v-tabs-tabContent">
                    <div class="tab-pane fade show active" id="v-tabs" role="tabpanel" aria-labelledby="v-tabstab">
                        @yield('contentProfile')
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
                    <button type="button" class="btn btn-secondary btnCloseModal" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="btnSave" attr-dia="">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @yield('profilejs')
@endsection
