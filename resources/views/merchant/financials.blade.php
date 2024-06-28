@extends('layouts.seller')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/financial.css') }}">
@endsection
@section('content-wrapper')
    <?php setlocale(LC_TIME, 'id_ID.utf8', 'Indonesian_indonesia.1252'); ?>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Laporan Keuangan</h3>
        </div>
        <div class="row">
            <div class="col-sm-4 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h5>Saldo Toko</h5>
                        <div class="row">
                            <div class="col-8 col-sm-12 col-xl-8 my-auto">
                                <div class="d-flex d-sm-block d-md-flex align-items-center">
                                    <h2 class="mb-0">Rp {{ number_format($shop->saldo_release, 0, '.', ',') }}</h2>
                                </div>
                                <h6 class="text-muted font-weight-normal">Nominal saldo yang dimiliki toko saat ini</h6>
                            </div>
                            <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                                <i class="icon-lg mdi mdi-wallet text-primary ml-auto"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h5>Pendapatan Bulan Ini</h5>
                        <div class="row">
                            <div class="col-8 col-sm-12 col-xl-8 my-auto">
                                <div class="d-flex d-sm-block d-md-flex align-items-center">
                                    <h2 class="mb-0">Rp {{ number_format($thisMonthRevenue, 0, '.', ',') }}</h2>
                                </div>
                                <h6 class="text-muted font-weight-normal">Nominal pendapatan gabungan antara metode
                                    pembayaran saldo dengan COD selama satu bulan</h6>
                            </div>
                            <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                                <i class="icon-lg mdi mdi-wallet-travel text-danger ml-auto"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h5>Pendapatan Total</h5>
                        <div class="row">
                            <div class="col-8 col-sm-12 col-xl-8 my-auto">
                                <div class="d-flex d-sm-block d-md-flex align-items-center">
                                    <h2 class="mb-0">Rp {{ number_format($allRevenue, 0, '.', ',') }}</h2>
                                </div>
                                <h6 class="text-muted font-weight-normal">Pendapatan sejak pembuatan akun toko</h6>
                            </div>
                            <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                                <i class="icon-lg mdi mdi-home-modern text-success ml-auto"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Selesai Per Bulan</h4>
                        <canvas id="lineChart" style="height: 332px; display: block; box-sizing: border-box; width: 664px;"
                            width="996" height="498"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Produk Terlaris</h4>
                        <canvas id="barChart" style="height: 332px; display: block; box-sizing: border-box; width: 664px;"
                            width="996" height="498"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Riwayat Pemasukan</h4>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th> Tanggal</th>
                                        <th> Nominal </th>
                                        <th> Metode Pembayaran </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($financialHistories as $item)
                                        <tr>
                                            <td> {{ strftime('%A, %d %B %Y', strtotime($item->date)) }} </td>
                                            <td>{{ number_format($item->income, 0, '.', ',') }}</td>
                                            <td class="text-success"> {{ $item->metode }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('js/financial.js') }}"></script>
@endsection
