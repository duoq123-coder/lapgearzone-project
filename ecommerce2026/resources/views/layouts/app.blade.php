<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SCRIPT CHỐNG FOUC (Anti-FOUC: Chạy ngay trước khi render CSS/DOM) -->
    <script>
        (() => {
            const storedTheme = localStorage.getItem('laptopking_theme');
            const preferredTheme = storedTheme ? storedTheme : (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-bs-theme', preferredTheme);
        })();
    </script>

    <title>@yield('title', 'LapGear Zone') - Cửa Hàng Công Nghệ</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-white.png') }}">
    
    <!-- Google Fonts: Inter, Space Grotesk, Space Mono, Chakra Petch -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:ital,wght@0,500;0,600;0,700;1,600&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Space+Grotesk:wght@500;600;700&family=Space+Mono:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom Style Sheet (Bellroy Considered Design System) -->
    <style>
        :root {
            /* Bellroy Brand Color Palette */
            --bellroy-orange: #cd4c20;
            --bellroy-orange-hover: #b85021;
            --bellroy-orange-subtle: #fbeee8;
            --bellroy-sage: #4e7969;
            --bellroy-sage-subtle: #edf5f1;
            --bellroy-amber: #d97706;
            --bellroy-charcoal: #1c1a19;
            --bellroy-dark: #2b2826;
            
            /* Theme Aliases */
            --primary-gradient: #1c1a19;
            --accent-gold: #cd4c20;
            --accent-gold-light: #fbeee8;
            --accent-gold-dark: #b85021;
            --accent-navy: #1c1a19;
            
            /* Surfaces & Backgrounds */
            --card-bg: #ffffff;
            --body-bg: #ffffff;
            --hero-bg: #ffffff;
            --surface-muted: #f8f9fa;
            --surface-sand: #f1f3f5;
            
            /* Typography Colors */
            --text-main: #18181b;
            --text-muted: #71717a;
            --text-secondary: #a1a1aa;
            
            /* Navigation & Borders */
            --navbar-bg: #111827;
            --border-color: #e4e4e7;
            --border-color-subtle: #f4f4f5;
            --card-shadow: 2px 2px 0px rgba(0, 0, 0, 0.04);
            --card-hover-shadow: 4px 4px 0px rgba(205, 76, 32, 0.18);
            --transition-smooth: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* ========================================================= */
        /* INDUSTRIAL BRUTALIST & TACTICAL TELEMETRY THEME SYSTEM   */
        /* ========================================================= */
        .industrial-theme-switcher-wrapper {
            position: relative;
            user-select: none;
        }

        .industrial-theme-btn {
            background: #151413;
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
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.35);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            outline: none;
        }

        .industrial-theme-btn:hover {
            border-color: var(--bellroy-orange);
            background: #1f1d1b;
            color: var(--bellroy-orange);
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(205, 76, 32, 0.25);
        }

        .industrial-theme-btn:active {
            transform: translateY(1px) scale(0.98);
        }

        .telemetry-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            display: inline-block;
            transition: all 0.25s ease;
        }

        /* Light Mode Telemetry State */
        [data-bs-theme="light"] .telemetry-dot {
            background: #ff7b00;
            box-shadow: 0 0 8px #ff7b00, 0 0 2px #ffffff;
        }

        /* Dark Mode Telemetry State */
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
            border-radius: 3px;
            font-size: 0.8rem;
            transition: all 0.25s ease;
        }

        .industrial-theme-btn:hover .telemetry-icon-box {
            background: var(--bellroy-orange);
            border-color: var(--bellroy-orange);
            color: #ffffff;
        }

        /* ========================================================= */
        /* DARK THEME SYSTEM - HIGH CONTRAST & READABILITY OVERRIDES */
        /* ========================================================= */
        [data-bs-theme="dark"] {
            --card-bg: #1A1A1E;
            --body-bg: #121214;
            --hero-bg: #121214;
            --surface-muted: #1A1A1E;
            --surface-sand: #24242a;
            
            --text-main: #f0f0f3;
            --text-muted: #a3a3ad;
            --text-secondary: #888894;
            
            --navbar-bg: #0e0e10;
            --border-color: #2E2E34;
            --border-color-subtle: #24242a;
            --card-shadow: 3px 3px 0px #000000;
            --card-hover-shadow: 4px 4px 0px #000000;
        }

        [data-bs-theme="dark"] .industrial-theme-btn {
            background: #17171a;
            border-color: rgba(74, 246, 38, 0.4);
            color: #f0f0f3;
        }

        [data-bs-theme="dark"] .industrial-theme-btn:hover {
            border-color: #4af626;
            box-shadow: 0 0 12px rgba(74, 246, 38, 0.25);
        }

        /* All surfaces & cards */
        [data-bs-theme="dark"] .bg-white,
        [data-bs-theme="dark"] .card,
        [data-bs-theme="dark"] .card-premium {
            background-color: var(--card-bg) !important;
            border-color: var(--border-color) !important;
            color: var(--text-main) !important;
        }

        [data-bs-theme="dark"] .bg-light {
            background-color: var(--surface-muted) !important;
            border-color: var(--border-color) !important;
            color: var(--text-main) !important;
        }

        /* Typography High Contrast */
        [data-bs-theme="dark"] .text-dark,
        [data-bs-theme="dark"] h1,
        [data-bs-theme="dark"] h2,
        [data-bs-theme="dark"] h3,
        [data-bs-theme="dark"] h4,
        [data-bs-theme="dark"] h5,
        [data-bs-theme="dark"] h6,
        [data-bs-theme="dark"] .serif-title,
        [data-bs-theme="dark"] .editorial-heading,
        [data-bs-theme="dark"] .display-font {
            color: var(--text-main) !important;
        }

        [data-bs-theme="dark"] .text-secondary {
            color: #c8c6c0 !important;
        }

        [data-bs-theme="dark"] .text-muted {
            color: #b0aead !important;
        }

        /* Form Inputs, Selects, Textareas */
        [data-bs-theme="dark"] .form-control,
        [data-bs-theme="dark"] .form-select,
        [data-bs-theme="dark"] input[type="text"],
        [data-bs-theme="dark"] input[type="number"],
        [data-bs-theme="dark"] input[type="tel"],
        [data-bs-theme="dark"] input[type="email"],
        [data-bs-theme="dark"] input[type="password"],
        [data-bs-theme="dark"] textarea {
            background-color: #1a1a1e !important;
            border-color: #2e2e34 !important;
            color: #f0f0f3 !important;
        }

        [data-bs-theme="dark"] .form-control:focus,
        [data-bs-theme="dark"] .form-select:focus,
        [data-bs-theme="dark"] input:focus,
        [data-bs-theme="dark"] textarea:focus {
            background-color: #222228 !important;
            border-color: var(--bellroy-orange) !important;
            color: #ffffff !important;
            box-shadow: 0 0 0 2px rgba(205, 76, 32, 0.25) !important;
        }

        [data-bs-theme="dark"] .input-group-text {
            background-color: #1a1a1e !important;
            border-color: #2e2e34 !important;
            color: #a3a3ad !important;
        }

        /* Input Placeholders - High Contrast */
        [data-bs-theme="dark"] input::placeholder,
        [data-bs-theme="dark"] textarea::placeholder,
        [data-bs-theme="dark"] .form-control::placeholder,
        [data-bs-theme="dark"] .form-select::placeholder {
            color: #a0a0b0 !important;
            opacity: 1 !important;
        }

        /* Search / Filter Bar */
        [data-bs-theme="dark"] .search-filter-bar {
            background: var(--card-bg) !important;
            border-color: var(--border-color) !important;
        }

        [data-bs-theme="dark"] .search-filter-bar input,
        [data-bs-theme="dark"] .search-filter-bar select,
        [data-bs-theme="dark"] .search-filter-bar .input-group-text {
            background-color: #1a1a1e !important;
            color: #f0f0f3 !important;
            border-color: #2e2e34 !important;
        }

        [data-bs-theme="dark"] .filter-chip {
            background: var(--surface-muted) !important;
            border-color: var(--border-color) !important;
            color: var(--text-main) !important;
        }

        /* Chatbot AI Widget */
        [data-bs-theme="dark"] #aiChatBox {
            background-color: #141416 !important;
            border-color: #26262a !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.75) !important;
        }

        [data-bs-theme="dark"] #chatBody {
            background: #0c0c0d !important;
        }

        [data-bs-theme="dark"] #chatBody .bg-white,
        [data-bs-theme="dark"] .chat-ai-msg {
            background-color: #1a1a1e !important;
            color: #f0f0f3 !important;
            border-color: #2e2e34 !important;
        }

        [data-bs-theme="dark"] #aiChatInput {
            background-color: #1a1a1e !important;
            color: #f0f0f3 !important;
            border-color: #2e2e34 !important;
        }

        /* Modals & Dropdowns */
        [data-bs-theme="dark"] .modal-content,
        [data-bs-theme="dark"] .dropdown-menu {
            background-color: var(--card-bg) !important;
            border-color: var(--border-color) !important;
            color: var(--text-main) !important;
        }

        [data-bs-theme="dark"] .dropdown-item {
            color: var(--text-main) !important;
        }

        [data-bs-theme="dark"] .dropdown-item:hover {
            background-color: var(--surface-muted) !important;
        }

        /* Badges & Pills */
        [data-bs-theme="dark"] .badge.bg-light {
            background-color: #26262b !important;
            color: #f0f0f3 !important;
            border-color: #35353d !important;
        }

        [data-bs-theme="dark"] .badge.border {
            border-color: #35353d !important;
            color: #f0f0f3 !important;
        }

        [data-bs-theme="dark"] .badge.bg-dark {
            background-color: #222228 !important;
            color: #f0f0f3 !important;
            border: 1px solid #363640 !important;
        }

        /* Tables in Dark Mode - Exhaustive Specificity */
        [data-bs-theme="dark"] .table,
        [data-bs-theme="dark"] .table > :not(caption) > * > *,
        [data-bs-theme="dark"] .table th,
        [data-bs-theme="dark"] .table td,
        [data-bs-theme="dark"] table th,
        [data-bs-theme="dark"] table td {
            --bs-table-bg: #141416 !important;
            --bs-table-color: #f0f0f3 !important;
            --bs-table-accent-bg: transparent !important;
            --bs-table-hover-bg: #1e1e24 !important;
            --bs-table-hover-color: #ffffff !important;
            --bs-table-border-color: #26262a !important;
            background-color: #141416 !important;
            color: #f0f0f3 !important;
            border-color: #26262a !important;
            box-shadow: none !important;
        }

        [data-bs-theme="dark"] .table thead th,
        [data-bs-theme="dark"] table thead th {
            background-color: #18181c !important;
            color: #a3a3ad !important;
            border-color: #26262a !important;
            box-shadow: none !important;
        }

        [data-bs-theme="dark"] .table tbody tr,
        [data-bs-theme="dark"] table tbody tr {
            background-color: #141416 !important;
            color: #f0f0f3 !important;
            border-color: #26262a !important;
        }

        [data-bs-theme="dark"] .table tbody tr:hover > *,
        [data-bs-theme="dark"] table tbody tr:hover > td {
            background-color: #1e1e24 !important;
            color: #ffffff !important;
            box-shadow: none !important;
        }

        [data-bs-theme="dark"] .table td strong,
        [data-bs-theme="dark"] .table td span,
        [data-bs-theme="dark"] .table td a {
            color: #f0f0f3 !important;
        }

        [data-bs-theme="dark"] .btn-outline-dark {
            color: #f0f0f3 !important;
            border-color: #3e3e48 !important;
            background-color: transparent !important;
        }

        [data-bs-theme="dark"] .btn-outline-dark:hover {
            background-color: #282830 !important;
            color: #ffffff !important;
        }

        /* Smooth Theme Transition */
        html, body, .card, .navbar, .modal-content, .dropdown-menu {
            transition: background-color 0.25s cubic-bezier(0.16, 1, 0.3, 1), color 0.25s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }

        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #ffffff;
            background-image: none;
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            line-height: 1.7;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        [data-bs-theme="dark"] body {
            background-color: #121214 !important;
            background-image: none !important;
        }

        /* High-End Angular Card Architecture (Cyber-Industrial Precision) */
        .card-premium, .card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 2px;
            box-shadow: 2px 2px 0px rgba(0, 0, 0, 0.04);
            transition: var(--transition-smooth);
        }

        .card-premium:hover, .card:hover {
            border-color: var(--bellroy-orange);
            box-shadow: 4px 4px 0px rgba(205, 76, 32, 0.18);
            transform: translate(-2px, -2px);
        }

        [data-bs-theme="dark"] .card-premium, 
        [data-bs-theme="dark"] .card {
            background-color: #1A1A1E !important;
            border: 1px solid #2E2E34 !important;
            box-shadow: 3px 3px 0px #000000 !important;
        }

        [data-bs-theme="dark"] .card-premium:hover, 
        [data-bs-theme="dark"] .card:hover {
            border-color: #CD4C20 !important;
            box-shadow: 4px 4px 0px #000000 !important;
        }

        h1, h2, h3, h4, h5, h6, .display-font {
            font-family: 'Space Grotesk', 'Inter', sans-serif;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--text-main);
        }

        .serif-title, .editorial-heading {
            font-family: 'Space Grotesk', 'Inter', sans-serif;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f0ede6;
        }
        ::-webkit-scrollbar-thumb {
            background: #cfc9be;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--bellroy-orange);
        }

        /* ========================================================= */
        /* AVANT-GARDE FLOATING DYNAMIC GLASS NAVBAR (2026 EDITION)  */
        /* ========================================================= */
        .navbar-custom {
            background: rgba(14, 17, 23, 0.88) !important;
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.06);
            padding: 10px 0;
            transition: var(--transition-smooth);
        }

        [data-bs-theme="light"] .navbar-custom {
            background: rgba(255, 255, 255, 0.9) !important;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08) !important;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04), inset 0 1px 0 rgba(255, 255, 255, 0.8);
        }

        .navbar-brand-custom {
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

        [data-bs-theme="light"] .navbar-brand-custom {
            color: #111827 !important;
        }

        [data-bs-theme="light"] .navbar-brand-custom img {
            filter: none !important;
        }

        .navbar-brand-custom:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .brand-live-badge {
            font-family: 'Space Mono', monospace;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #22c55e;
            background: rgba(34, 197, 94, 0.12);
            border: 1px solid rgba(34, 197, 94, 0.3);
            padding: 3px 8px;
            border-radius: 50rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .brand-pulse-dot {
            width: 6px;
            height: 6px;
            background-color: #22c55e;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 8px #22c55e;
            animation: pulseDot 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulseDot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.85); }
        }

        .nav-link-custom {
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

        [data-bs-theme="light"] .nav-link-custom {
            color: #4b5563 !important;
        }

        .nav-link-custom:hover {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.08);
            transform: translateY(-1px);
        }

        [data-bs-theme="light"] .nav-link-custom:hover {
            color: #111827 !important;
            background: rgba(0, 0, 0, 0.05);
        }

        .nav-link-custom.active {
            color: #ffffff !important;
            background: var(--bellroy-orange) !important;
            box-shadow: 0 2px 10px rgba(205, 76, 32, 0.35);
        }

        /* Cart Cyber-Capsule Button */
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
            background: #ffffff !important;
            border-color: #e5e7eb !important;
            color: #111827 !important;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05) !important;
        }

        .user-profile-pill:hover {
            border-color: var(--bellroy-orange) !important;
            box-shadow: 0 2px 12px rgba(205, 76, 32, 0.25) !important;
            transform: translateY(-1px);
        }

        .dropdown-item {
            text-decoration: none !important;
            font-size: 13.5px;
            font-weight: 500;
            color: var(--text-main) !important;
            transition: var(--transition-smooth);
        }
        .dropdown-item:hover {
            background-color: var(--surface-muted) !important;
            color: var(--bellroy-orange) !important;
        }

        /* Buttons — Angular Cyber-Chiseled */
        .btn-premium {
            background: var(--bellroy-orange);
            color: #ffffff !important;
            border: none;
            font-family: 'Space Grotesk', 'Inter', sans-serif;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-size: 12.5px;
            padding: 10px 24px;
            border-radius: 2px;
            clip-path: polygon(0 0, calc(100% - 9px) 0, 100% 9px, 100% 100%, 9px 100%, 0 calc(100% - 9px));
            transition: var(--transition-smooth);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            position: relative;
            overflow: hidden;
            box-shadow: 3px 3px 0px rgba(0, 0, 0, 0.25);
            text-decoration: none !important;
        }

        .btn-premium:hover {
            background: var(--bellroy-orange-hover);
            color: #ffffff !important;
            transform: translate(-1px, -1px);
            box-shadow: 4px 4px 0px rgba(0, 0, 0, 0.35);
            text-decoration: none !important;
        }
        
        .btn-premium:active {
            transform: translate(1px, 1px);
            box-shadow: 1px 1px 0px rgba(0, 0, 0, 0.2);
        }

        .btn-outline-premium {
            background: transparent;
            color: var(--text-main);
            border: 1px solid var(--border-color);
            font-family: 'Space Grotesk', 'Inter', sans-serif;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 12px;
            padding: 8px 22px;
            border-radius: 2px;
            clip-path: polygon(0 0, calc(100% - 7px) 0, 100% 7px, 100% 100%, 7px 100%, 0 calc(100% - 7px));
            transition: var(--transition-smooth);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none !important;
        }

        .btn-outline-premium:hover {
            background: var(--card-bg);
            border-color: var(--text-main);
            color: var(--text-main) !important;
            text-decoration: none !important;
            transform: translate(-1px, -1px);
            box-shadow: 3px 3px 0px rgba(0, 0, 0, 0.08);
        }

        /* Card — Clean Angular Considered Card */
        .card-premium {
            border: 1px solid var(--border-color);
            border-radius: 2px;
            background: var(--card-bg);
            box-shadow: var(--card-shadow);
            transition: var(--transition-smooth);
            overflow: hidden;
            position: relative;
        }

        .card-premium:hover {
            border-color: var(--bellroy-orange);
            box-shadow: 4px 4px 0px rgba(0, 0, 0, 0.08);
            transform: translate(-2px, -2px);
        }

        .card-img-zoom {
            overflow: hidden;
            position: relative;
            background: radial-gradient(ellipse at 50% 65%, rgba(243, 244, 246, 0.95) 0%, rgba(255, 255, 255, 0.6) 80%);
            border-radius: 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        }

        [data-bs-theme="dark"] .card-img-zoom {
            background: radial-gradient(ellipse at 50% 65%, rgba(31, 41, 55, 0.8) 0%, rgba(17, 24, 39, 0.4) 80%);
            border-bottom-color: rgba(255, 255, 255, 0.08);
        }
        
        .card-img-zoom img {
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), filter 0.4s ease;
            transform-origin: center;
            filter: drop-shadow(0 14px 20px rgba(0, 0, 0, 0.22));
        }
        
        .card-premium:hover .card-img-zoom img {
            transform: scale(1.08) translateY(-4px);
            filter: drop-shadow(0 22px 28px rgba(0, 0, 0, 0.28));
        }

        /* Bento Unified Product Card System */
        .bento-laptop-card {
            background: #ffffff;
            border: 1px solid #e4e4e7;
            border-radius: 2px;
            padding: 1.15rem 1rem 1.25rem 1rem;
            box-shadow: 2px 2px 0px rgba(0, 0, 0, 0.04);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }

        [data-bs-theme="dark"] .bento-laptop-card {
            background: #1A1A1E !important;
            border: 1px solid #2E2E34 !important;
            box-shadow: 3px 3px 0px #000000 !important;
        }

        .bento-laptop-card:hover {
            transform: translate(-3px, -3px);
            border-color: var(--bellroy-orange);
            box-shadow: 4px 4px 0px rgba(205, 76, 32, 0.18);
        }

        [data-bs-theme="dark"] .bento-laptop-card:hover {
            border-color: #CD4C20 !important;
            box-shadow: 4px 4px 0px #000000 !important;
        }

        .bento-img-stage {
            background: #ffffff;
            border-radius: 2px;
            padding: 0.5rem 0.5rem 0.75rem 0.5rem;
            height: 240px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            margin-bottom: 1.1rem;
            overflow: hidden;
            border: 1px solid #f4f4f5;
        }

        [data-bs-theme="dark"] .bento-img-stage {
            background: #151518 !important;
            border-color: #26262c !important;
        }

        .bento-img-stage img {
            max-height: 215px;
            max-width: 96%;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 14px 20px rgba(0, 0, 0, 0.14));
            transition: transform 0.4s cubic-bezier(0.32, 0.72, 0, 1);
        }

        .bento-laptop-card:hover .bento-img-stage img {
            transform: scale(1.08) translateY(-4px);
            filter: drop-shadow(0 18px 26px rgba(0, 0, 0, 0.2));
        }

        .bento-cat-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            right: auto;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(0, 0, 0, 0.12);
            padding: 4px 10px;
            border-radius: 1px;
            clip-path: polygon(0 0, calc(100% - 5px) 0, 100% 5px, 100% 100%, 5px 100%, 0 calc(100% - 5px));
            font-family: 'Space Mono', monospace;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #1f2937;
            z-index: 2;
        }

        [data-bs-theme="dark"] .bento-cat-badge {
            background: #121214 !important;
            border-color: #2E2E34 !important;
            color: #e5e7eb !important;
        }

        .bento-wishlist-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            left: auto;
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(0, 0, 0, 0.12);
            border-radius: 1px;
            clip-path: polygon(0 0, calc(100% - 5px) 0, 100% 5px, 100% 100%, 5px 100%, 0 calc(100% - 5px));
            display: inline-flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 2px 2px 0px rgba(0, 0, 0, 0.04);
        }

        .bento-wishlist-btn:hover {
            transform: translate(-1px, -1px);
            border-color: var(--bellroy-orange);
            box-shadow: 3px 3px 0px rgba(205, 76, 32, 0.3);
        }

        [data-bs-theme="dark"] .bento-wishlist-btn {
            background: #121214 !important;
            border-color: #2E2E34 !important;
            color: #e5e7eb !important;
            box-shadow: 2px 2px 0px #000000 !important;
        }

        [data-bs-theme="dark"] .bento-wishlist-btn:hover {
            border-color: #CD4C20 !important;
            color: #CD4C20 !important;
            box-shadow: 3px 3px 0px #000000 !important;
        }

        .bento-product-title {
            font-family: 'Inter', sans-serif;
            font-size: 1.18rem;
            font-weight: 700;
            line-height: 1.35;
            margin-bottom: 0.75rem;
        }

        .bento-product-title a {
            color: #111827;
            text-decoration: none;
            transition: color 0.2s ease;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .bento-product-title a:hover {
            color: var(--bellroy-orange);
        }

        [data-bs-theme="dark"] .bento-product-title a {
            color: #f9fafb;
        }

        [data-bs-theme="dark"] .bento-product-title a:hover {
            color: var(--bellroy-orange);
        }

        .bento-sold-wrap {
            margin-bottom: 0.85rem;
        }

        .bento-sold-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #f8fafc;
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-left: 3px solid var(--bellroy-orange);
            border-radius: 1px;
            padding: 5px 12px;
            font-size: 0.8rem;
            font-family: 'Space Mono', monospace;
            font-weight: 700;
            color: #334155;
        }

        .bento-sold-badge i {
            font-size: 0.95rem;
        }

        .bento-sold-badge strong {
            color: #111827;
            font-weight: 800;
        }

        [data-bs-theme="dark"] .bento-sold-badge {
            background: #151518 !important;
            border: 1px solid #2E2E34 !important;
            border-left: 3px solid #CD4C20 !important;
            color: #cbd5e1 !important;
        }

        [data-bs-theme="dark"] .bento-sold-badge strong {
            color: #f8fafc;
        }

        .bento-price-wrap {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .bento-price {
            font-family: 'Space Grotesk', 'Inter', sans-serif;
            font-size: 1.45rem;
            font-weight: 800;
            color: #111827;
            letter-spacing: -0.02em;
            line-height: 1;
        }

        [data-bs-theme="dark"] .bento-price {
            color: #ffffff;
        }

        .bento-price .currency {
            font-size: 0.95rem;
            font-weight: 700;
            margin-left: 2px;
        }

        .bento-stock-tag {
            font-size: 0.78rem;
            color: #64748b;
            font-weight: 500;
        }

        .bento-stock-tag strong {
            color: #111827;
        }

        [data-bs-theme="dark"] .bento-stock-tag strong {
            color: #f1f5f9;
        }

        .bento-product-desc {
            font-size: 0.85rem;
            color: #64748b;
            line-height: 1.45;
            margin-bottom: 1.25rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 2.5rem;
        }

        [data-bs-theme="dark"] .bento-product-desc {
            color: #94a3b8;
        }

        .bento-actions-wrap {
            margin-top: auto;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-bento-action {
            background: #111827;
            color: #ffffff !important;
            border: none;
            border-radius: 2px;
            clip-path: polygon(0 0, calc(100% - 9px) 0, 100% 9px, 100% 100%, 9px 100%, 0 calc(100% - 9px));
            padding: 10px 18px;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 0.86rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 2px 2px 0px rgba(0, 0, 0, 0.2);
        }

        .btn-bento-action:hover {
            background: var(--bellroy-orange);
            transform: translate(-1px, -1px);
            box-shadow: 4px 4px 0px rgba(0, 0, 0, 0.3);
        }

        [data-bs-theme="dark"] .btn-bento-action {
            background: #CD4C20 !important;
            color: #ffffff !important;
            box-shadow: 2px 2px 0px #000000 !important;
        }

        [data-bs-theme="dark"] .btn-bento-action:hover {
            background: #b85021 !important;
            color: #ffffff !important;
            box-shadow: 3px 3px 0px #000000 !important;
        }

        .btn-bento-cart {
            width: 44px;
            height: 44px;
            border-radius: 2px;
            clip-path: polygon(0 0, calc(100% - 6px) 0, 100% 6px, 100% 100%, 6px 100%, 0 calc(100% - 6px));
            background: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.15);
            color: #111827;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 2px 2px 0px rgba(0, 0, 0, 0.05);
        }

        .btn-bento-cart:hover {
            border-color: var(--bellroy-orange);
            color: var(--bellroy-orange);
            transform: translate(-1px, -1px);
            box-shadow: 3px 3px 0px rgba(205, 76, 32, 0.2);
        }

        [data-bs-theme="dark"] .btn-bento-cart {
            background: #1A1A1E !important;
            border: 1px solid #2E2E34 !important;
            color: #f8fafc !important;
            box-shadow: 2px 2px 0px #000000 !important;
        }

        [data-bs-theme="dark"] .btn-bento-cart:hover {
            background: #CD4C20 !important;
            border-color: #CD4C20 !important;
            color: #ffffff !important;
            box-shadow: 3px 3px 0px #000000 !important;
        }

        /* Badges — Tactical Angular Style */
        .badge-premium {
            background: var(--bellroy-charcoal);
            color: #ffffff;
            font-family: 'Space Mono', monospace;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 4px 10px;
            border-radius: 1px;
            clip-path: polygon(0 0, calc(100% - 5px) 0, 100% 5px, 100% 100%, 5px 100%, 0 calc(100% - 5px));
            font-size: 0.72rem;
        }

        .badge-terracotta {
            background: var(--bellroy-orange-subtle);
            color: var(--bellroy-orange);
            font-family: 'Space Mono', monospace;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 4px 10px;
            border-radius: 1px;
            clip-path: polygon(0 0, calc(100% - 5px) 0, 100% 5px, 100% 100%, 5px 100%, 0 calc(100% - 5px));
            font-size: 0.72rem;
        }

        .badge-sage {
            background: var(--bellroy-sage-subtle);
            color: var(--bellroy-sage);
            font-family: 'Space Mono', monospace;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 4px 10px;
            border-radius: 1px;
            clip-path: polygon(0 0, calc(100% - 5px) 0, 100% 5px, 100% 100%, 5px 100%, 0 calc(100% - 5px));
            font-size: 0.72rem;
        }

        [data-bs-theme="dark"] .badge-sage {
            background: rgba(78, 121, 105, 0.25) !important;
            color: #8edebd !important;
        }

        [data-bs-theme="dark"] .badge-terracotta {
            background: rgba(205, 76, 32, 0.2) !important;
            color: #ffaa88 !important;
        }

        [data-bs-theme="dark"] .badge-premium {
            background: #2b2826 !important;
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Quick Add-to-Cart Button — Chamfered Cut */
        .btn-add-cart-quick {
            background: var(--bellroy-charcoal);
            color: #ffffff !important;
            border: none;
            font-family: 'Space Grotesk', 'Inter', sans-serif;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 8px 16px;
            border-radius: 2px;
            clip-path: polygon(0 0, calc(100% - 7px) 0, 100% 7px, 100% 100%, 7px 100%, 0 calc(100% - 7px));
            transition: var(--transition-smooth);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.8rem;
            white-space: nowrap;
        }

        .btn-add-cart-quick:hover {
            background: var(--bellroy-orange);
            color: #ffffff !important;
            transform: translate(-1px, -1px);
            box-shadow: 3px 3px 0px rgba(0, 0, 0, 0.2);
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
            background: var(--bellroy-charcoal);
            border: 1px solid var(--border-color);
            border-radius: 2px;
            clip-path: polygon(0 0, calc(100% - 9px) 0, 100% 9px, 100% 100%, 9px 100%, 0 calc(100% - 9px));
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            box-shadow: 3px 3px 0px rgba(0, 0, 0, 0.3);
            transition: var(--transition-smooth);
        }

        .mobile-category-toggle:hover {
            transform: translate(-1px, -1px);
            background: var(--bellroy-orange);
            color: #ffffff;
            box-shadow: 4px 4px 0px rgba(205, 76, 32, 0.35);
        }

        @media (min-width: 992px) {
            .mobile-category-toggle {
                display: none !important;
            }
        }

        /* Offcanvas — Clean Warm Theme */
        .offcanvas-category {
            background: var(--body-bg) !important;
            border-right: 1px solid var(--border-color) !important;
            max-width: 300px;
        }

        .offcanvas-category .offcanvas-header {
            border-bottom: 1px solid var(--border-color);
        }

        /* Angular Tactical Pagination */
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
            clip-path: polygon(0 0, calc(100% - 6px) 0, 100% 6px, 100% 100%, 6px 100%, 0 calc(100% - 6px));
            min-width: 42px;
            min-height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Space Mono', monospace;
            font-weight: 700;
            font-size: 0.88rem;
            transition: var(--transition-smooth);
            padding: 0 14px;
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

        /* Dark Mode Specific Pagination Overrides */
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
            box-shadow: 3px 3px 0px rgba(205, 76, 32, 0.4);
        }

        [data-bs-theme="dark"] .pagination .page-item.active .page-link {
            background: var(--bellroy-orange) !important;
            border-color: var(--bellroy-orange) !important;
            color: #ffffff !important;
        }

        [data-bs-theme="dark"] .pagination .page-item.disabled .page-link {
            background: #141418 !important;
            border-color: #222228 !important;
            color: #666675 !important;
            opacity: 0.5;
        }

        /* ========================================================= */
        /* AVANT-GARDE TELEMETRY & BRUTALIST TECH MANIFESTO FOOTER    */
        /* ========================================================= */
        .avant-footer {
            background: #0d0f12 !important;
            color: #94a3b8;
            position: relative;
            overflow: hidden;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding: 0;
            margin-top: auto;
        }

        [data-bs-theme="light"] .avant-footer {
            background: #111827 !important;
            color: #9ca3af;
        }

        /* Ticker Marquee Ribbon */
        .footer-ticker-bar {
            background: #15181e;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 11px 0;
            overflow: hidden;
            white-space: nowrap;
            position: relative;
        }

        .footer-ticker-track {
            display: inline-flex;
            align-items: center;
            gap: 2.5rem;
            animation: marqueeTape 40s linear infinite;
        }

        .footer-ticker-track:hover {
            animation-play-state: paused;
        }

        @keyframes marqueeTape {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        .ticker-item {
            font-family: 'Space Mono', monospace;
            font-size: 0.74rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #e2e8f0;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .ticker-item em {
            font-style: normal;
            color: var(--bellroy-orange);
        }

        .ticker-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--bellroy-orange);
            display: inline-block;
        }

        /* Giant Architectural Watermark Background */
        .footer-watermark-text {
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(4rem, 12vw, 11rem);
            font-weight: 900;
            letter-spacing: -0.04em;
            color: rgba(255, 255, 255, 0.02);
            line-height: 0.9;
            pointer-events: none;
            white-space: nowrap;
            user-select: none;
            z-index: 0;
        }

        /* Modular Footer Bento Cards */
        .footer-bento-card {
            background: rgba(255, 255, 255, 0.025);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 4px;
            padding: 1.5rem;
            height: 100%;
            position: relative;
            z-index: 1;
            transition: var(--transition-smooth);
        }

        .footer-bento-card:hover {
            border-color: rgba(205, 76, 32, 0.35);
            background: rgba(255, 255, 255, 0.04);
            transform: translateY(-2px);
        }

        .footer-col-header {
            font-family: 'Space Mono', monospace;
            font-size: 0.74rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--bellroy-orange);
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .footer-nav-link {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 0.86rem;
            color: #94a3b8 !important;
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 4px 0;
            transition: var(--transition-smooth);
        }

        .footer-nav-link:hover {
            color: #ffffff !important;
            transform: translateX(4px);
        }

        .footer-nav-link i {
            font-size: 0.7rem;
            color: var(--bellroy-orange);
            opacity: 0;
            transition: opacity 0.2s ease, transform 0.2s ease;
            transform: translateX(-4px);
        }

        .footer-nav-link:hover i {
            opacity: 1;
            transform: translateX(0);
        }

        .footer-telemetry-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 5px 12px;
            border-radius: 50rem;
            background: rgba(34, 197, 94, 0.08);
            border: 1px solid rgba(34, 197, 94, 0.25);
            font-family: 'Space Mono', monospace;
            font-size: 0.68rem;
            font-weight: 700;
            color: #22c55e;
            letter-spacing: 0.04em;
        }

        .telemetry-status-pulse {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 8px #22c55e;
            animation: pulseDot 1.8s infinite;
        }

        /* Store Hours Dynamic Telemetry Badge */
        .store-status-badge {
            font-size: 0.65rem;
            font-family: 'Space Mono', monospace;
            letter-spacing: 0.04em;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 2px 7px;
            border-radius: 2px;
            vertical-align: middle;
            transition: var(--transition-smooth);
        }

        .store-status-badge.is-open {
            background: rgba(34, 197, 94, 0.12);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .store-status-badge.is-closed {
            background: rgba(148, 163, 184, 0.12);
            color: #94a3b8;
            border: 1px solid rgba(148, 163, 184, 0.25);
        }

        .store-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: inline-block;
        }

        .store-dot.is-open {
            background: #22c55e;
            box-shadow: 0 0 6px #22c55e;
            animation: pulseDot 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        .store-dot.is-closed {
            background: #64748b;
        }

        /* Hotline Card Action */
        .footer-hotline-box {
            background: rgba(205, 76, 32, 0.1);
            border: 1px solid rgba(205, 76, 32, 0.25);
            border-radius: 4px;
            padding: 10px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
            transition: var(--transition-smooth);
            text-decoration: none !important;
        }

        .footer-hotline-box:hover {
            background: rgba(205, 76, 32, 0.18);
            border-color: var(--bellroy-orange);
            transform: translateY(-1px);
        }

        .footer-hotline-number {
            font-family: 'Space Mono', monospace;
            font-weight: 700;
            font-size: 1.05rem;
            color: #ffffff;
            letter-spacing: 0.05em;
        }

        .footer-bottom-bar {
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            padding: 18px 0;
            position: relative;
            z-index: 1;
            font-size: 0.8rem;
        }

        .btn-scroll-top {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #e2e8f0;
            font-family: 'Space Mono', monospace;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 5px 14px;
            border-radius: 2px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: var(--transition-smooth);
        }

        .btn-scroll-top:hover {
            background: var(--bellroy-orange);
            border-color: var(--bellroy-orange);
            color: #ffffff;
            transform: translateY(-2px);
        }

        /* Animations */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .animate-fade-in {
            animation: fadeIn 0.45s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .animate-slide-up {
            animation: slideUp 0.45s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* Input Base & Focus Styles */
        .form-control, .form-select {
            border: 1px solid var(--border-color) !important;
            background-color: #ffffff !important;
            color: var(--text-main) !important;
            border-radius: 8px;
            font-size: 0.92rem;
            padding: 10px 14px;
            transition: var(--transition-smooth);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--bellroy-orange) !important;
            box-shadow: 0 0 0 2px rgba(205, 76, 32, 0.15) !important;
        }

        /* Floating Contact Widget */
        .floating-contact-btn {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bellroy-charcoal);
            color: white;
            text-decoration: none;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            transition: var(--transition-smooth);
        }
        .floating-contact-btn:hover {
            background: var(--bellroy-orange);
            color: white;
            transform: scale(1.06);
        }

        /* AI Chatbot Custom Styles */
        .chat-body::-webkit-scrollbar {
            width: 5px;
        }
        .chat-body::-webkit-scrollbar-thumb {
            background-color: #dcd7ce;
            border-radius: 10px;
        }

        /* Toast notification — Angular Chamfer */
        .toast-cart {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 9999;
            min-width: 280px;
            background: rgba(28, 26, 25, 0.96);
            backdrop-filter: blur(10px);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 2px;
            clip-path: polygon(0 0, calc(100% - 10px) 0, 100% 10px, 100% 100%, 10px 100%, 0 calc(100% - 10px));
            padding: 12px 24px;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 600;
            font-size: 0.88rem;
            box-shadow: 4px 4px 0px rgba(0, 0, 0, 0.35);
            transform: translateX(120%);
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            align-items: center;
        }

        .toast-cart.show {
            transform: translateX(0);
        }

        .toast-cart.toast-error {
            background: rgba(205, 76, 32, 0.98);
        }

        /* User Dropdown — Angular Edge */
        .dropdown-menu {
            border: 1px solid var(--border-color) !important;
            box-shadow: 4px 4px 0px rgba(0, 0, 0, 0.1) !important;
            border-radius: 2px !important;
            background: var(--card-bg);
        }

        .dropdown-item {
            border-radius: 1px !important;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 0.86rem;
            transition: var(--transition-smooth);
        }

        /* Global Angular & Chiseled Precision Overrides */
        .form-control, .form-select {
            border-radius: 2px !important;
            border-color: var(--border-color);
            transition: var(--transition-smooth);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--bellroy-orange) !important;
            box-shadow: 0 0 0 1px var(--bellroy-orange) !important;
        }

        .input-group-text {
            border-radius: 2px !important;
        }

        .btn-dark {
            background: var(--bellroy-charcoal) !important;
            border: 1px solid var(--bellroy-charcoal) !important;
            color: #ffffff !important;
            border-radius: 2px !important;
            clip-path: polygon(0 0, calc(100% - 8px) 0, 100% 8px, 100% 100%, 8px 100%, 0 calc(100% - 8px));
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            box-shadow: 3px 3px 0px rgba(0, 0, 0, 0.2);
            transition: var(--transition-smooth);
        }

        .btn-dark:hover {
            background: var(--bellroy-orange) !important;
            border-color: var(--bellroy-orange) !important;
            transform: translate(-1px, -1px);
            box-shadow: 4px 4px 0px rgba(205, 76, 32, 0.35);
        }

        .btn-wishlist {
            border-radius: 2px !important;
            clip-path: polygon(0 0, calc(100% - 5px) 0, 100% 5px, 100% 100%, 5px 100%, 0 calc(100% - 5px)) !important;
            box-shadow: 2px 2px 0px rgba(0, 0, 0, 0.08) !important;
            transition: var(--transition-smooth);
        }

        .btn-wishlist:hover {
            transform: translate(-1px, -1px);
            box-shadow: 3px 3px 0px rgba(205, 76, 32, 0.3) !important;
            border-color: var(--bellroy-orange) !important;
        }

        .modal-content {
            border-radius: 2px !important;
            border: 1px solid var(--border-color) !important;
            box-shadow: 6px 6px 0px rgba(0, 0, 0, 0.25) !important;
        }

        .alert {
            border-radius: 2px !important;
            border-left: 4px solid currentColor !important;
            box-shadow: 3px 3px 0px rgba(0, 0, 0, 0.05);
        }

        #navbarUserDropdown {
            border-radius: 2px !important;
            clip-path: polygon(0 0, calc(100% - 6px) 0, 100% 6px, 100% 100%, 6px 100%, 0 calc(100% - 6px)) !important;
            font-family: 'Space Grotesk', sans-serif;
            box-shadow: 2px 2px 0px rgba(0,0,0,0.1) !important;
        }

        #navbarUserDropdown img {
            border-radius: 2px !important;
        }

        .badge.rounded-pill {
            border-radius: 2px !important;
            font-family: 'Space Mono', monospace;
            letter-spacing: 0.05em;
        }

        /* Decorative Bellroy underline for headings */
        .bellroy-underline {
            position: relative;
            display: inline-block;
        }
        .bellroy-underline::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 0;
            width: 36px;
            height: 2.5px;
            background: var(--bellroy-orange);
            border-radius: 0;
            clip-path: polygon(0 0, 100% 0, calc(100% - 2px) 100%, 0 100%);
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Header Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
        <div class="container-fluid" style="padding-left: 2cm !important; padding-right: 2cm !important;">
            @php
                $logoUrl = url('/');
                if (Auth::check() && Auth::user()->role === 'delivery') {
                    $logoUrl = route('delivery.index');
                }
            @endphp
            <a class="navbar-brand navbar-brand-custom text-decoration-none" href="{{ $logoUrl }}">
                <img src="{{ asset('images/logo.png') }}" alt="LapGearZone Logo" height="80" style="border-radius: 8px; margin-right: -4px; margin-top: -20px; margin-bottom: -20px; filter: brightness(0) invert(1);"> 
                <span>LapGearZone</span>
                <span class="brand-live-badge d-none d-sm-inline-flex"><span class="brand-pulse-dot"></span>LIVE 2026</span>
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Menu bên trái -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                </ul>

                <!-- Menu bên phải -->
                <ul class="navbar-nav ms-auto text-center text-lg-start align-items-center">
                    @if(!request()->routeIs('login', 'register'))
                        <li class="nav-item me-2">
                            <a class="nav-link-custom text-decoration-none {{ request()->routeIs('news.*') ? 'active' : '' }}" href="{{ route('news.index') }}">
                                <i class="bi bi-newspaper"></i> Tin tức
                            </a>
                        </li>
                        <li class="nav-item me-2">
                            <a class="nav-link-custom text-decoration-none {{ request()->routeIs('contact.index') ? 'active' : '' }}" href="{{ route('contact.index') }}">
                                <i class="bi bi-chat-square-heart"></i> Góp ý   
                            </a>
                        </li>
                    @endif

                    <!-- Theme Switcher (Light / Dark Mode Toggle) -->
                    <li class="nav-item me-2 d-flex align-items-center">
                        @include('partials.theme-switcher')
                    </li>
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
                                @php
                                    $cartCount = \App\Models\Cart::where('user_id', Auth::id())->sum('quantity');
                                @endphp
                                <a class="nav-cart-pill text-decoration-none position-relative me-3 {{ request()->routeIs('cart.index') ? 'active' : '' }}" href="{{ route('cart.index') }}" title="Giỏ hàng">
                                    <i class="bi bi-bag fs-5"></i>
                                    @if ($cartCount > 0)
                                        <span class="cart-badge-glowing">
                                            {{ $cartCount }}
                                        </span>
                                    @endif
                                </a>
                            </li>
                        @endif
 
                        <!-- User Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="user-profile-pill dropdown-toggle text-decoration-none d-flex align-items-center shadow-sm" href="#" id="navbarUserDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                @if(Auth::user()->avatar)
                                    <img src="{{ Auth::user()->avatar_url }}" alt="Avatar" class="rounded-circle me-2" style="width: 28px; height: 28px; object-fit: cover; border: 1px solid rgba(255,255,255,0.2);">
                                @else
                                    <i class="bi bi-person-circle me-2 fs-5 opacity-75"></i>
                                @endif
                                <span class="fw-bold text-uppercase ms-1" style="font-size: 0.82rem; letter-spacing: 0.5px;">{{ Auth::user()->name }}</span>
                                @if(Auth::user()->role === 'admin')
                                    <i class="bi bi-award-fill text-warning ms-2 me-1 fs-6" title="Admin"></i>
                                @elseif(Auth::user()->role === 'delivery')
                                    <i class="bi bi-box-seam text-info ms-2 me-1 fs-6" title="Nhân viên giao hàng"></i>
                                @elseif(Auth::user()->role === 'customer_bronze')
                                    <i class="bi bi-star-fill ms-2 me-1 fs-6" style="color: #cd7f32;" title="Hạng Đồng"></i>
                                @elseif(Auth::user()->role === 'customer_silver')
                                    <i class="bi bi-star-fill text-secondary ms-2 me-1 fs-6" title="Hạng Bạc"></i>
                                @elseif(Auth::user()->role === 'customer_gold')
                                    <i class="bi bi-star-fill text-warning ms-2 me-1 fs-6" title="Hạng Vàng"></i>
                                @elseif(Auth::user()->role === 'customer_diamond')
                                    <i class="bi bi-gem text-info ms-2 me-1 fs-6" title="Hạng Kim Cương"></i>
                                @elseif(Auth::user()->role === 'customer_emerald')
                                    <i class="bi bi-gem text-success ms-2 me-1 fs-6" title="Hạng Lục Bảo"></i>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end border shadow-sm rounded-3 p-2 animate-fade-in" aria-labelledby="navbarUserDropdown" style="min-width: 190px;">
                                @if(!request()->routeIs('profile.index') && Auth::user()->role !== 'admin')
                                    <li>
                                        <a class="dropdown-item py-2 px-3 rounded-2 text-decoration-none" href="{{ route('profile.index') }}">
                                            <i class="bi bi-person-vcard me-2 text-muted"></i>Hồ sơ cá nhân
                                        </a>
                                    </li>
                                @endif
                                @if(Auth::user()->role === 'admin')
                                    <li><a class="dropdown-item py-2 px-3 rounded-2 text-decoration-none" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2 text-muted"></i>Khu quản trị</a></li>
                                @endif
                                @if(Auth::user()->role === 'delivery' && !request()->routeIs('delivery.index'))
                                    <li><a class="dropdown-item py-2 px-3 rounded-2 text-decoration-none" href="{{ route('delivery.index') }}"><i class="bi bi-truck me-2 text-muted"></i>Nhiệm vụ giao hàng</a></li>
                                @endif
                                <li><hr class="dropdown-divider opacity-10"></li>
                                <li>
                                    <a class="dropdown-item text-danger py-2 px-3 rounded-2 text-decoration-none" href="{{ route('logout') }}"
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
    <main class="flex-grow-1 position-relative">
        <!-- Toast / Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible border-0 shadow-sm rounded-4 p-3 mb-4 d-flex align-items-center container mt-3" style="background-color: var(--bellroy-sage-subtle); color: var(--bellroy-sage);" role="alert">
                <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                <div>
                    <strong>Thành công!</strong> {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible border-0 shadow-sm rounded-4 p-3 mb-4 d-flex align-items-center container mt-3" style="background-color: var(--bellroy-orange-subtle); color: var(--bellroy-orange);" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                <div>
                    <strong>Lỗi!</strong> {{ session('error') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="avant-footer">
        <!-- Telemetry Ticker Ribbon -->
        <div class="footer-ticker-bar">
            <div class="footer-ticker-track">
                <span class="ticker-item"><span class="ticker-dot"></span> LAPGEARZONE <em>EDITION 2026</em></span>
                <span class="ticker-item"><span class="ticker-dot"></span> HỆ THỐNG LAPTOP CHÍNH HÃNG 100%</span>
                <span class="ticker-item"><span class="ticker-dot"></span> BẢO HÀNH TOÀN DIỆN <em>24 THÁNG</em></span>
                <span class="ticker-item"><span class="ticker-dot"></span> GIAO HÀNG HỎA TỐC TOÀN QUỐC</span>
                <span class="ticker-item"><span class="ticker-dot"></span> HỖ TRỢ KỸ THUẬT CHUYÊN SÂU <em>24/7</em></span>
                <span class="ticker-item"><span class="ticker-dot"></span> HỖ TRỢ TRẢ GÓP <em>0% LÃI SUẤT</em></span>
                <!-- Duplicate for seamless infinite loop -->
                <span class="ticker-item"><span class="ticker-dot"></span> LAPGEARZONE <em>EDITION 2026</em></span>
                <span class="ticker-item"><span class="ticker-dot"></span> HỆ THỐNG LAPTOP CHÍNH HÃNG 100%</span>
                <span class="ticker-item"><span class="ticker-dot"></span> BẢO HÀNH TOÀN DIỆN <em>24 THÁNG</em></span>
                <span class="ticker-item"><span class="ticker-dot"></span> GIAO HÀNG HỎA TỐC TOÀN QUỐC</span>
                <span class="ticker-item"><span class="ticker-dot"></span> HỖ TRỢ KỸ THUẬT CHUYÊN SÂU <em>24/7</em></span>
                <span class="ticker-item"><span class="ticker-dot"></span> HỖ TRỢ TRẢ GÓP <em>0% LÃI SUẤT</em></span>
            </div>
        </div>

        <!-- Giant Watermark Background -->
        <div class="footer-watermark-text">LAPGEAR ZONE</div>

        <div class="container py-4 py-lg-5 position-relative" style="z-index: 2;">
            <div class="row g-3 g-lg-4">
                <!-- Cột 1: Thương hiệu & Telemetry Status -->
                <div class="col-lg-4 col-md-12">
                    <div class="footer-bento-card">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <img src="{{ asset('images/logo.png') }}" alt="lapgearzone" height="34" style="border-radius: 4px; filter: brightness(0) invert(1);">
                            <span class="fw-bold text-white fs-5" style="font-family: 'Space Grotesk', sans-serif; letter-spacing: -0.02em;">LapGearZone</span>
                        </div>
                        <p class="mb-3 text-secondary" style="font-size: 0.84rem; line-height: 1.6; color: #94a3b8 !important;">
                            Hệ sinh thái thiết bị công nghệ &amp; laptop cao cấp. Mua sắm tinh gọn, thẩm mỹ tối giản, bảo hành uy tín và hỗ trợ kỹ thuật tận tâm.
                        </p>
                        <div class="footer-telemetry-badge">
                            <span class="telemetry-status-pulse"></span>
                            <span>HỆ THỐNG: HOẠT ĐỘNG 100%</span>
                            <span style="opacity: 0.4;">•</span>
                            <span>VER 2026.4</span>
                        </div>
                    </div>
                </div>

                <!-- Cột 2: Chính sách Monospace Index -->
                <div class="col-lg-3 col-sm-5">
                    <div class="footer-bento-card">
                        <div class="footer-col-header">
                            <i class="bi bi-shield-check"></i>
                            <span>[01] CHÍNH SÁCH</span>
                        </div>
                        <ul class="list-unstyled d-flex flex-column gap-2 mb-0">
                            <li><a href="{{ route('policies.warranty') }}" class="footer-nav-link"><i class="bi bi-arrow-right"></i> Chính sách bảo hành</a></li>
                            <li><a href="{{ route('policies.return') }}" class="footer-nav-link"><i class="bi bi-arrow-right"></i> Chính sách đổi trả</a></li>
                            <li><a href="{{ route('policies.shipping') }}" class="footer-nav-link"><i class="bi bi-arrow-right"></i> Chính sách vận chuyển</a></li>
                            <li><a href="{{ route('policies.privacy') }}" class="footer-nav-link"><i class="bi bi-arrow-right"></i> Bảo mật thông tin</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Cột 3: Trạm Thông Tin & Hotline Trực Tiếp -->
                <div class="col-lg-5 col-sm-7">
                    <div class="footer-bento-card">
                        <div class="footer-col-header">
                            <i class="bi bi-broadcast"></i>
                            <span>[02] TRẠM KẾT NỐI TRỰC TIẾP</span>
                        </div>

                        <!-- Hotline Click-to-Call Card -->
                        <a href="tel:0346884415" class="footer-hotline-box">
                            <div>
                                <div style="font-size: 0.68rem; font-family: 'Space Mono', monospace; color: var(--bellroy-orange); text-transform: uppercase; letter-spacing: 0.05em;">Hotline Hỗ Trợ Kỹ Thuật / Zalo</div>
                                <div class="footer-hotline-number">0346 884 415</div>
                            </div>
                            <span class="btn btn-sm btn-dark" style="background: var(--bellroy-orange); border: none; font-size: 0.75rem; padding: 5px 12px; font-weight: 700; border-radius: 2px;">GỌI NGAY</span>
                        </a>

                        <ul class="list-unstyled d-flex flex-column gap-2 mb-0" style="font-size: 0.83rem; color: #94a3b8;">
                            <li class="d-flex align-items-start gap-2">
                                <i class="bi bi-geo-alt-fill mt-1 flex-shrink-0" style="color: var(--bellroy-orange);"></i>
                                <span>25A Ngõ 261, đường Phú Diễn, phường Phú Diễn, Hà Nội</span>
                            </li>
                            <li class="d-flex align-items-center gap-2">
                                <i class="bi bi-envelope-fill flex-shrink-0" style="color: #64748b;"></i>
                                <span>Email: <a href="mailto:duongtk2pc@gmail.com" class="text-white text-decoration-none hover-white">duongtk2pc@gmail.com</a></span>
                            </li>
                            @php
                                $vnTime = now()->timezone('Asia/Ho_Chi_Minh');
                                $currentMinTotal = (int)$vnTime->format('H') * 60 + (int)$vnTime->format('i');
                                $isStoreOpen = ($currentMinTotal >= 480 && $currentMinTotal < 1260); // 08:00 - 21:00
                            @endphp
                            <li class="d-flex align-items-center gap-2">
                                <i id="footerStoreIcon" class="bi bi-clock-fill flex-shrink-0" style="color: {{ $isStoreOpen ? '#22c55e' : '#94a3b8' }};"></i>
                                <span>Giờ làm việc: 08:00 - 21:00 
                                    <span id="footerStoreBadge" class="store-status-badge {{ $isStoreOpen ? 'is-open' : 'is-closed' }} ms-1" title="{{ $isStoreOpen ? 'Cửa hàng đang trong khung giờ phục vụ trực tiếp (08:00 - 21:00)' : 'Hiện ngoài giờ mở cửa (08:00 - 21:00) • Đơn hàng online vẫn được tiếp nhận 24/7' }}">
                                        <span id="footerStoreDot" class="store-dot {{ $isStoreOpen ? 'is-open' : 'is-closed' }}"></span>
                                        <span id="footerStoreText">{{ $isStoreOpen ? 'ĐANG MỞ CỬA' : 'ĐÃ ĐÓNG CỬA' }}</span>
                                    </span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="footer-bottom-bar">
            <div class="container">
                <div class="row align-items-center justify-content-between g-2">
                    <div class="col-md-4 text-center text-md-start">
                        <span style="color: #64748b;">&copy; {{ date('Y') }} <strong>LapGearZone</strong>. Architecture &amp; Tech.</span>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="d-flex justify-content-center align-items-center gap-3">
                            <a href="https://www.facebook.com/nguyen.duong.259630?locale=vi_VN" target="_blank" class="footer-nav-link text-secondary" title="Facebook"><i class="bi bi-facebook fs-6"></i></a>
                            <a href="https://www.youtube.com/@NguyenDuong-ml1fu" target="_blank" class="footer-nav-link text-secondary" title="YouTube"><i class="bi bi-youtube fs-6"></i></a>
                            <span class="badge" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); color: #94a3b8; font-size: 0.7rem; font-family: 'Space Mono', monospace;">VISA / PAYOS</span>
                        </div>
                    </div>
                    <div class="col-md-4 text-center text-md-end">
                        <button type="button" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="btn-scroll-top">
                            <i class="bi bi-arrow-up"></i> Đầu trang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Widget Liên Hệ Nổi (Floating Contact Buttons) -->
    <div class="position-fixed bottom-0 end-0 p-3 mb-3 me-2 d-flex flex-column gap-2" style="z-index: 1055;">
        <!-- Nút Mở Box Chat AI -->
        <button class="btn floating-contact-btn text-white fw-bold" style="background: var(--bellroy-orange); border: none;" type="button" data-bs-toggle="collapse" data-bs-target="#aiChatBox" aria-expanded="false" title="Chat với Trợ lý AI">
            <i class="bi bi-chat-dots-fill fs-5"></i>
        </button>

        <!-- Nút Zalo -->
        <a href="https://zalo.me/0346884415" target="_blank" class="floating-contact-btn bg-dark fw-bold" style="font-size: 11px; letter-spacing: 0.5px;" title="Chat qua Zalo">
            ZALO
        </a>
    </div>

    <!-- Box Giao diện Chat AI -->
    <div class="collapse position-fixed shadow-lg rounded-4 overflow-hidden" id="aiChatBox" style="bottom: 90px; right: 80px; width: 340px; z-index: 1060; background: #ffffff; border: 1px solid var(--border-color);">
        <!-- Header Chatbox -->
        <div class="p-3 text-white d-flex justify-content-between align-items-center" style="background: var(--bellroy-charcoal);">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-robot fs-5" style="color: var(--bellroy-White);"></i> 
                <h6 class="mb-0 fw-bold text-white">Trợ lý AI LapGear</h6>
            </div>  
            <button type="button" class="btn-close btn-close-white" data-bs-toggle="collapse" data-bs-target="#aiChatBox" aria-label="Đóng"></button>
        </div>
        
        <!-- Body Chatbox -->
        <div class="p-3 overflow-auto chat-body" style="height: 320px; display: flex; flex-direction: column; gap: 12px; background: #faf9f6;" id="chatBody">
            <!-- Tin nhắn từ AI (Chào mừng) -->
            <div class="d-flex justify-content-start">
                <div class="bg-white border rounded-3 p-3 shadow-sm text-dark" style="max-width: 85%; font-size: 0.88rem; border-color: var(--border-color) !important;">
                    Xin chào! 👋 Tôi là trợ lý AI của LapGearZone. Tôi có thể giúp gì cho bạn hôm nay?
                </div>
            </div>
        </div>
        
        <!-- Form Nhập Tin Nhắn -->
        <div class="p-2 border-top bg-white" style="border-color: var(--border-color) !important;">
            <form onsubmit="event.preventDefault(); sendAIMessage();" class="input-group">
                <input type="text" id="aiChatInput" class="form-control form-control-sm border" placeholder="Nhập câu hỏi của bạn..." autocomplete="off" required style="border-radius: 50rem 0 0 50rem; border-color: var(--border-color) !important;">
                <button class="btn btn-sm text-white px-3" style="background: var(--bellroy-orange); border-radius: 0 50rem 50rem 0;" type="submit">
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
    
    <!-- Script Xử Lý Chat Thông Minh -->
    <script>
        function sendAIMessage() {
            const input = document.getElementById('aiChatInput');
            const message = input.value.trim();
            if(!message) return;

            const chatBody = document.getElementById('chatBody');
            
            // 1. Hiển thị tin nhắn của User
            chatBody.innerHTML += `
                <div class="d-flex justify-content-end">
                    <div class="text-white rounded-3 p-2 shadow-sm" style="background: var(--bellroy-orange); max-width: 85%; font-size: 0.88rem;">
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
                    <div class="bg-white border rounded-3 p-2 shadow-sm text-muted" style="font-size: 0.82rem; border-color: var(--border-color) !important;">
                        <i>Trợ lý đang suy nghĩ...</i>
                    </div>
                </div>
            `;
            chatBody.scrollTop = chatBody.scrollHeight;

            // 3. Xử lý phản hồi tự động
            setTimeout(() => {
                const typingEl = document.getElementById(typingId);
                if (typingEl) typingEl.remove();
                
                const replyText = getBotReply(message);
                
                chatBody.innerHTML += `
                    <div class="d-flex justify-content-start">
                        <div class="bg-white border rounded-3 p-3 shadow-sm text-dark" style="max-width: 85%; font-size: 0.88rem; border-color: var(--border-color) !important;">
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
                reply = "🎮 Đối với nhu cầu chơi game hoặc làm đồ họa nặng, cửa hàng đang có các dòng laptop trang bị card rời mạnh mẽ (RTX series), tản nhiệt cực tốt.Gọi ngay 0346 884 415 để chốt nhanh nhé!";
            } 
            else if (msg.includes('văn phòng') || msg.includes('học tập') || msg.includes('sinh viên') || msg.includes('word') || msg.includes('excel') || msg.includes('code') || msg.includes('lập trình')) {
                reply = "💻 Dành cho học tập, làm việc văn phòng hoặc lập trình, cửa hàng có các dòng laptop mỏng nhẹ, pin trâu, chip khỏe (Core i5/i7 hoặc Ryzen) gõ phím cực êm. Bạn cần máy mỏng nhẹ hay màn hình lớn? Gọi ngay 0346 884 415 để được tư vấn mã phù hợp nhất!";
            } 
            else if (msg.includes('chuột') || msg.includes('bàn phím') || msg.includes('tai nghe') || msg.includes('balo') || msg.includes('sạc') || msg.includes('linh kiện') || msg.includes('phụ kiện')) {
                reply = "🎧 LapGearZone sẵn có đầy đủ các loại phụ kiện chính hãng: Bàn phím cơ, chuột gaming, tai nghe chống ồn, balo chống sốc và sạc dự phòng. Bạn cần tìm phụ kiện loại nào ạ?";
            }
            else if (msg.includes('sửa chữa') || msg.includes('sửa') || msg.includes('fix') ) {
                reply = "🎧 LapGearZone  nhận sửa chữa và kiểm tra nhiều lỗi như máy không lên nguồn, màn hình, bàn phím, pin, sạc, ổ cứng, RAM, lỗi Windows, máy nóng/lag, lỗi phần mềm... Liên hệ hotline để được tư vấn nhé.";
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
                reply = "Trụ sở chính của cửa hàng tọa lạc tại: Số 25A Ngõ 261, đường Phú Diễn, phường Phú Diễn, TP. Hà Nội. Rất hân hạnh được đón tiếp bạn ghé trải nghiệm trực tiếp!";
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
        <i class="bi bi-check-circle-fill me-2" style="color: var(--bellroy-sage);"></i>
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
                    const badge = document.querySelector('.bi-bag')?.closest('a')?.querySelector('.badge');
                    if(badge) {
                        badge.textContent = parseInt(badge.textContent || 0) + 1;
                    } else {
                        const cartLink = document.querySelector('.bi-bag')?.closest('a');
                        if(cartLink) {
                            const span = document.createElement('span');
                            span.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger';
                            span.style.fontSize = '0.65rem';
                            span.style.backgroundColor = 'var(--bellroy-orange)';
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

        // Logic Theme Switcher (Industrial Brutalist & Telemetry System)
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

            // Khởi tạo trạng thái ban đầu
            updateTelemetryUI(getCurrentTheme());

            // Lắng nghe sự kiện click nút chuyển đổi
            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', () => {
                    const currentTheme = getCurrentTheme();
                    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                    setTheme(newTheme);
                });
            }

            // Lắng nghe thay đổi theme từ hệ điều hành
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                if (!localStorage.getItem('laptopking_theme')) {
                    setTheme(e.matches ? 'dark' : 'light');
                }
            });

            // Telemetry Giờ Làm Việc Tự Động (08:00 - 21:00)
            function updateStoreHoursTelemetry() {
                const badge = document.getElementById('footerStoreBadge');
                const text = document.getElementById('footerStoreText');
                const dot = document.getElementById('footerStoreDot');
                const icon = document.getElementById('footerStoreIcon');
                if (!badge || !text || !dot || !icon) return;

                const now = new Date();
                const utcTime = now.getTime() + (now.getTimezoneOffset() * 60000);
                const vnDate = new Date(utcTime + (3600000 * 7));
                const currentMinTotal = vnDate.getHours() * 60 + vnDate.getMinutes();
                const isOpen = (currentMinTotal >= 480 && currentMinTotal < 1260);

                if (isOpen) {
                    badge.className = 'store-status-badge is-open ms-1';
                    badge.title = 'Cửa hàng đang trong khung giờ phục vụ trực tiếp (08:00 - 21:00)';
                    text.textContent = 'ĐANG MỞ CỬA';
                    dot.className = 'store-dot is-open';
                    icon.style.color = '#22c55e';
                } else {
                    badge.className = 'store-status-badge is-closed ms-1';
                    badge.title = 'Hiện ngoài giờ mở cửa (08:00 - 21:00) • Đơn hàng online vẫn được tiếp nhận 24/7';
                    text.textContent = 'ĐÃ ĐÓNG CỬA';
                    dot.className = 'store-dot is-closed';
                    icon.style.color = '#94a3b8';
                }
            }
            updateStoreHoursTelemetry();
            setInterval(updateStoreHoursTelemetry, 30000);
        });
    </script>
    
    @stack('scripts')
</body>
</html>