@extends('layouts.courier')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/courier-delivery.css') }}">

    <link rel="stylesheet" href="{{ asset('Canvas7/css/components/bs-filestyle.css') }}">
@endsection
@section('pageTitle')
    <?php setlocale(LC_TIME, 'id_ID.utf8', 'Indonesian_indonesia.1252'); ?>
    <section class="page-title bg-dark">
        <div class="container">
            <div class="page-title-row">
                <div class="page-title-content">
                    <h1 class="text-light">Rincian Pengiriman</h1>
                    {{-- Might not be used at all --}}
                    {{-- <span class="text-light">Uang Saku</span>
                    <h1 class="text-white" id="feeAvailable" data-val="{{ Auth::guard('courier')->user()->operationalFee }}">Rp
                        {{ number_format(Auth::guard('courier')->user()->operationalFee, 0, ',', '.') }}
                    </h1>
                    <button class="button button-border button-rounded button-dirtygreen mt-2" id="withdrawFee"
                        data-bs-toggle="modal" data-bs-target="#exampleModal">Tarik
                        Uang</button> --}}
                </div>
            </div>
        </div>
    </section>
@endsection
@section('content')
    <div class="ongoing-order">
        {{-- <button id="test" class="btn btn-danger">Test</button> --}}
        <h1>Pesanan Nomor #{{ $order->orderID }}</h1>
        <input type="hidden" name="dia" id="dia" value="{{ $order->id }}-{{ $order->deliveries[0]->id }}">
        <div class=" form-group row">
            <label for="orderNumber" class="col-sm-3 col-form-label"><b>Nama Pemesan:&nbsp;</b></label>
            <div class="col-sm-7">
                <label for="orderNumber">{{ $order->user->name }}</label>
            </div>
        </div>
        <div class=" form-group row">
            <label for="hp" class="col-sm-3 col-form-label"><b>Nomor HP:&nbsp;</b></label>
            <div class="col-sm-7">
                <label for="hp">{{ $order->user->phoneNumber }}</label>
            </div>
        </div>
        <div class=" form-group row">
            <label for="orderNumber" class="col-sm-3 col-form-label"><b>Nomor Pesanan:&nbsp;</b></label>
            <div class="col-sm-7">
                <label for="orderNumber">{{ $order->orderID }}</label>
            </div>
        </div>
        <div class=" form-group row">
            <label for="paymentType" class="col-sm-3 col-form-label"><b>Jenis Pembayaran:&nbsp;</b></label>
            <div class="col-sm-7">
                <label for="paymentType"
                    class="col-form-label">{{ $order->payment_method == 'cod' ? 'Pembayaran di tempat (COD)' : 'Saldo' }}</label>
            </div>
        </div>
        <div class="form-group row">
            <label for="orderNumber" class="col-sm-3 col-form-label"><b>Tanggal Pengiriman:&nbsp;</b></label>
            <div class="col-sm-7">
                <label for="orderNumber"
                    class="col-form-label">{{ strftime('%A, %d %B %Y', strtotime($order->deliveries[0]->start_date)) }}</label>
            </div>
        </div>
        <div class="form-group row">
            <label for="destination" class="col-sm-3 col-form-label"><b>Alamat Toko:&nbsp;</b></label>
            <div class="col-sm-9">
                <label for="destination" class="col-form-label">{{ $order->shop->address }}</label>
            </div>
        </div>
        <div class="form-group row">
            <label for="destination" class="col-sm-3 col-form-label"><b>Alamat Tujuan:&nbsp;</b></label>
            <div class="col-sm-9">
                <label for="destination" class="col-form-label">{{ $order->destination_address }}</label>
            </div>
        </div>
        @if ($order->payment_method == 'cod')
            <div class="style-msg infomsg">
                <div class="sb-msg"><i class="bi-exclamation-diamond-fill"></i><strong>Perhatian!</strong> Jangan lupa untuk
                    memastikan anda menerima uang operasional yang diberikan penjual sebelum memulai pengiriman</div>
            </div>
            <div class="form-group row">
                <label for="orderTotal" class="col-sm-3 col-form-label"><b>Nominal Uang Operational:&nbsp;</b></label>
                <div class="col-sm-7">
                    <label for="orderTotal" class="col-form-label">Rp.
                        {{ number_format($order->deliveries[0]->feeAssigned, 0, ',', '.') }}</label>
                </div>
            </div>
        @endif
        <hr>
        <div class="style-msg alertmsg">
            <div class="sb-msg"><i class="bi-exclamation-diamond-fill"></i><strong>Perhatian!</strong> Nyalakan GPS dan
                paket data agar lokasi lebih akurat!</div>
        </div>
        <div id="map" style="height: 360px" class="m-4"></div>
        <div class="divider divider-rounded divider-center"><i class="bi-camera-fill"></i></div>
        <div class="style-msg infomsg">
            <div class="sb-msg"><i class="bi-exclamation-diamond-fill"></i><strong>Perhatian!</strong> Gambar yang dipilih
                hanya dalam bentuk jpg, jpeg, dan png</div>
        </div>
        <div class="form-group row text-center">
            <label for="proof" class="col-form-label"><b>Bukti Selesai:&nbsp;</b></label>
            <div>
                <div class="btn btn-primary btn-file" tabindex="500" id="buttonInputUI"><i class="bi-folder2-open"></i>
                    <span class="hidden-xs">Pilih Gambar
                        …</span><input name="input10[]" type="file" multiple="" class=""
                        data-show-preview="false" capture="camera" id="proofImage">
                </div>
            </div>
        </div>
        <div class="form-group row m-4">
            <div class="col-12 d-flex justify-content-center overflow-hidden">
                <img src="#" alt="Mohon berikan bukti selesai pengiriman" id="file-preview">
            </div>
        </div>


        @if ($order->payment_method == 'cod')
            <div class="divider divider-rounded divider-center"><i class="bi-pencil"></i></div>
            <div class="style-msg alertmsg">
                <div class="sb-msg"><i class="bi-exclamation-diamond-fill"></i><strong>Perhatian!</strong> Isi bagian
                    "Uang
                    saku digunakan" untuk menyelesaikan pengiriman</div>
            </div>
            <div class="form-group row">
                <label for="orderTotal" class="col-sm-3 col-form-label"><b>Total Pembayaran:&nbsp;</b></label>
                <div class="col-sm-9">
                    <label for="orderTotal" class="col-form-label">Rp. {{ number_format($order->total, 0, ',', '.') }}
                </div>
            </div>
        @endif

        <div class="button-group-footer text-end mt-4">
            <button type="button" class="button button-border button-rounded button-teal button-fill"
                id="backButton"><span>Kembali</span></button>
            <button type="button" class="button button-border button-rounded button-green button-fill me-2"
                id="finishDelivery" disabled><span>Selesaikan</span></button>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('Canvas7/js/components/bs-filestyle.js') }}"></script>
    <script src="{{ asset('js/courier-delivery.js') }}"></script>
@endsection
