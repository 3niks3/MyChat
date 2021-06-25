<header>
    <div class="row g-0">
        <div class="col">
            <nav class="navbar navbar-expand-lg  sticky-lg-top navbar-dark bg-dark p-2  ps-3 pe-3">
                <div class="container-fluid">

                    <button type="button" id="sidebarCollapse" class="btn btn-outline-light me-4 ">
                        <i class="fas fa-align-left"></i>
                    </button>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">

                        <hr class="d-xs-block d-sm-block d-md-block d-lg-none" style="border: solid thin #cccccc">

                        <div class="float-lg-end">
                            <ul class="navbar-nav mb-2 mb-lg-0">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown123" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <img src="{{auth()->user()->avatar_icon_url}}" class="rounded-circle me-2" alt="profile avatar" width="50" height="50">
                                        {{auth()->user()->username}}
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown123">
                                        <li><a class="dropdown-item" href="{{route('profile-edit')}}">Edit Profile Information</a></li>
                                        <li><a class="dropdown-item" href="{{route('profile-change-password')}}">Change password</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a href="{{route('logout')}}" class="btn btn-outline-success order-lg-2 me-2 ms-2" type="submit">Sign out</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            @stack('menu.submenu')


        </div>
    </div>
</header>
