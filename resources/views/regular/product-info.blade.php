@extends('layouts.index')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/product-info.css') }}">
@endsection
@section('content')
    <div class="container mt-4">
        <!-- Page Title============================================= -->
        <section class="page-title bg-transparent mb-2">
            <div class="container">
                <div class="page-title-row">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#" class=" text-light">Home</a></li>
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
        </section><!-- .page-title end -->

        <!-- Content============================================= -->
        <section id="content">
            <div class="content-wrap card p-4">
                <div class="page-title-row mb-2">
                    <div class="page-title-content">
                        <h1>{{ $data->name }}</h1>
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
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next carousel-button" type="button"
                                            data-mdb-target="#carouselMDExample" data-mdb-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
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
                                                {{ number_format($data->price, 0, '.', ',') }}</h2>
                                            <div class="ms-auto text-warning">
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
                                                value="1" name="quantity" aria-label="Example text with button addon"
                                                aria-describedby="button-addon1" />
                                            <button class="btn btn-dark" type="button" id="button-plus"
                                                data-mdb-ripple-color="dark" style="width: 50px">
                                                <i class="fa-solid fa-plus"></i>
                                            </button>
                                        </div>

                                        @if (Auth::check() && Auth::user()->hasVerifiedEmail())
                                            <div class="btn-group m-0" role="group">

                                                <button type="button" class="add-to-cart btn btn-outline-dark">Buy
                                                    Now</button>
                                                <button type="button" class="add-to-cart btn btn-dark">Add to
                                                    cart</button>
                                            </div>
                                        @else
                                            Login to access full feature!
                                        @endif
                                    </div><!-- Product Single - Quantity & Cart Button End -->

                                    <hr class="mb-1 mt-3">

                                    <div class="spesification my-3">
                                        <h2>Spesification: </h2>
                                        <table class="table table-striped table-bordered">
                                            <tbody>
                                                <tr>
                                                    <td>Weight</td>
                                                    <td>{{ $data->weight }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Stock</td>
                                                    <td>{{ $data->stock }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="card shop-card my-4">
                                        <div class="card-body d-lg-flex align-items-center text-center">
                                            <div class="shop-img-container w-100 mb-2 mb-lg-0 d-flex align-items-center">
                                                <a href="#">
                                                    <img src="https://mdbcdn.b-cdn.net/img/new/avatars/2.webp"
                                                        alt="SellerImage" height="50" width="50"
                                                        class="rounded-circle" loading="lazy" style="object-fit: cover;">
                                                </a>
                                                <h4 class="ms-4 mb-0">{{ $data->shop->name }}</h4>
                                            </div>
                                            <div class="shop-content">
                                                <div class="btn-group btn-group-shop">
                                                    <a class="btn btn-outline-dark btn-chat">Chat</a>
                                                    <a class="btn btn-dark btn-visit">Visit</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Product Single - Meta============================================= -->
                                    <div class="card product-meta">
                                        <div class="card-body">
                                            <span itemprop="productID" class="sku_wrapper text-secondary">SKU: <span
                                                    class="sku text-dark">{{ $data->SKU }}</span></span>&nbsp;
                                            <span class="posted_in text-secondary">Category: <a href="#"
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
                                                    <span class="d-inline-block">Description</span>
                                                </button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="canvas-tabs-3-tab" data-bs-toggle="pill"
                                                    data-bs-target="#tabs-3" type="button" role="tab"
                                                    aria-controls="canvas-tabs-3" aria-selected="false">
                                                    <i class="me-1 bi-star-fill"></i>
                                                    <span class="d-inline-block">Reviews (2)</span>
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
                                                style="height: 600px; overflow-y:scroll;">
                                                <div id="reviews" class="p-4">
                                                    <section class="gradient-custom">
                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="row">
                                                                        <div class="col">
                                                                            <div class="d-flex flex-start">
                                                                                <img class="rounded-circle shadow-1-strong me-3"
                                                                                    src="https://mdbcdn.b-cdn.net/img/Photos/Avatars/img%20(10).webp"
                                                                                    alt="avatar" width="65"
                                                                                    height="65" />
                                                                                <div class="flex-grow-1 flex-shrink-1">
                                                                                    <div>
                                                                                        <div
                                                                                            class="d-flex justify-content-between align-items-center">
                                                                                            <p class="mb-1">
                                                                                                Maria Smantha <span
                                                                                                    class="small">-
                                                                                                    2 hours
                                                                                                    ago</span>
                                                                                            </p>
                                                                                            <a href="#!"><i
                                                                                                    class="fas fa-reply fa-xs"></i><span
                                                                                                    class="small">
                                                                                                    reply</span></a>
                                                                                        </div>
                                                                                        <p class="small mb-0">
                                                                                            It is a long established
                                                                                            fact that a reader will
                                                                                            be distracted by
                                                                                            the readable content of
                                                                                            a page.
                                                                                        </p>
                                                                                    </div>

                                                                                    <div class="d-flex flex-start mt-4">
                                                                                        <a class="me-3" href="#">
                                                                                            <img class="rounded-circle shadow-1-strong"
                                                                                                src="https://mdbcdn.b-cdn.net/img/Photos/Avatars/img%20(11).webp"
                                                                                                alt="avatar"
                                                                                                width="65"
                                                                                                height="65" />
                                                                                        </a>
                                                                                        <div
                                                                                            class="flex-grow-1 flex-shrink-1">
                                                                                            <div>
                                                                                                <div
                                                                                                    class="d-flex justify-content-between align-items-center">
                                                                                                    <p class="mb-1">
                                                                                                        Simona Disa
                                                                                                        <span
                                                                                                            class="small">-
                                                                                                            3 hours
                                                                                                            ago</span>
                                                                                                    </p>
                                                                                                </div>
                                                                                                <p class="small mb-0">
                                                                                                    letters, as
                                                                                                    opposed to using
                                                                                                    'Content here,
                                                                                                    content here',
                                                                                                    making it look
                                                                                                    like readable
                                                                                                    English.
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="d-flex flex-start mt-4">
                                                                                        <a class="me-3" href="#">
                                                                                            <img class="rounded-circle shadow-1-strong"
                                                                                                src="https://mdbcdn.b-cdn.net/img/Photos/Avatars/img%20(32).webp"
                                                                                                alt="avatar"
                                                                                                width="65"
                                                                                                height="65" />
                                                                                        </a>
                                                                                        <div
                                                                                            class="flex-grow-1 flex-shrink-1">
                                                                                            <div>
                                                                                                <div
                                                                                                    class="d-flex justify-content-between align-items-center">
                                                                                                    <p class="mb-1">
                                                                                                        John Smith
                                                                                                        <span
                                                                                                            class="small">-
                                                                                                            4 hours
                                                                                                            ago</span>
                                                                                                    </p>
                                                                                                </div>
                                                                                                <p class="small mb-0">
                                                                                                    the majority
                                                                                                    have suffered
                                                                                                    alteration in
                                                                                                    some form, by
                                                                                                    injected humour,
                                                                                                    or randomised
                                                                                                    words.
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="d-flex flex-start mt-4">
                                                                                <img class="rounded-circle shadow-1-strong me-3"
                                                                                    src="https://mdbcdn.b-cdn.net/img/Photos/Avatars/img%20(12).webp"
                                                                                    alt="avatar" width="65"
                                                                                    height="65" />
                                                                                <div class="flex-grow-1 flex-shrink-1">
                                                                                    <div>
                                                                                        <div
                                                                                            class="d-flex justify-content-between align-items-center">
                                                                                            <p class="mb-1">
                                                                                                Natalie Smith <span
                                                                                                    class="small">-
                                                                                                    2 hours
                                                                                                    ago</span>
                                                                                            </p>
                                                                                            <a href="#!"><i
                                                                                                    class="fas fa-reply fa-xs"></i><span
                                                                                                    class="small">
                                                                                                    reply</span></a>
                                                                                        </div>
                                                                                        <p class="small mb-0">
                                                                                            The standard chunk of
                                                                                            Lorem Ipsum used since
                                                                                            the 1500s is
                                                                                            reproduced below for
                                                                                            those interested.
                                                                                            Sections 1.10.32 and
                                                                                            1.10.33.
                                                                                        </p>
                                                                                    </div>

                                                                                    <div class="d-flex flex-start mt-4">
                                                                                        <a class="me-3" href="#">
                                                                                            <img class="rounded-circle shadow-1-strong"
                                                                                                src="https://mdbcdn.b-cdn.net/img/Photos/Avatars/img%20(31).webp"
                                                                                                alt="avatar"
                                                                                                width="65"
                                                                                                height="65" />
                                                                                        </a>
                                                                                        <div
                                                                                            class="flex-grow-1 flex-shrink-1">
                                                                                            <div>
                                                                                                <div
                                                                                                    class="d-flex justify-content-between align-items-center">
                                                                                                    <p class="mb-1">
                                                                                                        Lisa Cudrow
                                                                                                        <span
                                                                                                            class="small">-
                                                                                                            4 hours
                                                                                                            ago</span>
                                                                                                    </p>
                                                                                                </div>
                                                                                                <p class="small mb-0">
                                                                                                    Cras sit amet
                                                                                                    nibh libero, in
                                                                                                    gravida nulla.
                                                                                                    Nulla vel metus
                                                                                                    scelerisque ante
                                                                                                    sollicitudin
                                                                                                    commodo. Cras
                                                                                                    purus odio,
                                                                                                    vestibulum in
                                                                                                    vulputate at,
                                                                                                    tempus viverra
                                                                                                    turpis.
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="d-flex flex-start mt-4">
                                                                                        <a class="me-3" href="#">
                                                                                            <img class="rounded-circle shadow-1-strong"
                                                                                                src="https://mdbcdn.b-cdn.net/img/Photos/Avatars/img%20(29).webp"
                                                                                                alt="avatar"
                                                                                                width="65"
                                                                                                height="65" />
                                                                                        </a>
                                                                                        <div
                                                                                            class="flex-grow-1 flex-shrink-1">
                                                                                            <div>
                                                                                                <div
                                                                                                    class="d-flex justify-content-between align-items-center">
                                                                                                    <p class="mb-1">
                                                                                                        Maggie
                                                                                                        McLoan <span
                                                                                                            class="small">-
                                                                                                            5 hours
                                                                                                            ago</span>
                                                                                                    </p>
                                                                                                </div>
                                                                                                <p class="small mb-0">
                                                                                                    a Latin
                                                                                                    professor at
                                                                                                    Hampden-Sydney
                                                                                                    College in
                                                                                                    Virginia,
                                                                                                    looked up one of
                                                                                                    the more obscure
                                                                                                    Latin words,
                                                                                                    consectetur
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="d-flex flex-start mt-4">
                                                                                        <a class="me-3" href="#">
                                                                                            <img class="rounded-circle shadow-1-strong"
                                                                                                src="https://mdbcdn.b-cdn.net/img/Photos/Avatars/img%20(32).webp"
                                                                                                alt="avatar"
                                                                                                width="65"
                                                                                                height="65" />
                                                                                        </a>
                                                                                        <div
                                                                                            class="flex-grow-1 flex-shrink-1">
                                                                                            <div>
                                                                                                <div
                                                                                                    class="d-flex justify-content-between align-items-center">
                                                                                                    <p class="mb-1">
                                                                                                        John Smith
                                                                                                        <span
                                                                                                            class="small">-
                                                                                                            6 hours
                                                                                                            ago</span>
                                                                                                    </p>
                                                                                                </div>
                                                                                                <p class="small mb-0">
                                                                                                    Autem, totam
                                                                                                    debitis suscipit
                                                                                                    saepe sapiente
                                                                                                    magnam officiis
                                                                                                    quaerat
                                                                                                    necessitatibus
                                                                                                    odio assumenda,
                                                                                                    perferendis quae
                                                                                                    iusto
                                                                                                    labore
                                                                                                    laboriosam
                                                                                                    minima numquam
                                                                                                    impedit quam
                                                                                                    dolorem!
                                                                                                </p>
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
                                                    </section>
                                                    <hr class="my-3">
                                                    <!-- Modal Reviews============================================= -->
                                                    <div class="text-end mt-4">
                                                        <a href="#" data-bs-toggle="modal"
                                                            data-bs-target="#reviewFormModal" class="btn btn-dark m-0">Add
                                                            a Review</a>
                                                    </div>
                                                    <div class="modal fade" id="reviewFormModal" tabindex="-1"
                                                        role="dialog" aria-labelledby="reviewFormModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title" id="reviewFormModalLabel">
                                                                        Submit a
                                                                        Review</h4>
                                                                    <button type="button" class="btn-close btn-sm"
                                                                        data-bs-dismiss="modal"
                                                                        aria-hidden="true"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form class="row mb-0" id="template-reviewform"
                                                                        name="template-reviewform" action="#"
                                                                        method="post">

                                                                        <div class="col-6 mb-3">
                                                                            <label for="template-reviewform-name">Name
                                                                                <small>*</small></label>
                                                                            <div class="input-group">
                                                                                <div class="input-group-text"><i
                                                                                        class="uil uil-user"></i></div>
                                                                                <input type="text"
                                                                                    id="template-reviewform-name"
                                                                                    name="template-reviewform-name"
                                                                                    value=""
                                                                                    class="form-control required">
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-6 mb-3">
                                                                            <label for="template-reviewform-email">Email
                                                                                <small>*</small></label>
                                                                            <div class="input-group">
                                                                                <div class="input-group-text">@</div>
                                                                                <input type="email"
                                                                                    id="template-reviewform-email"
                                                                                    name="template-reviewform-email"
                                                                                    value=""
                                                                                    class="required email form-control">
                                                                            </div>
                                                                        </div>

                                                                        <div class="w-100"></div>

                                                                        <div class="col-12 mb-3">
                                                                            <label
                                                                                for="template-reviewform-rating">Rating</label>
                                                                            <select id="template-reviewform-rating"
                                                                                name="template-reviewform-rating"
                                                                                class="form-select">
                                                                                <option value="">-- Select One --
                                                                                </option>
                                                                                <option value="1">1</option>
                                                                                <option value="2">2</option>
                                                                                <option value="3">3</option>
                                                                                <option value="4">4</option>
                                                                                <option value="5">5</option>
                                                                            </select>
                                                                        </div>

                                                                        <div class="w-100"></div>

                                                                        <div class="col-12 mb-3">
                                                                            <label
                                                                                for="template-reviewform-comment">Comment
                                                                                <small>*</small></label>
                                                                            <textarea class="required form-control" id="template-reviewform-comment" name="template-reviewform-comment"
                                                                                rows="6" cols="30"></textarea>
                                                                        </div>

                                                                        <div class="col-12">
                                                                            <button class="button button-3d m-0"
                                                                                type="submit"
                                                                                id="template-reviewform-submit"
                                                                                name="template-reviewform-submit"
                                                                                value="submit">Submit Review</button>
                                                                        </div>

                                                                    </form>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                </div>
                                                            </div><!-- /.modal-content -->
                                                        </div><!-- /.modal-dialog -->
                                                    </div><!-- /.modal -->
                                                    <!-- Modal Reviews End -->
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
                <h3>Related Product</h3>
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
