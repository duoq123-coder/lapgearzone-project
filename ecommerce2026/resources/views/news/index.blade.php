@extends('layouts.app')

@section('title', 'Tin Tức & Xu Hướng Công Nghệ 2026 - LapGearZone')

@push('styles')
<style>
    .news-page-hero {
        background: radial-gradient(circle at 85% 25%, rgba(205, 76, 32, 0.12) 0%, transparent 60%),
                    radial-gradient(circle at 10% 85%, rgba(78, 121, 105, 0.1) 0%, transparent 60%),
                    var(--surface-muted);
        border-radius: 28px;
        padding: 3.5rem 2rem;
        margin-bottom: 3rem;
        position: relative;
        overflow: hidden;
        border: 1px solid var(--border-color);
    }

    .news-card-box {
        background: #ffffff !important;
        border: 1px solid rgba(0, 0, 0, 0.08) !important;
        border-radius: 20px !important;
        overflow: hidden;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease, border-color 0.3s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    [data-bs-theme="dark"] .news-card-box {
        background: #1e1e24 !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
    }

    .news-card-box:hover {
        transform: translateY(-6px);
        border-color: rgba(205, 76, 32, 0.45) !important;
        box-shadow: 0 16px 36px rgba(205, 76, 32, 0.12), 0 4px 14px rgba(0, 0, 0, 0.06);
    }

    .news-thumb-wrapper {
        position: relative;
        overflow: hidden;
        aspect-ratio: 16 / 9;
        background: #1a1c20;
    }

    .news-thumb-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .news-card:hover .news-thumb-img {
        transform: scale(1.06);
    }

    .news-category-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        background: rgba(18, 22, 28, 0.72);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #ffffff;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        padding: 4px 11px;
        border-radius: 50rem;
    }

    .news-card-body {
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .news-meta {
        font-size: 0.78rem;
        color: var(--text-muted);
        margin-bottom: 0.6rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .news-card-title {
        font-family: 'Inter', sans-serif;
        font-weight: 800;
        font-size: 1.05rem;
        line-height: 1.4;
        color: var(--text-main);
        margin-bottom: 0.6rem;
        transition: color 0.2s ease;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .news-card:hover .news-card-title {
        color: var(--bellroy-orange);
    }

    .news-card-excerpt {
        font-size: 0.85rem;
        color: var(--text-muted);
        line-height: 1.5;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .news-read-more {
        margin-top: auto;
        font-weight: 700;
        font-size: 0.85rem;
        color: var(--bellroy-orange);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        transition: gap 0.2s ease;
    }

    .news-card:hover .news-read-more {
        gap: 10px;
    }

    .category-filter-pill {
        display: inline-block;
        padding: 7px 18px;
        border-radius: 50rem;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        color: var(--text-muted);
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        transition: all 0.25s ease;
    }

    .category-filter-pill:hover,
    .category-filter-pill.active {
        background: var(--bellroy-orange);
        color: #ffffff;
        border-color: var(--bellroy-orange);
        box-shadow: 0 4px 12px rgba(205, 76, 32, 0.25);
    }
</style>
@endpush

@section('content')
<div class="container my-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('welcome') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item active fw-semibold text-dark" aria-current="page">Bản tin công nghệ</li>
        </ol>
    </nav>

    <!-- Hero Section -->
    <div class="news-page-hero text-center">
        <div class="d-inline-flex align-items-center gap-2 rounded-pill badge-live-beacon mb-3">
            <span class="live-ping-dot"></span>
            <span class="fw-bold"><i class="bi bi-newspaper me-1"></i> LAPGEARZONE EDITORIAL</span>
        </div>
        <h1 class="display-5 fw-bold text-dark mb-3 display-font">
            Xu Hướng &amp; <span style="color: var(--bellroy-orange);">Tin Tức Công Nghệ 2026</span>
        </h1>
        <p class="text-muted mx-auto mb-4" style="max-width: 680px; font-size: 1.05rem;">
            Cập nhật những chuyển động công nghệ mới nhất: Kỷ nguyên vi xử lý AI trên laptop, công nghệ màn hình OLED đỉnh cao, đánh giá chuyên sâu và cẩm nang sử dụng thiết bị tối ưu.
        </p>

        <!-- Search Bar -->
        <div class="d-flex justify-content-center">
            <form action="{{ route('news.index') }}" method="GET" class="w-100" style="max-width: 540px;">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <div class="input-group input-group-lg shadow-sm rounded-pill overflow-hidden border">
                    <span class="input-group-text bg-white border-0 ps-4 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" 
                           name="search" 
                           class="form-control border-0 bg-white" 
                           placeholder="Tìm kiếm bài viết, chủ đề công nghệ..." 
                           value="{{ request('search') }}">
                    <button class="btn btn-dark px-4 fw-bold" type="submit">Tìm kiếm</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Category Filter Bar -->
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 gap-3">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('news.index', request()->only(['search'])) }}" 
               class="category-filter-pill {{ !request('category') ? 'active' : '' }}">
                Tất cả chủ đề
            </a>
            @if(isset($categories))
                @foreach($categories as $cat)
                    <a href="{{ route('news.index', array_merge(request()->only(['search']), ['category' => $cat])) }}" 
                       class="category-filter-pill {{ request('category') == $cat ? 'active' : '' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            @endif
        </div>

        @if(request('search') || request('category'))
            <a href="{{ route('news.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                <i class="bi bi-x-circle me-1"></i> Xóa bộ lọc
            </a>
        @endif
    </div>

    <!-- News Grid (4 Cột chia thành các ô rõ ràng) -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3 g-xl-4 mb-5">
        @forelse($newsList as $item)
        <div class="col">
            <div class="card h-100 news-card-box">
                <div class="news-thumb-wrapper">
                    <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="news-thumb-img" loading="lazy">
                    <span class="news-category-badge">
                        {{ $item->category }}
                    </span>
                </div>
                <div class="card-body p-3 d-flex flex-column">
                    <div class="news-meta">
                        <span><i class="bi bi-calendar3 me-1"></i> {{ $item->formatted_date }}</span>
                        <span>•</span>
                        <span><i class="bi bi-clock me-1"></i> {{ $item->read_time }}</span>
                    </div>
                    <h4 class="news-card-title">
                        <a href="{{ route('news.show', $item->slug) }}" class="text-decoration-none text-inherit">
                            {{ $item->title }}
                        </a>
                    </h4>
                    <p class="news-card-excerpt flex-grow-1">
                        {{ $item->summary }}
                    </p>
                    <div class="news-card-footer pt-2 border-top d-flex justify-content-between align-items-center mt-auto">
                        <a href="{{ route('news.show', $item->slug) }}" class="news-read-more">
                            <span>Đọc bài viết</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                        <span class="text-muted small">
                            <i class="bi bi-eye me-1"></i>{{ number_format($item->views_count) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-journal-x text-muted" style="font-size: 3rem;"></i>
            <h4 class="fw-bold mt-3 text-dark">Không tìm thấy bài viết nào</h4>
            <p class="text-muted mb-4">Rất tiếc, không có bài viết nào khớp với từ khóa hoặc thể loại bạn đang tìm.</p>
            <a href="{{ route('news.index') }}" class="btn btn-premium px-4">
                Xem tất cả bài viết
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($newsList->hasPages())
    <div class="d-flex justify-content-center mb-5">
        {{ $newsList->links() }}
    </div>
    @endif
</div>
@endsection
