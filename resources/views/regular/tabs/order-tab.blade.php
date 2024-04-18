@extends('regular.profile')

@section('contentProfile')
    <main class="card p-3 shadow-2-strong">
        <?php setlocale(LC_TIME, 'id_ID.utf8', 'Indonesian_indonesia.1252'); ?>
        <h1>Pesanan Anda</h1>
        <!-- Tabs navs -->
        <ul class="nav nav-tabs nav-justified mb-3" id="ex1" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="ex3-tab-1" data-mdb-toggle="tab" href="#ex3-tabs-1" role="tab"
                    aria-controls="ex3-tabs-1" aria-selected="true" data-sts="new">Menunggu Seller</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="ex3-tab-2" data-mdb-toggle="tab" href="#ex3-tabs-2" role="tab"
                    aria-controls="ex3-tabs-2" aria-selected="false" data-sts="accepted">Sedang Diproses</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="ex3-tab-3" data-mdb-toggle="tab" href="#ex3-tabs-3" role="tab"
                    aria-controls="ex3-tabs-3" aria-selected="false" data-sts="sent">Sedang Dikirim</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="ex3-tab-3" data-mdb-toggle="tab" href="#ex3-tabs-4" role="tab"
                    aria-controls="ex3-tabs-3" aria-selected="false" data-sts="done">Selesai</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="ex3-tab-3" data-mdb-toggle="tab" href="#ex3-tabs-5" role="tab"
                    aria-controls="ex3-tabs-3" aria-selected="false" data-sts="cancel">Dibatalkan</a>
            </li>
        </ul>
        <!-- Tabs navs -->

        <!-- Tabs content -->
        <div class="tab-content" id="ex2-content">
            <div class="tab-pane fade show active" id="ex3-tabs-1" role="tabpanel" aria-labelledby="ex3-tab-1">
                @include('regular.tabs.horizontaltabs.pending-tab')
            </div>
            <div class="tab-pane fade" id="ex3-tabs-2" role="tabpanel" aria-labelledby="ex3-tab-2">
                @include('regular.tabs.horizontaltabs.processed-tab')
            </div>
            <div class="tab-pane fade" id="ex3-tabs-3" role="tabpanel" aria-labelledby="ex3-tab-3">
                @include('regular.tabs.horizontaltabs.sent-tab')
            </div>
            <div class="tab-pane fade" id="ex3-tabs-4" role="tabpanel" aria-labelledby="ex3-tab-4">
                @include('regular.tabs.horizontaltabs.finished-tab')
            </div>
            <div class="tab-pane fade" id="ex3-tabs-5" role="tabpanel" aria-labelledby="ex3-tab-5">
                @include('regular.tabs.horizontaltabs.cancel-tab')
            </div>
        </div>
        <!-- Tabs content -->
    </main>
@endsection
@section('profilejs')
    <script src="{{ asset('js/profile-order.js') }}"></script>
@endsection
