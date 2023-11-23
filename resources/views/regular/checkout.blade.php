@extends('layouts.index')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
@endsection
@section('content')
    <div class="mt-4">
        <!-- Page Title
                                                                                                                                          ============================================= -->
        <section class="page-title bg-transparent">
            <div class="container">
                <div class="page-title-row">

                    <div class="page-title-content text-light">
                        <h1>Checkout</h1>
                    </div>

                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/" class="text-light">Home</a></li>
                            <li class="breadcrumb-item"><a href="/Cart" class="text-light">Cart</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                        </ol>
                    </nav>

                </div>
            </div>
        </section><!-- .page-title end -->


        <!-- Content
                                                                                                                                      ============================================= -->
        <section id="content">
            <div class="content-wrap card p-4">
                <div class="container">
                    {{-- Address --}}
                    <div class="row col-mb-30 gutter-50 mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Shipping Address</h5>
                                    <div class="card-content d-flex justify-content-between">
                                        <div class="address">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            Voluptas,
                                            molestias.</div>
                                        <a href="#" class="btn btn-dark">Change</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row col-mb-50 gutter-50">
                        <div class="w-100"></div>

                        {{-- Products List --}}
                        <div class="col-lg-6">
                            <h4>Your Orders</h4>
                            <div class="table-responsive">
                                <table class="table cart">
                                    <thead>
                                        <tr>
                                            <th class="cart-product-thumbnail">&nbsp;</th>
                                            <th class="cart-product-name">Product</th>
                                            <th class="cart-product-quantity">Quantity</th>
                                            <th class="cart-product-subtotal">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="cart_item">
                                            <td class="cart-product-thumbnail">
                                                <a href="#"><img width="64" height="64"
                                                        src="{{ asset('images/dress/3.jpg') }}"
                                                        alt="Pink Printed Dress"></a>
                                            </td>

                                            <td class="cart-product-name">
                                                <a href="#">Pink Printed Dress</a>
                                            </td>

                                            <td class="cart-product-quantity">
                                                <div class="quantity">
                                                    1x2
                                                </div>
                                            </td>

                                            <td class="cart-product-subtotal">
                                                <span class="amount">$39.98</span>
                                            </td>
                                        </tr>
                                        <tr class="cart_item">
                                            <td class="cart-product-thumbnail">
                                                <a href="#"><img width="64" height="64"
                                                        src="{{ asset('images/dress/3-1.jpg') }}"
                                                        alt="Checked Canvas Shoes"></a>
                                            </td>

                                            <td class="cart-product-name">
                                                <a href="#">Checked Canvas Shoes</a>
                                            </td>

                                            <td class="cart-product-quantity">
                                                <div class="quantity">
                                                    1x1
                                                </div>
                                            </td>

                                            <td class="cart-product-subtotal">
                                                <span class="amount">$24.99</span>
                                            </td>
                                        </tr>
                                        <tr class="cart_item">
                                            <td class="cart-product-thumbnail">
                                                <a href="#"><img width="64" height="64"
                                                        src="{{ asset('images/dress/3-2.jpg') }}"
                                                        alt="Pink Printed Dress"></a>
                                            </td>

                                            <td class="cart-product-name">
                                                <a href="#">Blue Men Tshirt</a>
                                            </td>

                                            <td class="cart-product-quantity">
                                                <div class="quantity">
                                                    1x3
                                                </div>
                                            </td>

                                            <td class="cart-product-subtotal">
                                                <span class="amount">$41.97</span>
                                            </td>
                                        </tr>
                                    </tbody>

                                </table>
                            </div>
                        </div>

                        {{-- Total & Payment --}}
                        <div class="col-lg-6">
                            <h4>Cart Totals</h4>
                            <div class="table-responsive">
                                <table class="table cart">
                                    <tbody>
                                        <tr class="cart_item">
                                            <td class="border-top-0 cart-product-name">
                                                <strong>Cart Subtotal</strong>
                                            </td>

                                            <td class="border-top-0 cart-product-name">
                                                <span class="amount">$106.94</span>
                                            </td>
                                        </tr>
                                        <tr class="cart_item">
                                            <td class="cart-product-name">
                                                <strong>Shipping</strong>
                                            </td>

                                            <td class="cart-product-name">
                                                <span class="amount">Free Delivery</span>
                                            </td>
                                        </tr>
                                        <tr class="cart_item">
                                            <td class="cart-product-name">
                                                <strong>Total</strong>
                                            </td>

                                            <td class="cart-product-name">
                                                <span class="amount color lead"><strong>$106.94</strong></span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="payment-method btn-group w-100" role="group" aria-label="Payments Button">
                                <button class="btn btn-dark">Saldo ShopNow</button>
                                <button class="btn btn-outline-dark">Cash On Delivery</button>
                            </div>
                            <div class="d-flex justify-content-end my-4">
                                <a href="#" class="btn btn-dark btn-lg btn-block">Checkout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- #content end -->
    </div>
@endsection
@section('js')
    <script src="{{ asset('js/checkout.js') }}"></script>
@endsection
