@extends('regular.profile')

@section('contentProfile')
    <main class="card p-3 shadow-2-strong">
        <h1>{{ Auth::user()->name }}'s Notifications</h1>
        <div class="list-group p-2">
            {{-- Start Here --}}
            @foreach ($notifs as $item)
                <div class="list-group-item list-group-item card my-2" aria-current="true">
                    <div class="product-items card-body">
                        <h3>{{ $item->header }}</h3>
                        <small class="text-muted">{{ $item->date }}</small>
                        <p>{{ $item->content }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </main>
@endsection

@section('profilejs')
    <script src="{{ asset('js/profile-notif.js') }}"></script>
@endsection
