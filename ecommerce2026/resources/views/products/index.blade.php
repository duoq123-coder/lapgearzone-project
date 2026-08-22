@extends('layouts.app')
@section('title', 'Cửa hàng')
@section('content')

<!-- Custom styling for Products listing page -->
<style>
    .carousel-fade .carousel-item {
        transition: opacity 0.8s ease-in-out !important;
    }
    
    /* Carousel Navigation Buttons styling */
    #productCarousel .carousel-control-prev {
        left: -30px;
        width: 50px;
        z-index: 5;
    }
    #productCarousel .carousel-control-next {
        right: -30px;
        width: 50px;
        z-index: 5;
    }

    #productCarousel .carousel-control-prev-icon,
    #productCarousel .carousel-control-next-icon {
        background-color: transparent !important;
        border: none !important;
        padding: 0 !important;
        box-shadow: none !important;
        filter: none !important;
        width: 20px;
        height: 20px;
        transition: var(--transition-smooth);
    }
    
    #productCarousel .carousel-control-prev:hover .rounded-circle,
    #productCarousel .carousel-control-next:hover .rounded-circle {
        opacity: 1 !important;
        background-color: var(--accent-gold) !important;
        transform: scale(1.1);
    }

    /* Indicators */
    #productCarousel .carousel-indicators [data-bs-target] {
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 3px;
        height: 5px;
        width: 24px;
        border: none;
    }
    #productCarousel .carousel-indicators .active {
        background-color: var(--accent-gold);
        width: 32px;
    }

    /* Carousel banner container */
    .banner-gradient-box {
        background: linear-gradient(135deg, #ffffff 0%, #f9f9f9 100%);
        border: 1px solid var(--border-color) !important;
        position: relative;
        overflow: hidden;
        box-shadow: var(--card-shadow) !important;
    }
    
    .banner-gradient-box::before {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(245, 176, 65, 0.15) 0%, transparent 70%);
        border-radius: 50%;
        top: -100px;
        right: -50px;
        filter: blur(40px);
    }

    .banner-content-box {
        height: 420px;
        padding: 3rem !important;
        color: var(--text-main) !important;
        overflow: hidden;
    }
    
    @media (max-width: 767.98px) {
        .banner-content-box {
            height: auto;
        }
    }

    .banner-img-container img {
        transition: var(--transition-smooth);
        transform-origin: center;
    }
    
    .carousel-item.active .banner-img-container img {
        animation: floatImg 6s ease-in-out infinite;
    }
    
    @keyframes floatImg {
        0%, 100% { transform: translateY(0) rotate(0); }
        50% { transform: translateY(-10px) rotate(1deg); }
    }

    @media (max-width: 992px) {
        #productCarousel .carousel-control-prev { left: 10px; }
        #productCarousel .carousel-control-next { right: 10px; }
        .banner-content-box { padding: 2rem !important; }
    }

    /* Search/Filter Bar Glassmorphism */
    .search-filter-bar {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid var(--border-color);
        border-radius: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .search-filter-bar input, .search-filter-bar select {
        background-color: #fff !important;
        color: var(--text-main) !important;
        border-color: var(--border-color);
        font-weight: 500;
        transition: var(--transition-smooth);
    }
    
    .search-filter-bar input:focus, .search-filter-bar select:focus {
        border-color: var(--accent-gold);
        box-shadow: 0 0 0 3px rgba(245, 176, 65, 0.2);
    }
    
    .filter-chip {
        background: #fff;
        border: 1px solid var(--border-color) !important;
        color: var(--text-main);
        font-weight: 500;
        font-size: 0.85rem;
        padding: 6px 14px;
        border-radius: 30px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    /* Category list item styling - Đã sửa lỗi trùng lặp class */
    .category-list-item {
        border: none !important;
        border-radius: 12px !important;
        margin-bottom: 6px;
        font-weight: 600;
        color: var(--text-muted) !important;
        text-decoration: none !important;
        transition: var(--transition-smooth);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 16px !important;
    }
    
    .category-list-item:hover,
    .category-list-item:focus {
        background-color: rgba(245, 176, 65, 0.1) !important;
        color: var(--text-main) !important;
        text-decoration: none !important;
        padding-left: 20px !important;
    }

    .category-list-item.active {
        background: var(--accent-gold) !important;
        color: var(--accent-navy) !important;
        text-decoration: none !important;
        box-shadow: 0 4px 12px -3px rgba(245, 176, 65, 0.4);
    }

    /* Dark theme category items inside mobile offcanvas drawer - CHANGED TO LIGHT */
    .offcanvas-category {
        background: var(--card-bg) !important;
    }
    .offcanvas-category .category-list-item {
        color: var(--text-muted) !important;
        background: transparent;
        text-decoration: none !important;
    }
    .offcanvas-category .category-list-item:hover {
        background-color: rgba(245, 176, 65, 0.1) !important;
        color: var(--text-main) !important;
        text-decoration: none !important;
    }
    .offcanvas-category .category-list-item.active {
        color: var(--accent-navy) !important;
        text-decoration: none !important;
    }
    
    /* Product card customizations */
    .card-img-zoom {
        overflow: hidden;
        position: relative;
        background: #f8f9fa;
        border-radius: 20px 20px 0 0;
        border-bottom: 1px solid var(--border-color);
    }
    
    .card-img-zoom img {
        transition: var(--transition-smooth);
        transform-origin: center;
    }
    
    .card-premium:hover .card-img-zoom img {
        transform: scale(1.06);
    }
    
    /* Stagger fade in effect for grid items */
    .grid-item-animate {
        opacity: 0;
        animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<!-- ===== BANNER SẢN PHẨM BÁN CHẠY NHẤT THÁNG ===== -->
@php
    $topProductsStats = \Illuminate\Support\Facades\DB::table('order_items')
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->where('orders.status', 'paid')
        ->whereMonth('orders.created_at', now()->month)
        ->whereYear('orders.created_at', now()->year)
        ->select('order_items.product_id', \Illuminate\Support\Facades\DB::raw('SUM(order_items.quantity) as total_sold'))
        ->groupBy('order_items.product_id')
        ->having('total_sold', '>=', 3)
        ->orderByDesc('total_sold')
        ->limit(6)
        ->get()
        ->keyBy('product_id');

    $topProductIds = $topProductsStats->keys()->toArray();

    if (empty($topProductIds)) {
        $bannerProducts = collect([]);
    } else {
        $bannerProducts = \App\Models\Product::with('category')
            ->whereIn('id', $topProductIds)
            ->get()
            ->sortBy(function($product) use ($topProductIds) {
                return array_search($product->id, $topProductIds);
            });
            
        foreach ($bannerProducts as $product) {
            $product->total_sold = $topProductsStats[$product->id]->total_sold ?? 0;
        }
    }
@endphp

@if($bannerProducts->count() > 0)
    <div class="container px-md-5 mb-5 mt-3 animate-fade-in">
        <div id="productCarousel" class="carousel slide carousel-fade shadow-lg rounded-4 border-0 position-relative banner-gradient-box" data-bs-ride="carousel" style="overflow: visible;">
            
            <div class="carousel-indicators mb-3">
                @foreach($bannerProducts as $key => $bannerProduct)
                    <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}" aria-current="{{ $loop->first ? 'true' : 'false' }}"></button>
                @endforeach
            </div>

            <div class="carousel-inner rounded-4 overflow-hidden">
                @foreach($bannerProducts as $bannerProduct)
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                        <div class="position-relative text-dark p-4 p-md-5 d-flex align-items-center justify-content-between banner-content-box">
                            <div class="row align-items-center w-100 mx-auto">
                                <div class="col-md-6 text-start pe-md-4">
                                    <span class="badge bg-white text-dark shadow-sm fw-bold mb-3 px-3 py-2 rounded-pill shadow-sm">
                                        <i class="bi bi-star-fill text-warning me-1"></i> BÁN CHẠY NHẤT THÁNG
                                    </span>
                                    <h2 class="fw-bold fs-1 mb-2 text-dark display-font text-truncate" style="font-size: 2.2rem;" title="{{ $bannerProduct->name }}">{{ $bannerProduct->name }}</h2>
                                    <p class="text-muted mb-4" style="height: 72px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; font-size: 1.05rem; line-height: 1.6;">
                                        {{ $bannerProduct->description ?? 'Chưa có mô tả chi tiết cho sản phẩm.' }}
                                    </p>
                                    <div class="fw-bold fs-2 mb-2 text-dark">
                                        {{ number_format($bannerProduct->price, 0, ',', '.') }} đ
                                    </div>
                                    <div class="small text-muted mb-4 d-flex align-items-center gap-3">
                                        <span>Kho sẵn có: <span class="fw-semibold">{{ $bannerProduct->quantity }}</span></span>
                                        @if(isset($bannerProduct->total_sold) && $bannerProduct->total_sold > 0)
                                            <span class="text-danger fw-bold"><i class="bi bi-fire"></i> Đã bán trong tháng: {{ (int)$bannerProduct->total_sold }}</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('products.show', $bannerProduct) }}" class="btn btn-outline-premium rounded-pill px-4 py-2 fw-bold shadow-sm text-decoration-none">
                                        Xem chi tiết <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>

                                <div class="col-md-6 text-center mt-4 mt-md-0 banner-img-container">
                                    @if($bannerProduct->image)
                                        <img src="{{ asset('storage/'.$bannerProduct->image) }}" class="img-fluid rounded-4 shadow-lg" style="height: 290px; width: 100%; max-width: 380px; object-fit: cover;" alt="{{ $bannerProduct->name }}">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center bg-light border-0 rounded-4 mx-auto" style="height: 290px; max-width: 380px; width: 100%;">
                                            <i class="bi bi-laptop display-1 text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev" style="width: 5%; opacity: 1;">
                <div class="bg-dark text-white rounded-circle d-flex justify-content-center align-items-center shadow" style="width: 40px; height: 40px; opacity: 0.8; transition: all 0.3s ease;">
                    <span class="carousel-control-prev-icon" aria-hidden="true" style="width: 20px; height: 20px;"></span>
                </div>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next" style="width: 5%; opacity: 1;">
                <div class="bg-dark text-white rounded-circle d-flex justify-content-center align-items-center shadow" style="width: 40px; height: 40px; opacity: 0.8; transition: all 0.3s ease;">
                    <span class="carousel-control-next-icon" aria-hidden="true" style="width: 20px; height: 20px;"></span>
                </div>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
