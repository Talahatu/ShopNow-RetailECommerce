<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Halaman Seller</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('corona/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('corona/vendors/css/vendor.bundle.base.css') }}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('corona/vendors/jvectormap/jquery-jvectormap.css') }}">
    <link rel="stylesheet" href="{{ asset('corona/vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('corona/vendors/owl-carousel-2/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('corona/vendors/owl-carousel-2/owl.theme.default.min.css') }}">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('corona/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/seller.css') }}">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{ asset('corona/images/favicon.png') }}" />
    @yield('css')
</head>

<body>
    <div class="bg-dark opacity-75 loader-container d-none justify-content-center align-items-center" id="loader">
        <div class="dot-opacity-loader">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="container-scroller">
        <!-- partial:partials/_sidebar.html -->
        @include('layouts.seller-sidebar')
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_navbar.html -->
            @include('layouts.seller-navbar')
            <!-- partial -->
            <div class="main-panel">
                @yield('content-wrapper')
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright ©
                            bootstrapdash.com 2021</span>
                    </div>
                </footer>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="{{ asset('corona/vendors/js/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{ asset('corona/vendors/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('corona/vendors/progressbar.js/progressbar.min.js') }}"></script>
    <script src="{{ asset('corona/vendors/jvectormap/jquery-jvectormap.min.js') }}"></script>
    <script src="{{ asset('corona/vendors/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ asset('corona/vendors/owl-carousel-2/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('corona/js/jquery.cookie.js') }}" type="text/javascript"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ asset('corona/js/off-canvas.js') }}"></script>
    <script src="{{ asset('corona/js/hoverable-collapse.js') }}"></script>
    {{-- <script src="{{ asset('corona/js/misc.js') }}"></script> --}}
    <script src="{{ asset('corona/js/settings.js') }}"></script>
    <script src="{{ asset('corona/js/todolist.js') }}"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="{{ asset('corona/js/dashboard.js') }}"></script>
    <!-- End custom js for this page -->
    @yield('js')
</body>

</html>
