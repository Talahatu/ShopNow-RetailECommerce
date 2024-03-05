@extends('layouts.index')
@section('content')
    <section>
        <div class="container my-5">
            <header class="mb-4 text-white">
                <a href="#" class="text-white">
                    <h3>Wishlists Anda</h3>
                </a>
            </header>
            <div class="row" id="products-row">

                @for ($i = 0; $i < count($products); $i++)
                    <div class="col-lg-3 col-md-6 col-sm-6 mb-4 mb-lg-4">
                        <a href="{{ route('show.product', $products[$i]->id) }}">
                            <div class="card">
                                <img src="{{ isset($products[$i]->iname) ? asset('productimages/' . $products[$i]->iname) : 'https://mdbootstrap.com/img/Photos/Others/placeholder.jpg' }}"
                                    class="card-img-top" alt="Laptop" style="aspect-ratio:1/1; object-fit:cover" />
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <p class="small"><a href="#!"
                                                class="text-muted">{{ $products[$i]->cname }}</a>
                                        </p>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <h5 class="mb-0 d-inline-block text-truncate" style="max-width: 100%;">
                                            {{ $products[$i]->pname }}</h5>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3">
                                        <h5 class="text-dark mb-0">Rp.
                                            {{ number_format($products[$i]->price, 0, '.', ',') }}
                                        </h5>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <p class="text-muted mb-0"><span class="fw-bold">{{ $products[$i]->stock }}</span>
                                            Tersedia</p>
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
                        </a>
                    </div>
                @endfor
            </div>
        </div>
    </section>
@endsection
@section('js')
@endsection