@endif

<!-- ===== THANH TÌM KIẾM & BỘ LỌC ===== -->
<div id="ajax-filter-container" class="position-relative">
    <!-- Loading Overlay -->
    <div id="ajax-loading-overlay" class="position-absolute top-0 start-0 w-100 h-100 d-none justify-content-center align-items-start pt-5" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(4px); z-index: 1000; border-radius: 20px;">
        <div class="spinner-border text-primary shadow" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="search-filter-bar animate-fade-in shadow-sm">
    <form action="{{ url()->current() }}" method="GET" id="filter-form" class="row g-3 align-items-start">
        
        <div class="col-lg-4 col-md-12">
            <label class="form-label fw-semibold text-secondary small mb-1"><i class="bi bi-search me-1 text-primary"></i> Tìm kiếm sản phẩm</label>
            <input type="text" name="search" class="form-control shadow-none" placeholder="Nhập tên sản phẩm cần tìm..." value="{{ request('search') }}" style="border-color: rgba(0, 0, 0, 0.08); background-color: #ffffff; color: #222222; border-radius: 8px;">
        </div>

        <div class="col-lg-4 col-md-6">
            <label class="form-label fw-semibold text-secondary small mb-1"><i class="bi bi-cash me-1 text-primary"></i> Khoảng giá (VNĐ)</label>
            <div class="input-group">
                <input type="number" name="min_price" class="form-control shadow-none" placeholder="Từ..." value="{{ request('min_price') }}" style="border-color: rgba(0, 0, 0, 0.08); background-color: #ffffff; color: #222222;">
                <span class="input-group-text border-0" style="background-color: #f8f9fa; color: #fff;">-</span>
                <input type="number" name="max_price" class="form-control shadow-none" placeholder="Đến..." value="{{ request('max_price') }}" style="border-color: rgba(0, 0, 0, 0.08); background-color: #ffffff; color: #222222;">
            </div>
        </div>

        <div class="col-lg-3 col-md-4">
            <label class="form-label fw-semibold text-secondary small mb-1"><i class="bi bi-funnel me-1 text-primary"></i> Sắp xếp</label>
            <select name="sort" class="form-select shadow-none" style="border-color: rgba(0, 0, 0, 0.08); background-color: #ffffff; color: #222222; border-radius: 8px;">
                <option value="" {{ request('sort') == '' ? 'selected' : '' }}>Mặc định mới nhất</option>
                <option value="sales_desc" {{ request('sort') == 'sales_desc' ? 'selected' : '' }}>🔥 Nhiều Lượt Mua</option>
                <option value="wishlist_desc" {{ request('sort') == 'wishlist_desc' ? 'selected' : '' }}>❤️ Yêu Thích</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>💰 Giá Thấp Đến Cao</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>💰 Giá Cao Đến Thấp</option>
            </select>
        </div>

        <div class="col-lg-1 col-md-2 d-flex align-items-end">
             <button class="btn btn-primary w-100 fw-bold" type="submit" style="height: 38px; border-radius: 8px;">Lọc</button>
        </div>

        @if(request('category'))
            <input type="hidden" name="category" value="{{ request('category') }}">
        @endif

        <div class="col-12 pt-1">
            @if(request('search') || request('category') || request('sort') || request('min_price') || request('max_price'))
                <div class="d-flex flex-wrap align-items-center gap-2 pt-2 pt-md-4">
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
                    <a href="{{ url()->current() }}" class="btn btn-sm btn-link text-danger text-decoration-none small p-0 fw-bold ms-2">Xóa tất cả</a>
                </div>
            @endif
        </div>
    </form>
