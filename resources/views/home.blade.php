@extends('layouts.index')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endsection
@section('content')
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-white card">
        <!-- Container wrapper -->
        <div class="container justify-content-center justify-content-md-between">
            <!-- Toggle button -->
            <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarLeftAlignExample"
                aria-controls="navbarLeftAlignExample" aria-expanded="false" aria-label="Toggle navigation"><i
                    class="fas fa-bars"></i>
            </button>
            <!-- Collapsible wrapper -->
            <div class="collapse navbar-collapse" id="navbarLeftAlignExample">
                <!-- Left links -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <!-- Left links -->
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <!-- 8 categories -->
                        @for ($i = 0; $i < 8; $i++)
                            <li class="nav-item">
                                <a class="nav-link"
                                    href="{{ route('show.search', $categories[$i]->name) }}">{{ $categories[$i]->name }}</a>
                            </li>
                        @endfor
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('show.categories') }}">Kategori Lainnya</a>
                        </li>
                    </ul>
                    <!-- Left links -->
                </ul>
                <!-- Left links -->
            </div>
        </div>
        <!-- Container wrapper -->
    </nav>
    <!--  intro  -->
    <div class="container mt-4" id="categoryContainer">
        <main class="card p-3 shadow-2-strong">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card-banner h-auto p-5 bg-gradient rounded-5" style="height: 350px;">
                        <div>
                            <h2 class="text-white">
                                Produk Terbaik <br />
                                Dengan Layanan Terbaik
                            </h2>
                            <p class="text-white">Menyediakan berbagai barang terdekat yang kamu butuhkan. Mari belanja
                                bersama menggunakan ShopNow!</p>
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
                    <h3>Disekitar Anda! </h3>
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
                    <h3 class="section-title">Sebelumnya Dilihat</h3>
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
                                            <p class="text-muted mb-0"><span class="fw-bold">{{ $recent[$i]->stock }}</span>
                                                Tersedias</p>
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
