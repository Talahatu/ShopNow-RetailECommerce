@extends('layouts.seller')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/chat-seller.css') }}">
@endsection
@section('content-wrapper')
    <div class="container">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card chat-app">
                    <div id="plist" class="people-list card">
                        <ul class="list-unstyled chat-list mt-2 mb-0">
                            @foreach ($allCustomerChats as $item)
                                <li class="clearfix customer-item">
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
                                    {{-- 
                                        <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="avatar">
                                    <div class="chat-about">
                                        <h6 class="m-b-0">Aiden Chavez</h6>
                                    </div> --}}
                                </div>
                            </div>
                        </div>

                        <div class="chat-history">
                            <ul class="m-b-0" id="chat-history-ul">
                                {{-- <li class="clearfix">
                                    <div class="message-data text-right">
                                        <span class="message-data-time text-light">10:10 AM, Today</span>
                                        <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="avatar">
                                    </div>
                                    <div class="message other-message float-right"> Hi Aiden, how are you? How is the
                                        project coming along? </div>
                                </li> --}}
                            </ul>
                        </div>
                        <div class="chat-message clearfix" hidden>
                            <div class="input-group mb-0 d-flex align-items-center justify-content-center">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-light"><i class="mdi mdi-send"></i></span>
                                </div>
                                <input type="text" class="form-control text-light" placeholder="Enter text here...">
                                <img src="{{ file_exists(public_path('shopimages/' . $shop->logoImage)) ? asset('shopimages/' . $shop->logoImage) : 'https://bootdey.com/img/Content/avatar/avatar2.png' }}"
                                    alt="avatar" width="45" height="45" loading="lazy" style="object-fit: cover;"
                                    class="rounded-circle ms-2">
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
