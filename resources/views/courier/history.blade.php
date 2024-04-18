@extends('layouts.courier')

@section('pageTitle')
    <?php setlocale(LC_TIME, 'id_ID.utf8', 'Indonesian_indonesia.1252'); ?>
    <section class="page-title bg-dark">
        <div class="container">
            <div class="page-title-row">
                <div class="page-title-content">
                    <h1 class="text-light">Riwayat Pengiriman</h1>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('content')
    <div class="ongoing-order">
        <div class="row g-4 mb-5 mt-0 text-center section p-4 rounded" id="now">
            @if (count($finishDeliveries) == 0)
                <span>Belum Pernah Menyelesaikan Pengiriman Pesanan</span>
            @endif
            @foreach ($finishDeliveries as $delivery)
                <article class="entry event col-12 mb-4">
                    <div
                        class="grid-inner bg-white row g-0 p-3 border-0 rounded-5 shadow-sm h-shadow all-ts h-translate-y-sm">
                        <div class="col-md-12 p-4">
                            <div class="entry-meta no-separator mb-1 mt-0 d-flex d-sm-block justify-content-center">
                                <ul>
                                    <li>
                                        <span
                                            class="text-uppercase fw-medium">{{ strftime('%A, %d %B %Y', strtotime($delivery->start_date)) }}</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="entry-title">
                                <h3 class="text-center text-sm-start"><a>{{ $delivery->order->orderID }}
                                        @if (($delivery->arrive_date != null) & ($delivery->status == 'progress'))
                                            <span class="badge bg-warning ">{{ __('Belum Disetor') }}</span>
                                        @elseif(($delivery->arrive_date != null) & ($delivery->status == 'done'))
                                            <span class="badge bg-success ">{{ __('Selesai') }}</span>
                                        @endif
                                    </a></h3>
                            </div>
                            <div class="entry-meta no-separator text-center text-sm-start">
                                <ul>
                                    <li><a class="fw-normal"><i class="uil uil-map-marker"></i>
                                            {{ $delivery->order->destination_address }}</a></li>
                                </ul>
                            </div>
                            <div class="entry-content my-3 text-center text-md-start">
                                <button
                                    class="button button-border button-rounded button-blue button-fill btnDeliveryDetail"
                                    data-di="{{ $delivery->order_id }}-{{ $delivery->id }}" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal"><i class="fa-solid fa-book"></i><span>Rincian
                                        Pengiriman</span></button>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
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
                <div class="modal-body"></div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('js/courier-history.js') }}"></script>
@endsection
