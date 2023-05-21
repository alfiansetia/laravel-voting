<nav class="navbar navbar-expand-lg main-navbar">
    <a href="{{ route('home') }}" class="navbar-brand sidebar-gone-hide">{{ $comp->name }}</a>
    <div class="nav-collapse">
        <a class="sidebar-gone-show nav-collapse-toggle nav-link" href="#">
            <i class="fas fa-ellipsis-v"></i>
        </a>
        <ul class="navbar-nav">
            <li class="nav-item active"><a href="{{ route('home') }}" class="nav-link">Home</a></li>
            <li class="nav-item"><a href="{{ route('onauth.index') }}" class="nav-link">Event</a></li>
        </ul>
    </div>
    @if($title != 'Statistic')
    <div class="navbar-nav">
        <a href="#" class="nav-link sidebar-gone-show" data-toggle="sidebar"><i class="fas fa-bars"></i></a>
    </div>
    <ul class="navbar-nav navbar-right ml-auto">
        <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image" src="{{ asset('assets/img/avatar/avatar-1.png') }}" class="rounded-circle mr-1">
                <div class="d-sm-none d-lg-inline-block">Hi, {{ auth()->user()->name }}</div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-title">Logged in {{ \Carbon\Carbon::parse(auth()->user()->last_login_at)->diffForHumans() }}</div>
                <a href="{{ route('user.profile') }}" class="dropdown-item has-icon">
                    <i class="far fa-user"></i> Profile
                </a>
                <a href="{{ route('company.index') }}" class="dropdown-item has-icon">
                    <i class="fas fa-cog"></i> Settings
                </a>
                <div class="dropdown-divider"></div>
                <a href="javascript:void(0);" onclick="logout_();" class="dropdown-item has-icon text-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </li>
    </ul>
    @endif
</nav>
@if($title != 'Statistic')
<nav class="navbar navbar-secondary navbar-expand-lg">
    <div class="container">
        <ul class="navbar-nav">
            <li class="nav-item {{ $title == 'Dashboard' ? 'active' : '' }}">
                <a href="{{ route('home') }}" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>
            <li class="nav-item dropdown {{ $title == 'Data Calon' || $title == 'Data Event' || $title == 'Data Vote' || $title == 'Data User' ? 'active' : '' }}">
                <a href="javascript:void(0);" data-toggle="dropdown" class="nav-link has-dropdown"><i class="fas fa-database"></i><span>Master Data</span></a>
                <ul class="dropdown-menu">
                    <li class="nav-item {{ $title == 'Data Event' ? 'active' : '' }}"><a class="nav-link" href="{{ route('event.index') }}">Event</a></li>
                    <li class="nav-item {{ $title == 'Data Vote' ? 'active' : '' }}"><a class="nav-link" href="{{ route('vote.index') }}">Vote</a></li>
                    <li class="nav-item {{ $title == 'Data Calon' ? 'active' : '' }}"><a class="nav-link" href="{{ route('calon.index') }}">Calon</a></li>
                    <li class="nav-item {{ $title == 'Data User' ? 'active' : '' }}"><a class="nav-link" href="{{ route('user.index') }}">User</a></li>
                </ul>
            </li>
            <li class="nav-item {{ $title == 'New Votes' ? 'active' : '' }}">
                <a href="{{ route('vote.create') }}" class="nav-link"><i class="fas fa-cart-plus"></i><span>New Votes</span></a>
            </li>
        </ul>
    </div>
</nav>
@endif