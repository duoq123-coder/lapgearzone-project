@extends('layouts.app')

@section('title', $news->title . ' - LapGearZone')

@push('styles')
<style>
    .article-container {
        max-width: 880px;
        margin: 0 auto;
    }

    .article-hero-img-wrapper {
        border-radius: 24px;
        overflow: hidden;
        aspect-ratio: 16 / 9;
        box-shadow: 0 16px 36px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-color);
        margin-bottom: 2.5rem;
        background: #111;
    }

    .article-hero-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .article-meta-bar {
        padding: 1rem 0;
        border-top: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 2.5rem;
    }

    .article-body {
        font-size: 1.1rem;
        line-height: 1.85;
        color: var(--text-main);
    }

    .article-body p {
        margin-bottom: 1.6rem;
    }

    .article-body h4 {
        font-family: 'Inter', sans-serif;
        font-weight: 800;
        margin-top: 2.4rem;
        margin-bottom: 1rem;
        color: var(--text-main);
    }

    .article-body blockquote {
        background: var(--surface-muted);
        border-left: 4px solid var(--bellroy-orange);
        padding: 1.25rem 1.5rem;
        border-radius: 0 16px 16px 0;
        margin: 2rem 0;
    }

    .article-body ul, .article-body ol {
        margin-bottom: 1.6rem;
        padding-left: 1.5rem;
    }

    .article-body li {
        margin-bottom: 0.5rem;
    }

    /* Related News Cards */
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
</style>
@endpush

@section('content')
<div class="container my-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('welcome') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('news.index') }}" class="text-decoration-none text-muted">Bản tin công nghệ</a></li>
            <li class="breadcrumb-item active fw-semibold text-dark text-truncate" style="max-width: 350px;" aria-current="page">{{ $news->title }}</li>
        </ol>
    </nav>

    <!-- Main Article -->
    <article class="article-container mb-5">
        <!-- Category & Title -->
        <div class="mb-3">
            <span class="badge rounded-pill px-3 py-2 fw-bold text-uppercase" style="background: var(--bellroy-orange); color: #fff; font-size: 0.8rem; letter-spacing: 0.05em;">
                {{ $news->category }}
            </span>
        </div>

        <h1 class="display-5 fw-bold text-dark mb-4 display-font lh-sm">
            {{ $news->title }}
        </h1>

        <!-- Meta Bar -->
        <div class="article-meta-bar d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center fw-bold" style="width: 44px; height: 44px; font-size: 1.1rem; background: var(--bellroy-charcoal) !important;">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div>
                    <div class="fw-bold text-dark" style="font-size: 0.95rem;">{{ $news->author_name }}</div>
                    <div class="text-muted small">
                        <i class="bi bi-calendar3 me-1"></i>{{ $news->formatted_date }}
                        <span class="mx-1">•</span>
                        <i class="bi bi-clock me-1"></i>{{ $news->read_time }}
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <span class="text-muted small me-2"><i class="bi bi-eye me-1"></i>{{ number_format($news->views_count) }} lượt xem</span>
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill" onclick="copyArticleLink();" id="btnCopyLink">
                    <i class="bi bi-link-45deg me-1"></i> Chia sẻ
                </button>
            </div>
        </div>

        <!-- Hero Image -->
        <div class="article-hero-img-wrapper">
            <img src="{{ $news->image_url }}" alt="{{ $news->title }}" class="article-hero-img">
        </div>

        <!-- Excerpt highlight -->
        <div class="p-4 rounded-4 mb-4" style="background: var(--surface-muted); border-left: 5px solid var(--bellroy-orange);">
            <p class="fs-5 fw-semibold text-dark mb-0 fst-italic" style="line-height: 1.6;">
                "{{ $news->summary }}"
            </p>
        </div>

        <!-- Article Content -->
        <div class="article-body">
            {!! $news->content !!}
        </div>

        <!-- Article Footer / Tags -->
        <div class="mt-5 pt-4 border-top d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted small fw-bold text-uppercase">Chủ đề:</span>
                <a href="{{ route('news.index', ['category' => $news->category]) }}" class="badge bg-light text-dark border text-decoration-none rounded-pill px-3 py-2">
                    #{{ $news->category }}
                </a>
            </div>
            <a href="{{ route('news.index') }}" class="btn btn-outline-dark rounded-pill px-4">
                <i class="bi bi-arrow-left me-1"></i> Xem tất cả tin tức
            </a>
        </div>
    </article>

    <!-- Related Articles (4 Cột thoáng mắt) -->
    @if(isset($relatedNews) && $relatedNews->count() > 0)
    <section class="mt-5 pt-5 border-top">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h3 class="fw-bold text-dark display-font mb-1">Bài viết cùng chuyên mục</h3>
                <p class="text-muted small mb-0">Khám phá thêm các phân tích và xu hướng công nghệ liên quan</p>
            </div>
            <a href="{{ route('news.index') }}" class="btn btn-sm btn-link text-decoration-none fw-bold" style="color: var(--bellroy-orange);">
                Xem tất cả <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3 g-xl-4">
            @foreach($relatedNews as $item)
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
                        <div class="news-card-footer pt-2 border-top d-flex justify-content-between align-items-center mt-auto">
                            <a href="{{ route('news.show', $item->slug) }}" class="news-read-more">
                                <span>Đọc tiếp</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif
</div>

@push('scripts')
<script>
    function copyArticleLink() {
        navigator.clipboard.writeText(window.location.href).then(function() {
            const btn = document.getElementById('btnCopyLink');
            btn.innerHTML = '<i class="bi bi-check2 me-1"></i> Đã sao chép link';
            btn.classList.remove('btn-outline-secondary');
            btn.classList.add('btn-success');
            setTimeout(() => {
                btn.innerHTML = '<i class="bi bi-link-45deg me-1"></i> Chia sẻ';
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-secondary');
            }, 2500);
        });
    }
</script>
@endpush
@endsection
