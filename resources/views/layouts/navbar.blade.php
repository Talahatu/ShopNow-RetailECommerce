{{-- <!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <!-- Container wrapper -->
    <div class="container-fluid">
        <!-- Toggle button -->
        <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Collapsible wrapper -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Navbar brand -->
            <a class="navbar-brand mt-2 mt-lg-0" href="#">
                <img src="https://mdbcdn.b-cdn.net/img/logo/mdb-transaprent-noshadows.webp" height="15"
                    alt="MDB Logo" loading="lazy" />
            </a>
            <!-- Left links -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="#">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Team</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Projects</a>
                </li>
            </ul>
            <!-- Left links -->
        </div>
        <!-- Collapsible wrapper -->

        <!-- Right elements -->
        <div class="d-flex align-items-center">
            <!-- Icon -->
            <a class="text-reset me-3" href="#">
                <i class="fas fa-shopping-cart"></i>
            </a>

            <!-- Notifications -->
            <div class="dropdown">
                <a class="text-reset me-3 dropdown-toggle hidden-arrow" href="#" id="navbarDropdownMenuLink"
                    role="button" data-mdb-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell"></i>
                    <span class="badge rounded-pill badge-notification bg-danger">1</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                    <li>
                        <a class="dropdown-item" href="#">Some news</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">Another news</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </li>
                </ul>
            </div>
            <!-- Avatar -->
            <div class="dropdown">
                <a class="dropdown-toggle d-flex align-items-center hidden-arrow" href="#"
                    id="navbarDropdownMenuAvatar" role="button" data-mdb-toggle="dropdown" aria-expanded="false">
                    <img src="https://mdbcdn.b-cdn.net/img/new/avatars/2.webp" class="rounded-circle" height="25"
                        alt="Black and White Portrait of a Man" loading="lazy" />
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuAvatar">
                    <li>
                        <a class="dropdown-item" href="#">My profile</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">Settings</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Right elements -->
    </div>
    <!-- Container wrapper -->
</nav>
<!-- Navbar --> --}}


<header>
    <!-- Jumbotron -->
    <div class="p-3 text-center bg-white border-bottom">
        <div class="container">
            <div class="row gy-3">
                <!-- Left elements -->
                <div class="col-lg-2 col-sm-4 col-4">
                    <a href="https://mdbootstrap.com/" target="_blank" class="float-start">
                        <img src="https://mdbootstrap.com/img/logo/mdb-transaprent-noshadows.png" height="35" />
                    </a>
                </div>
                <!-- Left elements -->

                <!-- Center elements -->
                <div class="order-lg-last col-lg-5 col-sm-8 col-8">
                    <div class="d-flex float-end">
                        <a href="https://github.com/mdbootstrap/bootstrap-material-design"
                            class="me-1 border rounded py-1 px-3 nav-link d-flex align-items-center" target="_blank"> <i
                                class="fas fa-heart m-1 me-md-2"></i>
                            <p class="d-none d-md-block mb-0">Wishlist</p>
                        </a>
                        <a href="https://github.com/mdbootstrap/bootstrap-material-design"
                            class="border rounded py-1 px-3 nav-link d-flex align-items-center" target="_blank"> <i
                                class="fas fa-shopping-cart m-1 me-md-2"></i>
                            <p class="d-none d-md-block mb-0">My cart</p>
                        </a>
                        <a class="dropdown-toggle py-1 px-3 nav-link d-flex align-items-center hidden-arrow"
                            href="#" id="navbarDropdownMenuAvatar" role="button" data-mdb-toggle="dropdown"
                            aria-expanded="false">
                            <img src="https://mdbcdn.b-cdn.net/img/new/avatars/2.webp" class="rounded-circle"
                                height="25" alt="Black and White Portrait of a Man" loading="lazy" />
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuAvatar">
                            <li>
                                <a class="dropdown-item" href="#">My profile</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">Settings</a>
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="post">
                                    @csrf
                                    <a class="dropdown-item" id="logout">Logout</a>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Center elements -->

                <!-- Right elements -->
                <div class="col-lg-5 col-md-12 col-12">
                    <div class="input-group float-center">
                        <div class="form-outline">
                            <input type="search" id="form1" class="form-control" />
                            <label class="form-label" for="form1">Search</label>
                        </div>
                        <button type="button" class="btn btn-dark shadow-0">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <!-- Right elements -->
            </div>
        </div>
    </div>
    <!-- Jumbotron -->

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark bg-gradient text-white">
        <!-- Container wrapper -->
        <div class="container justify-content-center justify-content-md-between">
            <!-- Toggle button -->
            <button class="navbar-toggler" type="button" data-mdb-toggle="collapse"
                data-mdb-target="#navbarLeftAlignExample" aria-controls="navbarLeftAlignExample" aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Collapsible wrapper -->
            <div class="collapse navbar-collapse" id="navbarLeftAlignExample">
                <!-- Left links -->
                {{-- <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Hot offers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Gift boxes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Projects</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Menu item</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Menu name</a>
                    </li>
                </ul> --}}
                <!-- Left links -->
            </div>
        </div>
        <!-- Container wrapper -->
    </nav>
    <!-- Navbar -->
</header>
