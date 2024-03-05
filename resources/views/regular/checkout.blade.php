@extends('layouts.index')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
@endsection
@section('content')
    <div class="mt-4">
        <!-- Page Title============================================= -->
        <section class="page-title bg-transparent">
            <div class="container">
                <div class="page-title-row">

                    <div class="page-title-content text-light">
                        <h1>Pembayaran</h1>
                    </div>

                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/" class="text-light">Home</a></li>
                            <li class="breadcrumb-item"><a href="/cart" class="text-light">Keranjang</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Pembayaran</li>
                        </ol>
                    </nav>

                </div>
            </div>
        </section><!-- .page-title end -->
        <!-- Content ============================================= -->
        <section id="content">
            <div class="content-wrap card p-4">
                <div class="container">
                    {{-- Address --}}
                    <div class="row col-mb-30 gutter-50 mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Alamat Tujuan</h5>
                                    <div class="card-content d-flex flex-column justify-content-center">
                                        <address class="m-0" id="address-ship">
                                            {{ $address->name }} &nbsp;<button class="btn btn-outline-info btn-sm"
                                                data-bs-toggle="modal" data-bs-target="#exampleModal"
                                                attr-dia="{{ $address->id }}" data-bs-title="Ubah Alamat Tujuan"
                                                id="btnChangeShip">Ubah</button>
                                        </address>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row col-mb-50 gutter-50">
                        <div class="w-100"></div>
                        {{-- Products List --}}
                        <div class="col-lg-7">
                            <h4>Pesanan Anda</h4>
                            <div class="table-responsive">
                                <table class="table cart">
                                    <thead>
                                        <tr>
                                            <th class="cart-product-thumbnail">&nbsp;</th>
                                            <th class="cart-product-name">Nama Barang</th>
                                            <th class="cart-product-price">Harga</th>
                                            <th class="cart-product-quantity">Jumlah Barang</th>
                                            <th class="cart-product-subtotal">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($carts as $item)
                                            <tr class="cart_item">
                                                <td class="cart-product-thumbnail align-middle">
                                                    <a href="/show-product/{{ $item->id }}"><img width="64"
                                                            height="64"
                                                            src="{{ asset('productimages/' . $item->iname) }}"
                                                            alt="Pink Printed Dress"></a>
                                                </td>
                                                <td class="cart-product-name align-middle">
                                                    <a href="/show-product/{{ $item->id }}"
                                                        class="text-dark">{{ $item->pname }}</a>
                                                </td>
                                                <td class="cart-product-price align-middle">
                                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                                </td>
                                                <td class="cart-product-quantity align-middle text-center">
                                                    1x{{ $item->qty }}
                                                </td>
                                                <td class="cart-product-subtotal align-middle">
                                                    <span class="amount">Rp
                                                        {{ number_format($item->price * $item->qty, 0, ',', '.') }}</span>
                                                    <input type="hidden" name="total-row" class="total-row"
                                                        value="{{ $item->price * $item->qty }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Total & Payment --}}
                        <div class="col-lg-5">
                            <h4>Total Pesanan</h4>
                            <div class="table-responsive">
                                <table class="table cart">
                                    <tbody>
                                        <tr>
                                            <td class="border-top-0 cart-product-name">
                                                <strong>Subtotal Barang Keseluruhan</strong>
                                            </td>

                                            <td class="border-top-0 cart-product-name">
                                                <span class="amount" id="subtotal-label">Rp
                                                    {{ number_format($cartTotal, 0, ',', '.') }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="cart-product-name">
                                                <strong>Ongkos Kirim</strong>
                                            </td>
                                            <td class="cart-product-name">
                                                <span class="amount" id="shippingfee-label">Rp
                                                    {{ number_format($shippingFee, 0, ',', '.') }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="cart-product-name">
                                                <strong>Total Pembayaran</strong>
                                            </td>

                                            <td class="cart-product-name">
                                                <span class="amount color lead"><strong id="total-label">Rp
                                                        {{ number_format($total, 0, ',', '.') }}</strong></span>
                                                <input type="hidden" name="total-checkout" id="total-checkout"
                                                    value="{{ $total }}">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <small
                                class="text-danger">{{ Auth::user()->saldo < $total ? 'Saldo not enough...' : '' }}</small>
                            <div class="btn-group payment-method w-100">
                                <input type="radio" class="btn-check btn-method" name="options" id="option1"
                                    autocomplete="off" value="ew"
                                    {{ Auth::user()->saldo < $total ? 'disabled' : 'checked' }} />
                                <label class="btn {{ Auth::user()->saldo < $total ? 'btn-secondary' : 'btn-primary' }}"
                                    for="option1" data-mdb-ripple-init>Saldo ShopNow</label>

                                <input type="radio" class="btn-check btn-method" name="options" id="option2"
                                    autocomplete="off" value="cod"
                                    {{ Auth::user()->saldo < $total ? 'checked' : '' }} />
                                <label class="btn {{ Auth::user()->saldo < $total ? 'btn-primary' : 'btn-secondary' }}"
                                    for="option2" data-mdb-ripple-init>COD
                                    (Pembayaran di
                                    tempat)</label>
                            </div>
                            <div class="d-flex justify-content-end my-4">
                                <button class="btn btn-dark btn-lg btn-block" id="btnCheckout">Bayar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- #content end -->
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
    <script src="{{ asset('js/checkout.js') }}"></script>
@endsection
