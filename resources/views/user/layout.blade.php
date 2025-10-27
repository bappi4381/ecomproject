<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'User Dashboard')</title>

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Custom CSS --}}
    <link href="{{ asset('admin/styles.css') }}" rel="stylesheet">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
</head>
<body>
    {{-- Sidebar --}}
    <nav id="sidebar" class="sidebar">
        <div class="sidebar-header">

            <h4 class="text-white mb-0">
                <a href="{{route('home')}}" class="text-white text-decoration-none fw-bold" ><i class="bi bi-book me-2"></i> BookSaw </a> 
            </h4>
        </div>

        @php
            $currentRoute = Route::currentRouteName();

            $menus = [
                ['title' => 'Dashboard', 'icon' => 'bi-house-door', 'route' => 'user.dashboard'],
                ['title' => 'Profile', 'icon' => 'bi-person', 'route' => 'user.profile'],
                [
                    'title' => 'Orders',
                    'icon' => 'bi-bag',
                    'children' => [
                        ['title' => 'My Orders', 'route' => 'user.orders.index'],
                        ['title' => 'Track Order', 'route' => 'user.orders.track'],
                    ],
                ],
                ['title' => 'Messages', 'icon' => 'bi-chat-dots', 'route' => 'user.messages.index'],
            ];
        @endphp

        <ul class="sidebar-nav">
            @foreach ($menus as $menu)
                @if (isset($menu['children']))
                    @php
                        $childRoutes = array_column($menu['children'], 'route');
                        $isActive = in_array($currentRoute, $childRoutes) ? 'active' : '';
                        $isShow = in_array($currentRoute, $childRoutes) ? 'show' : '';
                    @endphp
                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center {{ $isActive }}"
                           data-bs-toggle="collapse" href="#collapse{{ Str::slug($menu['title']) }}" role="button"
                           aria-expanded="{{ $isShow ? 'true' : 'false' }}">
                            <span><i class="bi {{ $menu['icon'] }}"></i> {{ $menu['title'] }}</span>
                            <i class="bi bi-chevron-right small rotate-icon"></i>
                        </a>
                        <div class="collapse {{ $isShow }}" id="collapse{{ Str::slug($menu['title']) }}">
                            <ul class="nav flex-column ms-3">
                                @foreach ($menu['children'] as $child)
                                    <li class="nav-item">
                                        <a href="{{ route($child['route']) }}"
                                           class="nav-link {{ $currentRoute === $child['route'] ? 'active' : '' }}">
                                           {{ $child['title'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="{{ route($menu['route']) }}"
                           class="nav-link {{ $currentRoute === $menu['route'] ? 'active' : '' }}">
                            <i class="bi {{ $menu['icon'] }}"></i>
                            <span class="nav-text">{{ $menu['title'] }}</span>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </nav>

    {{-- Main Content Wrapper --}}
    <div id="main-wrapper" class="main-wrapper">
        {{-- Top Navbar --}}
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
            <div class="container-fluid">
                <button class="btn btn-outline-secondary d-lg-none me-3" type="button" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>

                {{-- Search Bar --}}
                <div class="d-flex flex-grow-1 me-3">
                    <div class="input-group" style="max-width: 400px;">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control border-start-0 bg-light" placeholder="Search...">
                    </div>
                </div>

                {{-- Notifications and Profile --}}
                <div class="d-flex align-items-center">
                    {{-- Notifications --}}
                    <div class="dropdown me-3">
                        <button class="btn position-relative" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-bell"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                2
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">New message received</a></li>
                            <li><a class="dropdown-item" href="#">Order shipped</a></li>
                        </ul>
                    </div>

                    {{-- User Profile --}}
                    <div class="dropdown">
                        @php
                            $user = Auth::user();
                            $avatarUrl = $user && $user->avatar 
                                ? asset('storage/' . $user->avatar)
                                : 'https://via.placeholder.com/32x32/28a745/ffffff?text=' . strtoupper(substr($user->name ?? 'U', 0, 2));
                            $userName = $user->name ?? 'User';
                        @endphp

                        <button class="btn dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                            <img src="{{ $avatarUrl }}" class="rounded-circle me-2" alt="User Avatar" width="32" height="32">
                            <span class="d-none d-md-inline">{{ $userName }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('user.profile') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('auth.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        {{-- Main Content --}}
        <main class="main-content">
            <div class="container-fluid py-4">
                @yield('content')
            </div>
        </main>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
