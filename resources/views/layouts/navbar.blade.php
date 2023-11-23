<header>
    <!-- Jumbotron -->
    <div class="p-3 text-center bg-white border-bottom">
        <div class="container">
            <div class="row gy-3">
                <!-- Left elements -->
                <div class="col-lg-2 col-sm-4 col-4">
                    <a href="/" class="float-start">
                        <img src="{{ asset('images/logoshpnw2_ver4.PNG') }}" height="35" />
                    </a>
                </div>
                <!-- Left elements -->

                <!-- Center elements -->
                <div class="order-lg-last col-lg-5 col-sm-8 col-8">
                    <div class="d-flex float-end">
                        @if (Auth::check() && Auth::user()->hasVerifiedEmail())
                            <a href="{{ route('cart.show') }}"
                                class="me-1 border rounded py-1 px-3 nav-link d-flex align-items-center" target="_self">
                                <i class="fas fa-shopping-cart m-1 me-md-2"></i>
                                <p class="d-none d-md-block mb-0">My cart</p>
                            </a>
                            <a href="{{ route('seller.create') }}"
                                class="me-1 border rounded py-1 px-3 nav-link d-flex align-items-center">
                                <i class="fas fa-shopping-bag m-1 me-md-2"></i>
                                <p class="d-none d-md-block mb-0">Seller Hub</p>
                            </a>
                        @endif
                        <a class="dropdown-toggle py-1 px-3 nav-link d-flex align-items-center hidden-arrow"
                            href="#" id="navbarDropdownMenuAvatar" role="button" data-mdb-toggle="dropdown"
                            aria-expanded="false">
                            <img src="{{ Auth::check() && file_exists(public_path('profileimages/' . Auth::user()->profilePicture)) ? asset('profileimages/' . Auth::user()->profilePicture) : 'https://mdbcdn.b-cdn.net/img/new/avatars/2.webp' }}"
                                class="rounded-circle" height="30" width="30"
                                alt="Black and White Portrait of a Man" loading="lazy" style="object-fit: cover;" />
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuAvatar">
                            @if (Auth::check())
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile') }}">My profile</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">Wishlist</a>
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="post">
                                        @csrf
                                        <a class="dropdown-item" id="logout">Logout</a>
                                    </form>
                                </li>
                            @else
                                <li>
                                    <a class="dropdown-item" href="{{ route('login') }}">Login</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <!-- Center elements -->

                <!-- Right elements -->
                <div class="col-lg-5 col-md-12 col-12">
                    <div class="input-group float-center">
                        <div class="form-outline">
                            <input type="search" id="searchInput" name="searchInput" value=""
                                class="form-control" placeholder="Search product's name, category, or brand" />
                        </div>
                        <a href="/search/" id="btnSearch" class="btn btn-dark shadow-0">
                            <i class="fas fa-search"></i>
                        </a>
                    </div>
                </div>
                <!-- Right elements -->
            </div>
        </div>
    </div>
    <!-- Jumbotron -->

    @if (Auth::check() && !Auth::user()->hasVerifiedEmail())
        <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
            <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                <path
                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
            </symbol>
            <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
                <path
                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
            </symbol>
            <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
                <path
                    d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
            </symbol>
        </svg>
        <div class="alert alert-danger d-flex alert-dismissible fade show m-4" role="alert">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:">
                <use xlink:href="#exclamation-triangle-fill" />
            </svg>
            <div>
                Verify your email to access transactional features! <a href="{{ route('verify.logged.email') }}"
                    class="alert-link">click here to verify</a>.
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                id="alertClose"></button>
        </div>
    @endif
</header>
