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

    <!-- Products -->
    <section>
        <div class="container my-5">
            <header class="mb-4 text-white">
                <h3>New products</h3>
            </header>

            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card my-2 shadow-0">
                        <a href="#" class="img-wrap">
                            <div class="mask" style="height: 50px;">
                                <div class="d-flex justify-content-start align-items-start h-100 m-2">
                                    <h6><span class="badge bg-success pt-2">Offer</span></h6>
                                </div>
                            </div>
                            <img src="https://bootstrap-ecommerce.com/bootstrap5-ecommerce/images/items/12.webp"
                                class="card-img-top" style="aspect-ratio: 1 / 1">
                        </a>
                        <div class="card-body p-0 pt-3">
                            <a href="#!" class="btn btn-light border px-2 pt-2 float-end icon-hover"><i
                                    class="fas fa-heart fa-lg px-1 text-secondary"></i></a>
                            <h5 class="card-title">$29.95</h5>
                            <p class="card-text mb-0">GoPro action camera 4K</p>
                            <p class="text-muted">
                                Model: X-200
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card my-2 shadow-0">
                        <a href="#" class="img-wrap">
                            <img src="https://bootstrap-ecommerce.com/bootstrap5-ecommerce/images/items/11.webp"
                                class="card-img-top" style="aspect-ratio: 1 / 1">
                        </a>
                        <div class="card-body p-0 pt-2">
                            <a href="#!" class="btn btn-light border px-2 pt-2 float-end icon-hover"><i
                                    class="fas fa-heart fa-lg px-1 text-secondary"></i></a>
                            <h5 class="card-title">$590.00</h5>
                            <p class="card-text mb-0">Canon EOS professional</p>
                            <p class="text-muted">
                                Capacity: 128GB
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card my-2 shadow-0">
                        <a href="#" class="img-wrap">
                            <img src="https://bootstrap-ecommerce.com/bootstrap5-ecommerce/images/items/10.webp"
                                class="card-img-top" style="aspect-ratio: 1 / 1">
                        </a>
                        <div class="card-body p-0 pt-2">
                            <a href="#!" class="btn btn-light border px-2 pt-2 float-end icon-hover"><i
                                    class="fas fa-heart fa-lg px-1 text-secondary"></i></a>
                            <h5 class="card-title">$29.95</h5>
                            <p class="card-text mb-0">Modern product name here</p>
                            <p class="text-muted">
                                Sizes: S, M, XL
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card my-2 shadow-0">
                        <a href="#" class="img-wrap">
                            <img src="https://bootstrap-ecommerce.com/bootstrap5-ecommerce/images/items/9.webp"
                                class="card-img-top" style="aspect-ratio: 1 / 1">
                        </a>
                        <div class="card-body p-0 pt-2">
                            <a href="#!" class="btn btn-light border px-2 pt-2 float-end icon-hover"><i
                                    class="fas fa-heart fa-lg px-1 text-secondary"></i></a>
                            <h5 class="card-title">$1099.00</h5>
                            <p class="card-text mb-0">Apple iPhone 13 Pro max</p>
                            <p class="text-muted">
                                Color: Black, Memory: 128GB
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card my-2 shadow-0">
                        <a href="#" class="img-wrap">
                            <img src="https://bootstrap-ecommerce.com/bootstrap5-ecommerce/images/items/5.webp"
                                class="card-img-top" style="aspect-ratio: 1 / 1">
                        </a>
                        <div class="card-body p-0 pt-2">
                            <a href="#!" class="btn btn-light border px-2 pt-2 float-end icon-hover"><i
                                    class="fas fa-heart fa-lg px-1 text-secondary"></i></a>
                            <h5 class="card-title">$29.95</h5>
                            <p class="card-text mb-0">Modern product name here</p>
                            <p class="text-muted">
                                Sizes: S, M, XL
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card my-2 shadow-0">
                        <a href="#" class="img-wrap">
                            <img src="https://bootstrap-ecommerce.com/bootstrap5-ecommerce/images/items/6.webp"
                                class="card-img-top" style="aspect-ratio: 1 / 1">
                        </a>
                        <div class="card-body p-0 pt-2">
                            <a href="#!" class="btn btn-light border px-2 pt-2 float-end icon-hover"><i
                                    class="fas fa-heart fa-lg px-1 text-secondary"></i></a>
                            <h5 class="card-title">$29.95</h5>
                            <p class="card-text mb-0">Modern product name here</p>
                            <p class="text-muted">
                                Model: X-200
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card my-2 shadow-0">
                        <a href="#" class="img-wrap">
                            <img src="https://bootstrap-ecommerce.com/bootstrap5-ecommerce/images/items/7.webp"
                                class="card-img-top" style="aspect-ratio: 1 / 1">
                        </a>
                        <div class="card-body p-0 pt-2">
                            <a href="#!" class="btn btn-light border px-2 pt-2 float-end icon-hover"><i
                                    class="fas fa-heart fa-lg px-1 text-secondary"></i></a>
                            <h5 class="card-title">$29.95</h5>
                            <p class="card-text mb-0">Modern product name here</p>
                            <p class="text-muted">
                                Sizes: S, M, XL
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card my-2 shadow-0">
                        <a href="#" class="img-wrap">
                            <img src="https://bootstrap-ecommerce.com/bootstrap5-ecommerce/images/items/8.webp"
                                class="card-img-top" style="aspect-ratio: 1 / 1">
                        </a>
                        <div class="card-body p-0 pt-2">
                            <a href="#!" class="btn btn-light border px-2 pt-2 float-end icon-hover"><i
                                    class="fas fa-heart fa-lg px-1 text-secondary"></i></a>
                            <h5 class="card-title">$29.95</h5>
                            <p class="card-text mb-0">Modern product name here</p>
                            <p class="text-muted">
                                Material: Jeans
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Products -->
@endsection
@section('js')
    <script src="{{ asset('js/home.js') }}"></script>
@endsection
