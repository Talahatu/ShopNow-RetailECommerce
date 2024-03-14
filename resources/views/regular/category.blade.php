@extends('layouts.index')

@section('content')
    <section>
        <div class="container my-5">
            <div class="row mt-2 g-4">
                @foreach ($categories as $item)
                    <a class="col-md-3 text-dark" href="{{ route('show.search', $item->name) }}">
                        <div class="card p-1">
                            <div class="d-flex justify-content-between align-items-center p-2">
                                <div class="flex-column lh-1 imagename"><b> {{ $item->name }}</b> </div>
                                <div> <img src="{{ asset('categoryImages/' . $item->id . '.jpg') }}" height="100"
                                        width="100" style="border-radius: 20px;object-fit:cover" />
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endsection
@section('js')
@endsection
