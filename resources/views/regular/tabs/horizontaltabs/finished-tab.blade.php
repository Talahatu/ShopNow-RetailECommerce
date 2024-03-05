<div class="list-group p-2" id="order-container">
    @if (count($finished) > 0)
        @foreach ($finished as $item)
            <div class="list-group-item list-group-item card" aria-current="true">
                <a href="{{ route('shop.show', $item->shop->id) }}" class="shop card-header d-flex align-items-center">
                    <img src="{{ file_exists(public_path('shopimages/' . $item->shop->logoImage)) ? asset('shopimages/' . $item->shop->logoImage) : 'https://mdbcdn.b-cdn.net/img/new/avatars/2.webp' }}"
                        alt="Image" style="width: 100px; height: 100px; object-fit: cover;" class="rounded-circle">
                    <h1 class="ms-4">{{ $item->shop->name }} <i class="fa-solid fa-chevron-right"></i></h1>
                </a>
                <div class="product-items card-body">
                    <h3>Tanggal Pesanan: {{ $item->order_date }}</h3>
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
    @else
        <div class="text text-center text-muted">
            <p>Tidak ada pesanan</p>
        </div>
    @endif
</div>
