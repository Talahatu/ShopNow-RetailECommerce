@extends('layouts.index')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endsection
@section('content')
    <!--  intro  -->
    <div class="container mt-4" id="categoryContainer">
        <main class="card p-3 shadow-2-strong">
            <div class="row">
                <div class="col-lg-3 nav-parent mb-2">
                    <nav class="nav flex-column nav-pills mb-md-2 d-none d-lg-block d-md-none">
                        <!-- 8 categories -->
                        @for ($i = 0; $i < 8; $i++)
                            <a class="nav-link my-0 py-2 ps-3" href="#">{{ $categories[$i]->name }}</a>
                        @endfor
                        <a class="nav-link my-0 py-2 ps-3" href="#">Other Categories</a>
                    </nav>
                    <nav class="nav flex-column nav-pills mb-md-2 d-block d-md-none">
                        <div class="container-fluid mb-1">
                            <!-- Toggle button -->
                            <button class="navbar-toggler" type="button" data-mdb-toggle="collapse"
                                data-mdb-target="#navbarSupportedContent2" aria-controls="navbarSupportedContent2"
                                aria-expanded="false" aria-label="Toggle navigation" class="d-flex justify-content-between"
                                style="width: 100%">
                                <strong>Categories</strong>
                                <i class="fas fa-caret-down"></i>
                            </button>
                        </div>
                        <!-- Collapsible wrapper -->
                        <div class="collapse navbar-collapse" id="navbarSupportedContent2">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                @for ($i = 0; $i < count($categories); $i++)
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">{{ $categories[$i]->name }}</a>
                                    </li>
                                @endfor
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Other Categories</a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
                <div class="col-lg-9">
                    <div class="card-banner h-auto p-5 bg-gradient rounded-5" style="height: 350px;">
                        <div>
                            <h2 class="text-white">
                                Great products with <br />
                                best deals
                            </h2>
                            <p class="text-white">Provide the nearest products just for your needs whenever and wherever.
                                Together lets shop happily with ShopNow!</p>
                            {{-- <a href="#" class="btn btn-light shadow-0 text-dark"> View more </a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <!-- container end.// -->

    <a href="/test">Test Page</a>
    <!-- Products -->
    <section>
        <div class="container my-5">
            <header class="mb-4 text-white">
                <a href="#" class="text-white">
                    <h3>New products <i class="fas fa-arrow-right"></i> </h3>
                </a>
            </header>
            <div class="row">
                @for ($i = 0; $i < 8; $i++)
                    <div class="col-lg-3 col-md-6 col-sm-6 mb-4 mb-lg-4">
                        <div class="card">
                            <img src="{{ isset($products[$i]->images[0]->name) ? asset('productimages/' . $products[$i]->images[0]->name) : 'https://mdbootstrap.com/img/Photos/Others/placeholder.jpg' }}"
                                class="card-img-top" alt="Laptop" style="aspect-ratio:1/1; object-fit:cover" />
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <p class="small"><a href="#!"
                                            class="text-muted">{{ $products[$i]->category->name }}</a></p>
                                </div>

                                <div class="d-flex justify-content-between mb-1">
                                    <h5 class="mb-0 d-inline-block text-truncate" style="max-width: 100%;">
                                        {{ $products[$i]->name }}</h5>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <h5 class="text-dark mb-0">Rp. {{ number_format($products[$i]->price, 0, '.', ',') }}
                                    </h5>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <p class="text-muted mb-0"><span class="fw-bold">{{ $products[$i]->stock }}</span>
                                        In Stock</p>
                                    <div class="ms-auto text-warning">
                                        @for ($j = 0; $j < floor($products[$i]->rating); $j++)
                                            <i class="fa fa-star"></i>
                                        @endfor
                                        @if (fmod($products[$i]->rating, 1) != '0.0')
                                            <i class="fa-regular fa-star-half-stroke"></i>
                                        @endif
                                        @for ($j = 0; $j < floor(5 - $products[$i]->rating); $j++)
                                            <i class="far fa-star"></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </section>
    <!-- Products -->

    <!-- Recently viewed -->
    <section class="mt-5 mb-4">
        <div class="container text-light">
            <header class="">
                <h3 class="section-title">Recently viewed</h3>
            </header>

            <div class="row gy-3">
                <div class="col-lg-2 col-md-4 col-4">
                    <a href="#" class="img-wrap">
                        <img height="200" width="200" class="img-thumbnail"
                            src="https://bootstrap-ecommerce.com/bootstrap5-ecommerce/images/items/1.webp" />
                    </a>
                </div>
                <!-- col.// -->
                <div class="col-lg-2 col-md-4 col-4">
                    <a href="#" class="img-wrap">
                        <img height="200" width="200" class="img-thumbnail"
                            src="https://bootstrap-ecommerce.com/bootstrap5-ecommerce/images/items/2.webp" />
                    </a>
                </div>
                <!-- col.// -->
                <div class="col-lg-2 col-md-4 col-4">
                    <a href="#" class="img-wrap">
                        <img height="200" width="200" class="img-thumbnail"
                            src="https://bootstrap-ecommerce.com/bootstrap5-ecommerce/images/items/3.webp" />
                    </a>
                </div>
                <!-- col.// -->
                <div class="col-lg-2 col-md-4 col-4">
                    <a href="#" class="img-wrap">
                        <img height="200" width="200" class="img-thumbnail"
                            src="https://bootstrap-ecommerce.com/bootstrap5-ecommerce/images/items/4.webp" />
                    </a>
                </div>
                <!-- col.// -->
                <div class="col-lg-2 col-md-4 col-4">
                    <a href="#" class="img-wrap">
                        <img height="200" width="200" class="img-thumbnail"
                            src="https://bootstrap-ecommerce.com/bootstrap5-ecommerce/images/items/5.webp" />
                    </a>
                </div>
                <!-- col.// -->
                <div class="col-lg-2 col-md-4 col-4">
                    <a href="#" class="img-wrap">
                        <img height="200" width="200" class="img-thumbnail"
                            src="https://bootstrap-ecommerce.com/bootstrap5-ecommerce/images/items/6.webp" />
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- Recently viewed -->
@endsection
@section('js')
    <script src="{{ asset('js/home.js') }}"></script>
@endsection
