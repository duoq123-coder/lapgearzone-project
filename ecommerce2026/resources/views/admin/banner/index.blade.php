@extends('admin.layouts.app')
@section('title', 'Quản Lý Banner Video Trang Chủ - Admin')

@section('content')
<div class="mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h2 class="mb-1 fw-bold text-dark display-font">
                <i class="bi bi-play-btn-fill me-2" style="color: var(--bellroy-orange, #ea580c);"></i>Quản Lý Banner Hero Video
            </h2>
            <p class="text-secondary small mb-0">Tùy biến video nền sống động, ảnh dự phòng và toàn bộ nội dung hiển thị trên banner trang chủ.</p>
        </div>
        <div>
            <a href="{{ route('welcome') }}" class="btn btn-outline-dark rounded-pill px-3 py-2 fw-semibold btn-sm shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Xem Trang Chủ
            </a>
        </div>
    </div>
</div>


@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 rounded-4 shadow-sm mb-4" role="alert">
        <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Có lỗi xảy ra, vui lòng kiểm tra lại:</div>
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<form action="{{ route('admin.banner.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row g-4">
        <!-- Cột trái: Form điều khiển -->
        <div class="col-lg-7">
            
            <!-- 1. Chế độ hiển thị Banner -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h5 class="fw-bold text-dark mb-3 d-flex align-items-center">
                    <i class="bi bi-display me-2 text-primary"></i> 1. Chế độ hiển thị Banner
                </h5>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="card h-100 p-3 border-2 rounded-4 text-center cursor-pointer banner-mode-option {{ old('hero_banner_type', $bannerType) === 'video' ? 'active-mode border-primary bg-primary-subtle' : 'border-light-subtle' }}" style="cursor: pointer;">
                            <input type="radio" name="hero_banner_type" value="video" class="d-none" {{ old('hero_banner_type', $bannerType) === 'video' ? 'checked' : '' }} onchange="onModeChange('video')">
                            <i class="bi bi-play-circle-fill text-primary display-6 mb-2"></i>
                            <span class="fw-bold d-block text-dark">Video nền sống động</span>
                            <span class="text-secondary small">Tự động phát, chạy lặp mượt mà</span>
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <label class="card h-100 p-3 border-2 rounded-4 text-center cursor-pointer banner-mode-option {{ old('hero_banner_type', $bannerType) === 'image' ? 'active-mode border-primary bg-primary-subtle' : 'border-light-subtle' }}" style="cursor: pointer;">
                            <input type="radio" name="hero_banner_type" value="image" class="d-none" {{ old('hero_banner_type', $bannerType) === 'image' ? 'checked' : '' }} onchange="onModeChange('image')">
                            <i class="bi bi-image text-primary display-6 mb-2"></i>
                            <span class="fw-bold d-block text-dark">Ảnh nền tĩnh</span>
                            <span class="text-secondary small">Hình ảnh sắc nét tối giản</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- 2. Cài đặt Video Nền -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" id="sectionVideoSettings" style="{{ old('hero_banner_type', $bannerType) === 'image' ? 'display: none;' : '' }}">
                <h5 class="fw-bold text-dark mb-3 d-flex align-items-center justify-content-between">
                    <span><i class="bi bi-camera-video-fill me-2 text-primary"></i> 2. Nguồn Video Nền</span>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill small">
                        Hiện tại: {{ basename($bannerVideo) }}
                    </span>
                </h5>

                <!-- Chọn video mẫu hoặc tự tải lên -->
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark small">A. Chọn từ danh sách Video Mẫu (Chuẩn HD tối ưu)</label>
                    <div class="vstack gap-2">
                        @foreach($presetVideos as $path => $label)
                            <label class="d-flex align-items-center p-2 px-3 border rounded-3 bg-light cursor-pointer hover-shadow" style="cursor: pointer;">
                                <input type="radio" name="hero_banner_video_preset" value="{{ $path }}" class="form-check-input me-3" {{ $bannerVideo === $path ? 'checked' : '' }} onchange="updateVideoPreview('{{ asset($path) }}')">
                                <div class="flex-grow-1">
                                    <div class="fw-bold text-dark small">{{ $label }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">Đường dẫn: <code>{{ $path }}</code></div>
                                </div>
                                <span class="badge bg-secondary-subtle text-secondary small">1080p</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Tải lên video mới -->
                <div class="mb-3">
                    <label for="hero_banner_video_file" class="form-label fw-bold text-dark small">B. Hoặc Tải lên video mới từ máy tính (.mp4, .webm)</label>
                    <input type="file" class="form-control rounded-3" id="hero_banner_video_file" name="hero_banner_video_file" accept="video/mp4,video/webm,video/ogg">
                    <div class="form-text small">Hỗ trợ định dạng MP4, WebM (Dung lượng tối đa khuyến nghị: dưới 30MB để tải trang nhanh nhất).</div>
                </div>

                <!-- URL video trực tiếp -->
                <div class="mb-2">
                    <label for="hero_banner_video_url" class="form-label fw-bold text-dark small">C. Hoặc Nhập URL video trực tiếp (CDN / Cloud)</label>
                    <input type="url" class="form-control rounded-3" id="hero_banner_video_url" name="hero_banner_video_url" placeholder="https://example.com/laptop-video.mp4" value="{{ filter_var($bannerVideo, FILTER_VALIDATE_URL) ? $bannerVideo : '' }}">
                </div>
            </div>

            <!-- 3. Cài đặt Ảnh Nền & Poster -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h5 class="fw-bold text-dark mb-3 d-flex align-items-center justify-content-between">
                    <span><i class="bi bi-file-earmark-image-fill me-2 text-primary"></i> 3. Ảnh Nền Poster & Fallback</span>
                    <span class="badge bg-secondary-subtle text-secondary px-2 py-1 rounded-pill small">
                        {{ basename($bannerImage) }}
                    </span>
                </h5>
                <p class="text-muted small mb-3">Ảnh này sẽ xuất hiện khi đang tải video, hoặc khi chọn chế độ Ảnh nền tĩnh.</p>
                <div class="mb-3">
                    <label for="hero_banner_image_file" class="form-label fw-bold text-dark small">Tải lên ảnh mới (.jpg, .png, .webp)</label>
                    <input type="file" class="form-control rounded-3" id="hero_banner_image_file" name="hero_banner_image_file" accept="image/*">
                </div>
                
                <!-- Độ tối lớp phủ (Overlay) -->
                <div class="mt-3 pt-3 border-top">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label for="hero_banner_overlay" class="form-label fw-bold text-dark small mb-0">Độ tối lớp phủ nền (Dark Overlay)</label>
                        <span class="badge bg-dark rounded-pill px-2 py-1" id="overlayValueBadge">{{ round((float)$bannerOverlay * 100) }}%</span>
                    </div>
                    <input type="range" class="form-range" id="hero_banner_overlay" name="hero_banner_overlay" min="0.2" max="0.95" step="0.05" value="{{ $bannerOverlay }}" oninput="updateOverlayPreview(this.value)">
                    <div class="form-text small">Tăng độ tối giúp chữ trắng và các nút bấm luôn nổi bật, dễ đọc trên mọi nền video.</div>
                </div>
            </div>

            <!-- 4. Quản lý Nội dung Văn bản & Nút Bấm -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h5 class="fw-bold text-dark mb-3 d-flex align-items-center">
                    <i class="bi bi-fonts me-2 text-primary"></i> 4. Nội Dung Văn Bản & Nút Bấm
                </h5>

                <!-- Tên Thương hiệu góc trên -->
                <div class="mb-3">
                    <label for="hero_banner_brand" class="form-label fw-bold text-dark small">Tên thương hiệu góc trên banner</label>
                    <input type="text" class="form-control rounded-3" id="hero_banner_brand" name="hero_banner_brand" value="{{ old('hero_banner_brand', $bannerBrand) }}" oninput="updateTextPreview('brand', this.value)">
                </div>

                <!-- Nhãn Badge -->
                <div class="mb-3">
                    <label for="hero_banner_badge" class="form-label fw-bold text-dark small">Nhãn nổi bật (Badge)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-warning"><i class="bi bi-lightning-charge-fill"></i></span>
                        <input type="text" class="form-control rounded-end-3" id="hero_banner_badge" name="hero_banner_badge" value="{{ old('hero_banner_badge', $bannerBadge) }}" oninput="updateTextPreview('badge', this.value)">
                    </div>
                </div>

                <!-- Tiêu đề chính -->
                <div class="mb-3">
                    <label for="hero_banner_headline" class="form-label fw-bold text-dark small">Tiêu đề chính (Headline)</label>
                    <input type="text" class="form-control rounded-3" id="hero_banner_headline" name="hero_banner_headline" value="{{ old('hero_banner_headline', $bannerHeadline) }}" oninput="updateTextPreview('headline', this.value)">
                    <div class="form-text small">Ví dụ: <em>Đẳng Cấp Laptop Cho Không Gian Đỉnh Cao</em></div>
                </div>

                <!-- Đoạn mô tả -->
                <div class="mb-3">
                    <label for="hero_banner_subtext" class="form-label fw-bold text-dark small">Đoạn mô tả ngắn (Subtext)</label>
                    <textarea class="form-control rounded-3" id="hero_banner_subtext" name="hero_banner_subtext" rows="3" oninput="updateTextPreview('subtext', this.value)">{{ old('hero_banner_subtext', $bannerSubtext) }}</textarea>
                </div>

                <!-- Nút CTA -->
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label for="hero_banner_btn_text" class="form-label fw-bold text-dark small">Chữ trên nút (Button Text)</label>
                        <input type="text" class="form-control rounded-3" id="hero_banner_btn_text" name="hero_banner_btn_text" value="{{ old('hero_banner_btn_text', $bannerBtnText) }}" oninput="updateTextPreview('btn_text', this.value)">
                    </div>
                    <div class="col-sm-6">
                        <label for="hero_banner_btn_url" class="form-label fw-bold text-dark small">Liên kết khi bấm nút (URL)</label>
                        <input type="text" class="form-control rounded-3" id="hero_banner_btn_url" name="hero_banner_btn_url" value="{{ old('hero_banner_btn_url', $bannerBtnUrl) }}">
                        <div class="form-text small">Mặc định: <code>#product-grid-section</code> (cuộn đến sản phẩm)</div>
                    </div>
                </div>
            </div>

            <!-- Nút Lưu cấu hình -->
            <div class="d-flex justify-content-end mb-5">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm fw-bold">
                    <i class="bi bi-check2-circle me-2"></i> Lưu Cấu Hình Banner
                </button>
            </div>

        </div>

        <!-- Cột phải: Live Preview trực tiếp -->
        <div class="col-lg-5">
            <div class="sticky-top" style="top: 90px; z-index: 10;">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fw-bold text-dark small d-flex align-items-center">
                            <span class="spinner-grow spinner-grow-sm text-danger me-2" role="status"></span>
                            Xem Trước Trực Tiếp (Live Preview)
                        </span>
                        <span class="badge bg-light text-secondary border small">Realtime</span>
                    </div>

                    <!-- Live Banner Frame -->
                    <div class="preview-banner-container" id="previewBannerContainer">
                        <!-- Video preview -->
                        <video id="previewVideo" autoplay loop muted playsinline class="preview-media" style="{{ old('hero_banner_type', $bannerType) === 'image' ? 'display: none;' : '' }}">
                            <source src="{{ asset($bannerVideo) }}" type="video/mp4">
                        </video>

                        <!-- Image fallback preview -->
                        <img id="previewImage" src="{{ asset($bannerImage) }}" alt="Preview Image" class="preview-media" style="{{ old('hero_banner_type', $bannerType) === 'video' ? 'display: none;' : '' }}">

                        <!-- Dark Overlay -->
                        <div class="preview-overlay" id="previewOverlay" style="background: linear-gradient(90deg, rgba(10, 12, 16, {{ $bannerOverlay }}) 0%, rgba(10, 12, 16, {{ (float)$bannerOverlay * 0.85 }}) 45%, rgba(10, 12, 16, {{ (float)$bannerOverlay * 0.4 }}) 80%, rgba(10, 12, 16, 0.2) 100%);"></div>

                        <!-- Content Mockup -->
                        <div class="preview-content">
                            <!-- Brand -->
                            <div class="preview-brand" id="previewBrandText">{{ $bannerBrand }}</div>

                            <!-- Badge -->
                            <div class="preview-badge">
                                <i class="bi bi-lightning-charge-fill text-warning"></i>
                                <span id="previewBadgeText">{{ $bannerBadge }}</span>
                            </div>

                            <!-- Headline -->
                            <div class="preview-headline" id="previewHeadlineText">{{ $bannerHeadline }}</div>

                            <!-- Subtext -->
                            <div class="preview-subtext" id="previewSubtextText">{{ $bannerSubtext }}</div>

                            <!-- Button -->
                            <div>
                                <span class="preview-btn" id="previewBtnText">
                                    {{ $bannerBtnText }} <i class="bi bi-arrow-right"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="text-center text-muted small mt-2">
                        <i class="bi bi-info-circle me-1"></i> Khung xem trước mô phỏng giao diện người dùng trên máy tính.
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>

<style>
    .cursor-pointer { cursor: pointer; }
    .banner-mode-option.active-mode {
        border-color: #0d6efd !important;
        background-color: #f0f7ff !important;
    }
    
    /* Live Preview Styles */
    .preview-banner-container {
        position: relative;
        width: 100%;
        height: 380px;
        border-radius: 2px;
        overflow: hidden;
        background: #111;
        box-shadow: 4px 4px 0px rgba(0,0,0,0.25);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .preview-media {
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
    }
    .preview-overlay {
        position: absolute;
        inset: 0;
        z-index: 1;
        pointer-events: none;
        transition: background 0.2s ease;
    }
    .preview-content {
        position: relative;
        z-index: 2;
        padding: 1.5rem;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        color: #fff;
    }
    .preview-brand {
        font-family: 'Space Grotesk', sans-serif;
        font-weight: 800;
        font-size: 1.1rem;
        color: #fff;
        border-bottom: 1px solid rgba(255,255,255,0.15);
        padding-bottom: 0.5rem;
    }
    .preview-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: rgba(255,255,255,0.12);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.25);
        border-radius: 1px;
        clip-path: polygon(0 0, calc(100% - 5px) 0, 100% 5px, 100% 100%, 5px 100%, 0 calc(100% - 5px));
        padding: 4px 10px;
        font-size: 0.65rem;
        font-weight: 700;
        font-family: 'Space Mono', monospace;
        text-transform: uppercase;
        width: fit-content;
        margin-bottom: 6px;
    }
    .preview-headline {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.25rem;
        font-weight: 800;
        line-height: 1.2;
        color: #ffffff;
        margin-bottom: 6px;
        text-shadow: 0 2px 8px rgba(0,0,0,0.5);
    }
    .preview-subtext {
        font-size: 0.75rem;
        line-height: 1.4;
        color: rgba(255,255,255,0.85);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 12px;
        max-width: 90%;
    }
    .preview-btn {
        background: #fff;
        color: #111;
        font-family: 'Space Grotesk', sans-serif;
        font-weight: 700;
        font-size: 0.78rem;
        padding: 8px 18px;
        border-radius: 2px;
        clip-path: polygon(0 0, calc(100% - 6px) 0, 100% 6px, 100% 100%, 6px 100%, 0 calc(100% - 6px));
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 2px 2px 0px rgba(0,0,0,0.3);
    }
