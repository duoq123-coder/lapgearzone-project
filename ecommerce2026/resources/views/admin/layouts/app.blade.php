<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Administrator</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-white.png') }}">

    <!-- SCRIPT CHỐNG FOUC (Anti-FOUC: Chạy ngay trước khi render CSS/DOM) -->
    <script>
        (() => {
            const storedTheme = localStorage.getItem('laptopking_theme');
            const preferredTheme = storedTheme ? storedTheme : (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-bs-theme', preferredTheme);
        })();
    </script>
    
    <!-- Google Fonts: Inter, Space Grotesk, Space Mono, Chakra Petch -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:ital,wght@0,500;0,600;0,700;1,600&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Space+Grotesk:wght@500;600;700;800&family=Space+Mono:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Admin Cyber-Industrial Angular Design System CSS -->
    <style>
        :root {
            /* Brand Color Palette */
            --bellroy-orange: #cd4c20;
            --bellroy-orange-hover: #b85021;
            --bellroy-orange-subtle: #fbeee8;
            --bellroy-sage: #4e7969;
            --bellroy-sage-subtle: #edf5f1;
            --bellroy-amber: #d97706;
            --bellroy-charcoal: #151413;
            --bellroy-dark: #22201e;
            
            /* Theme Aliases */
            --primary-gradient: #151413;
            --accent-gold: #cd4c20;
            --accent-gold-light: #fbeee8;
            --accent-gold-dark: #b85021;
            
            /* Surfaces & Backgrounds */
            --card-bg: #ffffff;
            --body-bg: #f7f6f3;
            --surface-muted: #f0ede6;
            --surface-sand: #e8e4dc;
            
            /* Typography Colors */
            --text-main: #232220;
            --text-muted: #6e6b66;
            --text-secondary: #8c8881;
            
            /* Navigation & Borders */
            --admin-navbar-bg: #151413;
            --border-color: #cfc8be;
            --border-color-subtle: #dfd8cd;
            --card-shadow: 0 1px 3px rgba(0, 0, 0, 0.04), 2px 2px 0px rgba(0, 0, 0, 0.05);
            --transition-smooth: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* Industrial Theme Switcher */
        .industrial-theme-switcher-wrapper {
            position: relative;
            user-select: none;
        }
        .industrial-theme-btn {
            background: #191817;
            color: #d8d4cd;
            border: 1px solid rgba(255, 255, 255, 0.22);
            width: 36px;
            height: 36px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            cursor: pointer;
            border-radius: 2px;
            clip-path: polygon(0 0, calc(100% - 5px) 0, 100% 5px, 100% 100%, 5px 100%, 0 calc(100% - 5px));
            box-shadow: 2px 2px 0px rgba(0, 0, 0, 0.35);
            transition: var(--transition-smooth);
            position: relative;
            outline: none;
        }
        .industrial-theme-btn:hover {
            border-color: var(--bellroy-orange);
            background: #242220;
            color: var(--bellroy-orange);
            transform: translateY(-1px);
            box-shadow: 3px 3px 0px rgba(205, 76, 32, 0.35);
        }
        .industrial-theme-btn:active {
            transform: translateY(1px);
        }
        .telemetry-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            display: inline-block;
            transition: all 0.25s ease;
        }
        [data-bs-theme="light"] .telemetry-dot {
            background: #ff7b00;
            box-shadow: 0 0 8px #ff7b00, 0 0 2px #ffffff;
        }
        [data-bs-theme="dark"] .telemetry-dot {
            background: #4af626;
            box-shadow: 0 0 10px #4af626, 0 0 3px #ffffff;
        }
        .telemetry-label {
            font-size: 0.72rem;
            line-height: 1;
            text-transform: uppercase;
        }
        .telemetry-icon-box {
            width: 22px;
            height: 22px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 2px;
            font-size: 0.8rem;
            transition: all 0.25s ease;
        }
        .industrial-theme-btn:hover .telemetry-icon-box {
            background: var(--bellroy-orange);
            border-color: var(--bellroy-orange);
            color: #ffffff;
        }

        /* Dark Mode Overrides */
        [data-bs-theme="dark"] {
            --body-bg: #111113;
            --admin-navbar-bg: #0e0e10;
            --card-bg: #1a1a1e;
            --surface-muted: #222228;
            --surface-sand: #2b2b34;
            --text-main: #f0f0f3;
            --text-muted: #a3a3ad;
            --text-secondary: #c8c6c0;
            --border-color: #383844;
            --border-color-subtle: #2b2b34;
            --card-shadow: 0 4px 16px rgba(0, 0, 0, 0.4), 2px 2px 0px #0b0b0d;
        }
        [data-bs-theme="dark"] body {
            color: #f0f0f3;
        }
        [data-bs-theme="dark"] .card {
            background-color: #1a1a1e !important;
            border: 1px solid #383844 !important;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.35), 2px 2px 0px #0b0b0d !important;
        }
        [data-bs-theme="dark"] .card-header {
            background-color: transparent !important;
            border-bottom: 1px solid #33333e !important;
        }
        [data-bs-theme="dark"] .card-body {
            background-color: transparent !important;
        }
        [data-bs-theme="dark"] .dropdown-menu {
            background-color: #1a1a1e !important;
            border: 1px solid #383844 !important;
        }
        [data-bs-theme="dark"] .form-control,
        [data-bs-theme="dark"] .form-select,
        [data-bs-theme="dark"] .input-group-text {
            background-color: #151518 !important;
            border: 1px solid #3c3c48 !important;
            color: #f0f0f3 !important;
        }
        [data-bs-theme="dark"] .form-control:focus,
        [data-bs-theme="dark"] .form-select:focus {
            border-color: var(--bellroy-orange) !important;
            box-shadow: 0 0 0 2px rgba(205, 76, 32, 0.3) !important;
        }
        [data-bs-theme="dark"] .table {
            border-color: #33333e !important;
        }
        [data-bs-theme="dark"] .table thead th {
            background-color: #141418 !important;
            border-bottom: 2px solid #383844 !important;
            color: #c5c5cf !important;
        }
        [data-bs-theme="dark"] .table tbody td {
            background-color: transparent !important;
            border-bottom: 1px solid #282832 !important;
            color: #f0f0f3 !important;
        }
        [data-bs-theme="dark"] .border,
        [data-bs-theme="dark"] .border-top,
        [data-bs-theme="dark"] .border-bottom,
        [data-bs-theme="dark"] .border-start,
        [data-bs-theme="dark"] .border-end {
            border-color: #383844 !important;
        }
        [data-bs-theme="dark"] .bg-light {
            background-color: #202026 !important;
            border-color: #383844 !important;
        }
        [data-bs-theme="dark"] .bg-white {
            background-color: #1a1a1e !important;
        }
        [data-bs-theme="dark"] .stat-card-tactical {
            background-color: #1a1a1e !important;
            border: 1px solid #383844 !important;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.35), 2px 2px 0px #0b0b0d !important;
        }
        [data-bs-theme="dark"] .card-header h2,
        [data-bs-theme="dark"] .card-header h4,
        [data-bs-theme="dark"] .card-header h5,
        [data-bs-theme="dark"] .form-label,
        [data-bs-theme="dark"] .dropdown-item,
        [data-bs-theme="dark"] .text-dark {
            color: #f0f0f3 !important;
        }
        [data-bs-theme="dark"] .text-muted {
            color: #a3a3ad !important;
        }
        [data-bs-theme="dark"] .text-secondary {
            color: #c8c6c0 !important;
        }

        /* Base Typography */
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--body-bg);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            letter-spacing: -0.01em;
        }

        .display-font, h1, h2, h3, h4, h5, h6 {
            font-family: 'Space Grotesk', sans-serif !important;
            letter-spacing: -0.02em;
        }

        .font-monospace, .mono-font, .telemetry-val {
            font-family: 'Space Mono', monospace !important;
        }

        /* Angular Override for Generic Rounded Utilities */
        .rounded-4, .rounded-3, .rounded-2, .rounded {
            border-radius: 2px !important;
        }

        /* ========================================================= */
        /* AVANT-GARDE FLOATING DYNAMIC GLASS NAVBAR (ADMIN EDITION) */
        /* ========================================================= */
        .navbar-custom, .navbar-admin {
            background: rgba(14, 17, 23, 0.88) !important;
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.06);
            padding: 10px 0;
            transition: var(--transition-smooth);
            z-index: 1030;
        }

        [data-bs-theme="light"] .navbar-custom, 
        [data-bs-theme="light"] .navbar-admin {
            background: rgba(255, 255, 255, 0.9) !important;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08) !important;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04), inset 0 1px 0 rgba(255, 255, 255, 0.8);
        }

        .admin-nav-container {
            padding-left: 2cm !important;
            padding-right: 2cm !important;
        }

        @media (max-width: 991.98px) {
            .admin-nav-container {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
        }

        .navbar-brand-custom, .navbar-brand-admin {
            font-family: 'Space Grotesk', 'Inter', sans-serif;
            font-weight: 800;
            font-size: 1.35rem;
            letter-spacing: -0.02em;
            color: #ffffff !important;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: var(--transition-smooth);
            text-decoration: none !important;
        }

        [data-bs-theme="light"] .navbar-brand-custom,
        [data-bs-theme="light"] .navbar-brand-admin {
            color: #111827 !important;
        }

        [data-bs-theme="light"] .navbar-brand-custom img,
        [data-bs-theme="light"] .navbar-brand-admin img {
            filter: none !important;
        }

        .navbar-brand-custom:hover, .navbar-brand-admin:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .brand-live-badge {
            font-family: 'Space Mono', monospace;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--bellroy-orange);
            background: rgba(205, 76, 32, 0.12);
            border: 1px solid rgba(205, 76, 32, 0.3);
            padding: 3px 8px;
            border-radius: 50rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .brand-pulse-dot {
            width: 6px;
            height: 6px;
            background-color: var(--bellroy-orange);
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 8px var(--bellroy-orange);
            animation: pulseDot 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulseDot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.85); }
        }

        .nav-link-custom, .nav-link-admin {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 600;
            text-transform: uppercase;
            color: #c5c1ba !important;
            padding: 7px 15px !important;
            transition: var(--transition-smooth);
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 0.78rem;
            letter-spacing: 0.05em;
            position: relative;
            text-decoration: none !important;
            border-radius: 3px;
        }

        [data-bs-theme="light"] .nav-link-custom,
        [data-bs-theme="light"] .nav-link-admin {
            color: #4b5563 !important;
        }

        .nav-link-custom:hover, .nav-link-admin:hover {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.08);
            transform: translateY(-1px);
        }

        [data-bs-theme="light"] .nav-link-custom:hover,
        [data-bs-theme="light"] .nav-link-admin:hover {
            color: #111827 !important;
            background: rgba(0, 0, 0, 0.05);
        }

        .nav-link-custom.active, .nav-link-admin.active {
            color: #ffffff !important;
            background: var(--bellroy-orange) !important;
            box-shadow: 0 2px 10px rgba(205, 76, 32, 0.35);
        }

        /* Cyber-Capsule Button (Notifications) */
        .nav-cart-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 4px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #ffffff !important;
            position: relative;
            transition: var(--transition-smooth);
            text-decoration: none !important;
        }

        [data-bs-theme="light"] .nav-cart-pill {
            background: rgba(0, 0, 0, 0.04);
            border-color: rgba(0, 0, 0, 0.1);
            color: #111827 !important;
        }

        .nav-cart-pill:hover {
            border-color: var(--bellroy-orange);
            background: rgba(205, 76, 32, 0.12);
            color: var(--bellroy-orange) !important;
            transform: translateY(-1px);
        }

        .cart-badge-glowing {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--bellroy-orange);
            color: #ffffff;
            font-family: 'Space Mono', monospace;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 5px;
            border-radius: 50rem;
            box-shadow: 0 0 10px rgba(205, 76, 32, 0.6);
            line-height: 1;
        }

        /* User Profile Pill */
        .user-profile-pill {
            background: rgba(255, 255, 255, 0.08) !important;
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.16) !important;
            color: #ffffff !important;
            border-radius: 50rem !important;
            padding: 4px 14px 4px 5px !important;
            transition: var(--transition-smooth);
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
        }

        [data-bs-theme="light"] .user-profile-pill {
            background: rgba(0, 0, 0, 0.05) !important;
            border-color: rgba(0, 0, 0, 0.1) !important;
            color: #111827 !important;
        }

        .user-profile-pill:hover {
            background: rgba(205, 76, 32, 0.15) !important;
            border-color: var(--bellroy-orange) !important;
            transform: translateY(-1px);
        }

        /* Dropdown Menus */
        .dropdown-menu {
            border-radius: 2px !important;
            border: 1px solid var(--border-color) !important;
            box-shadow: 5px 5px 0px rgba(0, 0, 0, 0.12) !important;
            background: var(--card-bg) !important;
            padding: 6px !important;
        }

        [data-bs-theme="dark"] .dropdown-menu {
            border-color: #2e2e34 !important;
            box-shadow: 5px 5px 0px rgba(0, 0, 0, 0.7) !important;
            background: #1a1a1e !important;
        }

        .dropdown-item {
            border-radius: 2px !important;
            font-size: 0.86rem;
            font-weight: 600;
            padding: 8px 14px !important;
            transition: var(--transition-smooth);
            color: var(--text-main) !important;
            display: flex;
            align-items: center;
        }

        .dropdown-item:hover,
        .dropdown-item:focus {
            background-color: var(--surface-muted) !important;
            color: var(--bellroy-orange) !important;
            padding-left: 18px !important;
        }

        [data-bs-theme="dark"] .dropdown-item:hover {
            background-color: #23232b !important;
            color: var(--bellroy-orange) !important;
        }

        .dropdown-divider {
            border-color: var(--border-color) !important;
            opacity: 0.6;
        }

        /* Admin Panels / Cards */
        .card {
            border: 1px solid var(--border-color) !important;
            border-radius: 2px !important;
            background: var(--card-bg) !important;
            box-shadow: var(--card-shadow) !important;
            overflow: hidden;
            margin-bottom: 24px;
            position: relative;
            transition: var(--transition-smooth);
        }

        .border, 
        .border-top, 
        .border-bottom, 
        .border-start, 
        .border-end {
            border-color: var(--border-color) !important;
        }

        .card-header {
            background: transparent !important;
            border-bottom: 1px solid var(--border-color) !important;
            padding: 16px 22px;
        }

        .card-header h2, .card-header h4, .card-header h5, .card-header h6 {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            color: var(--text-main) !important;
            letter-spacing: -0.02em;
        }

        .card-body {
            padding: 22px;
            background: transparent !important;
        }

        /* Tactical Telemetry Stat Widgets */
        .stat-card-tactical {
            border: 1px solid var(--border-color) !important;
            border-radius: 2px !important;
            background: var(--card-bg) !important;
            box-shadow: var(--card-shadow) !important;
            position: relative;
            overflow: hidden;
            transition: var(--transition-smooth);
        }
        .stat-card-tactical:hover {
            border-color: var(--bellroy-orange) !important;
            transform: translate(-2px, -2px);
            box-shadow: 4px 4px 0px rgba(205, 76, 32, 0.25) !important;
        }
        .stat-card-tactical::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--bellroy-orange);
            opacity: 0.85;
        }
        .stat-icon-box {
            width: 54px;
            height: 54px;
            border-radius: 2px;
            clip-path: polygon(0 0, calc(100% - 7px) 0, 100% 7px, 100% 100%, 7px 100%, 0 calc(100% - 7px));
            background: var(--surface-muted);
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition-smooth);
        }
        .stat-card-tactical:hover .stat-icon-box {
            background: var(--bellroy-orange);
            border-color: var(--bellroy-orange);
            color: #ffffff !important;
        }
        .stat-card-tactical:hover .stat-icon-box i {
            color: #ffffff !important;
        }

        /* Forms, Controls, Selects */
        .form-label {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: var(--text-main) !important;
            margin-bottom: 6px;
        }

        .form-control, .form-select, .input-group-text {
            background-color: var(--card-bg) !important;
            color: var(--text-main) !important;
            border-radius: 2px !important;
            padding: 9px 13px;
            border: 1px solid var(--border-color) !important;
            font-size: 0.9rem;
            transition: var(--transition-smooth);
        }
        
        .form-control:focus, .form-select:focus {
            background-color: var(--card-bg) !important;
            color: var(--text-main) !important;
            border-color: var(--bellroy-orange) !important;
            box-shadow: 0 0 0 2px rgba(205, 76, 32, 0.2) !important;
        }

        .input-group-text {
            background-color: var(--surface-muted) !important;
            color: var(--text-muted) !important;
            font-family: 'Space Mono', monospace;
            font-size: 0.85rem;
        }

        /* Admin Tables */
        .table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            color: var(--text-main) !important;
            border-color: var(--border-color) !important;
            margin-bottom: 0;
        }

        .table thead th {
            background-color: var(--surface-muted) !important;
            color: var(--text-main) !important;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.76rem;
            letter-spacing: 0.06em;
            border: none;
            padding: 13px 16px;
            border-bottom: 2px solid var(--border-color) !important;
            vertical-align: middle;
        }

        .table tbody td {
            padding: 13px 16px;
            border-bottom: 1px solid var(--border-color) !important;
            background-color: transparent;
            color: var(--text-main) !important;
            vertical-align: middle;
        }

        .table-hover tbody tr:hover td {
            background-color: rgba(205, 76, 32, 0.04) !important;
        }

        [data-bs-theme="dark"] .table-hover tbody tr:hover td {
            background-color: rgba(205, 76, 32, 0.08) !important;
        }

        /* Buttons — Angular Cyber-Chiseled */
        .btn {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 0.82rem;
            border-radius: 2px !important;
            clip-path: polygon(0 0, calc(100% - 7px) 0, 100% 7px, 100% 100%, 7px 100%, 0 calc(100% - 7px)) !important;
            padding: 8px 18px;
            transition: var(--transition-smooth);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            text-decoration: none !important;
            box-shadow: 2px 2px 0px rgba(0, 0, 0, 0.15);
            position: relative;
            overflow: hidden;
            white-space: nowrap !important;
            vertical-align: middle;
        }

        .btn:hover {
            transform: translate(-1px, -1px);
            box-shadow: 3px 3px 0px rgba(0, 0, 0, 0.25);
        }

        .btn:active {
            transform: translate(1px, 1px);
            box-shadow: 1px 1px 0px rgba(0, 0, 0, 0.1);
        }

        .btn-sm {
            padding: 0 12px !important;
            font-size: 0.74rem !important;
            height: 32px !important;
            min-height: 32px !important;
            max-height: 32px !important;
            line-height: 1 !important;
            white-space: nowrap !important;
            clip-path: polygon(0 0, calc(100% - 5px) 0, 100% 5px, 100% 100%, 5px 100%, 0 calc(100% - 5px)) !important;
        }

        .btn.rounded-pill {
            border-radius: 50rem !important;
            clip-path: none !important;
        }

        .btn-premium, .btn-primary {
            background: var(--bellroy-orange) !important;
            color: #ffffff !important;
            border: 1px solid var(--bellroy-orange) !important;
            box-shadow: 3px 3px 0px rgba(0, 0, 0, 0.2) !important;
        }

        .btn-premium:hover, .btn-primary:hover {
            background: var(--bellroy-orange-hover) !important;
            border-color: var(--bellroy-orange-hover) !important;
            color: #ffffff !important;
            box-shadow: 4px 4px 0px rgba(205, 76, 32, 0.35) !important;
        }

        .btn-dark {
            background: #151413 !important;
            border: 1px solid #151413 !important;
            color: #ffffff !important;
            box-shadow: 3px 3px 0px rgba(0, 0, 0, 0.2) !important;
        }

        .btn-dark:hover {
            background: #252321 !important;
            border-color: #252321 !important;
            color: #ffffff !important;
            box-shadow: 4px 4px 0px rgba(0, 0, 0, 0.3) !important;
        }

        .btn-outline-premium {
            background: transparent !important;
            color: var(--text-main) !important;
            border: 1px solid var(--border-color) !important;
            box-shadow: 2px 2px 0px rgba(0, 0, 0, 0.05) !important;
        }

        .btn-outline-premium:hover {
            background: var(--surface-muted) !important;
            border-color: var(--bellroy-orange) !important;
            color: var(--bellroy-orange) !important;
            box-shadow: 3px 3px 0px rgba(205, 76, 32, 0.25) !important;
        }

        .btn-outline-dark {
            background: transparent !important;
            border: 1px solid #151413 !important;
            color: var(--text-main) !important;
            box-shadow: 2px 2px 0px rgba(0, 0, 0, 0.05) !important;
        }

        .btn-outline-dark:hover {
            background: #151413 !important;
            color: #ffffff !important;
            box-shadow: 3px 3px 0px rgba(0, 0, 0, 0.2) !important;
        }

        [data-bs-theme="dark"] .btn-outline-dark,
        [data-bs-theme="dark"] .btn-outline-secondary {
            border-color: #3f3f46 !important;
            color: #f0f0f3 !important;
        }
        [data-bs-theme="dark"] .btn-outline-dark:hover,
        [data-bs-theme="dark"] .btn-outline-secondary:hover {
            background-color: #3f3f46 !important;
            color: #ffffff !important;
        }

        .btn-link {
            clip-path: none !important;
            box-shadow: none !important;
            padding: 2px 6px !important;
        }
        .btn-link:hover {
            transform: none !important;
            box-shadow: none !important;
        }

        /* Badges — Tactical Chamfered */
        .badge {
            font-family: 'Space Mono', monospace;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-radius: 1px !important;
            clip-path: polygon(0 0, calc(100% - 5px) 0, 100% 5px, 100% 100%, 5px 100%, 0 calc(100% - 5px)) !important;
            padding: 4px 8px;
            font-size: 0.72rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .badge.rounded-pill {
            border-radius: 1px !important;
        }

        .badge-terracotta {
            background-color: var(--bellroy-orange-subtle) !important;
            color: var(--bellroy-orange) !important;
            border: 1px solid rgba(205, 76, 32, 0.3) !important;
        }
        [data-bs-theme="dark"] .badge-terracotta {
            background-color: rgba(205, 76, 32, 0.22) !important;
            color: #fdba74 !important;
            border: 1px solid rgba(205, 76, 32, 0.4) !important;
        }

        .badge-sage {
            background-color: var(--bellroy-sage-subtle) !important;
            color: var(--bellroy-sage) !important;
            border: 1px solid rgba(78, 121, 105, 0.3) !important;
        }
        [data-bs-theme="dark"] .badge-sage {
            background-color: rgba(78, 121, 105, 0.25) !important;
            color: #86efac !important;
            border: 1px solid rgba(78, 121, 105, 0.4) !important;
        }

        .badge-premium {
            background-color: #151413 !important;
            color: #ffffff !important;
            border: 1px solid #3f3f46 !important;
        }
        [data-bs-theme="dark"] .badge-premium {
            background-color: #27272a !important;
            color: #f4f4f5 !important;
            border: 1px solid #52525b !important;
        }

        /* Nav Pills (Warehouse Tabs, Profile Tabs) */
        .nav-pills .nav-link {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            font-size: 0.82rem;
            border-radius: 2px !important;
            clip-path: polygon(0 0, calc(100% - 6px) 0, 100% 6px, 100% 100%, 6px 100%, 0 calc(100% - 6px)) !important;
            padding: 8px 18px;
            color: var(--text-muted);
            border: 1px solid transparent;
            transition: var(--transition-smooth);
        }

        .nav-pills .nav-link:hover {
            color: var(--text-main);
            background: var(--surface-muted);
        }

        .nav-pills .nav-link.active {
            background: var(--bellroy-orange) !important;
            color: #ffffff !important;
            border-color: var(--bellroy-orange) !important;
            box-shadow: 2px 2px 0px rgba(0, 0, 0, 0.25);
        }

        /* Modals */
        .modal-content {
            border-radius: 2px !important;
            border: 1px solid var(--border-color) !important;
            box-shadow: 8px 8px 0px rgba(0, 0, 0, 0.25) !important;
            background: var(--card-bg) !important;
        }
        [data-bs-theme="dark"] .modal-content {
            border-color: #2e2e34 !important;
            box-shadow: 8px 8px 0px rgba(0, 0, 0, 0.75) !important;
            background: #1a1a1e !important;
        }
        .modal-header {
            border-bottom: 1px solid var(--border-color) !important;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            padding: 16px 22px;
        }
        .modal-footer {
            border-top: 1px solid var(--border-color) !important;
            padding: 14px 22px;
        }

        /* Alerts */
        .alert {
            border-radius: 2px !important;
            clip-path: polygon(0 0, calc(100% - 8px) 0, 100% 8px, 100% 100%, 8px 100%, 0 calc(100% - 8px)) !important;
            border: 1px solid var(--border-color) !important;
            box-shadow: 3px 3px 0px rgba(0, 0, 0, 0.06);
            font-family: 'Inter', sans-serif;
        }

        /* Tactical Pagination */
        .pagination {
            gap: 8px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .pagination .page-item .page-link {
            background: var(--card-bg) !important;
            border: 1px solid var(--border-color) !important;
            color: var(--text-main) !important;
            border-radius: 2px !important;
            clip-path: polygon(0 0, calc(100% - 6px) 0, 100% 6px, 100% 100%, 6px 100%, 0 calc(100% - 6px)) !important;
            min-width: 38px;
            min-height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Space Mono', monospace;
            font-weight: 700;
            font-size: 0.85rem;
            transition: var(--transition-smooth);
            padding: 0 12px;
            text-decoration: none !important;
            box-shadow: 2px 2px 0px rgba(0, 0, 0, 0.06);
        }

        .pagination .page-item .page-link:hover {
            background: var(--surface-muted) !important;
            border-color: var(--bellroy-orange) !important;
            color: var(--bellroy-orange) !important;
            transform: translate(-1px, -1px);
            box-shadow: 3px 3px 0px rgba(205, 76, 32, 0.25);
        }

        .pagination .page-item.active .page-link {
            background: var(--bellroy-orange) !important;
            border-color: var(--bellroy-orange) !important;
            color: #ffffff !important;
            box-shadow: 3px 3px 0px rgba(0, 0, 0, 0.25) !important;
        }

        .pagination .page-item.disabled .page-link {
            background: var(--surface-muted) !important;
            border-color: var(--border-color) !important;
            color: var(--text-muted) !important;
            opacity: 0.4;
            cursor: not-allowed;
        }

        [data-bs-theme="dark"] .pagination .page-item .page-link {
            background: #1e1e24 !important;
            border-color: #33333d !important;
            color: #f0f0f5 !important;
            box-shadow: 2px 2px 0px rgba(0, 0, 0, 0.6);
        }

        [data-bs-theme="dark"] .pagination .page-item .page-link:hover {
            background: #2a2a34 !important;
            border-color: var(--bellroy-orange) !important;
            color: var(--bellroy-orange) !important;
        }

        [data-bs-theme="dark"] .pagination .page-item.active .page-link {
            background: var(--bellroy-orange) !important;
            border-color: var(--bellroy-orange) !important;
            color: #ffffff !important;
        }



        @keyframes slideIn {
            from { transform: translateY(16px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .animate-slide-in {
            animation: slideIn 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Admin Header Navbar (Avant-Garde Floating Dynamic Glass) -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
        <div class="container-fluid admin-nav-container">
            <a class="navbar-brand navbar-brand-custom text-decoration-none" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('images/logo.png') }}" alt="LapGearZone Logo" height="80" style="border-radius: 8px; margin-right: -4px; margin-top: -20px; margin-bottom: -20px; filter: brightness(0) invert(1);"> 
                <span>LapGearZone</span>
                <span class="brand-live-badge d-none d-sm-inline-flex">
                    <span class="brand-pulse-dot"></span>ADMIN OS 2026
                </span>
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar"
                aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link-custom text-decoration-none {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link-custom text-decoration-none {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                            <i class="bi bi-box-seam"></i> Sản Phẩm
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link-custom text-decoration-none {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                            <i class="bi bi-folder"></i> Danh Mục
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav ms-auto text-center text-lg-start align-items-center">
                    
                    <!-- Theme Switcher -->
                    <li class="nav-item me-2 d-flex align-items-center">
                        @include('partials.theme-switcher')
                    </li>
                    
                    <!-- Thông báo (Vấn đề của shop: Kho & Sự cố) -->
                    @php
                        $outOfStockCount = isset($shopIssues['out_of_stock']) ? $shopIssues['out_of_stock']->count() : 0;
                        $deliveryIssueCount = isset($shopIssues['delivery_issues']) ? $shopIssues['delivery_issues']->count() : 0;
                        $totalIssues = $outOfStockCount + $deliveryIssueCount;
                    @endphp
                    <li class="nav-item dropdown me-3">
                        <a class="nav-cart-pill text-decoration-none position-relative" href="#" id="shopIssuesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="Thông báo kho hàng & sự cố">
                            <i class="bi bi-bell fs-5"></i>
                            @if($totalIssues > 0)
                                <span class="cart-badge-glowing" style="background: #ef4444; box-shadow: 0 0 10px rgba(239, 68, 68, 0.6);">
                                    {{ $totalIssues }}
                                </span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border shadow-lg p-2 animate-slide-in" aria-labelledby="shopIssuesDropdown" style="min-width: 280px;">
                            <li><h6 class="dropdown-header fw-bold border-bottom pb-2 mb-2 font-monospace text-uppercase" style="font-size: 0.75rem; color: var(--text-main);">Thông báo kho hàng & Sự cố</h6></li>
                            
                            @if($totalIssues > 0)
                                @if($outOfStockCount > 0)
                                    @foreach($shopIssues['out_of_stock'] as $item)
                                    <li>
                                        <a class="dropdown-item py-2 px-3 text-warning d-flex align-items-start" href="{{ route('admin.products.edit', $item->id) }}" style="white-space: normal;">
                                            <i class="bi bi-exclamation-triangle-fill mt-1 me-2 flex-shrink-0"></i>
                                            <div>
                                                <span class="d-block fw-bold" style="font-size: 0.85rem;">Sắp hết hàng (còn {{ $item->quantity }})</span>
                                                <span class="text-secondary" style="font-size: 0.8rem;">{{ $item->name }}</span>
                                            </div>
                                        </a>
                                    </li>
                                    @endforeach
                                @endif

                                @if($deliveryIssueCount > 0)
                                    @foreach($shopIssues['delivery_issues'] as $issue)
                                    <li>
                                        <a class="dropdown-item py-2 px-3 text-danger d-flex align-items-start" href="{{ route('admin.attendance') }}" style="white-space: normal;">
                                            <i class="bi bi-exclamation-octagon-fill mt-1 me-2 flex-shrink-0"></i>
                                            <div>
                                                <span class="d-block fw-bold" style="font-size: 0.85rem;">Sự cố giao hàng: #ORD-{{ str_pad($issue->id, 5, '0', STR_PAD_LEFT) }}</span>
                                                <span class="text-secondary" style="font-size: 0.8rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" title="{{ $issue->delivery_issue }}">{{ $issue->delivery_issue }}</span>
                                            </div>
                                        </a>
                                    </li>
                                    @endforeach
                                @endif
                            @else
                                <li>
                                    <span class="dropdown-item text-secondary py-3 text-center" style="font-size: 0.88rem;">
                                        <i class="bi bi-check-circle text-success me-1"></i> Hệ thống ổn định, không có sự cố
                                    </span>
                                </li>
                            @endif
                        </ul>
                    </li>

                    <!-- User Profile Dropdown Pill -->
                    <li class="nav-item dropdown">
                        <a class="user-profile-pill dropdown-toggle text-decoration-none d-flex align-items-center shadow-sm" href="#" id="adminUserDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            @if(Auth::user()?->avatar)
                                <img src="{{ Auth::user()?->avatar_url }}" alt="Avatar" class="rounded-circle me-2" style="width: 28px; height: 28px; object-fit: cover; border: 1px solid rgba(255,255,255,0.3);">
                            @else
                                <i class="bi bi-person-circle me-2 fs-5 opacity-75"></i>
                            @endif
                            <span class="fw-bold text-uppercase ms-1" style="font-size: 0.82rem; letter-spacing: 0.5px;">{{ Auth::user()?->name ?? 'Admin' }}</span>
                            <i class="bi bi-award-fill text-warning ms-2 me-1 fs-6" title="Quản trị viên"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border shadow-lg p-2 animate-slide-in" aria-labelledby="adminUserDropdown" style="min-width: 220px;">
                            <li>
                                <a class="dropdown-item text-dark {{ request()->routeIs('admin.profile') ? 'fw-bold text-primary' : '' }}" href="{{ route('admin.profile') }}">
                                    <i class="bi bi-person-vcard me-2"></i>Hồ sơ cá nhân
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-dark {{ request()->routeIs('admin.attendance') ? 'fw-bold text-primary' : '' }}" href="{{ route('admin.attendance') }}">
                                    <i class="bi bi-calendar2-check me-2"></i>Nhân sự &amp; Chấm công
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-dark {{ request()->routeIs('admin.news.*') ? 'fw-bold text-primary' : '' }}" href="{{ route('admin.news.index') }}">
                                    <i class="bi bi-newspaper me-2"></i>Tin tức công nghệ
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-dark {{ request()->routeIs('admin.coupons.*') ? 'fw-bold text-primary' : '' }}" href="{{ route('admin.coupons.index') }}">
                                    <i class="bi bi-ticket-perforated me-2"></i>Voucher &amp; Mã giảm giá
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-dark {{ request()->routeIs('admin.events.*') ? 'fw-bold text-primary' : '' }}" href="{{ route('admin.events.index') }}">
                                    <i class="bi bi-calendar-event me-2"></i>Quản lý Sự Kiện Flash
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-dark {{ request()->routeIs('admin.contacts') ? 'fw-bold text-primary' : '' }}" href="{{ route('admin.contacts') }}">
                                    <i class="bi bi-envelope-open me-2"></i>Góp ý Khách hàng
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-dark {{ request()->routeIs('admin.settings.*') ? 'fw-bold text-primary' : '' }}" href="{{ route('admin.settings.index') }}">
                                    <i class="bi bi-wallet2 me-2"></i>Cổng thanh toán PayOS
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-dark {{ request()->routeIs('admin.banner.*') ? 'fw-bold text-primary' : '' }}" href="{{ route('admin.banner.index') }}">
                                    <i class="bi bi-play-btn-fill me-2"></i>Banner Video Trang Chủ
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-dark" href="{{ url('/') }}">
                                    <i class="bi bi-globe me-2"></i>Xem trang bán hàng
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger fw-bold" href="{{ route('logout') }}"
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
            <div class="alert alert-success alert-dismissible border-0 p-3 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                <div>
                    <strong>Thành công!</strong> {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible border-0 p-3 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-4 me-3"></i>
                <div>
                    <strong>Lỗi!</strong> {{ session('error') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Modals Section -->
    @yield('modals')
    @include('components.cropper-modal')

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Theme Switcher Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const themeToggleBtn = document.getElementById('themeSwitcherBtn');
            const iconSun = document.querySelector('.theme-icon-sun');
            const iconMoon = document.querySelector('.theme-icon-moon');
            const textLight = document.querySelector('.theme-text-light');
            const textDark = document.querySelector('.theme-text-dark');

            const getCurrentTheme = () => document.documentElement.getAttribute('data-bs-theme') || 'light';

            const updateTelemetryUI = (theme) => {
                if (theme === 'dark') {
                    iconSun?.classList.remove('d-none');
                    iconMoon?.classList.add('d-none');
                    textDark?.classList.remove('d-none');
                    textLight?.classList.add('d-none');
                } else {
                    iconSun?.classList.add('d-none');
                    iconMoon?.classList.remove('d-none');
                    textLight?.classList.remove('d-none');
                    textDark?.classList.add('d-none');
                }
            };

            const setTheme = (theme) => {
                document.documentElement.setAttribute('data-bs-theme', theme);
                localStorage.setItem('laptopking_theme', theme);
                updateTelemetryUI(theme);
            };

            updateTelemetryUI(getCurrentTheme());

            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', () => {
                    const currentTheme = getCurrentTheme();
                    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                    setTheme(newTheme);
                });
            }

            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                if (!localStorage.getItem('laptopking_theme')) {
                    setTheme(e.matches ? 'dark' : 'light');
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
