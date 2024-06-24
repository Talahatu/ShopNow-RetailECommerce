@extends('layouts.index')

@section('content')
    <section class="page-title bg-transparent mb-2">
        <div class="container">
            <div class="page-title-row">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/" class=" text-light">Home</a></li>
                        <li class="breadcrumb-item"><a href="#" class=" text-light text-muted">Toko</a>
                        </li>
                    </ol>
                </nav>

            </div>
        </div>
    </section>

    <section class="container row">
        <div class="card shop-card my-4">
            <div class="card-body align-items-center">
                <div class="shop-img-container w-100 mb-2 mb-lg-0 d-flex align-items-center">
                    <a href="{{ route('shop.show', $shop->id) }}">
                        <img src="{{ file_exists(public_path('shopimages/' . $shop->logoImage)) ? asset('shopimages/' . $shop->logoImage) : 'https://mdbcdn.b-cdn.net/img/new/avatars/2.webp' }}"
                            alt="SellerImage" height="50" width="50" class="rounded-circle" loading="lazy"
                            style="object-fit: cover;">
                    </a>
                    <h1 class="ms-4 mb-0">{{ $shop->name }}</h1>
                    <div class="shop-content ms-4">
                        <div class="btn-group btn-group-shop">
                            <a href="{{ route('chat.show', $shop->id) }}" class="btn btn-dark btn-chat">Chat</a>
                            <button class="btn btn-dark ms-1" id="btnShopMap" data-bs-toggle="modal"
                                data-bs-target="#exampleModalShop" data-dis="{{ $shop->id }}">Peta</button>
                        </div>
                    </div>
                </div>

                <div class="sub-content mb-2 mb-lg-0 w-100 mt-4">
                    <h3 class="ms-4 mb-0 text-muted">{{ $shop->address }}</h3>
                    <h5 class="ms-4 mb-0 text-muted">{{ $shop->phoneNumber }}</h5>
                </div>
            </div>
        </div>
        <div class="my-4 col-md-9 col-sm-12 ps-md-4">
            <header class="mb-4 text-white">
                <a href="#" class="text-white">
                    <h3>Barang pada {{ $shop->name }} </h3>
                </a>
            </header>
            <div class="row" id="products-row">
                @foreach ($shop->products as $item)
                    @if ($item->status == 'live')
                        <div class="col-lg-3 col-md-6 col-sm-6 mb-4 mb-lg-4">
                            <a href="/show-product/${products[index].id}">
                                <div class="card">
                                    <img src="{{ asset('productimages/' . $item->images[0]->name) }}" class="card-img-top"
                                        alt="Laptop" style="aspect-ratio:1/1; object-fit:cover" />
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <p class="small"><a href="#!"
                                                    class="text-muted">{{ $item->category->name }}</a></p>
                                        </div>

                                        <div class="d-flex justify-content-between mb-1">
                                            <h5 class="mb-0 d-inline-block text-truncate" style="max-width: 100%;">
                                                {{ $item->name }}</h5>
                                        </div>
                                        <div class="d-flex justify-content-between mb-3">
                                            <h5 class="text-dark mb-0">
                                                {{ number_format($item->price, 0, ',', '.') }}
                                            </h5>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <p class="text-muted mb-0"><span class="fw-bold">{{ $item->stock }}</span>
                                                Tersedia</p>
                                            <div class="ms-auto text-warning">
                                                @for ($j = 0; $j < floor($item->rating); $j++)
                                                    <i class="fa fa-star"></i>
                                                @endfor
                                                @if (fmod($item->rating, 1) != '0.0')
                                                    <i class="fa-regular fa-star-half-stroke"></i>
                                                @endif
                                                @for ($j = 0; $j < floor(5 - $item->rating); $j++)
                                                    <i class="far fa-star"></i>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

    <div class="modal fade" id="exampleModalShop" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" modal-tags="">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Peta Toko</h5>
                    <button type="button" class="btn-close btnCloseModal" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    <div class="mb-4" id="map" style="height: 300px"></div>
                </div>
                <div class="modal-footer" id="modalFooter">
                    <button type="button" class="btn btn-secondary btnCloseModal" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('js/shop.js') }}"></script>
@endsection
