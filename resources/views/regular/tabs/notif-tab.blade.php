@extends('regular.profile')

@section('contentProfile')
    <main class="card p-3 shadow-2-strong">
        <h1>{{ Auth::user()->name }}'s Notifications</h1>
        <div class="list-group p-2">
            {{-- Start Here --}}
            <div class="list-group-item list-group-item card" aria-current="true">
                <div class="product-items card-body">
                    <h3>Header</h3>
                    <small class="text-muted">Date</small>
                    <p>Content</p>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('profilejs')
    <script src="{{ asset('js/profile-notif.js') }}"></script>
@endsection
