<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Admin Panel</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item @if($page_name == 'dashboard') active @endif">
        <a class="nav-link" href="{{ secure_url('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Interface
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item @if($page_name == 'users') active @endif">
        <a class="nav-link" href="{{ secure_url('user') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>Users</span></a>
    </li>

    <li class="nav-item @if($page_name == 'recipes') active @endif">
        <a class="nav-link" href="{{ secure_url('recipe') }}">
            <i class="fas fa-fw fa-file-invoice"></i>
            <span>Recipes</span></a>
    </li>

    <li class="nav-item @if($page_name == 'cache') active @endif">
        <a class="nav-link" href="{{ secure_url('cache') }}">
            <i class="fas fa-fw fa-trash"></i>
            <span>Cache</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Account
    </div>

    <li class="nav-item @if($page_name == 'profile') active @endif">
        <a class="nav-link" href="{{ secure_url('profile') }}">
            <i class="fas fa-fw fa-user"></i>
            <span>Profile</span></a>
    </li>

    <li class="nav-item @if($page_name == 'change_password') active @endif">
        <a class="nav-link" href="{{ secure_url('change-password') }}">
            <i class="fas fa-fw fa-user-secret"></i>
            <span>Change Password</span></a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ secure_url('logout') }}">
            <i class="fas fa-fw fa-sign-out-alt"></i>
            <span>Logout</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
