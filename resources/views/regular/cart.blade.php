@extends('layouts.index')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
@endsection
@section('content')
    <div class="mt-4">
        <!-- Page Title
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              ============================================= -->
        <section class="page-title bg-transparent mb-2">
            <div class="container">
                <div class="page-title-row">
                    <div class="page-title-content">
                        <h1 class="text-light">Cart</h1>
                    </div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/" class="text-light">Home</a></li>
                            <li class="breadcrumb-item active text-secondary" aria-current="page">Cart</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </section><!-- .page-title end -->
        <!-- Content============================================= -->
        <section id="content">
            <div class="content-wrap card p-4">
                <div class="container">
                    <div class="row" style="--bs-gutter-x:6rem;">
                        <div class="col-xl-8 py-6 border-end">
                            <table class="table cart mb-5">
                                <thead>
                                    <tr>
                                        <th class="cart-product-check">&nbsp;</th>
                                        <th class="cart-product-remove">&nbsp;</th>
                                        <th class="cart-product-thumbnail">&nbsp;</th>
                                        <th class="cart-product-name">Product</th>
                                        <th class="cart-product-price">Unit Price</th>
                                        <th class="cart-product-quantity">Quantity</th>
                                        <th class="cart-product-subtotal">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr class="cart_item " attr-dia="{{ $item->id }}">
                                            <td class="cart-product-check align-middle">
                                                <input type="checkbox"
                                                    class="text-dark custom-checkbox-control item-selected"
                                                    name="selected[]" value="{{ $item->id }}">
                                            </td>
                                            <td class="cart-product-remove align-middle">
                                                <a href="#" class="btn-remove-cart text-danger"
                                                    title="Remove this item"><i class="fa-solid fa-trash"></i></a>
                                            </td>
                                            <td class="cart-product-thumbnail align-middle">
                                                <a href="/show-product/{{ $item->pid }}"><img width="64"
                                                        height="64" src="{{ asset('productimages/' . $item->iname) }}"
                                                        alt="Pink Printed Dress" style="object-fit: cover"></a>
                                            </td>
                                            <td class="cart-product-name align-middle">
                                                <a href="#">{{ $item->pname }}</a>
                                            </td>
                                            <td class="cart-product-price align-middle">
                                                <span
                                                    class="amount">Rp.{{ number_format($item->price, 0, '.', ',') }}</span>
                                                <input type="hidden" name="price" class="item-price"
                                                    value="{{ $item->price }}">
                                                <input type="hidden" name="distance" class="item-distance"
                                                    value="{{ $item->distance }}">
                                                <input type="hidden" name="weight" class="item-weight"
                                                    value="{{ $item->weight }}">
                                            </td>
                                            <td class="cart-product-quantity align-middle">
                                                <div class="btn-group m-lg-0 mb-3">
                                                    <button class="btn btn-dark btn-sm button-minus" type="button"
                                                        data-mdb-ripple-color="dark">
                                                        <i class="fa-solid fa-minus"></i>
                                                    </button>
                                                    <input type="number" step="1" min="1"
                                                        class="form-control item-qty" value="1" name="quantity"
                                                        aria-label="Example text with button addon"
                                                        aria-describedby="button-addon1" id="qty-input" />
                                                    <button class="btn btn-dark btn-sm button-plus" type="button"
                                                        data-mdb-ripple-color="dark">
                                                        <i class="fa-solid fa-plus"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="cart-product-subtotal align-middle">
                                                <span
                                                    class="amount item-total-label">Rp.{{ number_format($item->price * $item->qty, 0, '.', ',') }}</span>
                                                <input type="hidden" class="item-total" name="item-total"
                                                    value="{{ $item->price }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-xl-4 mt-4 mt-lg-0 py-6">
                            <div class="grid-inner">
                                <div class="row col-mb-30">
                                    <div class="col-12">
                                        <h4>Cart Totals</h4>
                                        <div class="table-responsive">
                                            <table class="table cart cart-totals">
                                                <tbody>
                                                    <tr class="cart_item">
                                                        <td class="cart-product-name">
                                                            <h5 class="mb-0">Cart Subtotal</h5>
                                                        </td>
                                                        <td class="cart-product-name text-end">
                                                            <span class="amount"
                                                                id="cartTotalLabel">Rp.{{ number_format('0', 0, '.', ',') }}</span>
                                                            <input type="hidden" name="cartTotal" id="cartTotal"
                                                                value="0">
                                                        </td>
                                                    </tr>
                                                    <tr class="cart_item">
                                                        <td class="cart-product-name">
                                                            <h5 class="mb-0">Shipping</h5>
                                                        </td>
                                                        <td class="cart-product-name text-end">
                                                            <span class="amount"
                                                                id="shippingLabel">Rp.{{ number_format('0', 0, '.', ',') }}</span>
                                                            <input type="hidden" name="shipping" id="shipping"
                                                                value="0">
                                                        </td>
                                                    </tr>
                                                    <tr class="cart_item">
                                                        <td class="cart-product-name">
                                                            <h5 class="mb-0">Total</h5>
                                                        </td>

                                                        <td class="cart-product-name text-end">
                                                            <span class="amount color lead fw-medium"
                                                                id="totalLabel">Rp.{{ number_format('0', 0, '.', ',') }}</span>
                                                            <input type="hidden" name="total" id="total"
                                                                value="0">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <a href="#" class="btn btn-dark d-block text-center m-0"
                                            id="btn-checkout">Proceed to
                                            Checkout</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- #content end -->
    </div>
@endsection
@section('js')
    <script src="{{ asset('js/cart.js') }}"></script>
@endsection
