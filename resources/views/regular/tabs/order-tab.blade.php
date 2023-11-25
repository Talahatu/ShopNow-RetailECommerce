@extends('regular.profile')

@section('contentProfile')
    <main class="card p-3 shadow-2-strong">
        <h1>{{ Auth::user()->name }}'s Orders</h1>
        <!-- Tabs navs -->
        <ul class="nav nav-tabs nav-justified mb-3" id="ex1" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="ex3-tab-1" data-mdb-toggle="tab" href="#ex3-tabs-1" role="tab"
                    aria-controls="ex3-tabs-1" aria-selected="true">Pending</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="ex3-tab-2" data-mdb-toggle="tab" href="#ex3-tabs-2" role="tab"
                    aria-controls="ex3-tabs-2" aria-selected="false">Processed</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="ex3-tab-3" data-mdb-toggle="tab" href="#ex3-tabs-3" role="tab"
                    aria-controls="ex3-tabs-3" aria-selected="false">Sent</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="ex3-tab-3" data-mdb-toggle="tab" href="#ex3-tabs-4" role="tab"
                    aria-controls="ex3-tabs-3" aria-selected="false">Finished</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="ex3-tab-3" data-mdb-toggle="tab" href="#ex3-tabs-5" role="tab"
                    aria-controls="ex3-tabs-3" aria-selected="false">Cancel</a>
            </li>
        </ul>
        <!-- Tabs navs -->

        <!-- Tabs content -->
        <div class="tab-content" id="ex2-content">
            <div class="tab-pane fade show active" id="ex3-tabs-1" role="tabpanel" aria-labelledby="ex3-tab-1">
                <div class="list-group p-2" id="order-container">
                    @foreach ($orders as $item)
                        <div class="list-group-item list-group-item card" aria-current="true">
                            <a href="#" class="shop card-header d-flex align-items-center">
                                <img src="{{ file_exists(public_path('shopimages/' . $item->shop->logoImage)) ? asset('shopimages/' . $item->shop->logoImage) : 'https://mdbcdn.b-cdn.net/img/new/avatars/2.webp' }}"
                                    alt="Image" style="width: 100px; height: 100px; object-fit: cover;"
                                    class="rounded-circle">
                                <h1 class="ms-4">{{ $item->shop->name }} <i class="fa-solid fa-chevron-right"></i></h1>
                            </a>
                            <div class="product-items card-body">
                                <h3>Order Date: {{ $item->order_date }}</h3>
                                @foreach ($item->details as $detail)
                                    <hr class="mb-1 mt-3">
                                    <a href="{{ route('show.product', $detail->product_id) }}"
                                        class="d-flex align-items-center p-2 my-2 text-dark">
                                        <img src="{{ file_exists(public_path('productimages/' . $detail->product->images[0]->name)) ? asset('productimages/' . $detail->product->images[0]->name) : 'https://mdbcdn.b-cdn.net/img/new/avatars/2.webp' }}"
                                            alt="Image" style="width: 100px; height: 100px; object-fit: cover;">
                                        <div class="info-product ms-4">
                                            <h5 class="mb-1">{{ $detail->product->name }}</h5>
                                            <div class="price-info">
                                                <span>Rp {{ number_format($detail->price, 0, ',', '.') }}</span>&nbsp;
                                                <span>Qty: {{ $detail->qty }}</span>
                                                <h4>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</h4>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                            <div class="card-footer text-end">
                                <h4>Total: Rp {{ number_format($item->subtotal, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- Tabs content -->
    </main>
@endsection
@section('profilejs')
    <script src="{{ asset('js/profile-order.js') }}"></script>
@endsection
