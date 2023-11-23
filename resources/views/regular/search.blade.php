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
    <section>
        <div class="container my-5">
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
