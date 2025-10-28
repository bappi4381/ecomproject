{{-- resources/views/admin/admin-dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>

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
                <i class="bi bi-speedometer2 me-2"></i>
                Admin Panel
            </h4>
        </div>

        @php
            $currentRoute = Route::currentRouteName();

            // Define menu structure
            $menus = [
                [
                    'title' => 'Dashboard',
                    'icon' => 'bi-house-door',
                    'route' => 'admin.dashboard',
                ],
                [
                    'title' => 'Users',
                    'icon' => 'bi-people',
                    'children' => [
                        ['title' => 'User List', 'route' => 'users.index'],
                        ['title' => 'Add User', 'route' => 'users.create'],
                    ],
                ],
                [
                    'title' => 'Category & Subcategory',
                    'icon' => 'bi-people',
                    'route' => 'category_subcategory.index',
                ],
                [
                    'title' => 'Products',
                    'icon' => 'bi-box',
                    'children' => [
                        ['title' => 'Product List', 'route' => 'products.index'],
                        ['title' => 'Add Product', 'route' => 'products.create'],
                    ],
                ],
                [
                    'title' => 'Orders',
                    'icon' => 'bi-bag',
                    'children' => [
                        ['title' => 'Order List', 'route' => 'orders.index'],
                        ['title' => 'Orders Create', 'route' => 'orders.create'],
                    ]
                ],
                [
                    'title' => 'Articles',
                    'icon' => 'bi-newspaper',
                    'children' => [
                        ['title' => 'Article List', 'route' => 'articles.index'],
                        ['title' => 'Add Article', 'route' => 'articles.create'],
                    ]
                    
                ],
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
        {{-- Top Navigation Bar --}}
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
            <div class="container-fluid">
                {{-- Mobile Menu Toggle --}}
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

                {{-- User Profile and Notifications --}}
                <div class="d-flex align-items-center">
                    {{-- Notifications --}}
                    <div class="dropdown me-3">
                        <button class="btn position-relative" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-bell"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                3
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">New user registered</a></li>
                            <li><a class="dropdown-item" href="#">Order #1234 completed</a></li>
                            <li><a class="dropdown-item" href="#">System update available</a></li>
                        </ul>
                    </div>

                    {{-- User Profile --}}
                    <div class="dropdown">
                        @php
                            $admin = Auth::guard('admin')->user();
                            $avatarUrl = $admin && $admin->avatar 
                                ? asset('storage/' . $admin->avatar) 
                                : 'https://via.placeholder.com/32x32/007bff/ffffff?text=' . strtoupper(substr($admin->name ?? 'JD', 0, 2));
                            $adminName = $admin->name ?? 'Admin';
                        @endphp

                        <button class="btn  dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                            <img src="{{ $avatarUrl }}" class="rounded-circle me-2" alt="User Avatar" width="32" height="32">
                            <span class="d-none d-md-inline">{{ $adminName }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.profile') }}">
                                    <i class="bi bi-person me-2"></i>Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('admin.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        {{-- Main Content Area --}}
        <main class="main-content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </main>
    </div>

    {{-- Bootstrap 5 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editorElement = document.getElementById('editor');

            if (editorElement && !editorElement.classList.contains('quill-initialized')) {
                
                editorElement.classList.add('quill-initialized'); // Prevent double init

                const quill = new Quill('#editor', { theme: 'snow' });

                // Load old content only once
                quill.root.innerHTML = `{!! old('content', $article->content ?? '') !!}`;

                const form = editorElement.closest('form');
                form.addEventListener('submit', function() {
                    document.getElementById('content').value = quill.root.innerHTML;
                });
            }
        });
    </script>

    {{-- Custom JS --}}
    {{-- <script src="{{ asset('admin/script.js') }}"></script> --}}
</body>
</html>
