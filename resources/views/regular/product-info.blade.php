@extends('layouts.index')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/product-info.css') }}">
@endsection
@section('content')
    <?php setlocale(LC_TIME, 'id_ID.utf8', 'Indonesian_indonesia.1252'); ?>
    <div class="container mt-4">
        <!-- Page Title============================================= -->
        <section class="page-title bg-transparent mb-2">
            <div class="container">
                <div class="page-title-row">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/" class=" text-light">Home</a></li>
                            <li class="breadcrumb-item"><a href="#"
                                    class=" text-light">{{ $data->category->name }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#" class=" text-light">{{ $data->brand->name }}</a>
                            </li>
                            <li class="breadcrumb-item active  text-secondary" aria-current="page">{{ $data->name }}</li>
                        </ol>
                    </nav>

                </div>
            </div>
        </section>
        <!-- .page-title end -->

        {{-- Toast Start --}}
        {{-- <button type="button" class="btn btn-primary" id="liveToastBtn">Show live toast</button> --}}

        <div class="toast position-fixed top-0 end-0 p-3 m-4" id="myToast" data-bs-autohide="false" style="z-index: 9999">
            <div class="toast-header">
                <strong class="me-auto" id="toastHeader"><i class="bi-gift-fill"></i> We miss you!</strong>
                <button type="button" class="btn-close" id="toastClose" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body" id="toastBody">
                It's been a long time since you visited us. We've something special for you. <a href="#">Click
                    here!</a>
            </div>
        </div>

        {{-- Toast End --}}
        <!-- Content============================================= -->
        <section id="content">
            <div class="content-wrap card p-4">
                <div class="page-title-row mb-2">
                    <div class="page-title-content">
                        <h1>{{ $data->name }} <a href="#" class="add-to-wishlist text-danger"
                                attr-dia="{{ $data->id }}"><i
                                    class="{{ $wishlist ? 'fa-solid' : 'fa-regular' }} fa-heart"
                                    id="wishlistStatus"></i></a>
                        </h1>
                    </div>
                </div>
                <hr class="my-3">
                <div class="container">
                    <div class="single-product">
                        <div class="product">
                            <div class="row gutter-40">
                                <div class="col-md-5 mb-sm-4">
                                    <!-- Carousel wrapper -->
                                    <div id="carouselMDExample" class="carousel slide carousel-fade"
                                        data-mdb-ride="carousel">
                                        <!-- Slides -->
                                        <div class="carousel-inner mb-5 shadow-1-strong rounded-3">
                                            @for ($i = 0; $i < count($data->images); $i++)
                                                <div
                                                    class="carousel-item {{ $i == 0 ? 'active' : '' }} responsive-car-image">
                                                    <img src="{{ asset('productimages/' . $data->images[$i]->name) }}"
                                                        class="d-block w-100 product-img"
                                                        style="height: 100%;object-fit:cover;object-position:center"
                                                        alt="img1" />
                                                </div>
                                            @endfor
                                        </div>
                                        <!-- Slides -->

                                        <!-- Controls -->
                                        <button class="carousel-control-prev carousel-button" type="button"
                                            data-mdb-target="#carouselMDExample" data-mdb-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Sebelum</span>
                                        </button>
                                        <button class="carousel-control-next carousel-button" type="button"
                                            data-mdb-target="#carouselMDExample" data-mdb-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Selanjutnya</span>
                                        </button>
                                        <!-- Controls -->

                                        <!-- Thumbnails -->
                                        <div class="carousel-indicators" style="margin-bottom: -20px;">
                                            @for ($i = 0; $i < count($data->images); $i++)
                                                <button type="button" data-mdb-target="#carouselMDExample"
                                                    data-mdb-slide-to="{{ $i }}"
                                                    class="{{ $i == 0 ? 'active' : '' }}" aria-current="true"
                                                    aria-label="Slide {{ $i + 1 }}" style="width: 100px;">
                                                    <img class="d-block w-100 shadow-1-strong rounded"
                                                        src="{{ asset('productimages/' . $data->images[$i]->name) }}"
                                                        class="img-fluid" />
                                                </button>
                                            @endfor
                                        </div>
                                        <!-- Thumbnails -->
                                    </div>
                                </div>

                                <div class="col-md-7 col-lg-7 product-desc">

                                    <div class="d-flex align-items-center justify-content-between">
                                        <!-- Product Single - Price  - Rating ============================================= -->
                                        <div class="product-price mt-4 ms-2 w-100 d-flex justify-content-between">
                                            <h2>Rp.
                                                {{ number_format($data->price, 0, '.', ',') }} <input type="hidden"
                                                    name="price" id="price" value="{{ $data->price }}"></h2>
                                            <div class="ms-auto text-warning">
                                                {{-- @dd($data->rating) --}}
                                                @for ($j = 0; $j < floor($data->rating); $j++)
                                                    <i class="fa fa-star"></i>
                                                @endfor
                                                @if (fmod($data->rating, 1) != '0.0')
                                                    <i class="fa-regular fa-star-half-stroke"></i>
                                                @endif
                                                @for ($j = 0; $j < floor(5 - $data->rating); $j++)
                                                    <i class="far fa-star"></i>
                                                @endfor
                                            </div>
                                        </div>
                                        <!-- Product Single - Price - Rating - End -->
                                    </div>

                                    <hr class="mt-1 mb-3">

                                    <!-- Product Single - Quantity & Cart Button
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             ============================================= -->
                                    <div class="cart mb-0  d-block d-lg-flex justify-content-between align-items-center">
                                        <div class="input-group m-lg-0 mb-3 input-quantity-width">
                                            <button class="btn  btn-dark" type="button" id="button-minus"
                                                data-mdb-ripple-color="dark" style="width: 50px">
                                                <i class="fa-solid fa-minus"></i>
                                            </button>
                                            <input type="number" step="1" min="1" class="form-control"
                                                value="1" name="quantity"
                                                aria-label="Example text with button addon"
                                                aria-describedby="button-addon1" id="qty-input" />
                                            <button class="btn btn-dark" type="button" id="button-plus"
                                                data-mdb-ripple-color="dark" style="width: 50px">
                                                <i class="fa-solid fa-plus"></i>
                                            </button>
                                        </div>

                                        @if (Auth::check() && Auth::user()->hasVerifiedEmail())
                                            <div class="btn-group m-0" role="group">

                                                <button type="button" class="buy-now btn btn-outline-dark"
                                                    attr-dia="{{ $data->id }}">Beli Sekarang</button>
                                                <button type="button" class="add-to-cart btn btn-dark"
                                                    attr-dia="{{ $data->id }}">Tambahkan ke Keranjang</button>
                                            </div>
                                        @else
                                            Masuk ke akun untuk melanjutkan pembelian!
                                        @endif
                                    </div><!-- Product Single - Quantity & Cart Button End -->

                                    <hr class="mb-1 mt-3">

                                    <div class="spesification my-3">
                                        <h2>Rincian Barang: </h2>
                                        <table class="table table-striped table-bordered">
                                            <tbody>
                                                <tr>
                                                    <td>Berat</td>
                                                    <td>{{ $data->weight }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Jumlah Tersedia</td>
                                                    <td>{{ $data->stock }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="card shop-card my-4">
                                        <div class="card-body d-lg-flex align-items-center text-center">
                                            <div class="shop-img-container w-100 mb-2 mb-lg-0 d-flex align-items-center">
                                                <a href="{{ route('shop.show', $data->shop->id) }}">
                                                    <img src="{{ file_exists(public_path('shopimages/' . $data->shop->logoImage)) ? asset('shopimages/' . $data->shop->logoImage) : 'https://mdbcdn.b-cdn.net/img/new/avatars/2.webp' }}"
                                                        alt="SellerImage" height="50" width="50"
                                                        class="rounded-circle" loading="lazy" style="object-fit: cover;">
                                                </a>
                                                <h4 class="ms-4 mb-0">{{ $data->shop->name }}</h4>
                                            </div>
                                            <div class="shop-content">
                                                <div class="btn-group btn-group-shop">
                                                    <a href="{{ route('chat.show', $data->shop->id) }}"
                                                        class="btn btn-outline-dark btn-chat">Chat</a>
                                                    <a href="{{ route('shop.show', $data->shop->id) }}"
                                                        class="btn btn-dark btn-visit">Kunjungi</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Product Single - Meta============================================= -->
                                    <div class="card product-meta">
                                        <div class="card-body">
                                            <span itemprop="productID" class="sku_wrapper text-secondary">SKU: <span
                                                    class="sku text-dark">{{ $data->SKU }}</span></span>&nbsp;
                                            <span class="posted_in text-secondary">Kategori: <a href="#"
                                                    rel="tag"
                                                    class="text-dark">{{ $data->category->name }}</a>.</span>&nbsp;
                                            <span class="tagged_as text-secondary">Tags: <a href="#" rel="tag"
                                                    class="text-dark">{{ $data->brand->name }}</a>
                                        </div>
                                    </div><!-- Product Single - Meta End -->
                                </div>
                                <div class="col-12 p-4">
                                    <div class="mb-0">
                                        <ul class="nav canvas-tabs tabs nav-tabs mb-3" id="tab-1" role="tablist"
                                            style="--bs-nav-link-font-weight: 500;">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="canvas-tabs-1-tab"
                                                    data-bs-toggle="pill" data-bs-target="#tabs-1" type="button"
                                                    role="tab" aria-controls="canvas-tabs-1" aria-selected="true">
                                                    <i class="me-1 bi-justify"></i>
                                                    <span class="d-inline-block">Deskripsi Barang</span>
                                                </button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="canvas-tabs-3-tab" data-bs-toggle="pill"
                                                    data-bs-target="#tabs-3" type="button" role="tab"
                                                    aria-controls="canvas-tabs-3" aria-selected="false">
                                                    <i class="me-1 bi-star-fill"></i>
                                                    <span class="d-inline-block">Ulasan ({{ count($reviews) }})</span>
                                                </button>
                                            </li>
                                        </ul>

                                        <div id="canvas-tab-alt-content" class="tab-content">
                                            <div class="tab-pane fade show active" id="tabs-1" role="tabpanel"
                                                aria-labelledby="canvas-tabs-1-tab" tabindex="0">
                                                <p>{!! nl2br($data->description) !!}</p>
                                            </div>
                                            <div class="tab-pane fade" id="tabs-3" role="tabpanel"
                                                aria-labelledby="canvas-tabs-3-tab" tabindex="0"
                                                style="max-height: 400px; overflow-y:scroll;">
                                                <div id="reviews" class="p-4">
                                                    <section class="gradient-custom">
                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="row">
                                                                        <div class="col">
                                                                            @foreach ($reviews as $item)
                                                                                <div class="d-flex flex-start mt-4 mb-4">
                                                                                    <img class="rounded-circle shadow-1-strong me-3"
                                                                                        src="{{ asset('profileimages/' . $item->user->profilePicture) }}"
                                                                                        alt="avatar" width="65"
                                                                                        height="65"
                                                                                        style="object-fit: cover" />
                                                                                    <div class="flex-grow-1 flex-shrink-1">
                                                                                        <div>
                                                                                            <div
                                                                                                class="d-flex justify-content-between align-items-center">
                                                                                                <p class="mb-1">
                                                                                                    {{ $item->user->name }}&nbsp;
                                                                                                    <span
                                                                                                        class="small text-muted">{{ strftime('%A, %d %B %Y', strtotime($item->created_at)) }}</span>
                                                                                                </p>
                                                                                                <div
                                                                                                    class="ms-auto text-warning">
                                                                                                    @for ($j = 0; $j < floor($item->rating); $j++)
                                                                                                        <i
                                                                                                            class="fa fa-star"></i>
                                                                                                    @endfor
                                                                                                    @for ($j = 0; $j < floor(5 - $item->rating); $j++)
                                                                                                        <i
                                                                                                            class="far fa-star"></i>
                                                                                                    @endfor
                                                                                                </div>
                                                                                            </div>
                                                                                            <p class="small mb-0">
                                                                                                {{ $item->review }}
                                                                                            </p>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </section>
                                                    <hr class="my-3">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- #content end -->


        <header class="my-4 text-white">
            <a href="#" class="text-white">
                <h3>Barang Serupa</h3>
            </a>
        </header>

        <div class="row">
            @for ($i = 0; $i < count($related); $i++)
                <div class="col-lg-3 col-md-6 col-sm-6 mb-4 mb-lg-4">
                    <a href="{{ route('show.product', $related[$i]->id) }}">
                        <div class="card">
                            <img src="{{ isset($related[$i]->images[0]->name) ? asset('productimages/' . $related[$i]->images[0]->name) : 'https://mdbootstrap.com/img/Photos/Others/placeholder.jpg' }}"
                                class="card-img-top" alt="Laptop" style="aspect-ratio:1/1; object-fit:cover" />
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <p class="small"><a href="#!"
                                            class="text-muted">{{ $related[$i]->category->name }}</a></p>
                                </div>

                                <div class="d-flex justify-content-between mb-1">
                                    <h5 class="mb-0 d-inline-block text-truncate" style="max-width: 100%;">
                                        {{ $related[$i]->name }}</h5>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <h5 class="text-dark mb-0">Rp.
                                        {{ number_format($related[$i]->price, 0, '.', ',') }}
                                    </h5>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <p class="text-muted mb-0"><span class="fw-bold">{{ $related[$i]->stock }}</span>
                                        In Stock</p>
                                    <div class="ms-auto text-warning">
                                        @for ($j = 0; $j < floor($related[$i]->rating); $j++)
                                            <i class="fa fa-star"></i>
                                        @endfor
                                        @if (fmod($related[$i]->rating, 1) != '0.0')
                                            <i class="fa-regular fa-star-half-stroke"></i>
                                        @endif
                                        @for ($j = 0; $j < floor(5 - $related[$i]->rating); $j++)
                                            <i class="far fa-star"></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endfor
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('js/product-info.js') }}"></script>
@endsection
