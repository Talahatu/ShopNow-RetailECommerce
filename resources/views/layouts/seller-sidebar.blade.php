<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
        <a class="sidebar-brand brand-logo" href="{{ route('seller.index') }}"><img
                src="{{ asset('images/logoshpnw2_ver4.png') }}" alt="logo" style="height: 35px;" /></a>
        <a class="sidebar-brand brand-logo-mini" href="{{ route('seller.index') }}"><img
                src="{{ asset('images/logoshpnw2_ver4.png') }}" alt="logo" style="height: 35px;" /></a>

    </div>
    <ul class="nav">
        <li class="nav-item profile">
            <div class="profile-desc">
                <div class="profile-pic">
                    <div class="count-indicator">
                        <img class="img-xs rounded-circle "
                            src="{{ file_exists(public_path('shopimages/' . $shop->logoImage)) ? asset('shopimages/' . $shop->logoImage) : asset('corona/images/faces/face15.jpg') }}"
                            alt="">
                        <span class="count bg-success"></span>
                    </div>
                    <div class="profile-name">
                        <h5 class="mb-0 font-weight-normal">{{ Auth::user()->name }}</h5>
                    </div>
                </div>
                <a href="#" id="profile-dropdown" data-bs-toggle="dropdown"><i
                        class="mdi mdi-dots-vertical"></i></a>
                <div class="dropdown-menu dropdown-menu-right sidebar-dropdown preview-list"
                    aria-labelledby="profile-dropdown">
                    <a href="#" class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-dark rounded-circle">
                                <i class="mdi mdi-settings text-primary"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <p class="preview-subject ellipsis mb-1 text-small">Account settings</p>
                        </div>
                    </a>
                </div>
            </div>
        </li>
        <li class="nav-item nav-category">
            <span class="nav-link">Navigation</span>
        </li>
        <li class="nav-item menu-items" id="navProduct">
            <a class="nav-link" data-bs-toggle="collapse" href="#product-page" aria-expanded="false"
                aria-controls="product-page">
                <span class="menu-icon">
                    <i class="mdi mdi-shopping"></i>
                </span>
                <span class="menu-title">Products</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="product-page">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item" id="myproduct"> <a class="nav-link" href="{{ route('product.index') }}">My
                            Products</a>
                    </li>
                    <li class="nav-item" id="newproduct"> <a class="nav-link" href="{{ route('product.create') }}">Add
                            new
                            Product</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item menu-items" id="navOrder">
            <a class="nav-link" data-bs-toggle="collapse" href="#order-page" aria-expanded="false"
                aria-controls="order-page">
                <span class="menu-icon">
                    <i class="mdi mdi-note-text"></i>
                </span>
                <span class="menu-title">Orders</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="order-page">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item" id="myorder"> <a class="nav-link" href="{{ route('order.index') }}">My
                            Orders</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" data-bs-toggle="collapse" href="#delivery-page" aria-expanded="false"
                aria-controls="delivery-page">
                <span class="menu-icon">
                    <i class="mdi mdi-motorbike"></i>
                </span>
                <span class="menu-title">Delivery</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="delivery-page">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="#">My Delivery</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="#">Add new
                            Courier</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" data-bs-toggle="collapse" href="#financial-page" aria-expanded="false"
                aria-controls="financial-page">
                <span class="menu-icon">
                    <i class="mdi mdi-chart-bar"></i>
                </span>
                <span class="menu-title">Financials</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="financial-page">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="#">My Financials</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" data-bs-toggle="collapse" href="#setting-page" aria-expanded="false"
                aria-controls="setting-page">
                <span class="menu-icon">
                    <i class="mdi mdi-settings"></i>
                </span>
                <span class="menu-title">Settings</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="setting-page">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="#">Shop Setting</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="#">Seller Account Setting</a></li>
                </ul>
            </div>
        </li>
    </ul>
</nav>
