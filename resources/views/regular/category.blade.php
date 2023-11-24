@extends('layouts.index')

@section('content')
    <section>
        <div class="container my-5">
            <div class="row">
                @foreach ($categories as $item)
                    <div class="col mx-1 my-2 px-2 text-center">
                        <a href="{{ route('show.search', $item->name) }}">
                            <div class="card">
                                <div class="card-body text-dark">
                                    {{ $item->name }}
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
@section('js')
@endsection
