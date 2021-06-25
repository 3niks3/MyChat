<nav id="sidebar" class="h-100 navbar d-block  navbar-dark bg-dark">
    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="">
        <a href="{{route('home')}}" class=" navbar-brand d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <i class="fas fa-home me-2"></i>
            Home
        </a>
        <hr>
        <ul class="navbar-nav ps-1 flex-column mb-auto">
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" id="dashboard-links" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Chat Groups
                </a>
                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dashboard-links">
                    <li>
                        <a class="dropdown-item" href="{{route('chat_group_create')}}">  <i class="far fa-plus-square"></i> Create Chat Group </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{route('chat_group_list')}}"> <i class="fas fa-comment-medical"></i> Join Chat Group </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="{{route('profile')}}"> <i class="fas fa-comments"></i> My Chat Groups</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
