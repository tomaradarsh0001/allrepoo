<!-- partial:partials/_navbar.html -->
<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <!-- SwatiMishra 03-05-2024, 5:50 PM logo change start -->
        <!-- <a class="navbar-brand brand-logo" href="{{route('dashboard')}}"><img src="{{asset('theme/assets/images/logoldo.png')}}" alt="logo" style="width: 55%; height: auto;" /></a> -->
        <a class="navbar-brand brand-logo" href="{{route('dashboard')}}">
        <img srcset="{{ asset('theme/assets/images/logoldo.png') }} 1x, {{ asset('theme/assets/images/logoldo@2x.png') }} 2x"
             src="{{ asset('theme/assets/images/logoldo.png') }}"
             alt="Land and Development Office"
             style="width: 98%; height: auto;" />
        </a>
        <a class="navbar-brand brand-logo-mini" href="{{route('dashboard')}}">
        <img src="{{ asset('theme/assets/images/logoldo-mini.jpg') }}"
             alt="logo"
             style="width: 48%; height: auto;" />
        </a>
        <!-- <a class="navbar-brand brand-logo-mini" href="index-2.html"><img src="{{asset('theme/assets/images/logo-mini.svg')}}" alt="logo" /></a> -->
        <!-- SwatiMishra 03-05-2024, 5:50 PM logo change end -->
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-stretch">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
        </button>
        <div class="search-field d-none d-md-block">
            <form class="d-flex align-items-center h-100" action="#">
                <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                        <i class="input-group-text border-0 mdi mdi-magnify"></i>
                    </div>
                    <!-- SwatiMishra 06-05-2024 11:00AM placeholder value change Start  -->
                    <input type="text" class="form-control bg-transparent border-0" placeholder="Search">
                    <!-- SwatiMishra 06-05-2024 11:00AM placeholder value change End  -->
                </div>
            </form>
        </div>
        <ul class="navbar-nav navbar-nav-right">
        <!-- New Settings Dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="settingsDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                <i class="mdi mdi-settings"></i>
            </a>
            <div class="dropdown-menu navbar-dropdown" aria-labelledby="settingsDropdown">
                <a class="dropdown-item" href="{{ route('excelUpload') }}" style="color: black; background-color: white;" onmouseover="this.style.color='#000'; this.style.backgroundColor='#f8f9fa';" onmouseout="this.style.color='black'; this.style.backgroundColor='white';">
                    <i class="mdi mdi-file-excel mr-2 text-success"></i> Import ESO Case Excel
                </a>
            </div>
        </li>
            
        <li class="nav-item nav-profile dropdown">
                <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                    <div class="nav-profile-img">
                        <img src="{{asset('theme/assets/images/faces-clipart/pic-1.png')}}" alt="image">
                        <span class="availability-status online"></span>
                    </div>
                    <div class="nav-profile-text">
                        <p class="mb-1 text-black">{{Auth::user()->name}}</p>
                    </div>
                </a>
                <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                    <!-- Other dropdown items can be added here if needed -->
                    <form method="post" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item" style="background: transparent; border: none; padding: 0; width: 100%; text-align: left; color: inherit; cursor: pointer;">
                            <i class="mdi mdi-logout mr-2 text-primary"></i> Logout
                        </button>
                    </form>
                </div>
            </li>

            <li class="nav-item d-none d-lg-block full-screen-link">
                <a class="nav-link">
                    <i class="mdi mdi-fullscreen" id="fullscreen-button"></i>
                </a>
            </li>

            <!-- SwatiMishra 03-05-2024 11:10 AM removing few navbar unused options Start-->

            <!-- <li class="nav-item dropdown">
                <a class="nav-link count-indicator dropdown-toggle" id="messageDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                    <i class="mdi mdi-email-outline"></i>
                    <span class="count-symbol bg-warning"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="messageDropdown">
                    <h6 class="p-3 mb-0">Messages</h6>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <img src="{{asset('theme/assets/images/faces/face4.jpg')}}" alt="image" class="profile-pic">
                        </div>
                        <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                            <h6 class="preview-subject ellipsis mb-1 font-weight-normal">Mark send you a message</h6>
                            <p class="text-gray mb-0"> 1 Minutes ago </p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <img src="{{asset('theme/assets/images/faces/face2.jpg')}}" alt="image" class="profile-pic">
                        </div>
                        <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                            <h6 class="preview-subject ellipsis mb-1 font-weight-normal">Cregh send you a message</h6>
                            <p class="text-gray mb-0"> 15 Minutes ago </p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <img src="{{asset('theme/assets/images/faces/face3.jpg')}}" alt="image" class="profile-pic">
                        </div>
                        <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                            <h6 class="preview-subject ellipsis mb-1 font-weight-normal">Profile picture updated</h6>
                            <p class="text-gray mb-0"> 18 Minutes ago </p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <h6 class="p-3 mb-0 text-center">4 new messages</h6>
                </div>
            </li> -->

            <!-- <li class="nav-item dropdown">
                <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
                    <i class="mdi mdi-bell-outline"></i>
                    <span class="count-symbol bg-danger"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                    <h6 class="p-3 mb-0">Notifications</h6>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-success">
                                <i class="mdi mdi-calendar"></i>
                            </div>
                        </div>
                        <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                            <h6 class="preview-subject font-weight-normal mb-1">Event today</h6>
                            <p class="text-gray ellipsis mb-0"> Just a reminder that you have an event today </p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-warning">
                                <i class="mdi mdi-settings"></i>
                            </div>
                        </div>
                        <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                            <h6 class="preview-subject font-weight-normal mb-1">Settings</h6>
                            <p class="text-gray ellipsis mb-0"> Update dashboard </p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-info">
                                <i class="mdi mdi-link-variant"></i>
                            </div>
                        </div>
                        <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                            <h6 class="preview-subject font-weight-normal mb-1">Launch Admin</h6>
                            <p class="text-gray ellipsis mb-0"> New admin wow! </p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <h6 class="p-3 mb-0 text-center">See all notifications</h6>
                </div>
            </li> -->
            <!-- <li class="nav-item nav-logout d-none d-lg-block">
                <a class="nav-link" href="#">
                    <i class="mdi mdi-power"></i>
                </a>
            </li>
            <li class="nav-item nav-settings d-none d-lg-block">
                <a class="nav-link" href="#">
                    <i class="mdi mdi-format-line-spacing"></i>
                </a>
            </li> -->

            <!-- SwatiMishra 06-05-2024 11:10 AM removing few navbar unused options End-->

        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>
</nav>
<!-- partial -->