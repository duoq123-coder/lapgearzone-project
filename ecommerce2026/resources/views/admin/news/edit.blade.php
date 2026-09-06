@extends('admin.layouts.app')
@section('title', 'Chỉnh sửa bài viết Tin tức - Admin')

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
        <i class="bi bi-pencil-square me-2" style="color: var(--bellroy-orange);"></i>Chỉnh sửa bài viết
    </h2>
    <p class="text-secondary small mb-0">Cập nhật nội dung, hình ảnh hoặc trạng thái bài viết: <strong>{{ $news->title }}</strong></p>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5">
    <form action="{{ route('admin.news.update', $news->id) }}" method="POST" enctype="multipart/form-data" id="newsForm">
        @csrf
        @method('PUT')

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
                           value="{{ old('title', $news->title) }}" 
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
                               value="{{ old('category', $news->category) }}" 
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
                               value="{{ old('author_name', $news->author_name) }}">
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
                               value="{{ old('read_time', $news->read_time) }}">
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
                              placeholder="Nhập đoạn tóm tắt ngắn..." 
                              required>{{ old('summary', $news->summary) }}</textarea>
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
                              rows="14" 
                              placeholder="Hỗ trợ thẻ HTML..." 
                              required>{{ old('content', $news->content) }}</textarea>
                    <div class="form-text">Hỗ trợ các thẻ HTML định dạng văn bản chuẩn.</div>
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
                        <img id="previewImg" src="{{ $news->image_url }}" alt="Preview">
                    </div>

                    <input type="file" 
                           class="form-control d-none @error('image') is-invalid @enderror" 
                           id="news_image_input" 
                           name="image" 
                           accept="image/*">

                    <input type="hidden" id="news_image_base64" name="image_base64">
                    
                    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                        <button type="button" class="btn btn-sm btn-outline-dark rounded-pill px-3" onclick="document.getElementById('news_image_input').click();">
                            <i class="bi bi-folder2-open me-1"></i> Chọn ảnh mới &amp; Cắt
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="cropCurrentImage();">
                            <i class="bi bi-crop me-1"></i> Căn chỉnh lại (16:9)
                        </button>
                    </div>
                    <div class="form-text small mt-1 text-center">Khung cắt tỉ lệ vàng 16:9 chuẩn hiển thị trên mọi thiết bị.</div>

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
                        <input class="form-check-input" type="checkbox" role="switch" id="is_published" name="is_published" value="1" {{ old('is_published', $news->is_published ? '1' : '0') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold text-dark" for="is_published">
                            Trạng thái hiển thị (Xuất bản)
                        </label>
                        <div class="form-text small">Nếu tắt, bài viết sẽ lưu dạng nháp và ẩn ngoài trang chủ.</div>
                    </div>

                    <div class="mb-3 small text-secondary">
                        <div><i class="bi bi-eye me-1"></i>Lượt xem: <strong>{{ number_format($news->views_count) }}</strong></div>
                        <div><i class="bi bi-calendar3 me-1"></i>Ngày đăng: {{ $news->formatted_date }}</div>
                        <div><i class="bi bi-link-45deg me-1"></i>Slug: <code>{{ $news->slug }}</code></div>
                    </div>

                    <hr class="my-3">

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-premium py-2">
                            <i class="bi bi-check2-circle me-1"></i> Lưu thay đổi
                        </button>
                        <a href="{{ route('news.show', $news->slug) }}" target="_blank" class="btn btn-outline-dark py-2">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Xem trước bài viết
                        </a>
                        <a href="{{ route('admin.news.index') }}" class="btn btn-link text-muted text-decoration-none py-1">
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
    });

    function cropCurrentImage() {
        const previewEl = document.getElementById('previewImg');
        if (!previewEl || !previewEl.src) return;

        const imageToCrop = document.getElementById('imageToCrop');
        imageToCrop.src = previewEl.src;

        currentInputFile = document.getElementById('news_image_input');
        currentHiddenInput = document.getElementById('news_image_base64');
        currentAspectRatio = 16 / 9;
        currentPreviewId = 'previewImg';

        let cropModal = bootstrap.Modal.getInstance(document.getElementById('globalCropModal'));
        if (!cropModal) {
            cropModal = new bootstrap.Modal(document.getElementById('globalCropModal'));
        }
        cropModal.show();
    }
</script>
@endpush
@endsection
