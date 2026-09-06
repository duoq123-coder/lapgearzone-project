@extends('layouts.app')
@section('title', 'Cửa hàng - Cửa Hàng Công Nghệ')
@section('content')

<!-- Custom styling for Products listing page (Bellroy Style) -->
<style>
    /* ========================================================= */
    /* STOREFRONT PRODUCT TAGS & CHIPS FILTER                   */
    /* ========================================================= */
    .btn-storefront-tag {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 11px;
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: 4px;
        text-decoration: none;
        background: var(--surface-card, #ffffff);
        color: var(--text-muted, #6e6b66);
        border: 1px solid var(--border-color, #e5e7eb);
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        font-family: 'Space Grotesk', sans-serif;
    }
    .btn-storefront-tag:hover {
        color: var(--bellroy-orange, #CD4C20);
        border-color: var(--bellroy-orange, #CD4C20);
        transform: translateY(-1px);
    }
    .btn-storefront-tag.active {
        background: rgba(205, 76, 32, 0.12) !important;
        color: var(--bellroy-orange, #CD4C20) !important;
        border-color: var(--bellroy-orange, #CD4C20) !important;
        box-shadow: 0 2px 6px rgba(205, 76, 32, 0.2);
    }
    .btn-storefront-tag .badge-count {
        font-size: 0.7rem;
        padding: 1px 5px;
        border-radius: 3px;
        background: rgba(0, 0, 0, 0.06);
        color: inherit;
    }
    [data-bs-theme="dark"] .btn-storefront-tag {
        background: #1A1A1E;
        color: #9CA3AF;
        border-color: #2E2E34;
    }
    [data-bs-theme="dark"] .btn-storefront-tag:hover {
        color: #ff9e75;
        border-color: #CD4C20;
    }
    [data-bs-theme="dark"] .btn-storefront-tag.active {
        background: #231C18 !important;
        color: #ff7d50 !important;
        border-color: #CD4C20 !important;
    }
    [data-bs-theme="dark"] .btn-storefront-tag .badge-count {
        background: #2E2E34;
    }

    /* Bento Card Tag Pill */
    .bento-tags-wrap {
        margin-top: 2px;
        margin-bottom: 6px;
    }
    .bento-tag-pill {
        display: inline-block;
        padding: 2px 7px;
        border-radius: 3px;
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.01em;
        font-family: 'Space Mono', monospace;
        background: rgba(205, 76, 32, 0.08);
        color: #CD4C20;
        border: 1px solid rgba(205, 76, 32, 0.22);
        text-decoration: none;
        transition: all 0.15s ease;
    }
    .bento-tag-pill:hover {
        background: #CD4C20;
        color: #ffffff !important;
        border-color: #CD4C20;
    }
    .bento-tag-pill.more {
        background: var(--surface-muted, #f3f4f6);
        color: var(--text-muted, #6e6b66);
        border-color: var(--border-color, #e5e7eb);
    }
    [data-bs-theme="dark"] .bento-tag-pill {
        background: rgba(205, 76, 32, 0.14);
        color: #ff8c61;
        border-color: rgba(205, 76, 32, 0.35);
    }
    [data-bs-theme="dark"] .bento-tag-pill:hover {
        background: #CD4C20;
        color: #ffffff !important;
    }
    [data-bs-theme="dark"] .bento-tag-pill.more {
        background: #232328;
        color: #9CA3AF;
        border-color: #2E2E34;
    }

    /* ========================================================= */
    /* LAPGEARZONE ULTRA-PREMIUM PRODUCT SLIDER HERO BANNER      */
    /* ========================================================= */
    /* ========================================================= */
    /* BRAND PERKS & TRUST STRIP (LIVELY & ENERGETIC)           */
    /* ========================================================= */
    .brand-perks-strip {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
        margin-bottom: 2.2rem;
    }

    @media (max-width: 991.98px) {
        .brand-perks-strip {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
    }

    @media (max-width: 575.98px) {
        .brand-perks-strip {
            grid-template-columns: 1fr;
        }
    }

    .perk-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 2px;
        padding: 1.2rem 1.35rem;
        display: flex;
        align-items: center;
        gap: 1.1rem;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
        box-shadow: 2px 2px 0px rgba(0, 0, 0, 0.04);
    }

    .perk-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(205, 76, 32, 0.08) 0%, transparent 60%);
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
    }

    .perk-card::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 8px;
        height: 8px;
        border-top: 2px solid var(--bellroy-orange);
        border-right: 2px solid var(--bellroy-orange);
    }

    .perk-card:hover {
        transform: translate(-2px, -2px);
        border-color: var(--bellroy-orange);
        box-shadow: 4px 4px 0px rgba(205, 76, 32, 0.18), 0 4px 10px rgba(0, 0, 0, 0.04);
    }

    [data-bs-theme="dark"] .perk-card {
        background: #1A1A1E !important;
        border: 1px solid #2E2E34 !important;
        box-shadow: 3px 3px 0px #000000 !important;
    }

    [data-bs-theme="dark"] .perk-card:hover {
        border-color: #CD4C20 !important;
        box-shadow: 4px 4px 0px #000000 !important;
    }

    .perk-card:hover::before {
        opacity: 1;
    }

    .perk-icon-box {
        width: 46px;
        height: 46px;
        border-radius: 2px;
        clip-path: polygon(0 0, calc(100% - 6px) 0, 100% 6px, 100% 100%, 6px 100%, 0 calc(100% - 6px));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        flex-shrink: 0;
        transition: transform 0.25s ease;
    }

    .perk-card:hover .perk-icon-box {
        transform: scale(1.08) rotate(3deg);
    }

    .perk-icon-orange {
        background: rgba(205, 76, 32, 0.12);
        color: var(--bellroy-orange);
        border: 1px solid rgba(205, 76, 32, 0.25);
    }

    .perk-icon-green {
        background: rgba(16, 185, 129, 0.12);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.25);
    }

    .perk-icon-blue {
        background: rgba(14, 165, 233, 0.12);
        color: #0ea5e9;
        border: 1px solid rgba(14, 165, 233, 0.25);
    }

    .perk-icon-purple {
        background: rgba(168, 85, 247, 0.12);
        color: #a855f7;
        border: 1px solid rgba(168, 85, 247, 0.25);
    }

    .perk-title {
        font-weight: 700;
        font-size: 0.94rem;
        color: var(--text-main);
        margin-bottom: 2px;
        letter-spacing: -0.01em;
    }

    .perk-desc {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin: 0;
        line-height: 1.4;
    }

    /* ========================================================= */
    /* FEATURED PRODUCTS HEADER (VIBRANT & LIVE)                */
    /* ========================================================= */
    .featured-header-wrapper {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 1.4rem;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .badge-live-beacon {
        background: rgba(205, 76, 32, 0.12);
        border: 1px solid rgba(205, 76, 32, 0.3);
        color: var(--bellroy-orange);
        font-size: 0.95rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 8px 22px;
        border-radius: 50rem;
    }

    .live-ping-dot {
        width: 10px;
        height: 10px;
        background-color: #ef4444;
        border-radius: 50%;
        position: relative;
        display: inline-block;
    }

    .live-ping-dot::after {
        content: '';
        position: absolute;
        top: -4px;
        left: -4px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background-color: rgba(239, 68, 68, 0.45);
        animation: pulsePing 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
    }

    @keyframes pulsePing {
        0% { transform: scale(0.6); opacity: 1; }
        100% { transform: scale(1.6); opacity: 0; }
    }

    .featured-section-title {
        font-family: 'Inter', sans-serif;
        font-weight: 800;
        font-size: clamp(2.2rem, 3.4vw, 2.9rem);
        letter-spacing: -0.03em;
        line-height: 1.25;
        color: var(--text-main);
        margin: 6px 0 0 0;
    }

    .featured-section-title em {
        font-family: inherit;
        font-style: normal;
        font-weight: 800;
        color: var(--bellroy-orange);
    }

    .live-countdown-badge {
        background: var(--card-bg);
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: 50rem;
        padding: 8px 18px;
        font-size: 0.86rem;
        color: var(--text-main);
        box-shadow: 0 4px 14px rgba(239, 68, 68, 0.08);
    }

    .countdown-digit {
        background: #ef4444;
        color: #ffffff;
        font-family: 'Space Mono', monospace;
        font-weight: 700;
        font-size: 0.88rem;
        padding: 3px 7px;
        border-radius: 2px;
        letter-spacing: 0.05em;
        box-shadow: 2px 2px 0px rgba(239, 68, 68, 0.3);
    }

    /* ========================================================= */
    /* LUXURY BENTO HORIZONTAL SLIDER (TOP 5 FEATURED LAPTOPS)   */
    /* ========================================================= */
    .bento-featured-section {
        margin-bottom: 3.5rem;
    }

    /* Bento Minimalist Typographic Navigation (Matching Mockup: Top Laptops | Browse | Shop) */
    .bento-minimal-nav {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 1.25rem;
        background: transparent;
        padding: 0;
        border: none;
    }

    /* Faint Horizontal Divider Lines (Darker & Symmetrical) */
    .bento-section-divider,
    .bento-nav-divider {
        height: 1px;
        width: 100%;
        max-width: 1400px;
        background: linear-gradient(90deg, transparent 0%, rgba(0, 0, 0, 0.16) 12%, rgba(0, 0, 0, 0.28) 50%, rgba(0, 0, 0, 0.16) 88%, transparent 100%);
        border: none;
    }

    .bento-section-divider {
        margin: 1.5rem auto 1.35rem auto;
    }

    .bento-nav-divider {
        margin: 1.35rem auto 2.25rem auto;
    }

    [data-bs-theme="dark"] .bento-section-divider,
    [data-bs-theme="dark"] .bento-nav-divider {
        background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.15) 12%, rgba(255, 255, 255, 0.3) 50%, rgba(255, 255, 255, 0.15) 88%, transparent 100%);
    }

    .bento-nav-item {
        border: 1px solid transparent;
        background: transparent;
        padding: 6px 16px;
        color: #71717a;
        font-family: 'Space Grotesk', sans-serif;
        font-size: 0.88rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
        line-height: 1.4;
        position: relative;
        user-select: none;
        border-radius: 2px;
    }

    .bento-nav-item:hover {
        color: var(--bellroy-orange);
        border-color: rgba(205, 76, 32, 0.3);
    }

    [data-bs-theme="dark"] .bento-nav-item {
        color: #94a3b8;
    }

    [data-bs-theme="dark"] .bento-nav-item:hover {
        color: #f8fafc;
        border-color: rgba(205, 76, 32, 0.5);
    }

    .bento-nav-item.active {
        color: #ffffff !important;
        background: var(--bellroy-orange) !important;
        border-color: var(--bellroy-orange) !important;
        clip-path: polygon(0 0, calc(100% - 6px) 0, 100% 6px, 100% 100%, 6px 100%, 0 calc(100% - 6px));
        font-weight: 700;
        box-shadow: 2px 2px 0px rgba(0, 0, 0, 0.2);
    }

    [data-bs-theme="dark"] .bento-nav-item.active {
        color: #ffffff !important;
        background: var(--bellroy-orange) !important;
        box-shadow: 2px 2px 0px rgba(205, 76, 32, 0.35);
    }

    .bento-headline-title {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        font-size: 2.35rem;
        font-weight: 800;
        color: #111827;
        letter-spacing: -0.03em;
        line-height: 1.25;
        margin-bottom: 2.25rem;
        text-align: center;
    }

    [data-bs-theme="dark"] .bento-headline-title {
        color: #f8fafc;
    }

    @media (max-width: 768px) {
        .bento-headline-title {
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
        }
        .bento-minimal-nav {
            gap: 1.5rem;
        }
    }

    /* Bento Slider & Track with Smooth GPU-Accelerated Glide */
    .bento-slider-wrapper {
        position: relative;
        max-width: 1400px;
        margin: 0 auto;
    }

    .bento-slider-viewport {
        overflow: hidden;
        width: 100%;
        padding: 0.5rem 0.25rem 1.5rem 0.25rem;
    }

    .bento-cards-track {
        display: flex;
        gap: 1.25rem;
        transition: transform 0.75s cubic-bezier(0.22, 1, 0.36, 1);
        will-change: transform;
    }

    .bento-card-slide {
        flex: 0 0 calc(25% - 0.95rem);
        min-width: 270px;
        max-width: 330px;
        box-sizing: border-box;
    }

    @media (max-width: 1199.98px) {
        .bento-card-slide {
            flex: 0 0 calc(33.333% - 0.85rem);
            min-width: 260px;
            max-width: 340px;
        }
    }

    @media (max-width: 850px) {
        .bento-card-slide {
            flex: 0 0 calc(50% - 0.65rem);
            min-width: 240px;
            max-width: none;
        }
    }

    @media (max-width: 575.98px) {
        .bento-card-slide {
            flex: 0 0 86%;
            min-width: 240px;
            max-width: none;
        }
    }

    /* Side Navigation Arrows — Angular Chamfer */
    .bento-side-arrow {
        position: absolute;
        top: calc(50% - 12px);
        transform: translateY(-50%);
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
        font-size: 1.15rem;
        box-shadow: 2px 2px 0px rgba(0, 0, 0, 0.08);
        cursor: pointer;
        z-index: 30;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .bento-arrow-left {
        left: -24px;
    }

    .bento-arrow-right {
        right: -24px;
    }

    @media (max-width: 991.98px) {
        .bento-arrow-left {
            left: -12px;
        }
        .bento-arrow-right {
            right: -12px;
        }
    }

    @media (max-width: 767.98px) {
        .bento-side-arrow {
            width: 38px;
            height: 38px;
            font-size: 1rem;
        }
        .bento-arrow-left {
            left: -6px;
        }
        .bento-arrow-right {
            right: -6px;
        }
    }

    .bento-side-arrow:hover {
        background: var(--bellroy-orange);
        color: #ffffff;
        border-color: var(--bellroy-orange);
        transform: translateY(-50%) translate(-1px, -1px);
        box-shadow: 3px 3px 0px rgba(205, 76, 32, 0.3);
    }

    .bento-side-arrow:active {
        transform: translateY(-50%) translate(1px, 1px);
    }

    [data-bs-theme="dark"] .bento-side-arrow {
        background: #1A1A1E !important;
        border: 1px solid #2E2E34 !important;
        color: #f8fafc !important;
        box-shadow: 3px 3px 0px #000000 !important;
    }

    [data-bs-theme="dark"] .bento-side-arrow:hover {
        background: #CD4C20 !important;
        border-color: #CD4C20 !important;
        color: #ffffff !important;
    }

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

    /* Studio Image Stage — Angular Precision */
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
        filter: drop-shadow(0 14px 20px rgba(0, 0, 0, 0.12));
        transition: transform 0.4s cubic-bezier(0.32, 0.72, 0, 1);
    }

    .bento-laptop-card:hover .bento-img-stage img {
        transform: scale(1.08) translateY(-4px);
        filter: drop-shadow(0 18px 26px rgba(0, 0, 0, 0.2));
    }

    /* Badges on stage - Top-left rank badge removed per user request */
    .bento-rank-badge {
        display: none !important;
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

    /* Product Title */
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
        color: #CD4C20 !important;
    }

    /* Sold Count Badge — Tactical Tag */
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

    /* Price Section */
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

    /* Actions */
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
        flex-shrink: 0;
    }

    .btn-bento-cart:hover {
        background: var(--bellroy-orange);
        color: #ffffff;
        border-color: var(--bellroy-orange);
        transform: translate(-1px, -1px);
        box-shadow: 3px 3px 0px rgba(205, 76, 32, 0.35);
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

    .btn-bento-all {
        background: #ffffff;
        color: #111827;
        border: 1px solid rgba(0, 0, 0, 0.15);
        border-radius: 2px;
        clip-path: polygon(0 0, calc(100% - 7px) 0, 100% 7px, 100% 100%, 7px 100%, 0 calc(100% - 7px));
        font-family: 'Space Grotesk', sans-serif;
        padding: 9px 20px;
        font-size: 0.88rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        text-decoration: none;
        box-shadow: 2px 2px 0px rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
    }

    .btn-bento-all:hover {
        background: var(--bellroy-orange);
        color: #ffffff;
        border-color: var(--bellroy-orange);
        transform: translate(-1px, -1px);
        box-shadow: 3px 3px 0px rgba(205, 76, 32, 0.25);
    }

    [data-bs-theme="dark"] .btn-bento-all {
        background: #1A1A1E !important;
        border: 1px solid #2E2E34 !important;
        color: #f1f5f9 !important;
        box-shadow: 2px 2px 0px #000000 !important;
    }

    [data-bs-theme="dark"] .btn-bento-all:hover {
        background: #CD4C20 !important;
        border-color: #CD4C20 !important;
        color: #ffffff !important;
        box-shadow: 3px 3px 0px #000000 !important;
    }

    /* Search/Filter Bar — Angular Modular Box */
    .search-filter-bar {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 2px;
        box-shadow: 2px 2px 0px rgba(0, 0, 0, 0.04);
        margin-bottom: 2rem;
    }
    
    .search-filter-bar input, .search-filter-bar select, .search-filter-bar .input-group-text {
        background-color: var(--surface-muted) !important;
        color: var(--text-main) !important;
        border: 1px solid var(--border-color) !important;
        border-radius: 2px !important;
        font-weight: 500;
        transition: var(--transition-smooth);
    }
    
    .search-filter-bar input:focus, .search-filter-bar select:focus {
        border-color: var(--bellroy-orange) !important;
        box-shadow: 2px 2px 0px rgba(205, 76, 32, 0.25) !important;
        background-color: var(--card-bg) !important;
    }

    /* Filter Bar Responsive Grid & Sizing */
    .filter-bar-label {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 0.72rem;
        letter-spacing: 0.04em;
        color: var(--text-main);
        white-space: nowrap;
        margin-bottom: 0.25rem;
    }

    [data-bs-theme="dark"] .filter-bar-label {
        color: #e2e8f0 !important;
    }

    @media (min-width: 992px) {
        .filter-bar-row {
            flex-wrap: nowrap !important;
        }
        .filter-col-search {
            flex: 0 1 260px !important;
            max-width: 270px !important;
            min-width: 180px !important;
        }
        .filter-col-price {
            flex: 0 1 240px !important;
            max-width: 250px !important;
            min-width: 190px !important;
        }
        .filter-col-tags {
            flex: 0 1 200px !important;
            max-width: 210px !important;
            min-width: 165px !important;
        }
        .filter-col-sort {
            flex: 0 1 160px !important;
            max-width: 170px !important;
            min-width: 140px !important;
        }
        .filter-col-btn {
            flex: 0 0 auto !important;
            width: auto !important;
            margin-left: auto !important;
        }
        .filter-col-btn .btn {
            min-width: 90px;
        }
    }
    
    .filter-chip {
        background: var(--surface-muted);
        border: 1px solid var(--border-color) !important;
        color: var(--text-main);
        font-family: 'Space Mono', monospace;
        font-weight: 700;
        font-size: 0.78rem;
        padding: 4px 12px;
        border-radius: 2px;
        clip-path: polygon(0 0, calc(100% - 6px) 0, 100% 6px, 100% 100%, 6px 100%, 0 calc(100% - 6px));
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: var(--transition-smooth);
    }

    .filter-chip a:hover {
        color: var(--bellroy-orange) !important;
    }

    /* Sidebar danh mục dạng Tactical Directory */
    .category-sidebar-card {
        position: -webkit-sticky !important;
        position: sticky !important;
        top: 96px !important;
        z-index: 90 !important;
        background: var(--card-bg) !important;
        border: 1px solid var(--border-color) !important;
        border-radius: 2px !important;
        box-shadow: 2px 2px 0px rgba(0, 0, 0, 0.04) !important;
        transition: box-shadow 0.25s ease, border-color 0.25s ease;
        max-height: calc(100vh - 120px);
        overflow-y: auto;
        scrollbar-width: thin;
    }
    .category-sidebar-card::-webkit-scrollbar {
        width: 3px;
    }
    .category-sidebar-card::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.15);
        border-radius: 1px;
    }
    [data-bs-theme="dark"] .category-sidebar-card {
        background: #1A1A1E !important;
        border: 1px solid #2E2E34 !important;
        box-shadow: 3px 3px 0px #000000 !important;
    }
    [data-bs-theme="dark"] .category-sidebar-card::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
    }

    /* Category list item styling */
    .category-list-item {
        border: none !important;
        border-radius: 2px !important;
        margin-bottom: 4px;
        font-family: 'Space Grotesk', 'Inter', sans-serif;
        font-weight: 600;
        font-size: 0.88rem;
        color: var(--text-muted) !important;
        text-decoration: none !important;
        transition: var(--transition-smooth);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 9px 14px !important;
    }
    
    .category-list-item:hover,
    .category-list-item:focus {
        background-color: var(--surface-muted) !important;
        color: var(--text-main) !important;
        text-decoration: none !important;
        padding-left: 18px !important;
    }

    /* Trạng thái Active cho danh mục (Desktop) */
    .category-list-item.active {
        background: var(--bellroy-charcoal) !important;
        color: #ffffff !important;
        text-decoration: none !important;
        border-left: 3px solid var(--bellroy-orange) !important;
        border-radius: 0 2px 2px 0 !important;
        box-shadow: 2px 2px 0px rgba(0, 0, 0, 0.15);
        font-weight: 700;
    }
    .category-list-item.active i {
        color: var(--bellroy-orange) !important;
    }

    /* Category in offcanvas */
    .offcanvas-category .category-list-item {
        color: var(--text-muted) !important;
        background: transparent;
        text-decoration: none !important;
        border-radius: 2px !important;
    }
    .offcanvas-category .category-list-item:hover {
        background-color: var(--surface-muted) !important;
        color: var(--text-main) !important;
    }
    
    .offcanvas-category .category-list-item.active {
        background: var(--bellroy-charcoal) !important;
        color: #ffffff !important;
        border-left: 3px solid var(--bellroy-orange) !important;
    }
    .offcanvas-category .category-list-item.active i {
        color: var(--bellroy-orange) !important;
    }
    
    /* Product card customizations — Synchronized with Bento Design */
    .card-premium {
        border: 1px solid rgba(0, 0, 0, 0.12);
        border-radius: 2px;
        background: var(--card-bg);
        box-shadow: 2px 2px 0px rgba(0, 0, 0, 0.05);
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        overflow: hidden;
        position: relative;
    }

    [data-bs-theme="dark"] .card-premium {
        border-color: rgba(255, 255, 255, 0.12);
        box-shadow: 3px 3px 0px rgba(0, 0, 0, 0.6);
    }

    .card-premium:hover {
        transform: translate(-3px, -3px);
        border-color: var(--bellroy-orange);
        box-shadow: 5px 5px 0px rgba(205, 76, 32, 0.2);
    }

    [data-bs-theme="dark"] .card-premium:hover {
        border-color: var(--bellroy-orange);
        box-shadow: 5px 5px 0px rgba(205, 76, 32, 0.35);
    }

    .card-img-zoom {
        overflow: hidden;
        position: relative;
        background: radial-gradient(ellipse at 50% 65%, rgba(243, 244, 246, 0.95) 0%, rgba(255, 255, 255, 0.6) 80%);
        border-radius: 2px;
        border: 1px solid rgba(0, 0, 0, 0.06);
    }

    [data-bs-theme="dark"] .card-img-zoom {
        background: radial-gradient(ellipse at 50% 65%, rgba(31, 41, 55, 0.8) 0%, rgba(17, 24, 39, 0.4) 80%);
        border-color: rgba(255, 255, 255, 0.05);
    }
    
    .card-img-zoom img {
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), filter 0.4s ease;
        transform-origin: center;
        filter: drop-shadow(0 14px 20px rgba(0, 0, 0, 0.14));
    }
    
    .card-premium:hover .card-img-zoom img {
        transform: scale(1.08) translateY(-4px);
        filter: drop-shadow(0 18px 26px rgba(0, 0, 0, 0.2));
    }

    /* Wishlist Button on Stage */
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
        box-shadow: 2px 2px 0px rgba(0, 0, 0, 0.05);
    }

    .bento-wishlist-btn:hover {
        transform: translate(-1px, -1px);
        border-color: var(--bellroy-orange);
        box-shadow: 3px 3px 0px rgba(205, 76, 32, 0.3);
    }

    [data-bs-theme="dark"] .bento-wishlist-btn {
        background: rgba(17, 24, 39, 0.9);
        border-color: rgba(255, 255, 255, 0.15);
        color: #e5e7eb;
        box-shadow: 2px 2px 0px rgba(0, 0, 0, 0.4);
    }
    
    /* Stagger fade in effect */
    .grid-item-animate {
        opacity: 0;
        animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    /* ========================================================= */
    /* HOMEDINE-INSPIRED LAPTOP EDITORIAL HERO BANNER           */
    /* ========================================================= */
    .curated-hero-banner {
        position: relative;
        background-color: #121417;
        background-position: center center;
        background-size: cover;
        background-repeat: no-repeat;
        border-radius: 4px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.35), 0 2px 8px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        min-height: calc(520px + 3cm);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 2.8rem 3.2rem 3.2rem 3.2rem;
        transition: all 0.3s ease;
    }

    .curated-hero-video {
        position: absolute;
        top: 50%;
        left: 50%;
        min-width: 100%;
        min-height: 100%;
        width: auto;
        height: auto;
        transform: translate(-50%, -50%);
        object-fit: cover;
        z-index: 0;
        pointer-events: none;
    }

    .curated-hero-overlay {
        position: absolute;
        inset: 0;
        z-index: 1;
        pointer-events: none;
    }

    @media (max-width: 991.98px) {
        .curated-hero-banner {
            padding: 2rem 1.8rem 2.2rem 1.8rem;
            min-height: auto;
        }
        .curated-hero-overlay {
            background: linear-gradient(180deg, rgba(10, 12, 16, 0.92) 0%, rgba(10, 12, 16, 0.82) 55%, rgba(10, 12, 16, 0.88) 100%) !important;
        }
    }

    @media (max-width: 767.98px) {
        .container-fluid[style*="2cm"] {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }
    }

    /* Inner Top Micro Nav (Matching Reference Image) */
    .banner-top-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.5rem;
        padding-bottom: 1.25rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        margin-bottom: 2.2rem;
        position: relative;
        z-index: 3;
    }

    .banner-nav-links {
        display: flex;
        align-items: center;
        gap: 1.6rem;
    }

    .banner-nav-link {
        color: rgba(255, 255, 255, 0.85);
        font-weight: 600;
        font-size: 0.92rem;
        text-decoration: none;
        transition: all 0.2s ease;
        letter-spacing: 0.02em;
    }

    .banner-nav-link:hover {
        color: #ffffff;
        text-shadow: 0 0 12px rgba(255, 255, 255, 0.6);
    }

    .banner-brand-title {
        font-family: 'Space Grotesk', 'Inter', sans-serif;
        font-weight: 800;
        font-size: 1.45rem;
        color: #ffffff;
        letter-spacing: -0.03em;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.2rem;
    }

    .banner-brand-title em {
        font-family: inherit;
        font-style: normal;
        font-weight: 700;
        color: #fcebd2;
    }

    /* Main Content Section */
    .curated-hero-main {
        position: relative;
        z-index: 2;
        margin-top: auto;
    }

    .banner-tag-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: #ffffff;
        font-family: 'Space Mono', monospace;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        padding: 5px 14px;
        border-radius: 2px;
        clip-path: polygon(0 0, calc(100% - 6px) 0, 100% 6px, 100% 100%, 6px 100%, 0 calc(100% - 6px));
        margin-bottom: 1.2rem;
    }

    .curated-headline {
        font-family: 'Space Grotesk', 'Inter', sans-serif;
        font-size: clamp(2.3rem, 4vw, 3.5rem);
        font-weight: 800;
        line-height: 1.14;
        letter-spacing: -0.025em;
        color: #ffffff;
        margin-bottom: 1.15rem;
        text-shadow: 0 3px 12px rgba(0, 0, 0, 0.45);
    }

    .curated-headline em {
        font-family: inherit;
        font-style: normal;
        font-weight: 800;
        color: #fcebd2;
        letter-spacing: -0.01em;
        padding-right: 4px;
    }

    .curated-subtext {
        font-size: 1.05rem;
        line-height: 1.62;
        color: rgba(255, 255, 255, 0.88);
        max-width: 520px;
        margin-bottom: 2rem;
        text-shadow: 0 2px 6px rgba(0, 0, 0, 0.35);
    }

    /* CTA Buttons — Angular Chamfer */
    .btn-curated-shop {
        background: #fdfbf7;
        color: #1c1a19 !important;
        font-family: 'Space Grotesk', sans-serif;
        font-weight: 700;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        padding: 13px 32px;
        border-radius: 2px;
        clip-path: polygon(0 0, calc(100% - 12px) 0, 100% 12px, 100% 100%, 12px 100%, 0 calc(100% - 12px));
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 4px 4px 0px rgba(0, 0, 0, 0.35);
        border: none;
    }

    .btn-curated-shop:hover {
        background: #ffffff;
        color: var(--bellroy-orange) !important;
        transform: translate(-2px, -2px);
        box-shadow: 6px 6px 0px rgba(205, 76, 32, 0.4);
    }

    .btn-curated-shop i {
        transition: transform 0.2s ease;
    }

    .btn-curated-shop:hover i {
        transform: translateX(4px);
    }

    /* Glassmorphism Stat Widget */
    .curated-glass-widget {
        background: rgba(14, 18, 24, 0.58);
    }

    /* ========================================================= */
    /* SWISS TYPOGRAPHIC EDITORIAL NEWS (MINIMALIST STYLE B)     */
    /* ========================================================= */
    .swiss-news-list {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 2px;
        overflow: hidden;
        box-shadow: 2px 2px 0px rgba(0, 0, 0, 0.04);
    }

    [data-bs-theme="dark"] .swiss-news-list {
        background: #1A1A1E !important;
        border-color: #2E2E34 !important;
        box-shadow: 3px 3px 0px #000000 !important;
    }

    .swiss-news-item {
        border-color: var(--border-color) !important;
        transition: background-color 0.2s ease;
    }

    .swiss-news-item:hover {
        background-color: var(--surface-muted);
    }

    .swiss-news-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.5rem;
        padding: 1.35rem 1.5rem;
        color: inherit;
    }

    .swiss-news-content {
        flex: 1;
        min-width: 0;
    }

    .swiss-meta {
        font-family: 'Space Mono', monospace;
        font-size: 0.74rem;
        color: var(--text-muted);
    }

    .swiss-date {
        color: var(--text-muted);
        font-weight: 600;
        letter-spacing: -0.01em;
    }

    .swiss-bullet {
        color: var(--border-color);
        font-size: 0.8rem;
    }

    .swiss-badge {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        background: rgba(205, 76, 32, 0.08);
        color: var(--bellroy-orange);
        border: 1px solid rgba(205, 76, 32, 0.2);
        padding: 2px 8px;
        border-radius: 2px;
    }

    [data-bs-theme="dark"] .swiss-badge {
        background: rgba(205, 76, 32, 0.15);
        color: #ff7849;
        border-color: rgba(205, 76, 32, 0.35);
    }

    .swiss-read-time, .swiss-views {
        color: var(--text-muted);
        font-size: 0.74rem;
    }

    .swiss-title {
        font-family: 'Space Grotesk', sans-serif;
        font-weight: 700;
        font-size: 1.08rem;
        line-height: 1.4;
        color: var(--text-main);
        margin: 0.4rem 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.2s ease;
    }

    .swiss-news-row:hover .swiss-title {
        color: var(--bellroy-orange);
    }

    .swiss-summary {
        font-size: 0.84rem;
        color: var(--text-muted);
        line-height: 1.5;
        margin: 0 0 0.6rem 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .swiss-action {
        display: inline-flex;
        align-items: center;
    }

    .swiss-link {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--bellroy-orange);
        letter-spacing: 0.02em;
        display: inline-flex;
        align-items: center;
        transition: transform 0.2s ease;
    }

    .swiss-link i {
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .swiss-news-row:hover .swiss-link i {
        transform: translateX(4px);
    }

    .swiss-thumb {
        width: 150px;
        height: 96px;
        flex-shrink: 0;
        border-radius: 2px;
        overflow: hidden;
        border: 1px solid var(--border-color);
        background: var(--surface-muted);
    }

    .swiss-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .swiss-news-row:hover .swiss-img {
        transform: scale(1.06);
    }

    @media (max-width: 767.98px) {
        .swiss-news-row {
            padding: 1rem;
            gap: 1rem;
        }
        .swiss-thumb {
            width: 96px;
            height: 72px;
        }
        .swiss-title {
            font-size: 0.96rem;
        }
        .swiss-summary {
            display: none;
        }
        .swiss-views {
            display: none;
        }
    }

    /* ========================================================= */
    /* TECH NEWS & EDITORIAL SECTION (ĐỊNH DẠNG Ô RÕ RÀNG)        */
    /* ========================================================= */
    .news-card-box {
        background: #ffffff !important;
        border: 1px solid rgba(0, 0, 0, 0.08) !important;
        border-radius: 2px !important;
        overflow: hidden;
        box-shadow: 2px 2px 0px rgba(0, 0, 0, 0.04);
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease, border-color 0.25s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    [data-bs-theme="dark"] .news-card-box {
        background: #1e1e24 !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
        box-shadow: 2px 2px 0px rgba(0, 0, 0, 0.25);
    }

    .news-card-box:hover {
        transform: translate(-2px, -2px);
        border-color: var(--bellroy-orange) !important;
        box-shadow: 4px 4px 0px rgba(205, 76, 32, 0.2);
    }

    .news-thumb-wrapper {
        position: relative;
        overflow: hidden;
        width: 100%;
        aspect-ratio: 16 / 9;
        background: #18191c;
        border-radius: 0 !important;
    }

    .news-thumb-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .news-card-box:hover .news-thumb-img {
        transform: scale(1.06);
    }

    .news-category-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        background: rgba(18, 22, 28, 0.85);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: #ffffff;
        font-family: 'Space Mono', monospace;
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        padding: 4px 10px;
        border-radius: 1px;
        clip-path: polygon(0 0, calc(100% - 5px) 0, 100% 5px, 100% 100%, 5px 100%, 0 calc(100% - 5px));
        z-index: 2;
    }

    .news-card-box .card-body {
        padding: 1.25rem 1.25rem 1.1rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .news-meta {
        font-size: 0.76rem;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .news-card-title {
        font-family: 'Inter', sans-serif;
        font-weight: 800;
        font-size: 1.02rem;
        line-height: 1.42;
        color: var(--text-main);
        margin-bottom: 0.6rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.2s ease;
    }

    .news-card-box:hover .news-card-title {
        color: var(--bellroy-orange);
    }

    .news-card-excerpt {
        font-size: 0.84rem;
        color: var(--text-muted);
        line-height: 1.52;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex-grow: 1;
    }

    .news-card-footer {
        padding-top: 0.75rem;
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
    }

    .news-read-more {
        font-weight: 700;
        font-size: 0.84rem;
        color: var(--bellroy-orange);
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        transition: gap 0.2s ease;
    }

    .news-card-box:hover .news-read-more {
        gap: 9px;
    }

    .btn-news-all {
        font-weight: 700;
        font-size: 0.92rem;
        color: var(--text-main);
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        padding: 10px 22px;
        border-radius: 50rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-news-all:hover {
        border-color: var(--bellroy-orange);
        color: var(--bellroy-orange);
        background: var(--surface-muted);
        transform: translateY(-2px);
    }

    /* ========================================================= */
    /* REVIEWS SIDEBAR – SOCIAL PROOF PANEL                       */
    /* ========================================================= */
    .reviews-sidebar {
        background: var(--card-bg, #ffffff);
        border: 1px solid var(--border-color, #e5e7eb);
        border-radius: 3px;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        box-shadow: 2px 2px 0px rgba(0, 0, 0, 0.04);
        transition: border-color 0.3s ease;
    }
    .reviews-sidebar:hover {
        border-color: rgba(205, 76, 32, 0.3);
    }
    [data-bs-theme="dark"] .reviews-sidebar {
        background: #1e1e24;
        border-color: rgba(255, 255, 255, 0.08);
        box-shadow: 2px 2px 0px rgba(0, 0, 0, 0.25);
    }

    .reviews-sidebar-header {
        padding: 1.25rem 1.25rem 1rem;
        border-bottom: 1px solid var(--border-color, #e5e7eb);
    }
    [data-bs-theme="dark"] .reviews-sidebar-header {
        border-color: rgba(255, 255, 255, 0.06);
    }

    .reviews-icon-wrap {
        width: 40px;
        height: 40px;
        border-radius: 2px;
        background: rgba(205, 76, 32, 0.1);
        color: var(--bellroy-orange, #CD4C20);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }
    [data-bs-theme="dark"] .reviews-icon-wrap {
        background: rgba(205, 76, 32, 0.15);
    }

    .reviews-sidebar-title {
        font-family: 'Inter', sans-serif;
        font-weight: 800;
        font-size: 1.05rem;
        color: var(--text-main);
        letter-spacing: -0.01em;
    }

    .reviews-list {
        flex-grow: 1;
        overflow-y: auto;
        max-height: 460px;
        padding: 0;
    }
    .reviews-list::-webkit-scrollbar {
        width: 3px;
    }
    .reviews-list::-webkit-scrollbar-track {
        background: transparent;
    }
    .reviews-list::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.12);
        border-radius: 3px;
    }
    [data-bs-theme="dark"] .reviews-list::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.1);
    }

    .review-item {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border-color, #e5e7eb);
        transition: background 0.2s ease;
    }
    .review-item:last-child {
        border-bottom: none;
    }
    .review-item:hover {
        background: rgba(205, 76, 32, 0.03);
    }
    [data-bs-theme="dark"] .review-item {
        border-color: rgba(255, 255, 255, 0.05);
    }
    [data-bs-theme="dark"] .review-item:hover {
        background: rgba(205, 76, 32, 0.06);
    }

    .review-header {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin-bottom: 0.5rem;
    }

    .review-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        overflow: hidden;
        flex-shrink: 0;
        border: 2px solid rgba(205, 76, 32, 0.2);
    }
    .review-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .review-avatar-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #CD4C20, #e8733f);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.82rem;
        font-family: 'Space Grotesk', sans-serif;
        text-transform: uppercase;
    }

    .review-user-info {
        flex-grow: 1;
        min-width: 0;
    }
    .review-user-name {
        font-weight: 700;
        font-size: 0.84rem;
        color: var(--text-main);
        display: block;
        line-height: 1.2;
        font-family: 'Space Grotesk', sans-serif;
    }
    .review-stars {
        display: flex;
        gap: 1px;
        margin-top: 2px;
    }
    .review-stars .star-active {
        color: #f59e0b;
        font-size: 0.68rem;
    }
    .review-stars .star-empty {
        color: #d1d5db;
        font-size: 0.68rem;
    }
    [data-bs-theme="dark"] .review-stars .star-empty {
        color: #3f3f46;
    }

    .review-date {
        font-size: 0.7rem;
        color: var(--text-muted);
        white-space: nowrap;
        font-family: 'Space Mono', monospace;
        flex-shrink: 0;
    }

    .review-comment {
        font-size: 0.84rem;
        color: var(--text-muted);
        line-height: 1.55;
        margin-bottom: 0.4rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .review-product-link {
        display: inline-flex;
        align-items: center;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--bellroy-orange);
        text-decoration: none;
        font-family: 'Space Mono', monospace;
        padding: 3px 8px;
        border-radius: 2px;
        background: rgba(205, 76, 32, 0.06);
        border: 1px solid rgba(205, 76, 32, 0.12);
        transition: all 0.2s ease;
    }
    .review-product-link:hover {
        background: rgba(205, 76, 32, 0.12);
        color: var(--bellroy-orange);
        border-color: rgba(205, 76, 32, 0.25);
    }
    [data-bs-theme="dark"] .review-product-link {
        background: rgba(205, 76, 32, 0.1);
        border-color: rgba(205, 76, 32, 0.2);
    }

    .reviews-sidebar-footer {
        padding: 1rem 1.25rem;
        border-top: 1px solid var(--border-color, #e5e7eb);
        background: var(--surface-muted, #f9fafb);
    }
    [data-bs-theme="dark"] .reviews-sidebar-footer {
        background: rgba(255, 255, 255, 0.02);
        border-color: rgba(255, 255, 255, 0.06);
    }

    .reviews-summary-stats {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .reviews-avg-score {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--text-main);
        line-height: 1;
        letter-spacing: -0.03em;
    }
    .reviews-avg-stars {
        display: flex;
        align-items: center;
        gap: 2px;
    }
    .reviews-avg-stars .star-active {
        color: #f59e0b;
        font-size: 0.78rem;
    }
    .reviews-avg-stars .star-empty {
        color: #d1d5db;
        font-size: 0.78rem;
    }
    [data-bs-theme="dark"] .reviews-avg-stars .star-empty {
        color: #3f3f46;
    }

    /* ========================================================= */
    /* BENTO COMPARE BUTTON & BADGES                            */
    /* ========================================================= */
    .bento-compare-btn {
        position: absolute;
        top: 48px;
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
        color: #6b7280;
    }

    .bento-compare-btn:hover {
        transform: translate(-1px, -1px);
        border-color: #0ea5e9;
        color: #0284c7;
        box-shadow: 3px 3px 0px rgba(14, 165, 233, 0.25);
    }

    .bento-compare-btn.active {
        background: #0ea5e9 !important;
        border-color: #0284c7 !important;
        color: #ffffff !important;
        box-shadow: 3px 3px 0px rgba(14, 165, 233, 0.4) !important;
    }

    [data-bs-theme="dark"] .bento-compare-btn {
        background: #121214 !important;
        border-color: #2E2E34 !important;
        color: #9ca3af !important;
    }

    [data-bs-theme="dark"] .bento-compare-btn:hover {
        border-color: #38bdf8 !important;
        color: #38bdf8 !important;
    }

    [data-bs-theme="dark"] .bento-compare-btn.active {
        background: #0284c7 !important;
        border-color: #38bdf8 !important;
        color: #ffffff !important;
    }

    /* ========================================================= */
    /* LAPTOP COMPARISON FLOATING DRAWER                         */
    /* ========================================================= */
    .laptop-compare-drawer {
        position: fixed;
        bottom: 24px;
        left: 50%;
        transform: translateX(-50%) translateY(160%);
        z-index: 1060;
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        max-width: 94vw;
        width: 660px;
        pointer-events: none;
    }

    .laptop-compare-drawer.show {
        transform: translateX(-50%) translateY(0);
        pointer-events: auto;
    }

    .compare-drawer-container {
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(0, 0, 0, 0.15);
        border-radius: 4px;
        box-shadow: 0 16px 36px rgba(0, 0, 0, 0.16), 4px 4px 0px rgba(0, 0, 0, 0.08);
        padding: 12px 18px;
    }

    [data-bs-theme="dark"] .compare-drawer-container {
        background: rgba(20, 20, 24, 0.96);
        border-color: #2E2E34;
        box-shadow: 0 16px 36px rgba(0, 0, 0, 0.4), 4px 4px 0px #000000;
    }

    .compare-drawer-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
        padding-bottom: 6px;
        border-bottom: 1px solid var(--border-color, #e5e7eb);
    }

    .compare-pulse-beacon {
        width: 8px;
        height: 8px;
        background: #0ea5e9;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 8px #0ea5e9;
        animation: comparePulse 2s infinite;
    }

    @keyframes comparePulse {
        0% { transform: scale(0.95); opacity: 0.8; }
        50% { transform: scale(1.3); opacity: 1; }
        100% { transform: scale(0.95); opacity: 0.8; }
    }

    .compare-drawer-title {
        font-family: 'Space Grotesk', sans-serif;
        font-weight: 700;
        font-size: 0.84rem;
        letter-spacing: 0.04em;
        color: var(--text-main, #111827);
    }

    .btn-close-compare {
        background: transparent;
        border: none;
        color: var(--text-muted, #6b7280);
        padding: 2px 6px;
        cursor: pointer;
        font-size: 0.85rem;
        transition: color 0.15s ease;
    }

    .btn-close-compare:hover {
        color: var(--text-main, #111827);
    }

    .compare-drawer-body {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .compare-slots-row {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-grow: 1;
    }

    .compare-slot-item {
        width: 54px;
        height: 54px;
        border: 1px dashed var(--border-color, #d1d5db);
        border-radius: 3px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        background: var(--surface-card, #f9fafb);
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    [data-bs-theme="dark"] .compare-slot-item {
        background: #17171b;
        border-color: #33333b;
    }

    .compare-slot-item.filled {
        border-style: solid;
        border-color: #0ea5e9;
        background: #ffffff;
        padding: 3px;
    }

    [data-bs-theme="dark"] .compare-slot-item.filled {
        background: #1f1f25;
        border-color: #0284c7;
    }

    .compare-slot-item img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .compare-slot-item .slot-placeholder {
        font-size: 0.72rem;
        color: var(--text-muted, #9ca3af);
        font-family: 'Space Mono', monospace;
    }

    .compare-slot-item .btn-remove-slot {
        position: absolute;
        top: -6px;
        right: -6px;
        width: 18px;
        height: 18px;
        background: #ef4444;
        color: #ffffff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        cursor: pointer;
        border: 1px solid #ffffff;
        line-height: 1;
        transition: transform 0.15s ease;
    }

    .compare-slot-item .btn-remove-slot:hover {
        transform: scale(1.15);
    }

    .compare-actions-row {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }

    .btn-launch-compare {
        background: #0ea5e9;
        border-color: #0ea5e9;
        color: #ffffff;
        font-family: 'Space Grotesk', sans-serif;
        letter-spacing: 0.02em;
        transition: all 0.2s ease;
    }

    .btn-launch-compare:hover {
        background: #0284c7;
        border-color: #0284c7;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(14, 165, 233, 0.35);
    }

    /* Comparison Modal Styling */
    .compare-table th {
        background: var(--surface-card, #f9fafb);
        color: var(--text-main, #111827);
        border-color: var(--border-color, #e5e7eb);
    }

    .compare-table td {
        border-color: var(--border-color, #e5e7eb);
        color: var(--text-main, #111827);
        vertical-align: middle;
    }

    [data-bs-theme="dark"] .compare-table th,
    [data-bs-theme="dark"] .compare-table td {
        background: #17171b;
        border-color: #2E2E34;
        color: #f3f4f6;
    }

    /* ========================================================= */
    /* RECENTLY VIEWED PRODUCTS STYLES                           */
    /* ========================================================= */
    .recent-product-card {
        background: var(--surface-card, #ffffff);
        border: 1px solid var(--border-color, #e5e7eb);
        border-radius: 2px;
        padding: 10px;
        display: flex;
        flex-direction: column;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 2px 2px 0px rgba(0, 0, 0, 0.03);
    }

    .recent-product-card:hover {
        transform: translateY(-2px);
        border-color: var(--bellroy-orange, #CD4C20);
        box-shadow: 3px 3px 0px rgba(205, 76, 32, 0.15);
    }

    [data-bs-theme="dark"] .recent-product-card {
        background: #1A1A1E !important;
        border-color: #2E2E34 !important;
        box-shadow: 2px 2px 0px #000000 !important;
    }

    [data-bs-theme="dark"] .recent-product-card:hover {
        border-color: #CD4C20 !important;
    }

    .recent-img-wrap {
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fdfdfd;
        border-radius: 2px;
        margin-bottom: 8px;
        overflow: hidden;
        padding: 6px;
    }

    [data-bs-theme="dark"] .recent-img-wrap {
        background: #141417;
    }

    .recent-img-wrap img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
        transition: transform 0.3s ease;
    }

    .recent-product-card:hover .recent-img-wrap img {
        transform: scale(1.08);
    }

    .recent-body {
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .recent-cat-pill {
        font-size: 0.68rem;
        font-weight: 600;
        color: var(--text-muted, #6b7280);
        text-transform: uppercase;
        font-family: 'Space Mono', monospace;
        margin-bottom: 4px;
    }

    .recent-title {
        font-size: 0.82rem;
        font-weight: 700;
        line-height: 1.3;
        margin-bottom: 6px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 2.15rem;
    }

    [data-bs-theme="dark"] .recent-title a {
        color: #f3f4f6 !important;
    }

    .recent-price {
        font-size: 0.85rem;
        font-weight: 800;
        color: var(--bellroy-orange, #CD4C20);
        font-family: 'Space Mono', monospace;
        margin-bottom: 8px;
    }

    .btn-recent-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        padding: 4px 8px;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 2px;
        text-decoration: none;
        background: var(--surface-muted, #f3f4f6);
        color: var(--text-main, #111827);
        border: 1px solid var(--border-color, #e5e7eb);
        transition: all 0.15s ease;
        margin-top: auto;
    }

    .btn-recent-action:hover {
        background: var(--bellroy-orange, #CD4C20);
        color: #ffffff !important;
        border-color: var(--bellroy-orange, #CD4C20);
    }

    [data-bs-theme="dark"] .btn-recent-action {
        background: #232329;
        color: #e5e7eb;
        border-color: #2E2E34;
    }

    [data-bs-theme="dark"] .btn-recent-action:hover {
        background: #CD4C20;
        color: #ffffff !important;
    }

    /* Single-Row Horizontal Track (No vertical wrapping) */
    .recent-products-track {
        overflow-x: auto;
        overflow-y: hidden;
        scrollbar-width: thin;
        scrollbar-color: rgba(205, 76, 32, 0.3) transparent;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
        padding-bottom: 6px;
    }

    .recent-products-track::-webkit-scrollbar {
        height: 4px;
    }

    .recent-products-track::-webkit-scrollbar-thumb {
        background: rgba(205, 76, 32, 0.3);
        border-radius: 2px;
    }

    .recent-item-col {
        flex: 0 0 calc((100% - 5 * 1rem) / 6);
        min-width: 175px;
        scroll-snap-align: start;
    }

    @media (max-width: 1399.98px) {
        .recent-item-col {
            flex: 0 0 calc((100% - 4 * 1rem) / 5);
            min-width: 170px;
        }
    }

    @media (max-width: 1199.98px) {
        .recent-item-col {
            flex: 0 0 calc((100% - 3 * 1rem) / 4);
            min-width: 165px;
        }
    }

    @media (max-width: 991.98px) {
        .recent-item-col {
            flex: 0 0 calc((100% - 2 * 1rem) / 3);
            min-width: 160px;
        }
    }

    @media (max-width: 575.98px) {
        .recent-item-col {
            flex: 0 0 calc((100% - 1 * 0.75rem) / 2);
            min-width: 150px;
        }
    }
</style>

<!-- ===== LAPGEARZONE ULTRA-PREMIUM PRODUCT SLIDER HERO BANNER ===== -->
@php
    // Nạp danh sách sự kiện đang hoạt động kèm sản phẩm (sắp xếp theo sort_order asc)
    $activeEvents = \App\Models\Event::with(['products' => function($q) {
        $q->where('quantity', '>', 0)->with('category');
    }])
    ->where('is_active', true)
    ->orderBy('sort_order', 'asc')
    ->get();

    // 1. Thống kê sản phẩm bán chạy nhất tháng (Đồng bộ chuẩn xác 100% với Admin Dashboard: Thống Kê Sản Phẩm Bán Chạy Nhất Tháng)
    $topSellingQuery = \Illuminate\Support\Facades\DB::table('order_items')
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->whereIn('orders.status', ['paid', 'completed'])
        ->where('orders.cash_remitted', true)
        ->whereMonth('orders.created_at', now()->month)
        ->whereYear('orders.created_at', now()->year)
        ->select('order_items.product_id', \Illuminate\Support\Facades\DB::raw('SUM(order_items.quantity) as total_sold'))
        ->groupBy('order_items.product_id')
        ->orderByDesc('total_sold')
        ->limit(10)
        ->get();

    $topSellingIds = $topSellingQuery->pluck('product_id')->toArray();
    $topLaptopsProducts = collect();
    if (!empty($topSellingIds)) {
        $productsDict = \App\Models\Product::with(['category', 'images', 'tags'])
            ->whereIn('id', $topSellingIds)
            ->where('quantity', '>', 0)
            ->get()
            ->keyBy('id');
        foreach ($topSellingQuery as $stat) {
            if (isset($productsDict[$stat->product_id])) {
                $prod = $productsDict[$stat->product_id];
                $prod->total_sold = (int)$stat->total_sold;
                $topLaptopsProducts->push($prod);
            }
        }
    }

    // Nếu chưa đủ 4 sản phẩm bán chạy trong tháng, bổ sung thêm từ các sản phẩm nổi bật
    if ($topLaptopsProducts->count() < 4) {
        $existingIds = $topLaptopsProducts->pluck('id')->toArray();
        $fallbackProducts = \App\Models\Product::whereNotIn('id', $existingIds)
            ->where('quantity', '>', 0)
            ->where('is_featured', true)
            ->with(['category', 'images', 'tags'])
            ->take(8 - $topLaptopsProducts->count())
            ->get();
        $topLaptopsProducts = $topLaptopsProducts->concat($fallbackProducts);
    }

    // Đảm bảo tab "Top Laptops" luôn hiển thị đúng danh sách Top Bán Chạy Nhất (Đồng bộ với Admin Dashboard)
    $hasTopEvent = false;
    foreach ($activeEvents as $event) {
        if ($event->slug === 'top-laptops' || \Illuminate\Support\Str::slug($event->name) === 'top-laptops' || str_contains(strtolower($event->name), 'top laptop')) {
            $hasTopEvent = true;
            if ($topLaptopsProducts->isNotEmpty()) {
                $event->setRelation('products', $topLaptopsProducts);
            }
        }
    }

    // Nếu sự kiện Top Laptops chưa có hoặc bị ẩn, tự động tạo Virtual Event đưa lên đầu tiên
    if (!$hasTopEvent && $topLaptopsProducts->isNotEmpty()) {
        $topVirtualEvent = new \App\Models\Event([
            'name' => 'Top Laptops',
            'slug' => 'top-laptops',
            'headline' => 'Top Laptop Bán Chạy Nhất',
            'is_active' => true,
            'sort_order' => 0,
        ]);
        $topVirtualEvent->id = 1;
        $topVirtualEvent->setRelation('products', $topLaptopsProducts);
        $activeEvents->prepend($topVirtualEvent);
    }

    // Lấy thống kê số lượng đã bán cho các sản phẩm trong các sự kiện
    $allEventProductIds = $activeEvents->pluck('products')->flatten()->pluck('id')->unique()->toArray();
    $eventProductStats = [];
    if (!empty($allEventProductIds)) {
        $eventProductStats = \Illuminate\Support\Facades\DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['paid', 'completed'])
            ->where('orders.cash_remitted', true)
            ->whereIn('order_items.product_id', $allEventProductIds)
            ->select('order_items.product_id', \Illuminate\Support\Facades\DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('order_items.product_id')
            ->pluck('total_sold', 'product_id')
            ->toArray();
    }

    foreach ($activeEvents as $event) {
        foreach ($event->products as $product) {
            if (!isset($product->total_sold)) {
                $product->total_sold = (int)($eventProductStats[$product->id] ?? 0);
            }
        }
    }

    // Hero Banner Dynamic Settings from Admin
    $heroBannerType     = \App\Models\Setting::getValue('hero_banner_type', 'video');
    $heroBannerVideo    = \App\Models\Setting::getValue('hero_banner_video', 'videos/laptop_hero.mp4');
    $heroBannerImage    = \App\Models\Setting::getValue('hero_banner_image', 'images/laptop_hero_banner.jpg');
    $heroBannerBrand    = \App\Models\Setting::getValue('hero_banner_brand', 'LapGearZone');
    $heroBannerBadge    = \App\Models\Setting::getValue('hero_banner_badge', 'Flagship Workstation & Gaming 2026');
    $heroBannerHeadline = \App\Models\Setting::getValue('hero_banner_headline', 'Đẳng Cấp Laptop Cho Không Gian Đỉnh Cao');
    $heroBannerSubtext  = \App\Models\Setting::getValue('hero_banner_subtext', 'Khám phá thế hệ laptop mới nhất với vi xử lý AI tiên phong, màn hình OLED siêu sắc nét và thời lượng pin đột phá. Sẵn sàng đồng hành cùng mọi ý tưởng lớn.');
    $heroBannerBtnText  = \App\Models\Setting::getValue('hero_banner_btn_text', 'Khám phá ngay');
    $heroBannerBtnUrl   = \App\Models\Setting::getValue('hero_banner_btn_url', '#product-grid-section');
    $heroBannerOverlay  = (float) \App\Models\Setting::getValue('hero_banner_overlay', '0.75');

    $videoUrl = filter_var($heroBannerVideo, FILTER_VALIDATE_URL) ? $heroBannerVideo : asset($heroBannerVideo);
    $imageUrl = filter_var($heroBannerImage, FILTER_VALIDATE_URL) ? $heroBannerImage : asset($heroBannerImage);
@endphp

<!-- ===== HOMEDINE-INSPIRED LAPTOP EDITORIAL HERO BANNER ===== -->
<div class="container-fluid mt-3 mb-4 animate-fade-in" style="padding-left: 2cm !important; padding-right: 2cm !important;">
    <div class="curated-hero-banner" style="background-image: url('{{ $imageUrl }}');">
        
        @if($heroBannerType === 'video')
            <!-- Dynamic Autoplaying Looping Hero Video -->
            <video class="curated-hero-video" autoplay muted loop playsinline poster="{{ $imageUrl }}">
                <source src="{{ $videoUrl }}" type="video/mp4">
            </video>
        @endif

        <!-- Dark Gradient Overlay for optimal legibility -->
        <div class="curated-hero-overlay" style="background: linear-gradient(90deg, rgba(10, 12, 16, {{ $heroBannerOverlay }}) 0%, rgba(10, 12, 16, {{ $heroBannerOverlay * 0.82 }}) 42%, rgba(10, 12, 16, {{ $heroBannerOverlay * 0.40 }}) 75%, rgba(10, 12, 16, 0.15) 100%);"></div>

        <!-- Top Micro Navigation Bar (Matching Reference) -->
        <div class="banner-top-bar d-none d-md-flex">
            <a href="{{ route('welcome') }}" class="banner-brand-title">
                @if($heroBannerBrand === 'LapGearZone')
                    LapGear<em>Zone</em>
                @else
                    {{ $heroBannerBrand }}
                @endif
            </a>
        </div>

        <!-- Main Banner Content -->
        <div class="row align-items-end g-4 curated-hero-main">
            <!-- Left Column: Typography & CTA -->
            <div class="col-lg-8 text-start">
                @if(!empty($heroBannerBadge))
                    <div class="banner-tag-badge">
                        <i class="bi bi-lightning-charge-fill text-warning"></i> {{ $heroBannerBadge }}
                    </div>
                @endif

                <h1 class="curated-headline">
                    @if($heroBannerHeadline === 'Đẳng Cấp Laptop Cho Không Gian Đỉnh Cao')
                        Đẳng Cấp <em>Laptop</em> Cho<br>
                        Không Gian Đỉnh Cao
                    @else
                        {!! nl2br(e($heroBannerHeadline)) !!}
                    @endif
                </h1>

                @if(!empty($heroBannerSubtext))
                    <p class="curated-subtext">
                        {{ $heroBannerSubtext }}
                    </p>
                @endif

                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <a href="{{ $heroBannerBtnUrl }}" class="btn-curated-shop">
                        <span>{{ $heroBannerBtnText }}</span>
                        <i class="bi bi-arrow-right fs-5"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- ===== KHỐI CAM KẾT THƯƠNG HIỆU (BRAND TRUST STRIP) ===== -->
<div class="container-fluid my-4" style="padding-left: 2cm !important; padding-right: 2cm !important;">
    <div class="brand-perks-strip">
        <div class="perk-card">
            <div class="perk-icon-box perk-icon-orange">
                <i class="bi bi-shield-check"></i>
            </div>
            <div>
                <div class="perk-title">Bảo Hành Chính Hãng</div>
                <div class="perk-desc">24 tháng tận tâm, 1 đổi 1 trong 30 ngày</div>
            </div>
        </div>
        <div class="perk-card">
            <div class="perk-icon-box perk-icon-green">
                <i class="bi bi-lightning-charge"></i>
            </div>
            <div>
                <div class="perk-title">Giao Hỏa Tốc 2 Giờ</div>
                <div class="perk-desc">Miễn phí ship toàn quốc cho đơn từ 5tr</div>
            </div>
        </div>
        <div class="perk-card">
            <div class="perk-icon-box perk-icon-blue">
                <i class="bi bi-credit-card-2-front"></i>
            </div>
            <div>
                <div class="perk-title">Trả Góp 0% Lãi Suất</div>
                <div class="perk-desc">Duyệt online 5 phút qua thẻ &amp; PayOS</div>
            </div>
        </div>
        <div class="perk-card">
            <div class="perk-icon-box perk-icon-purple">
                <i class="bi bi-headset"></i>
            </div>
            <div>
                <div class="perk-title">Hỗ Trợ Trọn Đời Máy</div>
                <div class="perk-desc">Vệ sinh máy &amp; cài phần mềm miễn phí</div>
            </div>
        </div>
    </div>
</div>

@if($activeEvents->count() > 0)
    <div class="container-fluid animate-fade-in bento-featured-section" style="padding-left: 2cm !important; padding-right: 2cm !important;">
        
        <!-- Đường kẻ ngang trên -->
        <div class="bento-section-divider"></div>

        <!-- Minimalist Typographic Navigation Bar (Dynamic Event Tabs | Shop) -->
        <div class="d-flex justify-content-center">
            <nav class="bento-minimal-nav" role="tablist" aria-label="Bento Navigation">
                @foreach($activeEvents as $idx => $event)
                    <button type="button" 
                            class="bento-nav-item {{ $idx === 0 ? 'active' : '' }}" 
                            data-event-id="{{ $event->id }}"
                            data-headline="{{ $event->headline ?: $event->name }}"
                            data-display-type="{{ $event->display_type ?? 'slider' }}"
                            title="Xem {{ $event->name }}">
                        {{ $event->name }}
                    </button>
                @endforeach
            </nav>
        </div>

        <!-- Đường kẻ ngang dưới -->
        <div class="bento-nav-divider"></div>


        <!-- Horizontal Bento Cards Slider with Side Arrows on same row -->
        <div class="bento-slider-wrapper position-relative">
            <!-- Left Navigation Arrow (Same row as cards) -->
            <button type="button" id="bentoPrevBtn" class="bento-side-arrow bento-arrow-left" aria-label="Trước" title="Xem sản phẩm trước">
                <i class="bi bi-chevron-left"></i>
            </button>

            <!-- Viewport Window for Smooth Sliding / Banner Display -->
            <div class="bento-slider-viewport" id="bentoSliderViewport">
                @foreach($activeEvents as $idx => $event)
                    @php
                        $displayType = $event->display_type ?? 'slider';
                    @endphp

                    @if($displayType === 'banner')
                        <!-- SỰ KIỆN DẠNG BANNER LỚN ĐỘC LẬP -->
                        <div id="bentoTrackEvent{{ $event->id }}" class="bento-event-track w-100 {{ $idx === 0 ? '' : 'd-none' }}" data-track-id="{{ $event->id }}" data-display-type="banner">
                            @if($event->banner_image)
                                @if($event->banner_link)
                                <a href="{{ $event->banner_link }}" class="d-block text-decoration-none bento-banner-showcase rounded-2 overflow-hidden shadow-sm border" style="border-color: rgba(255, 255, 255, 0.08) !important;">
                                    <img src="{{ $event->banner_url }}" alt="{{ $event->name }}" class="w-100 d-block" style="object-fit: contain; max-height: 650px;">
                                </a>
                                @else
                                <div class="bento-banner-showcase rounded-2 overflow-hidden shadow-sm border" style="border-color: rgba(255, 255, 255, 0.08) !important;">
                                    <img src="{{ $event->banner_url }}" alt="{{ $event->name }}" class="w-100 d-block" style="object-fit: contain; max-height: 650px;">
                                </div>
                                @endif
                            @else
                                <div class="text-center py-5 text-muted border rounded-2 bg-light bg-opacity-25">
                                    <i class="bi bi-card-image display-4 text-warning opacity-75 d-block mb-2"></i>
                                    <h5 class="fw-bold">Chưa tải lên ảnh Banner cho sự kiện này</h5>
                                    <p class="small text-muted mb-0">Vui lòng vào Quản Lý Sự Kiện trong Admin để tải ảnh Banner lên.</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <!-- SỰ KIỆN SLIDER HOẶC KẾT HỢP (BANNER + SLIDER) -->
                        <div id="bentoTrackEvent{{ $event->id }}" class="bento-event-track w-100 {{ $idx === 0 ? '' : 'd-none' }}" data-track-id="{{ $event->id }}" data-display-type="{{ $displayType }}">
                            @if($displayType === 'both' && $event->banner_image)
                                <!-- Banner Header cho Sự Kiện Kết Hợp -->
                                @if($event->banner_link)
                                <a href="{{ $event->banner_link }}" class="d-block text-decoration-none bento-combined-banner rounded-2 overflow-hidden shadow-sm mb-4 border" style="border-color: rgba(255, 255, 255, 0.08) !important;">
                                    <img src="{{ $event->banner_url }}" alt="{{ $event->name }}" class="w-100 d-block" style="object-fit: contain; max-height: 500px;">
                                </a>
                                @else
                                <div class="bento-combined-banner rounded-2 overflow-hidden shadow-sm mb-4 border" style="border-color: rgba(255, 255, 255, 0.08) !important;">
                                    <img src="{{ $event->banner_url }}" alt="{{ $event->name }}" class="w-100 d-block" style="object-fit: contain; max-height: 500px;">
                                </div>
                                @endif
                            @endif

                            <!-- Product Cards Slider Track -->
                            <div class="bento-cards-track" data-track-id="{{ $event->id }}">
                                @forelse($event->products->take(10) as $eventProduct)
                                    <div class="bento-card-slide">
                                        <div class="bento-laptop-card h-100 d-flex flex-column">
                                            
                                            <!-- Studio Image Stage -->
                                            <div class="bento-img-stage">
                                                <!-- Category Badge (Top Left) -->
                                                <span class="bento-cat-badge">
                                                    {{ $eventProduct->category->name ?? 'Laptop' }}
                                                </span>

                                                <!-- Wishlist Button (Top Right) -->
                                                <button class="bento-wishlist-btn btn-wishlist" 
                                                        data-product-id="{{ $eventProduct->id }}" 
                                                        onclick="toggleWishlist(event, this, {{ $eventProduct->id }})"
                                                        title="Yêu thích">
                                                    <i class="bi {{ in_array($eventProduct->id, $wishlistIds ?? []) ? 'bi-heart-fill text-danger' : 'bi-heart text-secondary' }}"></i>
                                                </button>

                                                <!-- Compare Button (Top Right, Below Wishlist) -->
                                                <button class="bento-compare-btn btn-compare" 
                                                        data-id="{{ $eventProduct->id }}"
                                                        data-name="{{ e($eventProduct->name) }}"
                                                        data-price="{{ $eventProduct->price }}"
                                                        data-price-format="{{ number_format($eventProduct->price, 0, ',', '.') }}đ"
                                                        data-image="{{ $eventProduct->image ? asset('storage/'.$eventProduct->image) : '' }}"
                                                        data-category="{{ e($eventProduct->category->name ?? 'Laptop') }}"
                                                        data-url="{{ route('products.show', $eventProduct) }}"
                                                        data-stock="{{ $eventProduct->quantity }}"
                                                        data-desc="{{ e(Str::limit($eventProduct->description ?? 'Đang cập nhật', 120)) }}"
                                                        data-tags="{{ e($eventProduct->tags ? $eventProduct->tags->pluck('name')->join(', ') : '') }}"
                                                        onclick="toggleCompareProduct(event, this)"
                                                        title="So sánh cấu hình">
                                                    <i class="bi bi-arrow-left-right"></i>
                                                </button>

                                                <!-- Laptop Image -->
                                                <a href="{{ route('products.show', $eventProduct) }}" 
                                                   onclick="trackRecentlyViewed({{ $eventProduct->id }}, '{{ addslashes($eventProduct->name) }}', {{ $eventProduct->price }}, '{{ number_format($eventProduct->price, 0, ',', '.') }}đ', '{{ $eventProduct->image ? asset('storage/'.$eventProduct->image) : '' }}', '{{ addslashes($eventProduct->category->name ?? 'Laptop') }}', '{{ route('products.show', $eventProduct) }}')"
                                                   class="d-flex align-items-center justify-content-center w-100 h-100 text-decoration-none">
                                                    @if($eventProduct->image)
                                                        <img src="{{ asset('storage/'.$eventProduct->image) }}" class="img-fluid" alt="{{ $eventProduct->name }}" loading="lazy">
                                                    @else
                                                        <i class="bi bi-laptop display-1 text-muted opacity-50"></i>
                                                    @endif
                                                </a>
                                            </div>

                                            <!-- Card Content Body -->
                                            <div class="d-flex flex-column flex-grow-1">
                                                <h4 class="bento-product-title" title="{{ $eventProduct->name }}">
                                                    <a href="{{ route('products.show', $eventProduct) }}">
                                                        {{ $eventProduct->name }}
                                                    </a>
                                                </h4>

                                                <!-- Sold Count Tag -->
                                                <div class="bento-sold-wrap">
                                                    <span class="bento-sold-badge">
                                                        <i class="bi bi-fire text-danger"></i>
                                                        @if(($eventProduct->total_sold ?? 0) > 0)
                                                            <span>Đã bán: <strong>{{ number_format($eventProduct->total_sold) }}</strong> máy</span>
                                                        @else
                                                            <span>Mới mở bán: <strong>Hàng mới 100%</strong></span>
                                                        @endif
                                                    </span>
                                                </div>

                                                <!-- Price & Stock -->
                                                <div class="bento-price-wrap">
                                                    <div class="bento-price">
                                                        {{ number_format($eventProduct->price, 0, ',', '.') }}<span class="currency">đ</span>
                                                    </div>
                                                    <div class="bento-stock-tag">
                                                        Kho: <strong>{{ $eventProduct->quantity }}</strong> máy
                                                    </div>
                                                </div>

                                                <!-- Excerpt -->
                                                <p class="bento-product-desc">
                                                    {{ Str::limit($eventProduct->description ?? 'Đẳng cấp thiết kế, cấu hình đột phá sẵn sàng tối ưu cho mọi công việc và giải trí.', 75) }}
                                                </p>

                                                <!-- Actions -->
                                                <div class="bento-actions-wrap">
                                                    <a href="{{ route('products.show', $eventProduct) }}" class="btn-bento-action flex-grow-1">
                                                        <span>Xem Chi Tiết</span>
                                                        <i class="bi bi-arrow-right ms-1"></i>
                                                    </a>
                                                    @auth
                                                        @if($eventProduct->quantity > 0)
                                                            <button type="button" class="btn-bento-cart" onclick="quickAddToCart({{ $eventProduct->id }}, this)" title="Thêm vào giỏ hàng">
                                                                <i class="bi bi-cart-plus"></i>
                                                            </button>
                                                        @endif
                                                    @endauth
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                @empty
                                    <div class="w-100 text-center py-5 text-muted">
                                        <i class="bi bi-box-seam fs-2 d-block mb-2 text-warning"></i>
                                        <span>Chưa có sản phẩm nào trong sự kiện này.</span>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endif
                @endforeach
            </div> <!-- End #bentoSliderViewport -->

            <!-- Right Navigation Arrow (Same row as cards) -->
            <button type="button" id="bentoNextBtn" class="bento-side-arrow bento-arrow-right" aria-label="Tiếp" title="Xem sản phẩm tiếp theo">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </div>

    <!-- Script for Dynamic Bento Tab Switching & True Infinite Circular Carousel Navigation -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabButtons = document.querySelectorAll('.bento-minimal-nav .bento-nav-item[data-event-id]');
            const tabLinkShop = document.getElementById('tabLinkShop');
            const title = document.getElementById('bentoSectionTitle');
            const prevBtn = document.getElementById('bentoPrevBtn');
            const nextBtn = document.getElementById('bentoNextBtn');
            const viewport = document.getElementById('bentoSliderViewport');

            function getActiveTrack() {
                return document.querySelector('.bento-cards-track:not(.d-none)');
            }

            const carouselsMap = new Map();

            function setupInfiniteCarousel(track) {
                if (!track || !viewport) return null;

                const originalCards = Array.from(track.children).filter(c => c.classList.contains('bento-card-slide'));
                const count = originalCards.length;
                if (count < 2) return null;

                // Clone cards before and after for continuous seamless loop
                const clonesBefore = originalCards.map(c => c.cloneNode(true));
                const clonesAfter = originalCards.map(c => c.cloneNode(true));

                clonesBefore.forEach(c => track.insertBefore(c, originalCards[0]));
                clonesAfter.forEach(c => track.appendChild(c));

                const TRANSITION_STYLE = 'transform 0.65s cubic-bezier(0.22, 1, 0.36, 1)';
                let currentIndex = count; // Starts at Real Card 1 (index count)
                let isAnimating = false;

                function getStep() {
                    const firstCard = track.querySelector('.bento-card-slide');
                    if (!firstCard) return 360;
                    const style = window.getComputedStyle(track);
                    const gap = parseFloat(style.gap) || 24;
                    return firstCard.offsetWidth + gap;
                }

                function setPosition(animate = true) {
                    const step = getStep();
                    const offset = currentIndex * step;
                    track.style.transition = animate ? TRANSITION_STYLE : 'none';
                    track.style.transform = `translateX(-${offset}px)`;
                }

                // Initial positioning without animation
                setPosition(false);

                // Handle seamless wrap-around when animation ends
                track.addEventListener('transitionend', function (e) {
                    if (e.target !== track || e.propertyName !== 'transform') return;

                    if (currentIndex >= count * 2) {
                        track.style.transition = 'none';
                        currentIndex -= count;
                        setPosition(false);
                        void track.offsetHeight; // Force reflow
                    } else if (currentIndex < count) {
                        track.style.transition = 'none';
                        currentIndex += count;
                        setPosition(false);
                        void track.offsetHeight; // Force reflow
                    }

                    isAnimating = false;
                });

                function next() {
                    if (isAnimating) return;
                    isAnimating = true;
                    currentIndex++;
                    setPosition(true);

                    // Safety timeout in case transitionend is interrupted
                    setTimeout(() => {
                        if (isAnimating) {
                            if (currentIndex >= count * 2) {
                                track.style.transition = 'none';
                                currentIndex -= count;
                                setPosition(false);
                                void track.offsetHeight;
                            }
                            isAnimating = false;
                        }
                    }, 700);
                }

                function prev() {
                    if (isAnimating) return;
                    isAnimating = true;
                    currentIndex--;
                    setPosition(true);

                    setTimeout(() => {
                        if (isAnimating) {
                            if (currentIndex < count) {
                                track.style.transition = 'none';
                                currentIndex += count;
                                setPosition(false);
                                void track.offsetHeight;
                            }
                            isAnimating = false;
                        }
                    }, 700);
                }

                function refresh() {
                    setPosition(false);
                    isAnimating = false;
                }

                return { next, prev, refresh };
            }

            function getActiveTrack() {
                const activeWrap = document.querySelector('[id^="bentoTrackEvent"]:not(.d-none)');
                if (!activeWrap) return null;
                return activeWrap.querySelector('.bento-cards-track');
            }

            function getActiveCarousel() {
                const active = getActiveTrack();
                if (!active) return null;
                const trackId = active.getAttribute('data-track-id');
                if (!carouselsMap.has(trackId)) {
                    carouselsMap.set(trackId, setupInfiniteCarousel(active));
                }
                return carouselsMap.get(trackId);
            }

            function updateArrowVisibility(displayType, cardCount) {
                if (!prevBtn || !nextBtn) return;
                if (displayType === 'banner' || cardCount < 2) {
                    prevBtn.classList.add('d-none');
                    nextBtn.classList.add('d-none');
                } else {
                    prevBtn.classList.remove('d-none');
                    nextBtn.classList.remove('d-none');
                }
            }

            // Init initial track carousel
            const initialActiveWrap = document.querySelector('[id^="bentoTrackEvent"]:not(.d-none)');
            if (initialActiveWrap) {
                const initType = initialActiveWrap.getAttribute('data-display-type') || 'slider';
                const innerCards = initialActiveWrap.querySelectorAll('.bento-card-slide').length;
                updateArrowVisibility(initType, innerCards);
                const initialActiveTrack = initialActiveWrap.querySelector('.bento-cards-track');
                if (initialActiveTrack) {
                    const initId = initialActiveTrack.getAttribute('data-track-id');
                    carouselsMap.set(initId, setupInfiniteCarousel(initialActiveTrack));
                }
            }

            nextBtn?.addEventListener('click', function () {
                getActiveCarousel()?.next();
            });

            prevBtn?.addEventListener('click', function () {
                getActiveCarousel()?.prev();
            });

            function switchEventTab(btn) {
                const eventId = btn.getAttribute('data-event-id');
                const headline = btn.getAttribute('data-headline');
                const displayType = btn.getAttribute('data-display-type') || 'slider';

                tabButtons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                if (title && headline) {
                    title.textContent = headline;
                }

                document.querySelectorAll('[id^="bentoTrackEvent"]').forEach(t => {
                    t.classList.add('d-none');
                });

                const targetTrack = document.getElementById('bentoTrackEvent' + eventId);
                if (targetTrack) {
                    targetTrack.classList.remove('d-none');
                    const innerCards = targetTrack.querySelectorAll('.bento-card-slide').length;
                    updateArrowVisibility(displayType, innerCards);

                    const innerTrack = targetTrack.querySelector('.bento-cards-track');
                    if (innerTrack) {
                        if (!carouselsMap.has(eventId)) {
                            carouselsMap.set(eventId, setupInfiniteCarousel(innerTrack));
                        } else {
                            carouselsMap.get(eventId)?.refresh();
                        }
                    }
                }
            }

            tabButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    switchEventTab(this);
                });
            });

            tabLinkShop?.addEventListener('click', function (e) {
                e.preventDefault();
                const filterSec = document.getElementById('ajax-filter-container');
                if (filterSec) {
                    filterSec.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });

            // Touch swipe support for mobile
            let touchStartX = 0;
            viewport?.addEventListener('touchstart', function (e) {
                touchStartX = e.changedTouches[0].screenX;
            }, { passive: true });

            viewport?.addEventListener('touchend', function (e) {
                const touchEndX = e.changedTouches[0].screenX;
                const diff = touchStartX - touchEndX;
                if (Math.abs(diff) > 40) {
                    if (diff > 0) {
                        getActiveCarousel()?.next();
                    } else {
                        getActiveCarousel()?.prev();
                    }
                }
            }, { passive: true });

            // Handle window resize
            window.addEventListener('resize', function () {
                carouselsMap.forEach(carousel => carousel?.refresh());
            });
        });
    </script>
@endif

<!-- ===== THANH TÌM KIẾM & BỘ LỌC ===== -->
<div id="ajax-filter-container" class="position-relative">
    <!-- Loading Overlay -->
    <div id="ajax-loading-overlay" class="position-absolute top-0 start-0 w-100 h-100 d-none justify-content-center align-items-start pt-5" style="background: rgba(250, 249, 246, 0.7); backdrop-filter: blur(4px); z-index: 1000; border-radius: 2px;">
        <div class="spinner-border text-dark shadow" role="status" style="width: 2.5rem; height: 2.5rem; color: var(--bellroy-orange) !important;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="container-fluid" style="padding-left: 2cm !important; padding-right: 2cm !important;">
        <div class="search-filter-bar animate-fade-in p-4 p-lg-4 mb-4">
            <form action="{{ url()->current() }}" method="GET" id="filter-form">
                <div class="row g-2 align-items-end filter-bar-row">
                    <div class="filter-col-search col-lg col-md-6 col-12">
                        <label class="form-label fw-bold small mb-1 filter-bar-label"><i class="bi bi-search me-1" style="color: var(--bellroy-orange);"></i> TÌM KIẾM</label>
                        <input type="text" name="search" class="form-control form-control-sm shadow-none" placeholder="Tên sản phẩm..." value="{{ request('search') }}" style="border-radius: 2px; padding: 7px 12px; font-family: 'Space Grotesk', sans-serif; font-size: 0.85rem; height: 38px;">
                    </div>

                    <div class="filter-col-price col-lg col-md-6 col-12">
                        <label class="form-label fw-bold small mb-1 filter-bar-label"><i class="bi bi-cash me-1" style="color: var(--bellroy-sage);"></i> KHOẢNG GIÁ</label>
                        <div class="input-group input-group-sm" style="height: 38px;">
                            <input type="number" name="min_price" class="form-control shadow-none h-100" placeholder="Từ..." value="{{ request('min_price') }}" style="border-radius: 2px 0 0 2px; padding: 7px 8px; font-family: 'Space Mono', monospace; font-size: 0.82rem;">
                            <span class="input-group-text bg-white h-100" style="border-color: var(--border-color); color: var(--text-muted); border-radius: 0; padding: 0 8px; font-size: 0.8rem;">-</span>
                            <input type="number" name="max_price" class="form-control shadow-none h-100" placeholder="Đến..." value="{{ request('max_price') }}" style="border-radius: 0 2px 2px 0; padding: 7px 8px; font-family: 'Space Mono', monospace; font-size: 0.82rem;">
                        </div>
                    </div>

                    @if(isset($allTags) && $allTags->count() > 0)
                    @php
                        $selectedTagSlugs = request('tag') ? array_filter(explode(',', request('tag'))) : [];
                    @endphp
                    <div class="filter-col-tags col-lg col-md-5 col-6">
                        <label class="form-label fw-bold small mb-1 filter-bar-label"><i class="bi bi-tags-fill me-1" style="color: var(--bellroy-orange);"></i> TAGS</label>
                        <select id="tag-filter-select" class="form-select form-select-sm shadow-none" style="border-radius: 2px; padding: 7px 28px 7px 10px; font-family: 'Space Grotesk', sans-serif; font-size: 0.82rem; height: 38px;">
                            <option value="">+ Thêm tag lọc...</option>
                            @foreach($allTags as $tg)
                                @if(!in_array($tg->slug, $selectedTagSlugs))
                                <option value="{{ $tg->slug }}">#{{ $tg->name }} ({{ $tg->products_count }})</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="filter-col-sort col-lg col-md-4 col-6">
                        <label class="form-label fw-bold small mb-1 filter-bar-label"><i class="bi bi-funnel me-1" style="color: var(--text-muted);"></i> SẮP XẾP</label>
                        <select name="sort" class="form-select form-select-sm shadow-none" style="border-radius: 2px; padding: 7px 28px 7px 10px; font-family: 'Space Grotesk', sans-serif; font-size: 0.82rem; height: 38px;">
                            <option value="" {{ request('sort') == '' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="sales_desc" {{ request('sort') == 'sales_desc' ? 'selected' : '' }}>Bán chạy</option>
                            <option value="wishlist_desc" {{ request('sort') == 'wishlist_desc' ? 'selected' : '' }}>Yêu thích</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá ↑</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá ↓</option>
                        </select>
                    </div>

                    <div class="filter-col-btn col-lg-auto col-md-3 col-12">
                        <button class="btn btn-dark btn-sm fw-bold w-100" style="padding: 7px 18px; height: 38px; display: inline-flex; align-items: center; justify-content: center; border-radius: 2px; white-space: nowrap; font-family: 'Space Grotesk', sans-serif; font-size: 0.82rem; letter-spacing: 0.04em;" type="submit">
                            <i class="bi bi-sliders me-1"></i> LỌC
                        </button>
                    </div>
                </div>

                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                @if(request('tag'))
                    <input type="hidden" name="tag" value="{{ request('tag') }}">
                @endif

                <div class="mt-2">
                    @if(request('search') || request('category') || request('tag') || request('sort') || request('min_price') || request('max_price'))
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <span class="text-muted small fw-semibold">Đang lọc:</span>
                            @if(request('search'))
                                <span class="filter-chip">
                                    "{{ request('search') }}"
                                    <a href="{{ url()->current() . '?' . http_build_query(request()->except(['search', 'page'])) }}" class="text-danger text-decoration-none fw-bold" style="line-height: 1;">&times;</a>
                                </span>
                            @endif
                            @if(request('category'))
                                @php
                                    $selectedCat = $categories->firstWhere('id', request('category'));
                                @endphp
                                @if($selectedCat)
                                    <span class="filter-chip">
                                        {{ $selectedCat->name }}
                                        <a href="{{ url()->current() . '?' . http_build_query(request()->except(['category', 'page'])) }}" class="text-danger text-decoration-none fw-bold" style="line-height: 1;">&times;</a>
                                    </span>
                                @endif
                            @endif
                            @if(request('tag'))
                                @php
                                    $activeTagSlugs = array_filter(explode(',', request('tag')));
                                @endphp
                                @foreach($activeTagSlugs as $activeSlug)
                                    @php
                                        $tagObj = isset($allTags) ? $allTags->first(fn($t) => $t->slug == trim($activeSlug)) : null;
                                        $remainingTags = array_filter($activeTagSlugs, fn($s) => trim($s) !== trim($activeSlug));
                                        $removeParams = request()->except(['tag', 'page']);
                                        if (count($remainingTags) > 0) {
                                            $removeParams['tag'] = implode(',', $remainingTags);
                                        }
                                    @endphp
                                    <span class="filter-chip">
                                        <i class="bi bi-tag-fill me-1" style="color: var(--bellroy-orange);"></i> {{ $tagObj ? $tagObj->name : $activeSlug }}
                                        <a href="{{ url()->current() . '?' . http_build_query($removeParams) }}" class="text-danger text-decoration-none fw-bold tag-remove-chip" style="line-height: 1;">&times;</a>
                                    </span>
                                @endforeach
                            @endif
                            @if(request('min_price'))
                                <span class="filter-chip">
                                    Từ: {{ number_format(request('min_price'), 0, ',', '.') }}đ
                                    <a href="{{ url()->current() . '?' . http_build_query(request()->except(['min_price', 'page'])) }}" class="text-danger text-decoration-none fw-bold" style="line-height: 1;">&times;</a>
                                </span>
                            @endif
                            @if(request('max_price'))
                                <span class="filter-chip">
                                    Đến: {{ number_format(request('max_price'), 0, ',', '.') }}đ
                                    <a href="{{ url()->current() . '?' . http_build_query(request()->except(['max_price', 'page'])) }}" class="text-danger text-decoration-none fw-bold" style="line-height: 1;">&times;</a>
                                </span>
                            @endif
                            @if(request('sort'))
                                <span class="filter-chip">
                                    @if(request('sort') == 'price_asc') Giá ↑
                                    @elseif(request('sort') == 'price_desc') Giá ↓
                                    @elseif(request('sort') == 'sales_desc') Bán chạy
                                    @elseif(request('sort') == 'wishlist_desc') Yêu thích
                                    @endif
                                    <a href="{{ url()->current() . '?' . http_build_query(request()->except(['sort', 'page'])) }}" class="text-danger text-decoration-none fw-bold" style="line-height: 1;">&times;</a>
                                </span>
                            @endif
                            <a href="{{ url()->current() }}" class="btn btn-sm btn-link text-decoration-none small p-0 fw-bold ms-2" style="color: var(--bellroy-orange);">Xóa tất cả</a>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- ===== PHẦN CHÍNH: SIDEBAR DANH MỤC + LƯỚI SẢN PHẨM ===== -->

    <!-- Nút toggle danh mục trên Mobile -->
    <button class="btn mobile-category-toggle d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileCategoryDrawer" aria-controls="mobileCategoryDrawer" title="Danh mục">
        <i class="bi bi-grid-3x3-gap-fill fs-5"></i>
    </button>

    <!-- Offcanvas Drawer cho Mobile -->
    <div class="offcanvas offcanvas-start offcanvas-category d-lg-none" tabindex="-1" id="mobileCategoryDrawer" aria-labelledby="mobileCategoryDrawerLabel">
        <div class="offcanvas-header py-3 px-4">
            <h5 class="offcanvas-title fw-bold text-dark display-font" id="mobileCategoryDrawerLabel">
                <i class="bi bi-grid-3x3-gap-fill me-2" style="color: var(--bellroy-orange);"></i>Danh mục
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body px-3 pt-0">
            <div class="list-group list-group-flush rounded-3">
                <a href="{{ url()->current() . '?' . http_build_query(request()->except(['category', 'page'])) }}" 
                   class="category-list-item text-decoration-none {{ !request('category') ? 'active' : '' }}">
                    <span><i class="bi bi-grid-fill me-2"></i> Tất cả</span>
                </a>
                @foreach ($categories as $category)
                    <a href="{{ url()->current() . '?' . http_build_query(array_merge(request()->except(['category', 'page']), ['category' => $category->id])) }}" 
                       class="category-list-item text-decoration-none {{ request('category') == $category->id ? 'active' : '' }}">
                        <span>
                            <i class="{{ $category->icon ?? 'bi bi-tag' }} me-2"></i> {{ $category->name }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div id="product-grid-section" class="container-fluid" style="padding-left: 2cm !important; padding-right: 2cm !important;">
        <div class="row g-4 animate-fade-in">
            <!-- Cột trái: Sidebar Danh mục (Desktop only) -->
            <div class="col-lg-2 d-none d-lg-block position-relative">
                <div class="category-sidebar-card p-3">
                    <div>
                        <h5 class="fw-bold mb-3 text-dark d-flex align-items-center gap-2" style="font-size: 1rem; letter-spacing: -0.2px;">
                            <i class="bi bi-grid-3x3-gap-fill" style="color: var(--bellroy-orange);"></i> Danh mục
                        </h5>
                        <div class="list-group list-group-flush">
                            <!-- Tất cả sản phẩm -->
                            <a href="{{ url()->current() . '?' . http_build_query(request()->except(['category', 'page'])) }}" 
                               class="category-list-item text-decoration-none {{ !request('category') ? 'active' : '' }}">
                                <span><i class="bi bi-grid-fill me-2"></i> Tất cả</span>
                            </a>
                            
                            <!-- Lặp các danh mục -->
                            @foreach ($categories as $category)
                                <a href="{{ url()->current() . '?' . http_build_query(array_merge(request()->except(['category', 'page']), ['category' => $category->id])) }}" 
                                   class="category-list-item text-decoration-none {{ request('category') == $category->id ? 'active' : '' }}">
                                    <span>
                                        <i class="{{ $category->icon ?? 'bi bi-tag' }} me-2"></i> {{ $category->name }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cột phải: Grid sản phẩm -->
            <div class="col-lg-10">
                <div class="mb-4">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <div style="width: 7px; height: 7px; background: var(--bellroy-orange); border-radius: 50%;"></div>
                        <span class="small fw-bold text-secondary" style="letter-spacing: 0.3px; font-size: 0.75rem;">SẢN PHẨM TUYỂN CHỌN</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="text-dark mb-0" style="font-weight: 800; letter-spacing: -0.025em; font-size: 1.6rem;">
                            @if(request('category'))
                                @php
                                    $activeCat = $categories->firstWhere('id', request('category'));
                                @endphp
                                {{ $activeCat ? $activeCat->name : 'Tất Cả Sản Phẩm' }}
                            @else
                                Tất Cả Sản Phẩm
                            @endif
                        </h3>
                        <span class="badge badge-terracotta px-3 py-2 fw-bold">{{ $products->total() }} sản phẩm</span>
                    </div>
                </div>

                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 mb-5">
                    @forelse ($products as $product)
                        <div class="col grid-item-animate" style="animation-delay: {{ $loop->index * 0.05 }}s;">
                            <div class="bento-laptop-card h-100 d-flex flex-column">
                                
                                <!-- Studio Image Stage -->
                                <div class="bento-img-stage">
                                    <!-- Category Badge (Top Left) -->
                                    <span class="bento-cat-badge">
                                        {{ $product->category->name ?? 'Công nghệ' }}
                                    </span>

                                    <!-- Wishlist Button (Top Right) -->
                                    <button class="bento-wishlist-btn btn-wishlist" 
                                            data-product-id="{{ $product->id }}" 
                                            onclick="toggleWishlist(event, this, {{ $product->id }})"
                                            title="Yêu thích">
                                        <i class="bi {{ in_array($product->id, $wishlistIds ?? []) ? 'bi-heart-fill text-danger' : 'bi-heart text-secondary' }}"></i>
                                    </button>

                                    <!-- Compare Button (Top Right, Below Wishlist) -->
                                    <button class="bento-compare-btn btn-compare" 
                                            data-id="{{ $product->id }}"
                                            data-name="{{ e($product->name) }}"
                                            data-price="{{ $product->price }}"
                                            data-price-format="{{ number_format($product->price, 0, ',', '.') }}đ"
                                            data-image="{{ $product->image ? asset('storage/'.$product->image) : '' }}"
                                            data-category="{{ e($product->category->name ?? 'Công nghệ') }}"
                                            data-url="{{ route('products.show', $product) }}"
                                            data-stock="{{ $product->quantity }}"
                                            data-desc="{{ e(Str::limit($product->description ?? 'Đang cập nhật', 120)) }}"
                                            data-tags="{{ e($product->tags ? $product->tags->pluck('name')->join(', ') : '') }}"
                                            onclick="toggleCompareProduct(event, this)"
                                            title="So sánh cấu hình">
                                        <i class="bi bi-arrow-left-right"></i>
                                    </button>

                                    <!-- Laptop Image -->
                                    <a href="{{ route('products.show', $product) }}" 
                                       onclick="trackRecentlyViewed({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, '{{ number_format($product->price, 0, ',', '.') }}đ', '{{ $product->image ? asset('storage/'.$product->image) : '' }}', '{{ addslashes($product->category->name ?? 'Công nghệ') }}', '{{ route('products.show', $product) }}')"
                                       class="d-flex align-items-center justify-content-center w-100 h-100 text-decoration-none">
                                        @if($product->image)
                                            <img src="{{ asset('storage/'.$product->image) }}" class="img-fluid" alt="{{ $product->name }}" loading="lazy">
                                        @else
                                            <i class="bi bi-laptop display-1 text-muted opacity-50"></i>
                                        @endif
                                    </a>

                                    @if($product->quantity <= 0)
                                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-white bg-opacity-75 d-flex align-items-center justify-content-center" style="z-index: 3;">
                                            <span class="badge badge-premium fs-6 py-2 px-3 fw-bold">HẾT HÀNG</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Card Content Body -->
                                <div class="d-flex flex-column flex-grow-1">
                                    <h4 class="bento-product-title" title="{{ $product->name }}">
                                        <a href="{{ route('products.show', $product) }}" onclick="trackRecentlyViewed({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, '{{ number_format($product->price, 0, ',', '.') }}đ', '{{ $product->image ? asset('storage/'.$product->image) : '' }}', '{{ addslashes($product->category->name ?? 'Công nghệ') }}', '{{ route('products.show', $product) }}')">
                                            {{ $product->name }}
                                        </a>
                                    </h4>

                                    <!-- Product Tags (GPU, CPU, Features...) -->
                                    @if($product->tags && $product->tags->count() > 0)
                                        <div class="bento-tags-wrap d-flex flex-wrap gap-1 mb-2">
                                            @foreach($product->tags->take(3) as $tg)
                                                <a href="{{ url()->current() . '?' . http_build_query(array_merge(request()->except('page'), ['tag' => $tg->slug])) }}" 
                                                   class="bento-tag-pill" 
                                                   title="Xem các sản phẩm có tag {{ $tg->name }}">
                                                    #{{ $tg->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Sold Count Tag -->
                                    <div class="bento-sold-wrap">
                                        <span class="bento-sold-badge">
                                            <i class="bi bi-fire text-danger"></i>
                                            @if(($product->total_sold ?? 0) > 0)
                                                <span>Đã bán: <strong>{{ number_format($product->total_sold) }}</strong> máy</span>
                                            @else
                                                <span>Chính hãng: <strong>Mới 100%</strong></span>
                                            @endif
                                        </span>
                                    </div>

                                    <!-- Price & Stock -->
                                    <div class="bento-price-wrap">
                                        <div class="bento-price">
                                            {{ number_format($product->price, 0, ',', '.') }}<span class="currency">đ</span>
                                        </div>
                                        <div class="bento-stock-tag">
                                            Kho: <strong>{{ $product->quantity }}</strong> máy
                                        </div>
                                    </div>

                                    <!-- Excerpt -->
                                    <p class="bento-product-desc">
                                        {{ Str::limit($product->description ?? 'Đẳng cấp thiết kế, cấu hình đột phá sẵn sàng tối ưu cho mọi công việc và giải trí.', 75) }}
                                    </p>

                                    <!-- Actions -->
                                    <div class="bento-actions-wrap">
                                        <a href="{{ route('products.show', $product) }}" class="btn-bento-action flex-grow-1">
                                            <span>Xem Chi Tiết</span>
                                            <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                        @auth
                                            @if($product->quantity > 0)
                                                <button type="button" class="btn-bento-cart" onclick="quickAddToCart({{ $product->id }}, this)" title="Thêm vào giỏ hàng">
                                                    <i class="bi bi-cart-plus"></i>
                                                </button>
                                            @endif
                                        @endauth
                                    </div>
                                </div>

                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-box-seam display-1 text-muted" style="color: var(--text-secondary) !important;"></i>
                            <p class="fs-5 text-muted mt-3">Không tìm thấy sản phẩm nào phù hợp với bộ lọc.</p>
                            <a href="{{ url()->current() }}" class="btn btn-premium mt-2">Xem tất cả sản phẩm</a>
                        </div>
                    @endforelse
                </div>

                <div class="d-flex justify-content-center mt-4 mb-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
</div> <!-- End #ajax-filter-container -->

<!-- ===== SẢN PHẨM BẠN VỪA XEM (RECENTLY VIEWED PRODUCTS) ===== -->
<section id="recently-viewed-section" class="container-fluid my-5 pt-3 d-none animate-fade-in" style="padding-left: 2cm !important; padding-right: 2cm !important;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="text-dark mb-0 fw-bold display-font" style="letter-spacing: -0.025em; font-size: 1.5rem;">
                Sản Phẩm Bạn <em>Vừa Xem Gần Đây</em>
            </h3>
        </div>
        <div>
            <button type="button" class="btn btn-sm btn-outline-secondary font-monospace" id="btnClearRecentlyViewed" onclick="clearRecentlyViewed()" style="font-size: 0.78rem; border-radius: 2px;">
                <i class="bi bi-trash3 me-1"></i>Xóa lịch sử
            </button>
        </div>
    </div>
    <div class="recent-products-track d-flex flex-nowrap gap-3 pb-1" id="recentlyViewedContainer">
        <!-- Rendered dynamically by JavaScript from localStorage (Strictly 1 Row) -->
    </div>
</section>

<!-- ===== TECH NEWS & CUSTOMER REVIEWS SECTION ===== -->
<section id="tech-news-section" class="container-fluid my-5 pt-4 animate-fade-in" style="padding-left: 2cm !important; padding-right: 2cm !important;">
    <div class="row g-4">
        <!-- CỘT TRÁI: TIN TỨC (col-8) -->
        <div class="col-lg-8">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
                <div>
                    <h3 class="featured-section-title mb-0">
                        Xu Hướng &amp; <em>Tin Tức Công Nghệ</em>
                    </h3>
                </div>
                <div>
                    <a href="{{ route('news.index') }}" class="btn-news-all d-inline-flex align-items-center gap-2">
                        <span>Xem tất cả</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="swiss-news-list">
                @if(isset($newsList) && $newsList->count() > 0)
                    @foreach($newsList as $item)
                    <article class="swiss-news-item {{ !$loop->last ? 'border-bottom' : '' }}">
                        <a href="{{ route('news.show', $item->slug) }}" class="swiss-news-row text-decoration-none">
                            <div class="swiss-news-content">
                                <div class="swiss-meta d-flex align-items-center gap-2 mb-2 flex-wrap">
                                    <span class="swiss-date"><i class="bi bi-calendar3 me-1"></i>{{ $item->formatted_date }}</span>
                                    <span class="swiss-bullet">•</span>
                                    <span class="swiss-badge">{{ $item->category }}</span>
                                    <span class="swiss-bullet">•</span>
                                    <span class="swiss-read-time"><i class="bi bi-clock me-1"></i>{{ $item->read_time }}</span>
                                    <span class="swiss-views ms-auto me-2"><i class="bi bi-eye me-1"></i>{{ number_format($item->views_count) }}</span>
                                </div>
                                <h4 class="swiss-title">
                                    {{ $item->title }}
                                </h4>
                                <p class="swiss-summary">
                                    {{ $item->summary }}
                                </p>
                                <div class="swiss-action">
                                    <span class="swiss-link">
                                        Đọc bài viết <i class="bi bi-arrow-right ms-1"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="swiss-thumb">
                                <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="swiss-img" loading="lazy">
                            </div>
                        </a>
                    </article>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- CỘT PHẢI: ĐÁNH GIÁ NỔI BẬT (col-4) -->
        <div class="col-lg-4">
            <div class="reviews-sidebar">
                <div class="reviews-sidebar-header">
                    <div class="d-flex align-items-center gap-2">
                        <div class="reviews-icon-wrap">
                            <i class="bi bi-chat-quote-fill"></i>
                        </div>
                        <div>
                            <h4 class="reviews-sidebar-title mb-0">Khách Hàng Nói Gì</h4>
                            <p class="text-muted small mb-0 mt-1">Đánh giá thực tế từ người dùng</p>
                        </div>
                    </div>
                </div>

                <div class="reviews-list">
                    @if(isset($featuredReviews) && $featuredReviews->count() > 0)
                        @foreach($featuredReviews as $review)
                        <div class="review-item">
                            <div class="review-header">
                                <div class="review-avatar">
                                    @if($review->user && $review->user->avatar_url)
                                        <img src="{{ $review->user->avatar_url }}" alt="{{ $review->user->name }}">
                                    @else
                                        <div class="review-avatar-placeholder">
                                            {{ $review->user ? mb_substr($review->user->name, 0, 1) : '?' }}
                                        </div>
                                    @endif
                                </div>
                                <div class="review-user-info">
                                    <span class="review-user-name">{{ $review->user->name ?? 'Ẩn danh' }}</span>
                                    <div class="review-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star-fill {{ $i <= $review->rating ? 'star-active' : 'star-empty' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <span class="review-date">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="review-comment">{{ Str::limit($review->comment, 120) }}</p>
                            @if($review->product)
                                <a href="{{ route('products.show', $review->product) }}" class="review-product-link">
                                    <i class="bi bi-laptop me-1"></i>
                                    {{ Str::limit($review->product->name, 40) }}
                                </a>
                            @endif
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-chat-square-text display-6 opacity-50 d-block mb-2"></i>
                            <p class="small mb-0">Chưa có đánh giá nổi bật</p>
                        </div>
                    @endif
                </div>

                @if(isset($featuredReviews) && $featuredReviews->count() > 0)
                <div class="reviews-sidebar-footer">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="reviews-summary-stats">
                            <span class="reviews-avg-score">{{ number_format($featuredReviews->avg('rating'), 1) }}</span>
                            <div class="reviews-avg-stars">
                                @for($s = 1; $s <= 5; $s++)
                                    <i class="bi bi-star-fill {{ $s <= round($featuredReviews->avg('rating')) ? 'star-active' : 'star-empty' }}"></i>
                                @endfor
                                <span class="text-muted small ms-1">trung bình</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- ===== THANH SO SÁNH LAPTOP NỔI (BOTTOM FLOATING DRAWER) ===== -->
<div id="laptopCompareDrawer" class="laptop-compare-drawer">
    <div class="compare-drawer-container">
        <div class="compare-drawer-header">
            <div class="d-flex align-items-center gap-2">
                <span class="compare-pulse-beacon"></span>
                <span class="compare-drawer-title">SO SÁNH CẤU HÌNH</span>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1 font-monospace" id="compareBadgeCount" style="font-size: 0.72rem;">0/3</span>
            </div>
            <button type="button" class="btn-close-compare" id="btnMinimizeCompare" onclick="clearAllCompare()" title="Đóng & Xóa tất cả">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="compare-drawer-body">
            <div class="compare-slots-row" id="compareSlotsContainer">
                <!-- Rendered dynamically by JS -->
            </div>
            <div class="compare-actions-row">
                <button type="button" class="btn btn-primary btn-sm fw-bold d-flex align-items-center gap-2 btn-launch-compare" id="btnLaunchCompareModal" onclick="openCompareModal()" disabled>
                    <i class="bi bi-sliders2"></i>
                    <span>So Sánh Ngay</span>
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" id="btnClearAllCompare" onclick="clearAllCompare()" title="Xóa tất cả">
                    <i class="bi bi-trash3"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ===== MODAL SO SÁNH ĐỐI CHIẾU CẤU HÌNH CHI TIẾT ===== -->
<div class="modal fade" id="laptopCompareModal" tabindex="-1" aria-labelledby="laptopCompareModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 4px;">
            <div class="modal-header border-bottom py-3 px-4 bg-light">
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 36px; height: 36px; background: rgba(14, 165, 233, 0.12); color: #0ea5e9; border-radius: 2px; display: flex; align-items: center; justify-content: center; font-size: 1.15rem;">
                        <i class="bi bi-sliders2"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0 display-font" id="laptopCompareModalLabel" style="font-size: 1.1rem; letter-spacing: -0.02em;">
                            BẢNG SO SÁNH ĐỐI CHIẾU CẤU HÌNH LAPTOP
                        </h5>
                        <p class="text-muted small mb-0">Đối chiếu trực quan thông số kỹ thuật, giá thành & cam kết bảo hành</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="compareModalBody">
                <!-- Rendered dynamically by JS -->
            </div>
            <div class="modal-footer border-top py-2 px-4 text-center">
                <span class="text-muted small mx-auto"><i class="bi bi-info-circle me-1"></i>Hỗ trợ đối chiếu tối đa 3 mẫu laptop cùng lúc</span>
            </div>
        </div>
    </div>
</div>

<!-- Script AJAX Filter, Laptop Compare & Recently Viewed -->
<script>
    // ==========================================
    // 1. HỆ THỐNG SO SÁNH CẤU HÌNH LAPTOP (MAX 3)
    // ==========================================
    const COMPARE_STORAGE_KEY = 'laptopking_compare_list';

    function getCompareList() {
        try {
            return JSON.parse(localStorage.getItem(COMPARE_STORAGE_KEY) || '[]');
        } catch(e) {
            return [];
        }
    }

    function saveCompareList(list) {
        localStorage.setItem(COMPARE_STORAGE_KEY, JSON.stringify(list));
        renderCompareDrawer();
        syncCompareButtons();
    }

    function toggleCompareProduct(event, btn) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        const id = parseInt(btn.dataset.id);
        if (!id) return;
        
        let list = getCompareList();
        const existingIndex = list.findIndex(item => item.id === id);
        
        if (existingIndex > -1) {
            // Đã có -> Bỏ chọn
            list.splice(existingIndex, 1);
            saveCompareList(list);
        } else {
            // Chưa có -> Thêm vào (Tối đa 3 máy)
            if (list.length >= 3) {
                alert('Bạn chỉ có thể so sánh tối đa 3 mẫu laptop cùng lúc! Vui lòng bỏ bớt 1 máy trước.');
                return;
            }
            
            const item = {
                id: id,
                name: btn.dataset.name || 'Laptop',
                price: btn.dataset.price || 0,
                price_format: btn.dataset.priceFormat || '',
                image: btn.dataset.image || '',
                category: btn.dataset.category || 'Laptop',
                url: btn.dataset.url || '#',
                stock: parseInt(btn.dataset.stock || 0),
                desc: btn.dataset.desc || '',
                tags: btn.dataset.tags || ''
            };
            list.push(item);
            saveCompareList(list);
        }
    }

    function removeCompareItem(id) {
        let list = getCompareList();
        list = list.filter(item => item.id !== parseInt(id));
        saveCompareList(list);
        
        // Nếu modal so sánh đang mở -> re-render lại bảng
        const modalEl = document.getElementById('laptopCompareModal');
        if (modalEl && modalEl.classList.contains('show')) {
            renderCompareModalTable();
        }
    }

    function clearAllCompare() {
        saveCompareList([]);
        const modalEl = document.getElementById('laptopCompareModal');
        if (modalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const bsModal = bootstrap.Modal.getInstance(modalEl);
            if (bsModal) bsModal.hide();
        }
    }

    function syncCompareButtons() {
        const list = getCompareList();
        const activeIds = list.map(item => item.id);
        document.querySelectorAll('.bento-compare-btn').forEach(btn => {
            const id = parseInt(btn.dataset.id);
            if (activeIds.includes(id)) {
                btn.classList.add('active');
                btn.setAttribute('title', 'Đang so sánh (Bấm để bỏ)');
            } else {
                btn.classList.remove('active');
                btn.setAttribute('title', 'So sánh cấu hình');
            }
        });
    }

    function renderCompareDrawer() {
        const drawer = document.getElementById('laptopCompareDrawer');
        const badge = document.getElementById('compareBadgeCount');
        const slots = document.getElementById('compareSlotsContainer');
        const launchBtn = document.getElementById('btnLaunchCompareModal');
        if (!drawer || !badge || !slots) return;
        
        const list = getCompareList();
        badge.textContent = `${list.length}/3`;
        
        if (list.length > 0) {
            drawer.classList.add('show');
        } else {
            drawer.classList.remove('show');
        }
        
        if (launchBtn) {
            launchBtn.disabled = list.length < 2; // Cần ít nhất 2 máy để so sánh đối đầu
            if (list.length >= 2) {
                launchBtn.classList.remove('btn-secondary');
                launchBtn.classList.add('btn-primary');
            } else {
                launchBtn.classList.remove('btn-primary');
                launchBtn.classList.add('btn-secondary');
            }
        }
        
        let html = '';
        for (let i = 0; i < 3; i++) {
            if (list[i]) {
                const item = list[i];
                html += `
                    <div class="compare-slot-item filled" title="${item.name}">
                        ${item.image ? `<img src="${item.image}" alt="${item.name}">` : `<i class="bi bi-laptop fs-4 text-muted"></i>`}
                        <button type="button" class="btn-remove-slot" onclick="removeCompareItem(${item.id})" title="Xóa máy này">&times;</button>
                    </div>
                `;
            } else {
                html += `
                    <div class="compare-slot-item">
                        <span class="slot-placeholder">+ Máy ${i+1}</span>
                    </div>
                `;
            }
        }
        slots.innerHTML = html;
    }

    function openCompareModal() {
        renderCompareModalTable();
        const modalEl = document.getElementById('laptopCompareModal');
        if (modalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            let modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (!modalInstance) {
                modalInstance = new bootstrap.Modal(modalEl);
            }
            modalInstance.show();
        }
    }

    function renderCompareModalTable() {
        const list = getCompareList();
        const modalContent = document.getElementById('compareModalBody');
        if (!modalContent) return;
        
        if (list.length === 0) {
            modalContent.innerHTML = `
                <div class="text-center py-5">
                    <i class="bi bi-sliders2 display-3 text-muted opacity-50"></i>
                    <h5 class="fw-bold mt-3">Chưa có sản phẩm nào trong danh sách so sánh</h5>
                    <p class="text-muted small">Hãy bấm icon So sánh trên thẻ sản phẩm để đối chiếu cấu hình.</p>
                </div>
            `;
            return;
        }
        
        const colWidth = (100 / (list.length + 1)).toFixed(1);
        
        let html = `
            <div class="table-responsive">
                <table class="table table-bordered align-middle compare-table mb-0">
                    <thead>
                        <tr class="bg-light">
                            <th style="width: 22%; min-width: 150px;" class="fw-bold text-muted font-monospace small">TIÊU CHÍ SO SÁNH</th>
                            ${list.map(p => `
                                <th style="width: ${colWidth}%; min-width: 200px;" class="text-center position-relative">
                                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2" onclick="removeCompareItem(${p.id})" title="Xóa máy này"></button>
                                    <div class="p-2">
                                        <div style="height: 140px;" class="d-flex align-items-center justify-content-center mb-2">
                                            ${p.image ? `<img src="${p.image}" class="img-fluid" style="max-height: 130px; object-fit: contain;">` : `<i class="bi bi-laptop display-4 text-muted"></i>`}
                                        </div>
                                        <span class="badge bg-secondary-subtle text-secondary px-2 py-1 mb-1 font-monospace small">${p.category}</span>
                                        <h6 class="fw-bold text-dark mb-1" style="font-size: 0.92rem; line-height: 1.3;">${p.name}</h6>
                                        <div class="text-danger fw-bold font-monospace fs-5">${p.price_format}</div>
                                    </div>
                                </th>
                            `).join('')}
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fw-bold text-muted small"><i class="bi bi-box-seam me-1 text-primary"></i> Trạng thái kho</td>
                            ${list.map(p => `
                                <td class="text-center">
                                    ${p.stock > 0 ? `<span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Còn ${p.stock} máy</span>` : `<span class="badge bg-danger-subtle text-danger px-2 py-1">Tạm hết hàng</span>`}
                                </td>
                            `).join('')}
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted small"><i class="bi bi-tags-fill me-1 text-warning"></i> Tags công nghệ</td>
                            ${list.map(p => `
                                <td class="text-center small">
                                    ${p.tags ? p.tags.split(',').map(t => `<span class="badge bg-light text-dark border me-1 mb-1">#${t.trim()}</span>`).join('') : '<span class="text-muted">Chính hãng</span>'}
                                </td>
                            `).join('')}
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted small"><i class="bi bi-cpu me-1 text-info"></i> Tóm tắt cấu hình</td>
                            ${list.map(p => `
                                <td class="small text-muted" style="line-height: 1.5;">
                                    ${p.desc || 'Đang cập nhật thông số chi tiết.'}
                                </td>
                            `).join('')}
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted small"><i class="bi bi-shield-check me-1 text-success"></i> Bảo hành & Cam kết</td>
                            ${list.map(p => `
                                <td class="small text-center">
                                    <i class="bi bi-check2-circle text-success me-1"></i>24 tháng chính hãng<br>
                                    <i class="bi bi-check2-circle text-success me-1"></i>Lỗi 1 đổi 1 trong 30 ngày
                                </td>
                            `).join('')}
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted small"><i class="bi bi-cart-check me-1 text-primary"></i> Hành động</td>
                            ${list.map(p => `
                                <td class="text-center p-3">
                                    <a href="${p.url}" class="btn btn-dark btn-sm w-100 mb-2 fw-semibold">
                                        <span>Xem Chi Tiết</span> <i class="bi bi-arrow-right"></i>
                                    </a>
                                    ${p.stock > 0 ? `
                                        <button type="button" class="btn btn-outline-primary btn-sm w-100 fw-semibold" onclick="quickAddToCart(${p.id}, this)">
                                            <i class="bi bi-cart-plus me-1"></i> Thêm vào giỏ
                                        </button>
                                    ` : ''}
                                </td>
                            `).join('')}
                        </tr>
                    </tbody>
                </table>
            </div>
        `;
        modalContent.innerHTML = html;
    }

    // ==========================================
    // 2. HỆ THỐNG SẢN PHẨM VỪA XEM (RECENTLY VIEWED)
    // ==========================================
    const RECENTLY_VIEWED_KEY = 'laptopking_recently_viewed';
    const MAX_RECENTLY_VIEWED = 6;

    function getRecentlyViewed() {
        try {
            let list = JSON.parse(localStorage.getItem(RECENTLY_VIEWED_KEY) || '[]');
            if (!Array.isArray(list)) list = [];
            // Tự động rút gọn nếu bộ nhớ cũ đang lưu quá 6 máy (tránh tràn hàng)
            if (list.length > MAX_RECENTLY_VIEWED) {
                list = list.slice(0, MAX_RECENTLY_VIEWED);
                localStorage.setItem(RECENTLY_VIEWED_KEY, JSON.stringify(list));
            }
            return list;
        } catch(e) {
            return [];
        }
    }

    function saveRecentlyViewed(item) {
        try {
            let list = getRecentlyViewed();
            list = list.filter(p => p && p.id !== item.id);
            list.unshift(item);
            if (list.length > MAX_RECENTLY_VIEWED) list = list.slice(0, MAX_RECENTLY_VIEWED);
            localStorage.setItem(RECENTLY_VIEWED_KEY, JSON.stringify(list));
        } catch(e) {}
    }

    function trackRecentlyViewed(id, name, price, priceFormat, image, category, url) {
        saveRecentlyViewed({
            id: id,
            name: name,
            price: price,
            price_format: priceFormat,
            image: image,
            category: category,
            url: url
        });
    }

    function renderRecentlyViewed() {
        const section = document.getElementById('recently-viewed-section');
        const container = document.getElementById('recentlyViewedContainer');
        if (!section || !container) return;
        
        let list = getRecentlyViewed();
        if (list.length === 0) {
            section.classList.add('d-none');
            return;
        }
        
        if (list.length > MAX_RECENTLY_VIEWED) {
            list = list.slice(0, MAX_RECENTLY_VIEWED);
        }
        
        section.classList.remove('d-none');
        
        container.innerHTML = list.map(item => `
            <div class="recent-item-col">
                <div class="recent-product-card h-100">
                    <a href="${item.url}" class="recent-img-wrap text-decoration-none" onclick="trackRecentlyViewed(${item.id}, '${item.name.replace(/'/g, "\\'")}', ${item.price}, '${item.price_format}', '${item.image}', '${item.category.replace(/'/g, "\\'")}', '${item.url}')">
                        ${item.image ? `<img src="${item.image}" alt="${item.name}" loading="lazy">` : `<i class="bi bi-laptop fs-1 text-muted"></i>`}
                    </a>
                    <div class="recent-body">
                        <span class="recent-cat-pill">${item.category}</span>
                        <h5 class="recent-title" title="${item.name}">
                            <a href="${item.url}" class="text-decoration-none text-dark">${item.name}</a>
                        </h5>
                        <div class="recent-price">${item.price_format}</div>
                        <a href="${item.url}" class="btn-recent-action">
                            <span>Xem máy</span> <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function clearRecentlyViewed() {
        localStorage.removeItem(RECENTLY_VIEWED_KEY);
        renderRecentlyViewed();
    }

    // Gắn vào window để gọi từ các sự kiện inline
    window.toggleCompareProduct = toggleCompareProduct;
    window.removeCompareItem = removeCompareItem;
    window.clearAllCompare = clearAllCompare;
    window.openCompareModal = openCompareModal;
    window.trackRecentlyViewed = trackRecentlyViewed;
    window.clearRecentlyViewed = clearRecentlyViewed;
    window.renderRecentlyViewed = renderRecentlyViewed;

    // Tự động re-render NGAY LẬP TỨC khi quay lại trang (Chống delay BFCache, không cần nhấn F5)
    window.addEventListener('pageshow', function (event) {
        renderRecentlyViewed();
        syncCompareButtons();
        renderCompareDrawer();
    });

    window.addEventListener('focus', function () {
        renderRecentlyViewed();
        syncCompareButtons();
    });

    document.addEventListener('visibilitychange', function () {
        if (!document.hidden) {
            renderRecentlyViewed();
            syncCompareButtons();
            renderCompareDrawer();
        }
    });

    window.addEventListener('storage', function (e) {
        if (e.key === RECENTLY_VIEWED_KEY) {
            renderRecentlyViewed();
        }
        if (e.key === COMPARE_STORAGE_KEY) {
            syncCompareButtons();
            renderCompareDrawer();
        }
    });

    // Bắt sự kiện click vào bất kỳ thẻ hoặc link xem chi tiết sản phẩm nào để lưu ngay trước khi rời trang
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a[href*="/products/"]');
        if (link && !link.href.includes('/products?') && !link.href.endsWith('/products') && !link.href.endsWith('/products/')) {
            const card = link.closest('.bento-laptop-card') || link.closest('.recent-product-card');
            if (card) {
                const compareBtn = card.querySelector('.bento-compare-btn');
                if (compareBtn) {
                    const id = parseInt(compareBtn.getAttribute('data-id'));
                    const name = compareBtn.getAttribute('data-name');
                    const price = parseFloat(compareBtn.getAttribute('data-price'));
                    const priceFormat = compareBtn.getAttribute('data-price-format');
                    const image = compareBtn.getAttribute('data-image');
                    const category = compareBtn.getAttribute('data-category');
                    const url = compareBtn.getAttribute('data-url');
                    if (id && name) {
                        saveRecentlyViewed({ id, name, price, price_format: priceFormat, image, category, url });
                    }
                }
            }
        }
    }, true);

    // ==========================================
    // 3. SCRIPT AJAX FILTER & TƯƠNG TÁC
    // ==========================================
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('ajax-filter-container');
        
        // Khởi chạy đồng bộ trạng thái So sánh & Vừa xem khi load trang
        syncCompareButtons();
        renderCompareDrawer();
        renderRecentlyViewed();

        function scrollToProductGrid() {
            const gridSection = document.getElementById('product-grid-section') || document.getElementById('ajax-filter-container');
            if (gridSection) {
                const navbar = document.querySelector('.navbar') || document.querySelector('header');
                const navHeight = navbar ? navbar.offsetHeight + 15 : 85;
                const rect = gridSection.getBoundingClientRect();
                const targetY = window.pageYOffset + rect.top - navHeight;
                window.scrollTo({
                    top: Math.max(0, targetY),
                    behavior: 'smooth'
                });
            }
        }

        function fetchAndUpdate(url, isPushState = true, shouldScroll = false) {
            const overlay = document.getElementById('ajax-loading-overlay');
            if(overlay) overlay.classList.remove('d-none');
            if(overlay) overlay.classList.add('d-flex');

            // Khóa chiều cao tối thiểu tạm thời để tránh giật màn hình
            if (container) {
                container.style.minHeight = Math.max(container.offsetHeight, 450) + 'px';
            }

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContent = doc.getElementById('ajax-filter-container');
                
                if (newContent) {
                    container.innerHTML = newContent.innerHTML;
                    
                    if (isPushState) {
                        window.history.pushState({path: url}, '', url);
                    }

                    // Tự động cuộn trở lại đầu lưới sản phẩm khi chọn danh mục hoặc lọc
                    if (shouldScroll) {
                        requestAnimationFrame(() => {
                            scrollToProductGrid();
                        });
                    }

                    // Đóng Mobile Drawer nếu đang mở
                    const offcanvasEl = document.getElementById('mobileCategoryDrawer');
                    if (offcanvasEl && typeof bootstrap !== 'undefined' && bootstrap.Offcanvas) {
                        const bsOffcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl);
                        if (bsOffcanvas) bsOffcanvas.hide();
                    }

                    // Đồng bộ lại trạng thái active các nút so sánh trên lưới sản phẩm mới nạp
                    syncCompareButtons();
                } else {
                    window.location.href = url;
                }
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                window.location.href = url;
            })
            .finally(() => {
                if (container) {
                    container.style.minHeight = '';
                }
                const newOverlay = document.getElementById('ajax-loading-overlay');
                if(newOverlay) newOverlay.classList.add('d-none');
                if(newOverlay) newOverlay.classList.remove('d-flex');
            });
        }

        window.addEventListener('popstate', function(e) {
            if (e.state && e.state.path) {
                fetchAndUpdate(e.state.path, false, true);
            } else {
                fetchAndUpdate(window.location.href, false, true);
            }
        });

        // Xử lý tag dropdown select - thêm tag vào danh sách, reset dropdown
        document.addEventListener('change', function(e) {
            const tagSelect = e.target.closest('#tag-filter-select');
            if (tagSelect) {
                const newTag = tagSelect.value;
                if (!newTag) return;

                const params = new URLSearchParams(window.location.search);
                const currentTags = params.get('tag') ? params.get('tag').split(',').filter(t => t) : [];
                
                // Không thêm nếu đã có
                if (!currentTags.includes(newTag)) {
                    currentTags.push(newTag);
                }
                
                params.set('tag', currentTags.join(','));
                params.delete('page');
                
                const url = window.location.pathname + '?' + params.toString();
                fetchAndUpdate(url, true, false);
                return;
            }
        });

        // Lắng nghe sự kiện click trên toàn document để bắt được cả click trong Mobile Drawer
        document.addEventListener('click', function(e) {
            // Xóa tag chip riêng lẻ - không cuộn trang
            const tagRemove = e.target.closest('a.tag-remove-chip');
            if (tagRemove) {
                const href = tagRemove.getAttribute('href');
                if (!href || href === '#') return;
                e.preventDefault();
                fetchAndUpdate(tagRemove.href, true, false);
                return;
            }

            const link = e.target.closest('a.category-list-item, #ajax-filter-container .pagination a, #ajax-filter-container .filter-chip a, #ajax-filter-container a.btn-link');
            if (link) {
                const href = link.getAttribute('href');
                if (!href || href === '#' || href.startsWith('javascript:')) return;
                
                e.preventDefault();
                fetchAndUpdate(link.href, true, true);
            }
        });

        document.addEventListener('submit', function(e) {
            const form = e.target.closest('#filter-form');
            if (form) {
                e.preventDefault();
                const formData = new FormData(form);
                const params = new URLSearchParams();
                
                for (const pair of formData.entries()) {
                    if (pair[1] !== '') {
                        params.append(pair[0], pair[1]);
                    }
                }
                
                const baseUrl = form.getAttribute('action');
                const url = baseUrl + (params.toString() ? '?' + params.toString() : '');
                
                fetchAndUpdate(url, true, true);
            }
        });
    });
</script>
@endsection