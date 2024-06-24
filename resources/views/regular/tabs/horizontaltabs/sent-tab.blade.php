<div class="list-group p-2" id="order-container">
    @if (count($sents) > 0)
        @foreach ($sents as $item)
            @if (count($item->deliveries) > 0)
                <div class="list-group-item list-group-item card" aria-current="true" id="item-{{ $item->id }}">
                    <div class="card-body ps-2 pe-2 text-center text-md-start">
                        <div class="top-side">
                            <span class="text-muted"> {{ strftime('%A, %d %B %Y', strtotime($item->order_date)) }}</span>
                            <h3 class="d-md-flex align-items-center text-center">{{ $item->orderID }}<span
                                    class="d-none d-md-block">&nbsp;</span>
                                @if ($item->deliveries[0]->arrive_date != null)
                                    <span class="badge bg-warning d-none d-md-block">Mohon Selesaikan</span>
                                @else
                                    <span class="badge bg-info d-none d-md-block">Dalam Perjalanan</span>
                                @endif
                            </h3>
                            @if ($item->deliveries[0]->status == 'done')
                                <span class="badge bg-warning d-md-none d-block">Mohon Selesaikan</span>
                            @else
                                <span class="badge bg-info d-md-none d-block">Dalam Perjalanan</span>
                            @endif

                            <p><i class="fa fa-location-dot"></i>&nbsp;&nbsp;{{ $item->destination_address }}</p>
                        </div>
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                            <div
                                class="snippet-info d-flex d-md-block flex-column align-items-center text-center text-md-start">
                                <a href="{{ route('show.product', $item->details[0]->product_id) }}"
                                    class="d-flex align-items-center p-2 my-2 text-dark">
                                    <img src="{{ file_exists(public_path('productimages/' . $item->details[0]->product->images[0]->name)) ? asset('productimages/' . $item->details[0]->product->images[0]->name) : 'https://mdbcdn.b-cdn.net/img/new/avatars/2.webp' }}"
                                        alt="Image" style="width: 100px; height: 100px; object-fit: cover;">
                                    <div class="info-product d-none d-md-block ms-4">
                                        <h5 class="mb-1">{{ $item->details[0]->product->name }}</h5>
                                        <div class="price-info">
                                            {{-- <span>Rp {{ number_format($item->details[0]->price, 0, ',', '.') }}</span>&nbsp; --}}
                                            <span>Qty: {{ $item->details[0]->qty }}</span>
                                            <h4>Rp {{ number_format($item->total, 0, ',', '.') }}</h4>
                                        </div>
                                    </div>
                                </a>
                                <div class="info-product d-block d-md-none">
                                    <h5 class="mb-1">{{ $item->details[0]->product->name }}</h5>
                                    <div class="price-info">
                                        {{-- <span>Rp {{ number_format($item->details[0]->price, 0, ',', '.') }}</span>&nbsp; --}}
                                        <span>Qty: {{ $item->details[0]->qty }}</span>
                                        <h4>Rp {{ number_format($item->total, 0, ',', '.') }}</h4>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-outline btn-dark btn-lg btn-profile-order-detail"
                                data-di="{{ $item->id }}" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">Rincian
                                Pesanan</button>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @else
        <div class="text text-center text-muted">
            <p>Tidak ada pesanan</p>
        </div>
    @endif
</div>
