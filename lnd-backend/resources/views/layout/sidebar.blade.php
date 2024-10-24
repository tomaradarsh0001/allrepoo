<!-- partial:partials/_sidebar.html -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="nav-profile-image">
                    <img src="{{asset('theme/assets/images/faces-clipart/pic-1.png')}}" alt="profile">
                    <span class="login-status online"></span>
                    <!--change to offline or busy as needed-->
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-2">{{Auth::user()->name}}</span>
                    <span class="text-secondary text-small">{{Auth::user()->role}}</span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
            </a>
        </li>
        <li class="nav-item">
             <!-- SwatiMishra 03-05-2024, 5:50 PM route set start -->
             <a class="nav-link" href="{{route('dashboard')}}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
            <!-- SwatiMishra 03-05-2024, 5:50 PM route set end -->
        </li>

        <!-- <li class="nav-item">
            <a class="nav-link" href="{{route('listComponent')}}">
                <span class="menu-title">Components</span>
                <i class="mdi mdi-checkerboard menu-icon"></i>
            </a>
        </li> -->

        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-menu" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-title">Manage Menu</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-menu menu-icon"></i>
            </a>
            <div class="collapse" id="ui-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{route('createMenu')}}">Add Menu</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{route('listMenu')}}">Menu List</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-component" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-title">Manage Components</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-checkerboard menu-icon"></i>
            </a>
            <div class="collapse" id="ui-component">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{route('createComponent')}}">Add Component</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{route('listComponent')}}">Components List</a></li>
                </ul>
            </div>
        </li>

        <!-- SwatiMishra 30-04-2024, 12:20 PM News Menu Option Start-->
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-news" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-title">Manage News</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-newspaper menu-icon"></i>
            </a>
            <div class="collapse" id="ui-news">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{route('createNews')}}">Add News</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{route('listNews')}}">News List</a></li>
                </ul>
            </div>
        </li>
        <!-- SwatiMishra 30-04-2024, 12:20 PM News Menu Option End-->

        <!-- SwatiMishra 30-04-2024, 12:20 PM Office Document Option Start-->
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-docs" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-title">Manage Office Docs</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-file-document menu-icon"></i>
            </a>
            <div class="collapse" id="ui-docs">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{route('createDocs')}}">Add Office Document</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{route('listDocs')}}">Office Documents List</a></li>
                </ul>
            </div>
        </li>
        <!-- SwatiMishra 30-04-2024, 12:20 PM Office Document Option End-->

        <!-- SwatiMishra 11-05-2024, 12:20 PM Directory Option Start-->
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-directory" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-title">Manage Who's Who</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-account-card-details menu-icon"></i>
            </a>
            <div class="collapse" id="ui-directory">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{route('createDirectory')}}">Add Who's Who</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{route('listDirectory')}}">Who's Who List</a></li>
                </ul>
            </div>
        </li>
        <!-- SwatiMishra 11-05-2024, 12:20 PM Directory Option End-->

        <!-- SwatiMishra 29-05-2024, 10:20 AM ESOCOURT Option Start-->
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-esocourt" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-title">Manage ESO Court</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-gavel menu-icon"></i>
            </a>
            <div class="collapse" id="ui-esocourt">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{route('createCase')}}">Add Case Detail</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{route('listCase')}}">ESO Court List</a></li>
                </ul>
            </div>
        </li>
        <!-- SwatiMishra 29-05-2024, 10:20 AM ESOCOURT Option End-->

        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-users" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-title">Manage Users</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi mdi-account-multiple menu-icon"></i>
            </a>
            <div class="collapse" id="ui-users">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{route('createUsers')}}">Add New User</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{route('userList')}}">User Management List</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-title">Roles and Permissions</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-block-helper  menu-icon"></i>
            </a>
            <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{route('listRole')}}">Roles</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{route('listPermission')}}">Permissions</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{route('updateUserRoleOrPermission')}}">Assignment</a></li>
                </ul>
            </div>
        </li>

    </ul>
</nav>
<!-- partial -->