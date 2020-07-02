<nav class="navbar top-navbar navbar-expand-lg navbar-dark">
    <div class="navbar-header logo-bar">
        <!-- This is for the sidebar toggle which is visible on mobile only -->
        <a class="nav-toggler waves-effect waves-light d-block d-lg-none" href="javascript:void(0)"><i
                class="ti-menu ti-close"></i></a>
        <!-- ============================================================== -->
        <!-- Logo -->
        <!-- ============================================================== -->
        <a class="navbar-brand" href="{{url('/shule/bora/profile')}}">
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
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i
                class="ti-more"></i></a>
    </div>
    <!-- ============================================================== -->
    <!-- End Logo -->
    <!-- ============================================================== -->
    <div class="navbar-collapse collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto float-left">
            <!-- ============================================================== -->
            <!-- Search -->
            <!-- ============================================================== -->
            <li class="nav-item d-none d-md-block search-box">
                <a class="nav-link d-none d-md-block waves-effect waves-dark" href="javascript:void(0)">
                    <!-- <i class="ti-search"></i> -->
                </a>
                <!-- <form class="app-search">
                    <input type="text" class="form-control" placeholder="Search & enter"> 
                    <a class="srh-btn"><i class="ti-close"></i></a> 
                </form> -->
            </li>
        </ul>
        <!-- ============================================================== -->
        <!-- Right side toggle and nav items -->
        <!-- ============================================================== -->
        <ul class="navbar-nav float-right">
            <!-- Authentication Links -->
            <!-- Authentication Links -->
            @guest
                <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Ebooks</a></li>
                
                <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Videos</a></li>

                <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">FAQs</a></li>

                <!-- <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">eBooks</a></li> -->
                
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}"><span class="auth-links"><i class="mdi mdi-lock"></i>Login</span></a></li>

                @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">
                            <span class="auth-links"><i class="mdi mdi-account-circle"></i>
                            Register</span>
                        </a>
                    </li>
                @endif
            @else
            <!-- ============================================================== -->
            <!-- Comment -->
            <!-- ============================================================== -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-message"></i>
                    <div class="notify"> <span class="heartbit--"></span> <span class="point"></span> </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right mailbox scale-up">
                    <ul class="list-style-none">
                        <li>
                            <div class="border-bottom rounded-top py-3 px-4">
                                <h5 class="mb-0 font-weight-medium">Notifications</h5>
                            </div>
                        </li>
                        <li>
                            <div class="message-center notifications position-relative" style="height:250px;">
                                <!-- Message -->
                                <a href="javascript:void(0)" class="message-item d-flex align-items-center border-bottom px-3 py-2">
                                    <span class="btn btn-danger rounded-circle btn-circle"><i class="fa fa-link"></i></span>
                                    <div class="w-75 d-inline-block v-middle pl-2">
                                        <h5 class="message-title mb-0 mt-1">Luanch Admin</h5> <span class="font-12 text-nowrap d-block text-muted text-truncate">Just see the my new admin!</span> <span class="font-12 text-nowrap d-block text-muted">9:30 AM</span>
                                    </div>
                                </a>
                            </div>
                        </li>
                        <li>
                            <a class="nav-link border-top text-center text-dark pt-3" href="javascript:void(0);"> <strong>Check all notifications</strong> <i class="fa fa-angle-right"></i> </a>
                        </li>
                    </ul>
                </div>
            </li>
            <!-- ============================================================== -->
            <!-- End Comment -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Messages -->
            <!-- ============================================================== -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" id="2"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i
                        class="mdi mdi-email"></i>
                    <div class="notify"> <span class="heartbit--"></span> <span class="point"></span> </div>
                </a>
                <div class="dropdown-menu mailbox dropdown-menu-right scale-up" aria-labelledby="2">
                    <ul class="list-style-none">
                        <li>
                            <div class="border-bottom rounded-top py-3 px-4">
                                <h5 class="font-weight-medium mb-0">You have 4 new messages</h5>
                            </div>
                        </li>
                        <li>
                            <div class="message-center message-body position-relative" style="height:250px;">
                                <!-- Message -->
                                <a href="javascript:void(0)" class="message-item d-flex align-items-center border-bottom px-3 py-2">
                                    <span class="user-img position-relative d-inline-block"> <img src="{{asset('icons/avatar.png')}}" alt="user" class="rounded-circle w-100"> <span class="profile-status rounded-circle online"></span> </span>
                                    <div class="w-75 d-inline-block v-middle pl-2">
                                        <h5 class="message-title mb-0 mt-1">Sender name</h5> <span class="font-12 text-nowrap d-block text-muted text-truncate">Just see the my admin!</span> <span class="font-12 text-nowrap d-block text-muted">9:30 AM</span>
                                    </div>
                                </a>
                            </div>
                        </li>
                        <li>
                            <a class="nav-link border-top text-center text-dark pt-3" href="javascript:void(0);"> <b>See all e-Mails</b> <i class="fa fa-angle-right"></i> </a>
                        </li>
                    </ul>
                </div>
            </li>
            <!-- ============================================================== -->
            <!-- End Messages -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Profile -->
            <!-- ============================================================== -->
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
                        <li class="user-list"><a class="px-3 py-2" href="{{url('/shule/bora/profile')}}"><i class="ti-wallet"></i> My Balance</a></li>
                        <li class="user-list"><a class="px-3 py-2" href="{{url('/shule/bora/profile')}}"><i class="ti-email"></i> Inbox</a></li>
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
            @endguest
        </ul>
    </div>
</nav>