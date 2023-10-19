@extends('layouts.index')

@section('content')
    <!--  intro  -->
    <div class="container mt-4">
        <main class="card p-3 shadow-2-strong">
            <div class="row">
                <div class="col-lg-3">
                    <nav class="nav flex-column nav-pills mb-md-2">
                        <a class="nav-link active py-2 ps-3 my-0" aria-current="page" href="#">Electronics</a>
                        <a class="nav-link my-0 py-2 ps-3 bg-white" href="#">Clothes and wear</a>
                        <a class="nav-link my-0 py-2 ps-3 bg-white" href="#">Home interiors</a>
                        <a class="nav-link my-0 py-2 ps-3 bg-white" href="#">Computer and tech</a>
                        <a class="nav-link my-0 py-2 ps-3 bg-white" href="#">Tools, equipments</a>
                        <a class="nav-link my-0 py-2 ps-3 bg-white" href="#">Sports and outdoor</a>
                        <a class="nav-link my-0 py-2 ps-3 bg-white" href="#">Animal and pets</a>
                        <a class="nav-link my-0 py-2 ps-3 bg-white" href="#">Machinery tools</a>
                        <a class="nav-link my-0 py-2 ps-3 bg-white" href="#">Other products</a>
                    </nav>
                </div>
                <div class="col-lg-9">
                    <div class="card-banner h-auto p-5 bg-dark bg-gradient rounded-5" style="height: 350px;">
                        <div>
                            <h2 class="text-white">
                                Great products with <br />
                                best deals
                            </h2>
                            <p class="text-white">No matter how far along you are in your sophistication as an amateur
                                astronomer, there is always one.</p>
                            <a href="#" class="btn btn-light shadow-0 text-dark"> View more </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <!-- container end.// -->
@endsection
