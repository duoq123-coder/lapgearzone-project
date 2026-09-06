@extends('admin.layouts.app')
@section('title', 'Thêm bài viết Tin tức mới - Admin')

@push('styles')
<style>
    .image-preview-container {
        width: 100%;
        max-width: 460px;
        aspect-ratio: 16 / 9;
        border: 2px dashed #cbd5e1;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
        background: #f8fafc;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .image-preview-container:hover {
        border-color: var(--bellroy-orange);
        background: #fff8f5;
    }
    .image-preview-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    [data-bs-theme="dark"] .image-preview-container {
        background: #1e1e24;
        border-color: #3f3f46;
    }
</style>
@endpush

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.news.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-2">
        <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
    </a>
    <h2 class="mb-0 fw-bold text-dark display-font">
        <i class="bi bi-pencil-square me-2" style="color: var(--bellroy-orange);"></i>Thêm bài viết Tin tức mới
    </h2>
    <p class="text-secondary small mb-0">Tạo bài viết xu hướng công nghệ để hiển thị tại trang chủ và chuyên mục tin tức</p>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5">
    <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" id="newsForm">
        @csrf

        <div class="row g-4">
            <!-- Cột trái: Thông tin bài viết -->
            <div class="col-lg-8">
                <!-- Tiêu đề bài viết -->
                <div class="mb-3">
                    <label for="title" class="form-label fw-bold text-dark">
                        Tiêu đề bài viết <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control form-control-lg @error('title') is-invalid @enderror" 
                           id="title" 
                           name="title" 
                           placeholder="VD: Kỷ Nguyên Laptop AI 2026: NPU Thế Hệ Mới..." 
                           value="{{ old('title') }}" 
                           required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Thể loại & Tác giả & Thời lượng đọc -->
                <div class="row g-3 mb-3">
                    <div class="col-md-5">
                        <label for="category" class="form-label fw-bold text-dark">
                            Thể loại / Nhãn <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('category') is-invalid @enderror" 
                               id="category" 
                               name="category" 
                               list="categoryList" 
                               placeholder="VD: Kỷ Nguyên AI" 
                               value="{{ old('category') }}" 
                               required>
                        <datalist id="categoryList">
                            <option value="Kỷ Nguyên AI">
                            <option value="Màn Hình & Đồ Họa">
                            <option value="Cẩm Nang Tối Ưu">
                            <option value="Xu Hướng Công Nghệ">
                            <option value="Gaming & Phần Cứng">
                            <option value="Thủ Thuật Công Nghệ">
                        </datalist>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="author_name" class="form-label fw-bold text-dark">Tác giả</label>
                        <input type="text" 
                               class="form-control @error('author_name') is-invalid @enderror" 
                               id="author_name" 
                               name="author_name" 
                               placeholder="VD: Ban Biên Tập LapGearZone" 
                               value="{{ old('author_name', Auth::user()->name ?? 'Ban Biên Tập LapGearZone') }}">
                        @error('author_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="read_time" class="form-label fw-bold text-dark">Thời gian đọc</label>
                        <input type="text" 
                               class="form-control @error('read_time') is-invalid @enderror" 
                               id="read_time" 
                               name="read_time" 
                               placeholder="VD: 5 phút đọc" 
                               value="{{ old('read_time', '5 phút đọc') }}">
                        @error('read_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Tóm tắt ngắn (Excerpt) -->
                <div class="mb-3">
                    <label for="summary" class="form-label fw-bold text-dark">
                        Đoạn tóm tắt hiển thị ngoài thẻ (Excerpt) <span class="text-danger">*</span>
                    </label>
                    <textarea class="form-control @error('summary') is-invalid @enderror" 
                              id="summary" 
                              name="summary" 
                              rows="3" 
                              placeholder="Nhập 1 - 2 câu tóm tắt nổi bật nhất của bài viết để kích thích người đọc bấm vào xem..." 
                              required>{{ old('summary') }}</textarea>
                    <div class="form-text">Đoạn trích này sẽ hiển thị trực tiếp trên thẻ tin tức ngoài trang chủ.</div>
                    @error('summary')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Nội dung chi tiết (Content) -->
                <div class="mb-3">
                    <label for="content" class="form-label fw-bold text-dark">
                        Nội dung chi tiết bài viết <span class="text-danger">*</span>
                    </label>
                    <textarea class="form-control font-monospace @error('content') is-invalid @enderror" 
                              id="content" 
                              name="content" 
                              rows="12" 
                              placeholder="Hỗ trợ thẻ định dạng HTML như <p>, <h4>, <ul>, <li>, <blockquote>, <strong>..." 
                              required>{{ old('content') }}</textarea>
                    <div class="form-text">Có thể chèn các thẻ HTML cơ bản hoặc đoạn văn thông thường để trình bày bài viết chuẩn đẹp.</div>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Cột phải: Ảnh đại diện & Tùy chọn -->
            <div class="col-lg-4">
                <div class="card bg-light border-0 rounded-4 p-4 mb-4">
                    <h5 class="fw-bold text-dark mb-3">
                        <i class="bi bi-image me-1" style="color: var(--bellroy-orange);"></i> Ảnh bìa bài viết
                    </h5>
                    
                    <div class="image-preview-container mb-3" onclick="document.getElementById('news_image_input').click();">
                        <div id="previewPlaceholder" class="text-center p-3">
                            <i class="bi bi-crop text-secondary" style="font-size: 2.5rem;"></i>
                            <p class="text-muted small mt-2 mb-0">Bấm vào đây để chọn &amp; cắt ảnh bìa (16:9)</p>
                            <span class="badge bg-secondary text-white mt-1">Chuẩn 16:9 (1280x720)</span>
                        </div>
                        <img id="previewImg" src="#" alt="Preview" class="d-none">
                    </div>

                    <input type="file" 
                           class="form-control d-none @error('image') is-invalid @enderror" 
                           id="news_image_input" 
                           name="image" 
                           accept="image/*">

                    <input type="hidden" id="news_image_base64" name="image_base64">
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-sm btn-outline-dark rounded-pill px-3" onclick="document.getElementById('news_image_input').click();">
                            <i class="bi bi-crop me-1"></i> Chọn ảnh &amp; Cắt (16:9)
                        </button>
                        <button type="button" class="btn btn-sm btn-link text-danger text-decoration-none d-none" id="removeImgBtn" onclick="clearNewsImage();">
                            <i class="bi bi-trash"></i> Bỏ chọn
                        </button>
                    </div>

                    @error('image')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Cài đặt xuất bản -->
                <div class="card bg-light border-0 rounded-4 p-4">
                    <h5 class="fw-bold text-dark mb-3">
                        <i class="bi bi-gear-fill me-1" style="color: var(--bellroy-orange);"></i> Cài đặt xuất bản
                    </h5>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_published" name="is_published" value="1" {{ old('is_published', '1') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold text-dark" for="is_published">
                            Xuất bản ngay lập tức
                        </label>
                        <div class="form-text small">Nếu tắt, bài viết sẽ lưu dưới dạng bản nháp và chưa xuất hiện ngoài trang chủ.</div>
                    </div>

                    <hr class="my-3">

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-premium py-2">
                            <i class="bi bi-check2-circle me-1"></i> Lưu và Đăng bài viết
                        </button>
                        <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary py-2">
                            Hủy bỏ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if(typeof initImageCropper === 'function') {
            initImageCropper('news_image_input', 'news_image_base64', 16 / 9, 'previewImg');
        }

        const btnApply = document.getElementById('btnCropApply');
        if (btnApply) {
            btnApply.addEventListener('click', function() {
                const previewImg = document.getElementById('previewImg');
                const placeholder = document.getElementById('previewPlaceholder');
                const removeBtn = document.getElementById('removeImgBtn');
                if (previewImg) previewImg.classList.remove('d-none');
                if (placeholder) placeholder.classList.add('d-none');
                if (removeBtn) removeBtn.classList.remove('d-none');
            });
        }
    });

    function clearNewsImage() {
        const input = document.getElementById('news_image_input');
        const hiddenInput = document.getElementById('news_image_base64');
        const previewImg = document.getElementById('previewImg');
        const placeholder = document.getElementById('previewPlaceholder');
        const removeBtn = document.getElementById('removeImgBtn');

        if (input) input.value = '';
        if (hiddenInput) hiddenInput.value = '';
        if (previewImg) {
            previewImg.src = '#';
            previewImg.classList.add('d-none');
        }
        if (placeholder) placeholder.classList.remove('d-none');
        if (removeBtn) removeBtn.classList.add('d-none');
    }
</script>
@endpush
@endsection
