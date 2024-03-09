<!DOCTYPE html>
<html dir="ltr" lang="en-US">

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="SemiColonWeb">
    <meta name="description"
        content="Get Canvas to build powerful websites easily with the Highly Customizable &amp; Best Selling Bootstrap Template, today.">

    <!-- Font Imports -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital@0;1&display=swap"
        rel="stylesheet">

    <!-- Core Style (Contain Bootstrap 5) -->
    <link rel="stylesheet" href="{{ asset('Canvas7/style.css') }}">

    <!-- Font Icons -->
    <link rel="stylesheet" href="{{ asset('Canvas7/css/font-icons.css') }}">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('Canvas7/css/custom.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Document Title
 ============================================= -->
    <title>Halaman Kurir</title>
    @yield('css')
</head>

<body class="stretched">
    <!-- Document Wrapper ============================================= -->
    <div id="wrapper">
        <!-- Header ============================================= -->
        <header id="header" class="full-header">
            <div id="header-wrap">
                <div class="container">
                    <div class="header-row">
                        <!-- Logo ============================================= -->
                        <div id="logo">
                            <a href="{{ route('courier.home') }}">
                                <img class="logo-default" style="height: 35px; width:100%"
                                    src="{{ asset('images/logoshpnw2_ver4.png') }}" alt="Canvas Logo">
                                <img class="logo-dark" style="height: 35px; width:100%"
                                    src="{{ asset('images/logoshpnw2_ver4.png') }}" alt="Canvas Logo">
                            </a>
                        </div><!-- #logo end -->

                        <div class="header-misc">
                            <div class="header-misc-icon d-flex align-items-center">
                                <form action="{{ route('courier.logout') }}" method="post" style="margin: 0px;">
                                    @csrf
                                    <button class="dropdown-item preview-item" type="submit">
                                        <i class="fa-solid fa-right-from-bracket"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="primary-menu-trigger">
                            <button class="cnvs-hamburger" type="button" title="Open Mobile Menu">
                                <span class="cnvs-hamburger-box"><span class="cnvs-hamburger-inner"></span></span>
                            </button>
                        </div>

                        <!-- Primary Navigation
      ============================================= -->
                        <nav class="primary-menu">
                            <ul class="menu-container">
                                <li class="menu-item">
                                    <a class="menu-link" href="{{ route('courier.home') }}">
                                        <div>Home</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="">
                                        <div>Features</div>
                                    </a>
                                </li>
                            </ul>
                        </nav><!-- #primary-menu end -->
                    </div>
                </div>
            </div>
            <div class="header-wrap-clone"></div>
        </header><!-- #header end -->

        <!-- Page Title
  ============================================= -->

        @yield('pageTitle')
        <!-- .page-title end -->

        <!-- Content
  ============================================= -->
        <section id="content">
            <div class="content-wrap">
                <div class="container">
                    @yield('content')
                </div>
            </div>
        </section><!-- #content end -->

        <!-- Footer
  ============================================= -->
        <footer id="footer" class="dark">
            <div class="container">
                <!-- Footer Widgets
    ============================================= -->
                <div class="footer-widgets-wrap">
                    <div class="row col-mb-50">
                        <div class="col-lg-8">
                            <div class="row col-mb-50">
                                <div class="col-md-4">
                                    <div class="widget widget_links">
                                        <a href="/" target="_blank" class="text-white h2">
                                            ShopNow
                                        </a>
                                        <p class="mt-1 text-white">
                                            © 2023 Copyright: MDBootstrap.com & Canvas
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- .footer-widgets-wrap end -->
            </div>
        </footer><!-- #footer end -->
    </div><!-- #wrapper end -->

    <!-- Go To Top
 ============================================= -->
    <div id="gotoTop" class="uil uil-angle-up"></div>

    <!-- JavaScripts
 ============================================= -->
    <script src="{{ asset('Canvas7/js/plugins.min.js') }}"></script>
    <script src="{{ asset('Canvas7/js/functions.bundle.js') }}"></script>
    @yield('js')
</body>

</html>
