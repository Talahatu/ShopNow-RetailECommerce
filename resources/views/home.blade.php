@extends('layouts.index')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endsection
@section('content')
    <!--  intro  -->
    <div class="container mt-4">
        <main class="card p-3 shadow-2-strong">
            <div class="row">
                <div class="col-lg-3 nav-parent">
                    <nav class="nav flex-column nav-pills mb-md-2">
                        {{-- 8 categories --}}
                        @for ($i = 0; $i < count($categories); $i++)
                            <a class="nav-link my-0 py-2 ps-3" href="#">{{ $categories[$i]->name }}</a>
                        @endfor
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
                                Lets shop happily together with ShopNow!</p>
                            {{-- <a href="#" class="btn btn-light shadow-0 text-dark"> View more </a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <!-- container end.// -->
@endsection
@section('js')
    <script src="{{ asset('js/home.js') }}"></script>
@endsection
