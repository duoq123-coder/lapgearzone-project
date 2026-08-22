<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LapGearZone') - Cửa Hàng Công Nghệ</title>
    
    <!-- Google Fonts: Outfit & Inter for premium tech feel -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom Style Sheet -->
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #1B2838 0%, #2C3E50 100%);
            --accent-gold: #F5B041;
            --accent-gold-light: #F9D689;
            --accent-gold-dark: #D4941F;
            --accent-navy: #1B2838;
            --card-bg: #FFFFFF;
            --body-bg: #F8F9FA;
            --text-main: #222222;
            --text-muted: #666666;
            --text-secondary: #999999;
            --navbar-bg: #FFFFFF;
            --border-color: #EAEAEA;
            --card-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
            --card-hover-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
            --transition-smooth: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--body-bg);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            line-height: 1.7;
        }

        h1, h2, h3, h4, h5, h6, .display-font {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: var(--text-main);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 7px;
            height: 7px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--accent-gold);
        }

        /* Clean White Navbar */
        .navbar-custom {
            background: var(--navbar-bg);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid var(--border-color);
            padding: 12px 0;
            transition: var(--transition-smooth);
        }
        
        .navbar-brand-custom {
            font-family: 'Outfit', sans-serif;
            font-weight: 900;
            font-size: 1.6rem;
            letter-spacing: -0.5px;
            color: var(--accent-navy);
            -webkit-text-fill-color: initial;
            background: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: var(--transition-smooth);
            text-decoration: none !important;
        }

        .navbar-brand-custom i {
            color: var(--accent-gold);
        }
        
        .navbar-brand-custom:hover {
            color: var(--accent-navy);
            opacity: 0.85;
            transform: scale(1.02);
            text-decoration: none !important;
        }

        .nav-link-custom {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            color: var(--text-muted) !important;
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

        .nav-link-custom:hover {
            color: var(--text-main) !important;
            background: rgba(0, 0, 0, 0.04);
            text-decoration: none !important;
        }

        .nav-link-custom.active {
            color: var(--accent-navy) !important;
            background: var(--accent-gold);
            box-shadow: 0 4px 12px -3px rgba(245, 176, 65, 0.4);
            text-decoration: none !important;
        }

        .dropdown-item {
            text-decoration: none !important;
        }

        /* Buttons — Gold Accent */
        .btn-premium {
            background: var(--accent-gold);
            color: var(--accent-navy) !important;
            border: none;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            padding: 10px 26px;
            border-radius: 30px;
            transition: var(--transition-smooth);
            box-shadow: 0 4px 15px -3px rgba(245, 176, 65, 0.4);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            position: relative;
            overflow: hidden;
            text-decoration: none !important;
        }

        .btn-premium::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.5s ease, height 0.5s ease;
        }

        .btn-premium:active::after {
            width: 300px;
            height: 300px;
        }

        .btn-premium:hover {
            background: var(--accent-gold-dark);
            color: #fff !important;
            transform: translateY(-2px) scale(1.03);
            box-shadow: 0 10px 25px -5px rgba(245, 176, 65, 0.5);
            text-decoration: none !important;
        }
        
        .btn-premium:active {
            transform: translateY(0) scale(0.98);
        }

        .btn-outline-premium {
            background: var(--card-bg);
            color: var(--text-main);
            border: 2px solid var(--border-color);
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            padding: 8px 24px;
            border-radius: 30px;
            transition: var(--transition-smooth);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none !important;
        }

        .btn-outline-premium:hover {
            background: var(--accent-gold);
            border-color: var(--accent-gold);
            color: var(--accent-navy) !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px -5px rgba(245, 176, 65, 0.35);
            text-decoration: none !important;
        }

        /* Card — Clean White with Subtle Shadow */
        .card-premium {
            border: 1px solid var(--border-color);
            border-radius: 20px;
            background: var(--card-bg);
            box-shadow: var(--card-shadow);
            transition: var(--transition-smooth);
            overflow: hidden;
            position: relative;
        }

        .card-premium::before {
            display: none;
        }

        .card-premium:hover {
            transform: translateY(-6px);
            box-shadow: var(--card-hover-shadow);
            border-color: #ddd;
        }

        /* Badge Custom */
        .badge-premium {
            background: var(--accent-gold);
            color: var(--accent-navy);
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            letter-spacing: 0.2px;
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 0.8rem;
        }

        /* Quick Add-to-Cart Button */
        .btn-add-cart-quick {
            background: var(--accent-gold);
            color: var(--accent-navy) !important;
            border: none;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            padding: 8px 16px;
            border-radius: 30px;
            transition: var(--transition-smooth);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            box-shadow: 0 4px 12px -3px rgba(245, 176, 65, 0.35);
            white-space: nowrap;
        }

        .btn-add-cart-quick:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px -5px rgba(245, 176, 65, 0.5);
            background: var(--accent-gold-dark);
            color: #fff !important;
        }

        .btn-add-cart-quick:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        /* Mobile Category Drawer Toggle */
        .mobile-category-toggle {
            position: fixed;
            bottom: 90px;
            left: 16px;
            z-index: 1040;
            background: var(--accent-gold);
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent-navy);
            box-shadow: 0 6px 20px rgba(245, 176, 65, 0.4);
            transition: var(--transition-smooth);
        }

        .mobile-category-toggle:hover {
            transform: scale(1.1);
            box-shadow: 0 10px 28px rgba(245, 176, 65, 0.5);
            color: var(--accent-navy);
        }

        @media (min-width: 992px) {
            .mobile-category-toggle {
                display: none !important;
            }
        }

        /* Offcanvas — Light Theme */
        .offcanvas-category {
            background: var(--card-bg) !important;
            border-right: 1px solid var(--border-color) !important;
            max-width: 300px;
        }

        .offcanvas-category .offcanvas-header {
            border-bottom: 1px solid var(--border-color);
        }

        .offcanvas-category .btn-close {
            filter: none;
        }

        /* Light Theme Pagination */
        .pagination {
            gap: 6px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .pagination .page-item .page-link {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            color: var(--text-main);
            border-radius: 12px !important;
            min-width: 44px;
            min-height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: 0.95rem;
            transition: var(--transition-smooth);
            padding: 0;
            text-decoration: none !important;
        }

        .pagination .page-item .page-link:hover {
            background: rgba(245, 176, 65, 0.12);
            border-color: var(--accent-gold);
            color: var(--accent-navy);
            transform: translateY(-1px);
        }

        .pagination .page-item.active .page-link {
            background: var(--accent-gold);
            border-color: var(--accent-gold);
            color: var(--accent-navy);
            box-shadow: 0 4px 12px -3px rgba(245, 176, 65, 0.4);
        }

        .pagination .page-item.disabled .page-link {
            background: #f0f0f0;
            color: #bbb;
            border-color: #e8e8e8;
        }

        /* Footer — Navy Dark */
        footer {
            background: var(--accent-navy);
            color: #94a3b8;
            border-top: none;
            padding: 50px 0 30px 0;
            margin-top: auto;
            position: relative;
            overflow: hidden;
        }
        
        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 20%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(245, 176, 65, 0.06) 0%, transparent 70%);
            z-index: 1;
            pointer-events: none;
        }
        
        footer::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(245, 176, 65, 0.04) 0%, transparent 70%);
            z-index: 1;
            pointer-events: none;
        }

        footer a.text-secondary {
            color: #94a3b8 !important;
            transition: var(--transition-smooth);
        }
        footer a.text-secondary:hover {
            color: var(--accent-gold) !important;
            padding-left: 6px;
        }

        /* Animations */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .animate-slide-up {
            animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* Input Base & Focus Styles */
        .form-control, .form-select {
            border: 1px solid #CBD5E1 !important;
            background-color: #ffffff !important;
            color: var(--text-main) !important;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--accent-gold) !important;
            box-shadow: 0 0 0 3px rgba(245, 176, 65, 0.15) !important;
        }

        @keyframes pulse-glow {
            0% { box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15), 0 0 0 0 rgba(0, 0, 0, 0.2); }
            70% { box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15), 0 0 0 12px rgba(0, 0, 0, 0); }
            100% { box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15), 0 0 0 0 rgba(0, 0, 0, 0); }
        }

        /* Floating Contact Widget */
        .floating-contact-btn {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            transition: var(--transition-smooth);
            animation: pulse-glow 2s infinite;
        }
        .floating-contact-btn:hover {
            transform: scale(1.1) translateY(-3px);
            color: white;
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.2), 0 0 0 15px rgba(0, 0, 0, 0);
            animation: none;
        }

        /* AI Chatbot Custom Styles */
        .chat-body::-webkit-scrollbar {
            width: 6px;
        }
        .chat-body::-webkit-scrollbar-thumb {
            background-color: #ddd;
            border-radius: 10px;
        }

        /* Toast notification */
        .toast-cart {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 9999;
            min-width: 280px;
            background: rgba(16, 185, 129, 0.95);
            backdrop-filter: blur(10px);
            color: #fff;
            border-radius: 16px;
            padding: 14px 20px;
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            box-shadow: 0 12px 30px rgba(16, 185, 129, 0.3);
            transform: translateX(120%);
            transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .toast-cart.show {
            transform: translateX(0);
        }

        .toast-cart.toast-error {
            background: rgba(239, 68, 68, 0.95);
            box-shadow: 0 12px 30px rgba(239, 68, 68, 0.3);
        }

        /* User Dropdown — Light */
        .dropdown-menu {
            border: 1px solid var(--border-color) !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
        }

        /* Decorative gold underline for headings */
        .gold-underline {
            position: relative;
            display: inline-block;
        }
        .gold-underline::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 40px;
            height: 3px;
            background: var(--accent-gold);
            border-radius: 2px;
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Header Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand navbar-brand-custom text-decoration-none" href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}" alt="LapGearZone Logo" height="80" style="border-radius: 8px; margin-right: -4px; margin-top: -20px; margin-bottom: -20px;"> LapGearZone
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Menu bên trái -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @if(!request()->routeIs('login', 'register'))
                        <li class="nav-item">
                            <a class="nav-link-custom text-decoration-none {{ request()->routeIs('contact.index') ? 'active' : '' }}" href="{{ route('contact.index') }}">
                                <i class="bi bi-chat-square-heart"></i> Góp ý   
                            </a>
                        </li>
                    @endif

                </ul>

                <!-- Menu bên phải -->
                <ul class="navbar-nav ms-auto text-center text-lg-start align-items-center">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link-custom text-decoration-none" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
                            </a>
                        </li>
                        <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                            <a class="btn-premium text-decoration-none d-inline-block text-center" href="{{ route('register') }}">
                                Đăng ký
                            </a>
                        </li>
                    @else
                        @if(!in_array(Auth::user()->role, ['admin', 'delivery']))
                            <!-- Shopping Cart -->
                            <li class="nav-item">
                                <a class="nav-link-custom text-decoration-none position-relative me-3" href="{{ route('cart.index') }}" title="Giỏ hàng">
                                    <i class="bi bi-cart3 fs-5"></i>
                                    @php
                                        $cartCount = \App\Models\Cart::where('user_id', Auth::id())->sum('quantity');
                                    @endphp
                                    @if ($cartCount > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                                            {{ $cartCount }}
                                        </span>
                                    @endif
                                </a>
                            </li>
                        @endif
 
                        <!-- User Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link-custom text-decoration-none dropdown-toggle active d-flex align-items-center" href="#" id="navbarUserDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                @if(Auth::user()->avatar)
                                    <img src="{{ Auth::user()->avatar_url }}" alt="Avatar" class="rounded-circle me-2" style="width: 24px; height: 24px; object-fit: cover;">
                                @else
                                    <i class="bi bi-person-circle me-1"></i>
                                @endif
                                <span class="me-2">{{ Auth::user()->name }}</span>
                                @if(Auth::user()->role === 'admin')
                                    <i class="bi bi-award-fill text-warning fs-5" title="Admin"></i>
                                @elseif(Auth::user()->role === 'delivery')
                                    <i class="bi bi-box-seam text-info fs-5" title="Nhân viên giao hàng"></i>
                                @elseif(Auth::user()->role === 'customer_bronze')
                                    <i class="bi bi-star-fill fs-5" style="color: #cd7f32;" title="Hạng Đồng"></i>
                                @elseif(Auth::user()->role === 'customer_silver')
                                    <i class="bi bi-star-fill text-secondary fs-5" title="Hạng Bạc"></i>
                                @elseif(Auth::user()->role === 'customer_gold')
                                    <i class="bi bi-star-fill text-warning fs-5" title="Hạng Vàng"></i>
                                @elseif(Auth::user()->role === 'customer_diamond')
                                    <i class="bi bi-gem text-info fs-5" title="Hạng Kim Cương"></i>
                                @elseif(Auth::user()->role === 'customer_emerald')
                                    <i class="bi bi-gem text-success fs-5" title="Hạng Lục Bảo"></i>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-2 animate-fade-in" aria-labelledby="navbarUserDropdown" style="background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); min-width: 180px;">
                                @if(!request()->routeIs('profile.index'))
                                    <li>
                                        <a class="dropdown-item text-dark py-2 px-3 rounded-3 text-decoration-none" href="{{ route('profile.index') }}">
                                            <i class="bi bi-person-vcard me-2 text-primary"></i>Hồ sơ cá nhân
                                        </a>
                                    </li>
                                @endif
                                @if(Auth::user()->role === 'admin')
                                    <li><a class="dropdown-item text-dark py-2 px-3 rounded-3 text-decoration-none" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2 text-primary"></i>Khu quản trị</a></li>
                                    <li><hr class="dropdown-divider opacity-10"></li>
                                @endif
                                @if((Auth::user()->role === 'admin' || Auth::user()->role === 'delivery') && !request()->routeIs('delivery.index'))
                                    <li><a class="dropdown-item text-dark py-2 px-3 rounded-3 text-decoration-none" href="{{ route('delivery.index') }}"><i class="bi bi-truck me-2 text-info"></i>Nhiệm vụ giao hàng</a></li>
                                    <li><hr class="dropdown-divider opacity-10"></li>
                                @endif
                                <li>
                                    <a class="dropdown-item text-danger py-2 px-3 rounded-3 text-decoration-none" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-left me-2"></i> Đăng xuất
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container py-5 animate-slide-up">
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
    <footer class="text-secondary">
        <div class="container py-3">
            <div class="row g-4 align-items-stretch">
                <!-- Cột 1: Thương hiệu & Slogan -->
                <div class="col-lg-5 col-md-6 d-flex flex-column">
                    <h5 class="fw-bold mb-2 d-flex align-items-center gap-2" style="font-size: 1.2rem; letter-spacing: -0.5px; color: #fbfbfbff;">
                        <img src="{{ asset('images/logo.png') }}" alt="lapgearzone" height="60" style="border-radius: 6px; margin-right: -4px;"> LapGearZone
                    </h5>
                    <p class="mb-0 text-secondary" style="font-size: 0.85rem; line-height: 1.5; text-align: justify;">
                        Chuyên cung cấp các thiết bị công nghệ chính hãng với mẫu mã đẹp, hiện đại và mức giá tốt nhất. Mua sắm dễ dàng, giao hàng nhanh chóng tận nơi, đi kèm chế độ bảo hành rõ ràng và đội ngũ hỗ trợ nhiệt tình trước lẫn sau khi mua.
                    </p>
                </div>
        
                <!-- Cột 2: Thông tin liên hệ -->
                <div class="col-lg-7 col-md-6 d-flex flex-column">
                    <h5 class="text-light fw-bold mb-2" style="font-size: 1.1rem;">Thông tin liên hệ</h5>
                    <ul class="list-unstyled d-flex flex-column gap-2 mb-0" style="font-size: 0.85rem;">
                        <li class="d-flex align-items-start gap-2">
                            <i class="bi bi-geo-alt-fill text-danger fs-6 mt-1"></i>
                            <div>
                                <span class="d-block text-light fw-semibold">Địa chỉ:</span>
                                <span class="text-secondary">Số 25A Ngõ 261, đường Phú Diễn, phường Phú Diễn, Thành phố Hà Nội</span>
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-2">
                            <i class="bi bi-telephone-fill text-success fs-6 mt-1"></i>
                            <div>
                                <span class="d-block text-light fw-semibold">Hotline / Zalo:</span>
                                <a href="tel:0346884415" class="text-secondary text-decoration-none">0346 884 415</a>
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-2">
                            <i class="bi bi-clock-fill text-warning fs-6 mt-1"></i>
                            <div>
                                <span class="d-block text-light fw-semibold">Giờ làm việc:</span>
                                <span class="text-secondary">08:00 - 21:00 (Tất cả các ngày)</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <hr class="my-3" style="border-color: rgba(0,0,0,0.1);">

            <div class="row align-items-center text-secondary">
                <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                    <p class="small mb-0">&copy; {{ date('Y') }} LapGearZone. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="small mb-0">Thiết kế tinh tế &amp; Trải nghiệm vượt trội.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Widget Liên Hệ Nổi (Floating Contact Buttons) -->
    <div class="position-fixed bottom-0 end-0 p-3 mb-3 me-2 d-flex flex-column gap-2" style="z-index: 1055;">
        <!-- Nút Mở Box Chat AI -->
        <button class="btn floating-contact-btn text-white fw-bold" style="background: var(--primary-gradient); border: none;" type="button" data-bs-toggle="collapse" data-bs-target="#aiChatBox" aria-expanded="false" title="Chat với Trợ lý AI">
            <i class="bi bi-robot fs-4"></i>
        </button>
        <!-- Nút Hotline -->
        <a href="tel:0346884415" class="floating-contact-btn bg-success" title="Gọi Hotline">
            <i class="bi bi-telephone-fill fs-5"></i>
        </a>
        <!-- Nút Zalo -->
        <a href="https://zalo.me/0346884415" target="_blank" class="floating-contact-btn bg-primary fw-bold" style="font-size: 11px;" title="Chat qua Zalo">
            ZALO
        </a>
    </div>

    <!-- Box Giao diện Chat AI -->
    <div class="collapse position-fixed shadow-lg rounded-4 overflow-hidden" id="aiChatBox" style="bottom: 90px; right: 80px; width: 330px; z-index: 1060; background: #fff; border: 1px solid rgba(0,0,0,0.1);">
        <!-- Header Chatbox -->
        <div class="p-3 text-white d-flex justify-content-between align-items-center" style="background: var(--primary-gradient);">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-robot fs-5" style="color: #ffffffff;"></i> 
                <h6 class="mb-0 fw-bold" style="color: #ffffffff;">Trợ lý AI</h6>
            </div>  
            <button type="button" class="btn-close btn-close-white" data-bs-toggle="collapse" data-bs-target="#aiChatBox" aria-label="Đóng"></button>
        </div>
        
        <!-- Body Chatbox -->
        <div class="p-3 overflow-auto chat-body bg-light" style="height: 320px; display: flex; flex-direction: column; gap: 12px;" id="chatBody">
            <!-- Tin nhắn từ AI (Chào mừng) -->
            <div class="d-flex justify-content-start">
                <div class="bg-white border rounded-3 p-2 shadow-sm text-dark" style="max-width: 85%; font-size: 0.9rem;">
                    Xin chào! 👋 Tôi là trợ lý AI của cửa hàng. Tôi có thể giúp gì cho bạn hôm nay?
                </div>
            </div>
        </div>
        
        <!-- Form Nhập Tin Nhắn -->
        <div class="p-2 border-top bg-white">
            <form onsubmit="event.preventDefault(); sendAIMessage();" class="input-group">
                <input type="text" id="aiChatInput" class="form-control form-control-sm border-secondary-subtle" placeholder="Nhập câu hỏi của bạn..." autocomplete="off" required>
                <button class="btn btn-sm text-white" style="background: var(--primary-gradient);" type="submit">
                    <i class="bi bi-send-fill"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Modals (if any) -->
    @yield('modals')
    @include('components.cropper-modal')

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script Xử Lý Chat Thông Minh (Chuyên sâu Laptop & Đồ điện tử) -->
    <script>
        function sendAIMessage() {
            const input = document.getElementById('aiChatInput');
            const message = input.value.trim();
            if(!message) return;

            const chatBody = document.getElementById('chatBody');
            
            // 1. Hiển thị tin nhắn của User
            chatBody.innerHTML += `
                <div class="d-flex justify-content-end">
                    <div class="text-white rounded-3 p-2 shadow-sm" style="background: var(--primary-gradient); max-width: 85%; font-size: 0.9rem;">
                        ${message}
                    </div>
                </div>
            `;
            
            input.value = '';
            chatBody.scrollTop = chatBody.scrollHeight;

            // 2. Thêm hiệu ứng "Đang suy nghĩ..."
            const typingId = 'typing-' + Date.now();
            chatBody.innerHTML += `
                <div class="d-flex justify-content-start" id="${typingId}">
                    <div class="bg-white border rounded-3 p-2 shadow-sm text-muted" style="font-size: 0.85rem;">
                        <i>Trợ lý đang suy nghĩ...</i>
                    </div>
                </div>
            `;
            chatBody.scrollTop = chatBody.scrollHeight;

            // 3. Xử lý phản hồi tự động thông minh sau 0.4 giây
            setTimeout(() => {
                // Xóa hiệu ứng "Đang suy nghĩ..."
                document.getElementById(typingId).remove();
                
                // Lấy nội dung trả lời dựa trên từ khóa chuyên sâu
                const replyText = getBotReply(message);
                
                // Hiển thị câu trả lời ra khung chat
                chatBody.innerHTML += `
                    <div class="d-flex justify-content-start">
                        <div class="bg-white border rounded-3 p-2 shadow-sm text-dark" style="max-width: 85%; font-size: 0.9rem;">
                            ${replyText}
                        </div>
                    </div>
                `;
                chatBody.scrollTop = chatBody.scrollHeight;
            }, 400);
        }

        function getBotReply(userMessage) {
            const msg = userMessage.toLowerCase();
            
            let reply = "Cảm ơn bạn đã quan tâm đến sản phẩm tại LapGearZone! Bạn có thể xem danh mục sản phẩm trên web hoặc gọi ngay hotline 0346 884 415 để được tư vấn cấu hình chi tiết nhé.";

            if (msg.includes('chào') || msg.includes('hi') || msg.includes('hello')) {
                reply = "Xin chào! 👋 Chào mừng bạn đến với LapGearZone. Tôi có thể hỗ trợ tư vấn laptop, PC hoặc linh kiện nào cho bạn hôm nay?";
            } 
            else if (msg.includes('gaming') || msg.includes('game') || msg.includes('chơi game') || msg.includes('đồ họa nặng')) {
                reply = "🎮 Đối với nhu cầu chơi game hoặc làm đồ họa nặng, cửa hàng đang có các dòng laptop trang bị card rời mạnh mẽ (RTX series), tản nhiệt cực tốt. Bạn muốn tìm máy tầm giá khoảng bao nhiêu để nhân viên gửi mẫu ạ? Hoặc gọi ngay 0346 884 415 để chốt nhanh nhé!";
            } 
            else if (msg.includes('văn phòng') || msg.includes('học tập') || msg.includes('sinh viên') || msg.includes('word') || msg.includes('excel') || msg.includes('code') || msg.includes('lập trình')) {
                reply = "💻 Dành cho học tập, làm việc văn phòng hoặc lập trình, cửa hàng có các dòng laptop mỏng nhẹ, pin trâu, chip khỏe (Core i5/i7 hoặc Ryzen) gõ phím cực êm. Bạn cần máy mỏng nhẹ hay màn hình lớn? Gọi ngay 0346 884 415 để được tư vấn mã phù hợp nhất!";
            } 
            else if (msg.includes('chuột') || msg.includes('bàn phím') || msg.includes('tai nghe') || msg.includes('balo') || msg.includes('sạc') || msg.includes('linh kiện') || msg.includes('phụ kiện')) {
                reply = "🎧 LapGearZone sẵn có đầy đủ các loại phụ kiện chính hãng: Bàn phím cơ, chuột gaming, tai nghe chống ồn, balo chống sốc và sạc dự phòng. Bạn cần tìm phụ kiện loại nào ạ?";
            }
            else if (msg.includes('sửa chữa') || msg.includes('sửa') || msg.includes('fix') ) {
                reply = "🎧 Bên em nhận sửa chữa và kiểm tra nhiều lỗi như máy không lên nguồn, màn hình, bàn phím, pin, sạc, ổ cứng, RAM, lỗi Windows, máy nóng/lag, lỗi phần mềm...Liên hệ hotline để được tư vấn";
            }
            else if (msg.includes('bảo hành') || msg.includes('đổi trả') || msg.includes('hư') || msg.includes('lỗi')) {
                reply = "🛡️ Tất cả sản phẩm laptop và thiết bị điện tử tại cửa hàng đều được áp dụng chính sách bảo hành chính hãng từ 12 - 24 tháng, hỗ trợ 1 đổi 1 trong tuần đầu nếu có lỗi nhà sản xuất. Bạn cần hỗ trợ kỹ thuật gấp hãy gọi ngay hotline 0346 884 415 nhé!";
            }
            else if (
                msg.includes('giá') || 
                msg.includes('bao nhiêu') || 
                msg.includes('chi phí') || 
                msg.includes('triệu') || 
                msg.includes('củ') ||
                msg.includes('tầm tiền') || 
                msg.includes('mấy tiền')
            ) {
                reply = "Dạ, để được tư vấn chính xác cấu hình và mức giá tốt nhất theo nhu cầu, bạn vui lòng gọi trực tiếp qua số Hotline/Zalo: 0346 884 415 để nhân viên hỗ trợ chi tiết nhé!";
            } 
            else if (msg.includes('địa chỉ') || msg.includes('ở đâu') || msg.includes('đường') || msg.includes('cửa hàng')) {
                reply = "Trụ sở chính của cửa hàng tọa lạc tại: Số 41A đường Phú Diễn, phường Phú Diễn, Thành phố Hà Nội. Rất hân hạnh được đón tiếp bạn ghé trải nghiệm trực tiếp!";
            } 
            else if (msg.includes('liên hệ') || msg.includes('sdt') || msg.includes('số điện thoại') || msg.includes('zalo') || msg.includes('hotline')) {
                reply = "📞 Bạn có thể liên hệ trực tiếp qua số Hotline/Zalo: 0346 884 415. Chúng tôi làm việc từ 08:00 - 21:00 tất cả các ngày trong tuần!";
            }

            return reply;
        }

        // --- Logic Xử lý Yêu thích (Wishlist) AJAX ---
        function toggleWishlist(event, btn, productId) {
            if(event) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            @guest
                alert('Vui lòng đăng nhập để sử dụng tính năng Yêu thích sản phẩm.');
                window.location.href = "{{ route('login') }}";
                return;
            @endguest

            const icon = btn.querySelector('i');
            
            fetch(`/wishlist/toggle/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if(!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                if(data.status === 'added') {
                    icon.classList.remove('bi-heart', 'text-secondary');
                    icon.classList.add('bi-heart-fill', 'text-danger');
                } else if (data.status === 'removed') {
                    icon.classList.remove('bi-heart-fill', 'text-danger');
                    icon.classList.add('bi-heart', 'text-secondary');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra, vui lòng thử lại sau.');
            });
        }
    </script>

    <!-- Toast thông báo giỏ hàng -->
    <div id="cartToast" class="toast-cart">
        <i class="bi bi-check-circle-fill me-2"></i>
        <span id="cartToastMsg"></span>
    </div>

    <!-- AJAX Add-to-Cart Logic -->
    <script>
        function quickAddToCart(productId, btn) {
            if(btn) btn.disabled = true;
            
            fetch(`/cart/add/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ quantity: 1 })
            })
            .then(response => {
                if(response.redirected) {
                    window.location.href = response.url;
                    return;
                }
                return response;
            })
            .then(response => {
                if(!response) return;
                if(response.ok) {
                    showCartToast('Đã thêm vào giỏ hàng!', false);
                    const badge = document.querySelector('.bi-cart3')?.closest('a')?.querySelector('.badge');
                    if(badge) {
                        badge.textContent = parseInt(badge.textContent || 0) + 1;
                    } else {
                        const cartLink = document.querySelector('.bi-cart3')?.closest('a');
                        if(cartLink) {
                            const span = document.createElement('span');
                            span.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger';
                            span.style.fontSize = '0.65rem';
                            span.textContent = '1';
                            cartLink.appendChild(span);
                        }
                    }
                } else {
                    response.text().then(text => {
                        showCartToast('Không thể thêm. Vui lòng đăng nhập!', true);
                    });
                }
            })
            .catch(error => {
                showCartToast('Có lỗi xảy ra, thử lại sau.', true);
            })
            .finally(() => {
                if(btn) setTimeout(() => btn.disabled = false, 1000);
            });
        }

        function showCartToast(msg, isError) {
            const toast = document.getElementById('cartToast');
            const msgEl = document.getElementById('cartToastMsg');
            msgEl.textContent = msg;
            toast.classList.toggle('toast-error', isError);
            toast.querySelector('i').className = isError ? 'bi bi-exclamation-circle-fill me-2' : 'bi bi-check-circle-fill me-2';
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
        }
    </script>
    
    @stack('scripts')
</body>
</html>