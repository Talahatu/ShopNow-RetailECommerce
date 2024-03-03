@extends('layouts.courier')
@section('pageTitle')
    <section class="page-title bg-dark">
        <div class="container">
            <div class="page-title-row">
                <div class="page-title-content">
                    <h1 class="text-white">Order List</h1>
                    <span class="text-white">Showcase of order assigned by seller</span>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('content')
    <div class="ongoing-order">
        <h1>On-Going Order</h1>
        <div class="row g-4 mb-5 mt-0 text-center section p-4 rounded">
            <span>No Orders Taken</span>
        </div>
    </div>
    <div class="new-order">
        <h1>New Orders</h1>
        <div class="row g-4 mb-5 mt-0 section p-4 rounded">
            <article class="entry event col-12 mb-4">
                <div class="grid-inner bg-white row g-0 p-3 border-0 rounded-5 shadow-sm h-shadow all-ts h-translate-y-sm">
                    <div class="col-md-8 p-4">
                        <div class="entry-meta no-separator mb-1 mt-0">
                            <ul>
                                <li>
                                    <span class="text-uppercase fw-medium">Fri, Jan 23 @5:30PM</span>
                                </li>
                            </ul>
                        </div>
                        <div class="entry-title nott">
                            <h3><a href="#">#Order ID</a></h3>
                        </div>
                        <div class="entry-meta no-separator">
                            <ul>
                                <li><a href="#" class="fw-normal"><i class="uil uil-map-marker"></i>
                                        Destination Address</a></li>
                            </ul>
                        </div>
                        <div class="entry-content my-3">
                            <button class="button button-border button-rounded button-aqua button-fill"><i
                                    class="fa-solid fa-book"></i><span>Details</span></button>
                            <button class="button button-border button-rounded button-green button-fill"><i
                                    class="fa-solid fa-truck-fast"></i><span>Take Order/Pick Up
                                    Products</span></button>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </div>
@endsection