</style>

<script>
    function onModeChange(mode) {
        document.querySelectorAll('.banner-mode-option').forEach(el => {
            el.classList.remove('active-mode', 'border-primary', 'bg-primary-subtle');
            el.classList.add('border-light-subtle');
        });
        const activeLabel = event.target.closest('.banner-mode-option');
        if (activeLabel) {
            activeLabel.classList.add('active-mode', 'border-primary', 'bg-primary-subtle');
            activeLabel.classList.remove('border-light-subtle');
        }

        const videoSection = document.getElementById('sectionVideoSettings');
        const previewVideo = document.getElementById('previewVideo');
        const previewImage = document.getElementById('previewImage');

        if (mode === 'video') {
            videoSection.style.display = 'block';
            previewVideo.style.display = 'block';
            previewImage.style.display = 'none';
        } else {
            videoSection.style.display = 'none';
            previewVideo.style.display = 'none';
            previewImage.style.display = 'block';
        }
    }

    function updateVideoPreview(src) {
        const previewVideo = document.getElementById('previewVideo');
        previewVideo.src = src;
        previewVideo.load();
        previewVideo.play().catch(e => {});
    }

    function updateOverlayPreview(val) {
        document.getElementById('overlayValueBadge').innerText = Math.round(val * 100) + '%';
        const darkVal = parseFloat(val);
        const midVal = (darkVal * 0.85).toFixed(2);
        const lowVal = (darkVal * 0.4).toFixed(2);
        document.getElementById('previewOverlay').style.background = 
            `linear-gradient(90deg, rgba(10, 12, 16, ${darkVal}) 0%, rgba(10, 12, 16, ${midVal}) 45%, rgba(10, 12, 16, ${lowVal}) 80%, rgba(10, 12, 16, 0.2) 100%)`;
    }

    function updateTextPreview(field, val) {
        if (field === 'brand') document.getElementById('previewBrandText').innerText = val || 'LapGearZone';
        if (field === 'badge') document.getElementById('previewBadgeText').innerText = val || 'Flagship Workstation 2026';
        if (field === 'headline') document.getElementById('previewHeadlineText').innerText = val || 'Đẳng Cấp Laptop';
        if (field === 'subtext') document.getElementById('previewSubtextText').innerText = val || 'Khám phá laptop mới nhất';
        if (field === 'btn_text') document.getElementById('previewBtnText').innerHTML = (val || 'Khám phá ngay') + ' <i class="bi bi-arrow-right"></i>';
    }

    // Video file preview if uploaded locally
    document.getElementById('hero_banner_video_file')?.addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const url = URL.createObjectURL(e.target.files[0]);
            updateVideoPreview(url);
        }
    });

    // Image file preview
    document.getElementById('hero_banner_image_file')?.addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const url = URL.createObjectURL(e.target.files[0]);
            document.getElementById('previewImage').src = url;
        }
    });
</script>
@endsection
