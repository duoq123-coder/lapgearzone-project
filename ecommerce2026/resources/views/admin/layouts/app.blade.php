<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Administrator</title>
    
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Admin Premium Theme CSS -->
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #1B2838 0%, #2C3E50 100%);
            --accent-gold: #F5B041;
            --body-bg: #F8F9FA;
            --admin-navbar-bg: #FFFFFF;
            --card-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            --transition-smooth: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--body-bg);
            color: #222222;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-admin {
            background-color: var(--admin-navbar-bg);
            border-bottom: 1px solid #EAEAEA;
            padding: 14px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02) !important;
        }

        .navbar-brand-admin {
            font-weight: 800;
            font-size: 1.4rem;
            color: #1B2838 !important;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none !important;
        }

        .navbar-brand-admin:hover,
        .navbar-brand-admin:focus {
            text-decoration: none !important;
            color: #1B2838 !important;
        }

        .navbar-brand-admin span {
            color: #F5B041;
        }
        
        .navbar-brand-admin i {
            color: #F5B041;
        }

        .nav-link-admin {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            color: #555555 !important;
            padding: 8px 18px !important;
            border-radius: 30px;
            transition: var(--transition-smooth);
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
            position: relative;
            text-decoration: none !important;
        }

        .nav-link-admin:hover,
        .nav-link-admin:focus {
            color: #222222 !important;
            background: rgba(0, 0, 0, 0.04);
            text-decoration: none !important;
        }
        
        .nav-link-admin.active {
            color: #1B2838 !important;
            background: var(--accent-gold);
            box-shadow: 0 4px 10px rgba(245, 176, 65, 0.3);
            text-decoration: none !important;
        }

        .dropdown-item {
            color: #222222 !important;
            text-decoration: none !important;
        }

        .dropdown-item:hover,
        .dropdown-item:focus {
            background-color: rgba(0,0,0,0.04) !important;
            text-decoration: none !important;
        }

        /* Admin Panels / Cards */
        .card {
            border: 1px solid #EAEAEA !important;
            border-radius: 20px;
            background: #FFFFFF !important;
            box-shadow: var(--card-shadow) !important;
            overflow: hidden;
            margin-bottom: 30px;
        }

        .card-header {
            background: #FFFFFF !important;
            border-bottom: 1px solid #EAEAEA !important;
            padding: 20px 24px;
        }

        .card-header h2, .card-header h4 {
            font-weight: 700;
            color: #222222 !important;
        }

        .card-body {
            padding: 24px;
            background: #FFFFFF !important;
        }

        /* Forms */
        .form-label {
            font-weight: 600;
            color: #222222 !important;
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            background-color: #FFFFFF !important;
            color: #222222 !important;
            border-radius: 10px;
            padding: 10px 14px;
            border: 1px solid #CBD5E1 !important;
            transition: var(--transition-smooth);
        }
        
        .form-control:focus, .form-select:focus {
            background-color: #FFFFFF !important;
            color: #222222 !important;
            border-color: var(--accent-gold) !important;
            box-shadow: 0 0 0 3px rgba(245, 176, 65, 0.2) !important;
        }

        /* Admin Table */
        .table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            color: #222222 !important;
        }

        .table thead th {
            background-color: #F8F9FA !important;
            color: #222222 !important;
            font-weight: 600;
            border: none;
            padding: 14px 16px;
            border-bottom: 2px solid #EAEAEA !important;
        }

        .table thead th:first-child {
            border-top-left-radius: 12px;
        }

        .table thead th:last-child {
            border-top-right-radius: 12px;
        }

        .table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #F1F1F1 !important;
            background-color: #FFFFFF !important;
            color: #444444 !important;
        }

        .table tbody tr:hover td {
            background-color: #F8F9FA !important;
        }

        .table tbody tr:last-child td:first-child {
            border-bottom-left-radius: 12px;
        }

        .table tbody tr:last-child td:last-child {
            border-bottom-right-radius: 12px;
        }

        /* Buttons */
        .btn {
            border-radius: 30px;
            padding: 8px 22px;
            font-weight: 600;
            transition: var(--transition-smooth);
            text-decoration: none !important;
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            color: #ffffff;
            box-shadow: 0 4px 15px -3px rgba(0, 122, 255, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px -2px rgba(0, 122, 255, 0.5);
            opacity: 0.95;
            color: #ffffff;
            text-decoration: none !important;
        }

        .btn-premium {
            background: var(--accent-gold);
            color: #1B2838 !important;
            border: none;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            padding: 10px 26px;
            border-radius: 30px;
            transition: var(--transition-smooth);
            text-decoration: none !important;
        }
        .btn-premium:hover {
            background: #D4941F;
            color: #ffffff !important;
            text-decoration: none !important;
        }

        /* Footer */
        footer {
            background-color: #1B2838 !important;
            color: #FFFFFF !important;
            border-top: none;
            padding: 24px 0;
            margin-top: auto;
        }
        
        .dropdown-menu {
            border: 1px solid #EAEAEA !important;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05) !important;
            background: #FFFFFF !important;
        }

        @keyframes slideIn {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .animate-slide-in {
            animation: slideIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Admin Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-admin shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand navbar-brand-admin text-decoration-none" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('images/logo.png') }}" alt="LapGearZone Logo" height="80" style="border-radius: 8px; margin-right: -4px; margin-top: -20px; margin-bottom: -20px;"> LapGearZone <span>Admin</span>
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar"
                aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link-admin text-decoration-none {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link-admin text-decoration-none {{ request()->routeIs('admin.attendance') ? 'active' : '' }}" href="{{ route('admin.attendance') }}">
                            <i class="bi bi-calendar2-check"></i> Nhân Sự
                        </a>
                    </li>


                    <li class="nav-item border-start border-secondary-subtle ps-3 ms-3 d-none d-lg-block">
                        <a class="nav-link-admin text-info text-decoration-none" href="{{ url('/') }}">
                            <i class="bi bi-globe"></i> Xem trang bán hàng
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav ms-auto align-items-center">
                    
                    <!-- Thông báo (Vấn đề của shop) -->
                    @php
                        $outOfStockCount = isset($shopIssues['out_of_stock']) ? $shopIssues['out_of_stock']->count() : 0;
                        $totalIssues = $outOfStockCount;
                    @endphp
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link-admin position-relative d-flex align-items-center" href="#" id="shopIssuesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 0.5rem;">
                            <i class="bi bi-bell-fill fs-5 text-dark"></i>
                            @if($totalIssues > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow-sm" style="font-size: 0.65rem; border: 2px solid #fff;">
                                    {{ $totalIssues }}
                                </span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-2 animate-fade-in" aria-labelledby="shopIssuesDropdown" style="background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); min-width: 250px;">
                            <li><h6 class="dropdown-header text-dark fw-bold border-bottom pb-2 mb-2">Thông báo kho hàng</h6></li>
                            
                            @if($outOfStockCount > 0)
                                @foreach($shopIssues['out_of_stock'] as $item)
                                <li>
                                    <a class="dropdown-item py-2 px-3 rounded-3 text-danger d-flex align-items-start" href="{{ route('admin.products.edit', $item->id) }}" style="white-space: normal;">
                                        <i class="bi bi-exclamation-triangle-fill mt-1 me-2"></i>
                                        <div>
                                            <span class="d-block fw-bold" style="font-size: 0.85rem;">Sản phẩm hết hàng</span>
                                            <span class="text-secondary" style="font-size: 0.8rem;">{{ $item->name }}</span>
                                        </div>
                                    </a>
                                </li>
                                @endforeach
                            @else
                                <li>
                                    <span class="dropdown-item text-secondary py-3 text-center" style="font-size: 0.9rem;">
                                        <i class="bi bi-check-circle text-success me-1"></i> Không có vấn đề nào
                                    </span>
                                </li>
                            @endif
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link-admin dropdown-toggle text-decoration-none active d-flex align-items-center" href="#" id="adminUserDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            @if(Auth::user()->avatar)
                                <img src="{{ Auth::user()->avatar_url }}" alt="Avatar" class="rounded-circle me-2" style="width: 24px; height: 24px; object-fit: cover;">
                            @else
                                <i class="bi bi-person-circle me-1"></i>
                            @endif
                            {{ Auth::user()->name ?? 'Admin' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-2 animate-fade-in" aria-labelledby="adminUserDropdown" style="background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); min-width: 180px;">
                            <li>
                                <a class="dropdown-item text-dark py-2 px-3 rounded-3 text-decoration-none" href="{{ route('admin.profile') }}">
                                    <i class="bi bi-person-vcard me-2 text-primary"></i>Hồ sơ cá nhân
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-dark py-2 px-3 rounded-3 text-decoration-none {{ request()->routeIs('admin.products.*') ? 'bg-light fw-bold' : '' }}" href="{{ route('admin.products.index') }}">
                                    <i class="bi bi-box-seam me-2 text-primary"></i>Sản phẩm
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-dark py-2 px-3 rounded-3 text-decoration-none {{ request()->routeIs('admin.categories.*') ? 'bg-light fw-bold' : '' }}" href="{{ route('admin.categories.index') }}">
                                    <i class="bi bi-folder-fill me-2 text-primary"></i>Danh mục
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-dark py-2 px-3 rounded-3 text-decoration-none {{ request()->routeIs('admin.coupons.*') ? 'bg-light fw-bold' : '' }}" href="{{ route('admin.coupons.index') }}">
                                    <i class="bi bi-ticket-perforated-fill me-2 text-primary"></i>Voucher
                                </a>
                            </li>
                            <li><hr class="dropdown-divider opacity-10"></li>
                            <li>
                                <a class="dropdown-item text-danger py-2 px-3 rounded-3 text-decoration-none" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                                    <i class="bi bi-box-arrow-left me-2"></i> Đăng xuất
                                </a>
                                <form id="admin-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <main class="container py-4 animate-slide-in">
        <!-- Toast / Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible border-0 shadow-sm rounded-4 p-3 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                <div>
                    <strong>Thành công!</strong> {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible border-0 shadow-sm rounded-4 p-3 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-4 me-3"></i>
                <div>
                    <strong>Lỗi!</strong> {{ session('error') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container text-center">
            <p class="small mb-0">&copy; {{ date('Y') }} LapGearZone Administrator Panel. All rights reserved.</p>
        </div>
    </footer>

    <!-- Modals Section -->
    @yield('modals')
    @include('components.cropper-modal')

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>