</div>

<!-- ===== PHẦN CHÍNH: SIDEBAR DANH MỤC + LƯỚI SẢN PHẨM ===== -->

<!-- Nút toggle danh mục trên Mobile -->
<button class="btn mobile-category-toggle d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileCategoryDrawer" aria-controls="mobileCategoryDrawer" title="Danh mục sản phẩm">
    <i class="bi bi-grid-3x3-gap-fill fs-5"></i>
</button>

<!-- Offcanvas Drawer cho Mobile -->
<div class="offcanvas offcanvas-start offcanvas-category d-lg-none" tabindex="-1" id="mobileCategoryDrawer" aria-labelledby="mobileCategoryDrawerLabel">
    <div class="offcanvas-header py-3 px-4">
        <h5 class="offcanvas-title fw-bold text-dark display-font" id="mobileCategoryDrawerLabel">
            <i class="bi bi-grid-3x3-gap-fill text-primary me-2"></i>Danh mục
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body px-3 pt-0">
        <div class="list-group list-group-flush rounded-3">
            <a href="{{ url()->current() . '?' . http_build_query(request()->except(['category', 'page'])) }}" 
               class="category-list-item text-decoration-none {{ !request('category') ? 'active' : '' }}">
                <span><i class="bi bi-grid-fill me-2"></i> Tất cả sản phẩm</span>
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

