<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
        <a class="sidebar-brand brand-logo" href="{{ route('seller.index') }}"><img
                src="{{ asset('images/logoshpnw2_ver4.png') }}" alt="logo" style="height: 35px;width:124px" /></a>
    </div>
    <ul class="nav">
        <li class="nav-item profile">
            <div class="profile-desc">
                <div class="profile-pic">
                    <div class="count-indicator">
                        <img class="img-xs rounded-circle " style="object-fit:cover;"
                            src="{{ file_exists(public_path('shopimages/' . $shop->logoImage)) ? asset('corona/images/faces/face15.jpg') : asset('corona/images/faces/face15.jpg') }}">
                        <span class="count bg-success"></span>
                    </div>
                    <div class="profile-name">
                        <h5 class="mb-0 font-weight-normal">{{ $shop->name }}</h5>
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
                            <p class="preview-subject ellipsis mb-1 text-small">Pengaturan</p>
                        </div>
                    </a>
                </div>
            </div>
        </li>
        <li class="nav-item nav-category">
            <span class="nav-link">Navigasi</span>
        </li>
        <li class="nav-item menu-items" id="navProduct">
            <a class="nav-link" data-bs-toggle="collapse" href="#product-page" aria-expanded="false"
                aria-controls="product-page">
                <span class="menu-icon">
                    <i class="mdi mdi-shopping"></i>
                </span>
                <span class="menu-title">Produk</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="product-page">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item" id="myproduct"> <a class="nav-link" href="{{ route('product.index') }}">Daftar
                            Produk</a>
                    </li>
                    <li class="nav-item" id="newproduct"> <a class="nav-link" href="{{ route('product.create') }}">Buat
                            Produk Baru</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item menu-items" id="navOrder">
            <a class="nav-link" data-bs-toggle="collapse" href="#order-page" aria-expanded="false"
                aria-controls="order-page">
                <span class="menu-icon">
                    <i class="mdi mdi-note-text"></i>
                </span>
                <span class="menu-title">Pesanan</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="order-page">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item" id="myorder"> <a class="nav-link" href="{{ route('order.index') }}">Daftar
                            Pesanan</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item menu-items" id="navCourier">
            <a class="nav-link" data-bs-toggle="collapse" href="#delivery-page" aria-expanded="false"
                aria-controls="delivery-page">
                <span class="menu-icon">
                    <i class="mdi mdi-motorbike"></i>
                </span>
                <span class="menu-title">Kurir</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="delivery-page">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item" id="mycourier"> <a class="nav-link" href="{{ route('courier.index') }}">Daftar
                            Kurir</a>
                    </li>
                    <li class="nav-item" id="newCourier"> <a class="nav-link"
                            href="{{ route('courier.create') }}">Tambahkan Kurir Baru</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item menu-items" id="navReport">
            <a class="nav-link" data-bs-toggle="collapse" href="#financial-page" aria-expanded="false"
                aria-controls="financial-page">
                <span class="menu-icon">
                    <i class="mdi mdi-chart-bar"></i>
                </span>
                <span class="menu-title">Keuangan</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="financial-page">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item" id="myReport"> <a class="nav-link"
                            href="{{ route('seller.financial') }}">Laporan
                            Keuangan</a>
                    </li>
                </ul>
            </div>
        </li>
        {{-- <li class="nav-item menu-items">
            <a class="nav-link" data-bs-toggle="collapse" href="#setting-page" aria-expanded="false"
                aria-controls="setting-page">
                <span class="menu-icon">
                    <i class="mdi mdi-settings"></i>
                </span>
                <span class="menu-title">Pengaturan</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="setting-page">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="#">Pengaturan Toko</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="#">Pengaturan Akun</a></li>
                </ul>
            </div>
        </li> --}}
    </ul>
</nav>
