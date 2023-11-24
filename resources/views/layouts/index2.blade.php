<!DOCTYPE html>
<html dir="ltr" lang="en-US">

<head>

    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="author" content="SemiColonWeb">
    <meta name="description"
        content="Get Canvas to build powerful websites easily with the Highly Customizable &amp; Best Selling Bootstrap Template, today.">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Font Imports -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital@0;1&display=swap"
        rel="stylesheet">

    <!-- Core Style -->
    <link rel="stylesheet" href="{{ asset('css/style-canvas.css') }}">

    <!-- Font Icons -->
    <link rel="stylesheet" href="{{ asset('css/font-icons.css') }}">

    <!-- Plugins/Components CSS -->
    <link rel="stylesheet" href="{{ asset('css/swiper.css') }}">

    <!-- Custom CSS -->

    <meta name="viewport" content="width=device-width, initial-scale=1">
    @yield('css')
    <style>
        @media (min-width: 992px) {
            .header-misc-icon>a {
                width: 1.5rem;
                height: 1.5rem;
                font-size: 1.5rem;
                line-height: 1.5rem;
            }
        }

        .header-row .fbox-sm .fbox-icon {
            width: 1.25rem;
            height: 1.25rem;
        }

        .header-row .fbox-sm .fbox-icon i {
            position: relative;
            top: 1px;
            font-size: 1.25rem;
        }

        .header-shop-search input::-moz-placeholder {
            font-style: italic;
            opacity: .7;
        }

        .header-shop-search input::-webkit-input-placeholder {
            font-style: italic;
            opacity: .7;
        }
    </style>

    <!-- Document Title
 ============================================= -->
    <title>Shop | Canvas</title>
    @laravelPWA
</head>

<body class="stretched">

    <!-- Document Wrapper
 ============================================= -->
    <div id="wrapper">

        <!-- Header
  ============================================= -->
        <header id="header" class="bg-light header-size-md">
            <div id="header-wrap" class="border-top border-f5">
                <div class="container">
                    <div class="header-row justify-content-between justify-content-lg-start">
                        <!-- Logo============================================= -->
                        <div id="logo" class="me-0 my-4">
                            <a href="index.html">
                                <img class="logo-default" style="width: 127px; height:auto;"
                                    src="{{ asset('images/logoshpnw2_ver4.png') }}" alt="Canvas Logo">
                                <img class="logo-dark" src="{{ asset('images/logoshpnw2_ver4.png') }}"
                                    alt="Canvas Logo">
                            </a>
                        </div><!-- #logo end -->

                        <div class="header-misc ms-0">

                            <div class="header-misc-icon">
                                <a href="#"><i class="bi-heart"></i></a>
                            </div>

                            <div class="header-misc-icon ms-3">
                                <a href="#"><i class="bi-people"></i></a>
                            </div>

                            <!-- Top Cart============================================= -->
                            <div id="top-cart" class="header-misc-icon d-none d-sm-block ms-3">
                                <a href="#" id="top-cart-trigger"><i class="uil uil-shopping-bag"></i><span
                                        class="top-cart-number">5</span></a>
                                <div class="top-cart-content">
                                    <div class="top-cart-title">
                                        <h4>Shopping Cart</h4>
                                    </div>
                                    <div class="top-cart-items">
                                        {{-- <div class="top-cart-item">
                                            <div class="top-cart-item-image">
                                                <a href="#"><img src="images/shop/small/1.jpg"
                                                        alt="Blue Round-Neck Tshirt"></a>
                                            </div>
                                            <div class="top-cart-item-desc">
                                                <div class="top-cart-item-desc-title">
                                                    <a href="#">Blue Round-Neck Tshirt with a Button</a>
                                                    <span class="top-cart-item-price d-block">$19.99</span>
                                                </div>
                                                <div class="top-cart-item-quantity">x 2</div>
                                            </div>
                                        </div> --}}
                                    </div>
                                    <div class="top-cart-action">
                                        <span class="top-checkout-price">$114.95</span>
                                        <a href="#" class="button button-3d button-small m-0">View Cart</a>
                                    </div>
                                </div>
                            </div><!-- #top-cart end -->

                        </div>

                        <div class="primary-menu-trigger">
                            <button class="cnvs-hamburger" type="button" title="Open Mobile Menu">
                                <span class="cnvs-hamburger-box"><span class="cnvs-hamburger-inner"></span></span>
                            </button>
                        </div>

                        <!-- Primary Navigation============================================= -->
                        <nav class="primary-menu with-arrows ms-lg-5">

                            <ul class="menu-container">
                                <li class="menu-item">
                                    <a class="menu-link p-3" href="index.html">
                                        <div>Men</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link p-3" href="index.html">
                                        <div>Women</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link p-3" href="index.html">
                                        <div>Kids</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link p-3" href="index.html">
                                        <div>Accessories</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link p-3" href="index.html">
                                        <div>Beauty</div>
                                    </a>
                                </li>
                            </ul>

                        </nav><!-- #primary-menu end -->

                        <div class="header-shop-search mx-lg-5 col">
                            <form action="" class="mb-0">
                                <div class="input-group">
                                    <input type="text" class="form-control rounded-pill border-3 px-4"
                                        aria-label="Text input with dropdown button"
                                        style="border-top-right-radius: 0 !important; border-bottom-right-radius: 0 !important;"
                                        placeholder="Search Products..">
                                    <button class="btn btn-dark rounded-pill border-width-3" type="button"
                                        style="border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important; margin-left: -3px;"><i
                                            class="uil uil-search"></i></button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
            <div class="header-wrap-clone"></div>
        </header><!-- #header end -->

        <!-- Content
  ============================================= -->
        <main id="content">
            <div class="content-wrap">
                <div class="container">
                    @yield('content')
                </div>
            </div>
        </main><!-- #content end -->


    </div><!-- #wrapper end -->

    <!-- Go To Top
 ============================================= -->
    <div id="gotoTop" class="uil uil-angle-up"></div>

    <!-- JavaScripts
 ============================================= -->
    <script src="{{ asset('js/plugins.min.js') }}"></script>
    <script src="{{ asset('js/functions.bundle.js') }}"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js"
        integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc" crossorigin="anonymous">
    </script> --}}
    <!-- Custom scripts -->
    <script src="{{ asset('js/index.js') }}"></script>
    @yield('js')
</body>

</html>
