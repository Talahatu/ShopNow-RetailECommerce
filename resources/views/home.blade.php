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
                            <a class="nav-link my-0 py-2 ps-3"
                                href="{{ route('show.search', $categories[$i]->name) }}">{{ $categories[$i]->name }}</a>
                        @endfor
                        <a class="nav-link my-0 py-2 ps-3" href="{{ route('show.categories') }}">Other Categories</a>
                    </nav>
                    <nav class="nav flex-column nav-pills mb-md-2 d-block d-lg-none">
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
                                        <a class="nav-link"
                                            href="{{ route('show.search', $categories[$i]->name) }}">{{ $categories[$i]->name }}</a>
                                    </li>
                                @endfor
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('show.categories') }}">Other Categories</a>
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
    <!-- Products -->
    <section>
        <div class="container my-5">
            <header class="mb-4 text-white">
                <a href="#" class="text-white">
                    <h3>Closest To You! </h3>
                </a>
            </header>
            <div class="row" id="products-row"></div>
        </div>
    </section>
    <!-- Products -->

    <!-- Recently viewed -->
    @if (isset($recent))
        <section class="mt-5 mb-4">
            <div class="container text-light">
                <header class="">
                    <h3 class="section-title">Recently viewed</h3>
                </header>
                <div class="row gy-3">
                    @for ($i = 0; $i < min(count($recent), 4); $i++)
                        <div class="col-lg-3 col-md-6 col-sm-6 mb-4 mb-lg-4">
                            <a href="{{ route('show.product', $recent[$i]->id) }}">
                                <div class="card">
                                    <img src="{{ isset($recent[$i]->images[0]->name) ? asset('productimages/' . $recent[$i]->images[0]->name) : 'https://mdbootstrap.com/img/Photos/Others/placeholder.jpg' }}"
                                        class="card-img-top" alt="Laptop" style="aspect-ratio:1/1; object-fit:cover" />
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <p class="small"><a href="#!"
                                                    class="text-muted">{{ $recent[$i]->category->name }}</a></p>
                                        </div>

                                        <div class="d-flex justify-content-between mb-1">
                                            <h5 class="mb-0 d-inline-block text-truncate text-dark"
                                                style="max-width: 100%;">
                                                {{ $recent[$i]->name }}</h5>
                                        </div>
                                        <div class="d-flex justify-content-between mb-3">
                                            <h5 class="text-dark mb-0">Rp.
                                                {{ number_format($recent[$i]->price, 0, '.', ',') }}
                                            </h5>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <p class="text-muted mb-0"><span
                                                    class="fw-bold">{{ $recent[$i]->stock }}</span>
                                                In Stock</p>
                                            <div class="ms-auto text-warning">
                                                @for ($j = 0; $j < floor($recent[$i]->rating); $j++)
                                                    <i class="fa fa-star"></i>
                                                @endfor
                                                @if (fmod($recent[$i]->rating, 1) != '0.0')
                                                    <i class="fa-regular fa-star-half-stroke"></i>
                                                @endif
                                                @for ($j = 0; $j < floor(5 - $recent[$i]->rating); $j++)
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
        </section>
    @endif
    <!-- Recently viewed -->
@endsection
@section('js')
    <script src="{{ asset('js/home.js') }}"></script>
@endsection
