<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ShopNow</title>
    <!-- MDB icon -->
    <link rel="icon" type="image/x-icon" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <!-- Google Fonts Roboto -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" />
    <!-- MDB -->
    <link rel="stylesheet" href="{{ asset('mdb5/css/mdb.min.css') }}" />

    <!-- CSS -->
    {{-- <link rel="stylesheet" href="{{ asset('css/style-canvas.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    @yield('css')
    @laravelPWA
</head>

<body class="d-flex flex-column min-vh-100">
    <!-- Header -->
    @include('layouts.navbar')

    <!-- Content -->
    <div class="container my-4">
        @yield('content')
    </div>


    <!-- Footer -->
    <footer class="text-center text-lg-start text-muted mt-auto navbar-fixed-bottom">
        <!-- Section: Links  -->
        <div class="container text-center text-md-start pt-4 pb-4">
            <!-- Grid row -->
            <div class="row mt-3">
                <!-- Grid column -->
                <div class="col-12 col-lg-3 col-sm-12 mb-2">
                    <!-- Content -->
                    <a href="/" target="_blank" class="text-white h2">
                        ShopNow
                    </a>
                    <p class="mt-1 text-white">
                        © 2023 Copyright: MDBootstrap.com & Canvas
                    </p>
                </div>
                <!-- Grid column -->
            </div>
            <!-- Grid row -->
        </div>
        <!-- Section: Links  -->
    </footer>
    <!-- End your project here-->

    <!-- MDB -->
    <script type="text/javascript" src="{{ asset('mdb5/js/mdb.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js"
        integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc" crossorigin="anonymous">
    </script>
    <!-- Custom scripts -->
    <script src="{{ asset('js/index.js') }}"></script>
    @yield('js')
</body>

</html>
