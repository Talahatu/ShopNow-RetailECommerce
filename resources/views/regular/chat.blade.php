@extends('layouts.index')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
@endsection
@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card" id="chat3" style="border-radius: 15px;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-lg-5 col-xl-4 mb-4 mb-md-0" id="chat-accounts">
                                <!-- Toggle button -->
                                <button class="navbar-toggler d-block d-lg-none" type="button" data-mdb-toggle="collapse"
                                    data-mdb-target="#navbarLeftAlignExample" aria-controls="navbarLeftAlignExample"
                                    aria-expanded="true" aria-label="Toggle navigation"><i class="fas fa-bars"></i>
                                    <span class="ms-4">Semua Kontak</span>
                                </button>
                                <div class="collapse navbar-collapse show" id="navbarLeftAlignExample">
                                    <div class="p-3">
                                        <h2 class="title">{{ Auth::user()->name }} Chats:</h2>
                                        <div data-mdb-perfect-scrollbar="true"
                                            style="position: relative; height: 400px;overflow-y: scroll">
                                            <ul class="list-unstyled list-group list-group-flush mb-0">
                                                <li class="p-2 border-bottom"></li>
                                                @foreach ($chats as $item)
                                                    <li class="p-2 border-bottom list-group-item list-group-item-action">
                                                        <input type="hidden" name="dis" class="dis"
                                                            value="{{ $item->shop_id }}">
                                                        <div id="selectChat"
                                                            class="d-flex justify-content-between text-dark btn-select-chat">
                                                            <div class="d-flex flex-row">
                                                                <div>
                                                                    <img src="{{ file_exists(public_path('shopimages/' . $item->shop->logoImage)) ? asset('shopimages/' . $item->shop->logoImage) : 'https://mdbcdn.b-cdn.net/img/new/avatars/2.webp' }}"
                                                                        alt="avatar"
                                                                        class="d-flex align-self-center me-3 rounded-circle seller-images-pp"
                                                                        width="60" height="60" loading="lazy"
                                                                        style="object-fit: cover;">
                                                                    <span class="badge bg-success badge-dot"></span>
                                                                </div>
                                                                <div class="pt-1">
                                                                    <p class="fw-bold mb-0" id="seller-name">
                                                                        {{ $item->shop->name }}</p>
                                                                    <p class="small text-muted">
                                                                        {{ $item->contents->where('sender', '!=', 'customer')->first() ? $item->contents->where('sender', '!=', 'customer')->last()->content : '' }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-7 col-xl-8" hidden id="chat-container">
                                <div class="d-flex justify-content-between text-dark btn-select-chat" id="chat-header">
                                </div>
                                <div class="pt-3 pe-3" data-mdb-perfect-scrollbar="true"
                                    style="position: relative; height: 400px;overflow-y: scroll" id="chat-content">
                                </div>
                                <div class="text-muted d-flex justify-content-start align-items-center pe-3 pt-3 mt-2 ">
                                    <img src="{{ asset('profileimages/' . Auth::user()->profilePicture) }}" alt="avatar 3"
                                        style="object-fit:cover;" width="30" height="30" class="rounded-circle"
                                        id="user-images-pp">
                                    <input type="text" class="form-control form-control-lg" id="exampleFormControlInput2"
                                        placeholder="Ketik pesan...">
                                    <a class="ms-3" href="#!" id="submitChat"><i class="fas fa-paper-plane"></i></a>
                                    <input type="hidden" name="diu" id="diu" value="{{ Auth::user()->id }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('js/chat.js') }}"></script>
@endsection
