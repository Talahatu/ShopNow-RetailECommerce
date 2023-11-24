@extends('layouts.index')

@section('content')
    <input type="hidden" name="query" id="query" value="{{ $query }}">
    <section class="page-title bg-transparent mb-2">
        <div class="container">
            <div class="page-title-row">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/" class=" text-light">Home</a></li>
                        <li class="breadcrumb-item"><a href="#" class=" text-light text-muted">Categories</a>
                        </li>
                    </ol>
                </nav>

            </div>
        </div>
    </section>

    <section class="container row">
        <aside class="col-md-3 col-sm-12 card">
            <div class="flex-shrink-0 p-3 bg-white" style="width: 100%">
                <a href="/" class="d-flex align-items-center pb-3 mb-1 link-dark text-decoration-none border-bottom">
                    <h2>Filter</h2>
                </a>
                <ul class="list-unstyled ps-0">
                    <li class="mb-4">
                        <h5 class="text-dark">
                            Price
                        </h5>
                        <div class="d-flex flex-column gap-1 text-center">
                            <input type="number" name="from" id="price-from" class="form-control" placeholder="Rp. Min">
                            <h5><i class="fa-solid fa-ellipsis-vertical"></i></h5>
                            <input type="number" name="to" id="price-to" class="form-control" placeholder="Rp. Max">
                            <button class="btn btn-dark btn-block" id="btnApplyFilter">Apply</button>
                        </div>
                    </li>
                    <li class="mb-1">
                        <button class="btn btn-link text-dark align-items-center rounded" data-bs-toggle="collapse"
                            data-bs-target="#category-collapse" aria-expanded="true">
                            <i class="fa-solid fa-chevron-down"></i> &nbsp; Category
                        </button>
                        <div class="collapse show" id="category-collapse" style="max-height: 250px; overflow-y:scroll;">
                            <ul class="list-group list-group-flush">
                                @foreach ($categories as $item)
                                    <li class="list-group-item"><input type="checkbox" class="category-check"
                                            name="category-check" id="category-check"
                                            value="{{ $item->id }}">&nbsp;{{ $item->name }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    <li class="border-top my-3"></li>
                    <li class="mb-1">
                        <button class="btn btn-link text-dark align-items-center rounded" data-bs-toggle="collapse"
                            data-bs-target="#brand-collapse" aria-expanded="false">
                            <i class="fa-solid fa-chevron-down"></i> &nbsp; Brand
                        </button>
                        <div class="collapse show" id="brand-collapse" style="max-height: 250px; overflow-y:scroll;">
                            <ul class="list-group list-group-flush">
                                @foreach ($brands as $item)
                                    <li class="list-group-item"><input type="checkbox" class="brand-check"
                                            name="brand-check" id="brand-check"
                                            {{ $item->id }}>&nbsp;{{ $item->name }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </aside>
        <div class="my-4 col-md-9 col-sm-12 ps-md-4">
            <header class="mb-4 text-white">
                <a href="#" class="text-white">
                    <h3>Search Result: </h3>
                </a>
            </header>
            <div class="row" id="products-row"></div>
        </div>
    </section>
@endsection
@section('js')
    <script src="{{ asset('js/search.js') }}"></script>
@endsection
