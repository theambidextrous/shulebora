<nav class="navbar top-navbar navbar-expand-lg navbar-dark">
    <div class="navbar-header logo-bar">
        <!-- This is for the sidebar toggle which is visible on mobile only -->
        <a class="nav-toggler waves-effect waves-light d-block d-lg-none" href="javascript:void(0)">
            @guest
            @else
            <i class="ti-menu ti-close"></i>
            @endguest
            </a>
        <!-- ============================================================== -->
        <!-- Logo -->
        <!-- ============================================================== -->
        <a class="navbar-brand" href="{{url('/')}}">
            <!-- Logo icon -->
            <b class="logo-icon">
                <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                <!-- Dark Logo icon -->
                <img src="{{asset('icons/shulebora-logo-light.png')}}" style="max-width:120px!important;" alt="homepage" class="dark-logo" />
                <!-- Light Logo icon -->
                <img src="{{asset('icons/shulebora-logo-light.png')}}" style="max-width:120px!important;" alt="homepage" class="light-logo" />
            </b>
            <!--End Logo icon -->
            <!-- Logo text -->
            <span class="logo-text">
                <!-- dark Logo text -->
                <!-- <img src="{{asset('icons/shulebora-logo-light.png')}}" alt="homepage" class="dark-logo" /> -->
                <!-- Light Logo text -->
                <!-- <img src="{{asset('icons/shulebora-logo-light.png')}}" class="light-logo" alt="homepage" /> -->
            </span>
        </a>
        <!-- ============================================================== -->
        <!-- End Logo -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Toggle which is visible on mobile only -->
        <!-- ============================================================== -->
        <a class="topbartoggler d-block d-lg-none waves-effect waves-light" href="javascript:void(0)"
            data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i style="font-size:20px;" class="ti-menu ti-close"></i></a>
    </div>
    <!-- ============================================================== -->
    <!-- End Logo -->
    <!-- ============================================================== -->
    <div class="navbar-collapse collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto float-left">
            <!-- ============================================================== -->
            <!-- Search -->
            <!-- ============================================================== -->
        </ul>
        <!-- ============================================================== -->
        <!-- Right side toggle and nav items -->
        <!-- ============================================================== -->
            @guest
            <ul class="navbar-nav guest-menu">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('free_files') }}">Printable Notes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('free_videos') }}">Videos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}"><i class="mdi mdi-lock"></i>Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('student_form') }}"><i class="mdi mdi-account-circle"></i>Register</a>
                </li>
            </ul>
            @else
            <!-- ============================================================== -->
            <ul class="navbar-nav float-right">
            <!-- Authentication Links -->
            <!-- Authentication Links -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <img src="{{asset('icons/avatar.png')}}" alt="user" width="30" class="profile-pic rounded-circle" />
                </a>
                <div class="dropdown-menu mailbox dropdown-menu-right scale-up">
                    <ul class="dropdown-user list-style-none">
                        <li>
                            <div class="dw-user-box p-3 d-flex">
                                <div class="u-img"><img src="{{asset('icons/avatar.png')}}" alt="user" class="rounded" width="80"></div>
                                <div class="u-text ml-2">
                                    <h4 class="mb-0">{{Auth::user()->name}}</h4>
                                    <p class="text-muted mb-1 font-14">{{Auth::user()->email}}</p>
                                    <a href="{{url('/shule/bora/profile')}}" class="btn btn-rounded btn-danger btn-sm text-white d-inline-block">ViewProfile</a>
                                </div>
                            </div>
                        </li>
                        <li role="separator" class="dropdown-divider"></li>
                        <li class="user-list"><a class="px-3 py-2" href="{{url('/shule/bora/profile')}}"><i class="ti-user"></i> My Profile</a></li>
                        <li role="separator" class="dropdown-divider"></li>
                        <li class="user-list"><a class="px-3 py-2" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <i class="fa fa-power-off"></i> Logout</a>
                        </li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </ul>
                </div>
            </li>
            </ul>
            @endguest
    </div>
</nav>