@extends('layouts.seller')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/chat-seller.css') }}">
@endsection
@section('content-wrapper')
    <div class="container">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card chat-app">
                    <div id="plist" class="people-list card" style="height: 500px;overflow-y:scroll">
                        <ul class="list-unstyled chat-list mt-2 mb-0">
                            @foreach ($allCustomerChats as $item)
                                <li class="clearfix customer-item">
                                    <input type="hidden" name="dic" class="dic" value="{{ $item->user->id }}">
                                    <img src="{{ file_exists(public_path('profileimages/' . $item->user->profilePicture)) ? asset('profileimages/' . $item->user->profilePicture) : 'https://bootdey.com/img/Content/avatar/avatar2.png' }}"
                                        alt="avatar" width="45" height="45" loading="lazy"
                                        style="object-fit: cover;" class="customer-images-pp rounding-circle">
                                    <div class="about">
                                        <div class="name">{{ $item->user->name }}</div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="chat">
                        <div class="chat-header clearfix">
                            <div class="row">
                                <div class="col-lg-6" id="chat-header">
                                </div>
                            </div>
                        </div>

                        <div class="chat-history" id="chat-history" style="height: 400px;overflow-y:scroll">
                            <ul class="m-b-0" id="chat-history-ul">
                            </ul>
                        </div>
                        <div class="chat-message clearfix" hidden>
                            <div class="input-group mb-0 d-flex align-items-center justify-content-center">
                                <input type="hidden" name="dio" id="dio" value="{{ $shop->id }}">
                                <img src="{{ file_exists(public_path('shopimages/' . $shop->logoImage)) ? asset('shopimages/' . $shop->logoImage) : 'https://bootdey.com/img/Content/avatar/avatar2.png' }}"
                                    alt="avatar" width="45" height="45" loading="lazy" style="object-fit: cover;"
                                    class="rounded-circle me-2" id="seller-images-pp">
                                <input type="text" class="form-control text-light" id="chat-content-text"
                                    placeholder="Enter text here...">
                                <a class="input-group-append" id="submitChat" tabindex="0">
                                    <span class="input-group-text text-light"><i class="mdi mdi-send"></i></span>
                                </a>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('js/chat-seller.js') }}"></script>
@endsection