<div class="row g-4 animate-fade-in">
    <!-- Cột trái: Sidebar Danh mục (Desktop only) -->
    <div class="col-lg-3 d-none d-lg-block">
        <div class="card border-0 shadow-sm rounded-4 p-3 sticky-lg-top" style="top: 90px; z-index: 10; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);">
            <div>
                <h5 class="fw-bold mb-3 text-dark d-flex align-items-center gap-2" style="font-size: 1.1rem; letter-spacing: -0.2px;">
                    <i class="bi bi-grid-3x3-gap-fill text-primary"></i> Danh mục
                </h5>
                <div class="list-group list-group-flush rounded-3">
                    <!-- Tất cả sản phẩm -->
                    <a href="{{ url()->current() . '?' . http_build_query(request()->except(['category', 'page'])) }}" 
                       class="category-list-item text-decoration-none {{ !request('category') ? 'active' : '' }}">
                        <span><i class="bi bi-grid-fill me-2"></i> Tất cả sản phẩm</span>
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
    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-dark mb-0 d-flex align-items-center gold-underline gap-2" style="letter-spacing: -0.5px;">
                <i class="bi bi-cpu-fill text-primary"></i> Khám phá sản phẩm
            </h3>
            <span class="badge bg-warning bg-opacity-10 text-dark px-3 py-2 rounded-pill fw-semibold">{{ $products->total() }} sản phẩm</span>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4 mb-5">
            @forelse ($products as $product)
                <div class="col grid-item-animate" style="animation-delay: {{ $loop->index * 0.06 }}s;">
                    <div class="card h-100 card-premium">
                        <div class="position-absolute top-0 start-0 m-3 z-3 d-flex justify-content-between w-100 pe-5">
                            <span class="badge rounded-pill small fw-bold shadow-sm" style="background: var(--accent-gold); color: var(--accent-navy); padding: 6px 12px; border: 1px solid rgba(255,255,255,0.12); font-size: 0.75rem; letter-spacing: 0.3px;">
                                <i class="bi bi-tag-fill me-1" style="color: #007aff;"></i>{{ $product->category->name ?? 'Chưa phân loại' }}
                            </span>
                        </div>
                        <div class="position-absolute top-0 end-0 m-3 z-3">
                            <button class="btn btn-light rounded-circle shadow-sm btn-wishlist d-flex align-items-center justify-content-center" 
                                    style="width: 35px; height: 35px;" 
                                    data-product-id="{{ $product->id }}" 
                                    onclick="toggleWishlist(event, this, {{ $product->id }})">
                                <i class="bi {{ in_array($product->id, $wishlistIds ?? []) ? 'bi-heart-fill text-danger' : 'bi-heart text-secondary' }}"></i>
                            </button>
                        </div>
                        
                        <div class="card-img-zoom">
                            @if(!empty($product->image))
                                <div class="position-relative" style="aspect-ratio: 4/3;">
                                    <img src="{{ asset('storage/'.$product->image) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-100 h-100" 
                                         style="object-fit: cover;" 
                                         loading="lazy">
                                    @if($product->quantity <= 0)
                                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-white shadow-sm bg-opacity-50 d-flex align-items-center justify-content-center">
                                            <span class="badge bg-danger fs-6 py-2 px-3 rounded-pill fw-bold shadow-sm">HẾT HÀNG</span>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="d-flex flex-column align-items-center justify-content-center position-relative" style="aspect-ratio: 4/3; background: linear-gradient(135deg, #f8f9fa 0%, #eaeaea 100%);">
                                    <i class="bi bi-cpu" style="font-size: 3rem; opacity: 0.2; color: #F5B041;"></i>
                                    <span class="text-dark small mt-2" style="opacity: 0.25; font-size: 0.75rem;">Chưa có hình ảnh</span>
                                    @if($product->quantity <= 0)
                                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-white shadow-sm bg-opacity-50 d-flex align-items-center justify-content-center">
                                            <span class="badge bg-danger fs-6 py-2 px-3 rounded-pill fw-bold shadow-sm">HẾT HÀNG</span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="card-body d-flex flex-column p-4">
                            <h5 class="card-title fw-bold text-dark text-truncate mb-2" title="{{ $product->name }}" style="font-size: 1.15rem; letter-spacing: -0.2px;">
                                {{ $product->name }}
                            </h5>
                            
                            <div class="d-flex justify-content-between align-items-baseline mb-3">
                                <div class="text-danger fw-bold fs-5 display-font" style="background: linear-gradient(135deg, #F5B041 0%, #D4941F 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                                    {{ number_format($product->price, 0, ',', '.') }} đ
                                </div>
                                <small class="text-muted">
                                    @if($product->quantity > 0)
                                        Còn lại: <strong class="text-success">{{ $product->quantity }}</strong>
                                    @else
                                        <span class="text-danger">Liên hệ</span>
                                    @endif
                                </small>
                            </div>
                            
                            <div class="d-flex gap-2 mt-auto">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-outline-premium flex-grow-1 justify-content-center text-decoration-none" style="padding: 8px 12px; font-size: 0.85rem;">
                                    Xem chi tiết <i class="bi bi-arrow-right small ms-1"></i>
                                </a>
                                @auth
                                    @if($product->quantity > 0)
                                        <button type="button" 
                                                class="btn btn-add-cart-quick" 
                                                onclick="quickAddToCart({{ $product->id }}, this)" 
                                                title="Thêm vào giỏ hàng">
                                            <i class="bi bi-cart-plus-fill"></i>
                                            <span class="d-none d-xl-inline">Thêm giỏ</span>
                                        </button>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-emoji-frown display-1 text-muted"></i>
                    <p class="fs-4 text-muted mt-3">Không tìm thấy sản phẩm nào phù hợp.</p>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
        </div>
    </div>
</div>
</div> <!-- End #ajax-filter-container -->

<!-- Script AJAX Filter -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('ajax-filter-container');
        
        function fetchAndUpdate(url, isPushState = true) {
            const overlay = document.getElementById('ajax-loading-overlay');
            if(overlay) overlay.classList.remove('d-none');
            if(overlay) overlay.classList.add('d-flex');

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
                } else {
                    window.location.href = url; // Fallback
                }
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                window.location.href = url; // Fallback
            })
            .finally(() => {
                const newOverlay = document.getElementById('ajax-loading-overlay');
                if(newOverlay) newOverlay.classList.add('d-none');
                if(newOverlay) newOverlay.classList.remove('d-flex');
            });
        }

        // Bắt sự kiện quay lại trang trước (Back button)
        window.addEventListener('popstate', function(e) {
            if (e.state && e.state.path) {
                fetchAndUpdate(e.state.path, false);
            } else {
                fetchAndUpdate(window.location.href, false);
            }
        });

        // Sử dụng Event Delegation cho toàn bộ container
        container.addEventListener('click', function(e) {
            // 1. Chặn click thẻ 'a' thuộc danh mục, phân trang, xoá filter chip
            const link = e.target.closest('a.category-list-item, .pagination a, .filter-chip a, a.btn-link');
            if (link) {
                // Kiểm tra nếu là link "#" thì bỏ qua
                const href = link.getAttribute('href');
                if (!href || href === '#' || href.startsWith('javascript:')) return;
                
                e.preventDefault();
                fetchAndUpdate(link.href);
            }
        });

        // 2. Chặn submit form tìm kiếm
        container.addEventListener('submit', function(e) {
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
                
                fetchAndUpdate(url);
            }
        });
    });
</script>

<!-- Script kích hoạt carousel chạy tự động -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var carouselEl = document.getElementById('productCarousel');
        if (carouselEl) {
            var carousel = new bootstrap.Carousel(carouselEl, {
                interval: 3500,
                ride: 'carousel',
                wrap: true
            });
        }
    });
</script>
@endsection