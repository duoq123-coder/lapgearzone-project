@extends('admin.layouts.app')

@section('title', 'Tạo Sự Kiện & Bộ Sưu Tập Mới')

@section('content')
<div class="container-fluid py-4 px-md-4">
    <!-- Breadcrumb & Header -->
    <div class="mb-4 pb-3 border-bottom">
        <div class="d-flex align-items-center gap-2 mb-1">
            <a href="{{ route('admin.events.index') }}" class="text-decoration-none text-muted small font-monospace">
                <i class="bi bi-arrow-left me-1"></i>DANH SÁCH SỰ KIỆN
            </a>
            <span class="text-muted small">/</span>
            <span class="text-muted small font-monospace">TẠO MỚI</span>
        </div>
        <h2 class="h3 fw-bold mb-1" style="font-family: 'Space Grotesk', sans-serif; letter-spacing: -0.02em;">
            <i class="bi bi-plus-circle me-2" style="color: var(--bellroy-orange);"></i>Tạo Sự Kiện Mới
        </h2>
        <p class="text-muted small mb-0">
            Sự kiện sẽ xuất hiện thành một tab trên thanh Bento Tabs ngoài trang Cửa Hàng kèm theo slider sản phẩm riêng biệt.
        </p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger border-0 mb-4 shadow-sm" role="alert">
            <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Vui lòng kiểm tra lại thông tin:</div>
            <ul class="mb-0 small ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" id="eventCreateForm">
        @csrf
        <div class="row g-4">
            <!-- Left Column: Event General Info & Banner Config -->
            <div class="col-lg-5">
                <div class="card shadow-sm border h-100">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="card-title mb-0 fw-bold d-flex align-items-center gap-2" style="font-size: 0.95rem;">
                            <i class="bi bi-info-circle text-muted"></i>
                            1. THÔNG TIN SỰ KIỆN & BANNER
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Tên sự kiện -->
                        <div class="mb-3">
                            <label class="form-label required" for="name">
                                Tên Sự Kiện / Tên Tab <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="{{ old('name') }}" required 
                                   placeholder="Ví dụ: Siêu Sale Mùa Thu, Banner Khuyến Mãi AI Laptop...">
                            <div class="form-text small text-muted">
                                Tên xuất hiện trên thanh Tab sự kiện ngoài Cửa Hàng.
                            </div>
                        </div>

                        <!-- Kiểu hiển thị sự kiện -->
                        <div class="mb-3 p-3 rounded-2" style="background: var(--surface-muted); border: 1px solid var(--border-color);">
                            <label class="form-label fw-bold d-flex align-items-center justify-content-between mb-2 small">
                                <span><i class="bi bi-aspect-ratio me-1 text-primary"></i>KIỂU HIỂN THỊ SỰ KIỆN</span>
                                <span class="text-muted fw-normal" style="font-size: 11px;">Chọn dạng hiển thị</span>
                            </label>
                            
                            <div class="row g-2">
                                <div class="col-4">
                                    <label class="display-type-option d-block border rounded-2 p-2 text-center bg-white cursor-pointer transition-all {{ old('display_type', 'slider') === 'slider' ? 'border-primary shadow-sm active-type' : '' }}" style="cursor: pointer;">
                                        <input type="radio" name="display_type" value="slider" class="d-none" {{ old('display_type', 'slider') === 'slider' ? 'checked' : '' }}>
                                        <i class="bi bi-collection-play fs-4 d-block mb-1 text-primary"></i>
                                        <span class="fw-bold d-block" style="font-size: 11px;">Chỉ Slider</span>
                                        <span class="text-muted" style="font-size: 10px;">Ds sản phẩm</span>
                                    </label>
                                </div>
                                <div class="col-4">
                                    <label class="display-type-option d-block border rounded-2 p-2 text-center bg-white cursor-pointer transition-all {{ old('display_type') === 'banner' ? 'border-danger shadow-sm active-type' : '' }}" style="cursor: pointer;">
                                        <input type="radio" name="display_type" value="banner" class="d-none" {{ old('display_type') === 'banner' ? 'checked' : '' }}>
                                        <i class="bi bi-image fs-4 d-block mb-1 text-danger"></i>
                                        <span class="fw-bold d-block text-danger" style="font-size: 11px;">Sự Kiện Banner</span>
                                        <span class="text-muted" style="font-size: 10px;">Banner lớn độc lập</span>
                                    </label>
                                </div>
                                <div class="col-4">
                                    <label class="display-type-option d-block border rounded-2 p-2 text-center bg-white cursor-pointer transition-all {{ old('display_type') === 'both' ? 'border-success shadow-sm active-type' : '' }}" style="cursor: pointer;">
                                        <input type="radio" name="display_type" value="both" class="d-none" {{ old('display_type') === 'both' ? 'checked' : '' }}>
                                        <i class="bi bi-layers fs-4 d-block mb-1 text-success"></i>
                                        <span class="fw-bold d-block text-success" style="font-size: 11px;">Kết Hợp Cả Hai</span>
                                        <span class="text-muted" style="font-size: 10px;">Banner + Slider</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Upload Banner Section -->
                        <div class="mb-3 p-3 rounded-2 border" id="bannerConfigCard" style="background: rgba(205, 76, 32, 0.03); border-color: rgba(205, 76, 32, 0.2) !important;">
                            <label class="form-label fw-bold small text-dark d-flex align-items-center gap-1" for="banner_image">
                                <i class="bi bi-card-image" style="color: var(--bellroy-orange);"></i> TẢI LÊN ẢNH BANNER SỰ KIỆN
                            </label>
                            <input type="file" class="form-control form-control-sm" id="banner_image" name="banner_image" accept="image/jpeg,image/png,image/webp,image/jpg,image/gif">
                            <div class="form-text small text-muted">
                                Kích thước khuyến nghị: 1920x600px (16:9 hoặc 21:9). Dung lượng tối đa: 10MB.
                            </div>

                            <!-- Live Preview -->
                            <div id="bannerPreviewBox" class="mt-2 d-none">
                                <div class="position-relative border rounded-2 overflow-hidden shadow-sm" style="max-height: 140px; background: #000;">
                                    <img id="bannerPreviewImg" src="" alt="Banner Preview" class="w-100" style="max-height: 140px; object-fit: cover;">
                                    <button type="button" id="btnRemoveBannerPreview" class="btn btn-sm btn-dark position-absolute top-0 end-0 m-1 py-0 px-1 opacity-75" title="Hủy ảnh">
                                        <i class="bi bi-x fs-5"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Banner Link (CTA URL) -->
                            <div class="mt-3">
                                <label class="form-label fw-bold small text-dark" for="banner_link">
                                    <i class="bi bi-link-45deg me-1"></i>Liên Kết Khi Bấm Banner (CTA URL)
                                </label>
                                <input type="text" class="form-control form-control-sm font-monospace" id="banner_link" name="banner_link" 
                                       value="{{ old('banner_link') }}" 
                                       placeholder="Ví dụ: /products?category=gaming hoặc https://...">
                                <div class="form-text small text-muted">
                                    Khách bấm vào banner sẽ được mở liên kết này (để trống nếu không click).
                                </div>
                            </div>
                        </div>

                        <!-- Headline tiêu đề lớn -->
                        <div class="mb-3">
                            <label class="form-label" for="headline">
                                Tiêu Đề Nổi Bật (Headline)
                            </label>
                            <input type="text" class="form-control" id="headline" name="headline" 
                                   value="{{ old('headline') }}" 
                                   placeholder="Ví dụ: Siêu Khuyến Mãi Mùa Tựu Trường 2026">
                            <div class="form-text small text-muted">
                                Dòng tiêu đề cỡ lớn phía trên banner / slider khi khách bấm chọn tab này.
                            </div>
                        </div>

                        <!-- Slug & Sort Order -->
                        <div class="row g-2 mb-3">
                            <div class="col-sm-6">
                                <label class="form-label" for="slug">
                                    Đường Dẫn (Slug)
                                </label>
                                <input type="text" class="form-control font-monospace" id="slug" name="slug" 
                                       value="{{ old('slug') }}" 
                                       placeholder="tu-dong-tao-neu-de-trong">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="sort_order">
                                    Thứ Tự Tab (Sort Order)
                                </label>
                                <input type="number" class="form-control font-monospace" id="sort_order" name="sort_order" 
                                       value="{{ old('sort_order', $nextSort) }}" min="0" step="1">
                            </div>
                        </div>

                        <!-- Trạng thái hiển thị -->
                        <div class="mb-3">
                            <div class="form-check form-switch p-0 ps-5">
                                <input class="form-check-input ms-0" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold small ms-2" for="is_active">
                                    Kích hoạt hiển thị ngay trên Cửa Hàng
                                </label>
                            </div>
                        </div>

                        <!-- Mô tả -->
                        <div class="mb-3">
                            <label class="form-label" for="description">
                                Mô Tả Ngắn (Ghi chú / Giới thiệu)
                            </label>
                            <textarea class="form-control" id="description" name="description" rows="2" 
                                      placeholder="Mô tả tóm tắt ý nghĩa sự kiện hoặc thông điệp quảng cáo...">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Interactive Product Picker -->
            <div class="col-lg-7">
                <div class="card shadow-sm border h-100">
                    <div class="card-header bg-transparent py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div>
                            <h5 class="card-title mb-0 fw-bold d-flex align-items-center gap-2" style="font-size: 0.95rem;">
                                <i class="bi bi-laptop text-muted"></i>
                                2. CHỌN SẢN PHẨM CHO SỰ KIỆN (<span id="selectedCount">0</span> ĐÃ CHỌN)
                            </h5>
                            <div class="small text-muted" id="bannerModeHint" style="font-size: 11px;">
                                Với chế độ <strong>Chỉ Banner</strong>, bạn không bắt buộc phải chọn sản phẩm.
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="btnSelectAll">
                                Chọn tất cả
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="btnDeselectAll">
                                Bỏ chọn tất cả
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <!-- Quick Search and Filter in Product Picker -->
                        <div class="row g-2 mb-3">
                            <div class="col-sm-7">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    <input type="text" id="searchProductInput" class="form-control" placeholder="Tìm theo tên máy tính...">
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <select id="filterCategorySelect" class="form-select form-select-sm">
                                    <option value="">Tất cả danh mục</option>
                                    @foreach($products->pluck('category.name')->filter()->unique() as $catName)
                                        <option value="{{ $catName }}">{{ $catName }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Product List Scrollable Container -->
                        <div class="border rounded-2 p-2 bg-light bg-opacity-25" style="max-height: 480px; overflow-y: auto;" id="productPickContainer">
                            @forelse($products as $product)
                                <label class="product-item-row d-flex align-items-center p-2 rounded-2 mb-1 border bg-white cursor-pointer" 
                                       style="cursor: pointer; transition: all 0.2s ease;"
                                       data-name="{{ mb_strtolower($product->name) }}"
                                       data-category="{{ $product->category->name ?? '' }}">
                                    <div class="form-check me-3 mb-0">
                                        <input class="form-check-input product-checkbox" type="checkbox" name="product_ids[]" value="{{ $product->id }}" 
                                               id="prod_{{ $product->id }}"
                                               {{ in_array($product->id, old('product_ids', [])) ? 'checked' : '' }}>
                                    </div>
                                    <div class="me-3 flex-shrink-0">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                                                 class="rounded border" style="width: 44px; height: 44px; object-fit: cover;">
                                        @else
                                            <div class="rounded border d-flex align-items-center justify-content-center bg-light text-muted" 
                                                 style="width: 44px; height: 44px; font-size: 18px;">
                                                <i class="bi bi-laptop"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="fw-bold text-dark text-truncate" style="font-size: 0.9rem;" title="{{ $product->name }}">
                                            {{ $product->name }}
                                        </div>
                                        <div class="d-flex align-items-center gap-2 mt-1">
                                            <span class="badge badge-terracotta" style="font-size: 10px;">
                                                {{ $product->category->name ?? 'Chưa phân loại' }}
                                            </span>
                                            <span class="fw-bold font-monospace text-dark small">
                                                {{ number_format($product->price, 0, ',', '.') }}đ
                                            </span>
                                            <span class="text-muted small font-monospace">
                                                (Kho: {{ $product->quantity }})
                                            </span>
                                        </div>
                                    </div>
                                </label>
                            @empty
                                <div class="text-center py-4 text-muted small">
                                    Không có sản phẩm nào trong kho.
                                </div>
                            @endforelse
                        </div>
                        <div class="small text-muted mt-2">
                            <i class="bi bi-info-circle me-1"></i>
                            Gợi ý: Chọn từ 3 đến 8 sản phẩm để slider hiển thị đẹp nhất ngoài trang Cửa Hàng.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
            <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Hủy & Quay Lại
            </a>
            <button type="submit" class="btn btn-premium px-4 py-2">
                <i class="bi bi-check-lg me-1"></i>Lưu & Kích Hoạt Sự Kiện
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.product-checkbox');
    const selectedCountSpan = document.getElementById('selectedCount');
    const searchInput = document.getElementById('searchProductInput');
    const categorySelect = document.getElementById('filterCategorySelect');
    const productRows = document.querySelectorAll('.product-item-row');
    const btnSelectAll = document.getElementById('btnSelectAll');
    const btnDeselectAll = document.getElementById('btnDeselectAll');

    function updateSelectedCount() {
        const checked = document.querySelectorAll('.product-checkbox:checked').length;
        if (selectedCountSpan) selectedCountSpan.textContent = checked;
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            const row = this.closest('.product-item-row');
            if (this.checked) {
                row.style.background = 'rgba(205, 76, 32, 0.06)';
                row.style.borderColor = 'var(--bellroy-orange)';
            } else {
                row.style.background = '#fff';
                row.style.borderColor = 'var(--border-color)';
            }
            updateSelectedCount();
        });
        if (cb.checked) {
            const row = cb.closest('.product-item-row');
            row.style.background = 'rgba(205, 76, 32, 0.06)';
            row.style.borderColor = 'var(--bellroy-orange)';
        }
    });

    updateSelectedCount();

    function filterProducts() {
        const query = searchInput.value.toLowerCase().trim();
        const cat = categorySelect.value.trim();

        productRows.forEach(row => {
            const name = row.getAttribute('data-name') || '';
            const category = row.getAttribute('data-category') || '';
            const matchName = !query || name.includes(query);
            const matchCat = !cat || category === cat;

            if (matchName && matchCat) {
                row.classList.remove('d-none');
            } else {
                row.classList.add('d-none');
            }
        });
    }

    searchInput?.addEventListener('input', filterProducts);
    categorySelect?.addEventListener('change', filterProducts);

    btnSelectAll?.addEventListener('click', function () {
        productRows.forEach(row => {
            if (!row.classList.contains('d-none')) {
                const cb = row.querySelector('.product-checkbox');
                if (cb && !cb.checked) {
                    cb.checked = true;
                    row.style.background = 'rgba(205, 76, 32, 0.06)';
                    row.style.borderColor = 'var(--bellroy-orange)';
                }
            }
        });
        updateSelectedCount();
    });

    btnDeselectAll?.addEventListener('click', function () {
        productRows.forEach(row => {
            const cb = row.querySelector('.product-checkbox');
            if (cb && cb.checked) {
                cb.checked = false;
                row.style.background = '#fff';
                row.style.borderColor = 'var(--border-color)';
            }
        });
        updateSelectedCount();
    });

    // Display type options toggle
    const displayTypeOptions = document.querySelectorAll('.display-type-option');
    const bannerConfigCard = document.getElementById('bannerConfigCard');
    const bannerModeHint = document.getElementById('bannerModeHint');

    function refreshDisplayType(val) {
        displayTypeOptions.forEach(opt => {
            const radio = opt.querySelector('input[type="radio"]');
            if (radio && radio.value === val) {
                radio.checked = true;
                opt.classList.add('active-type');
                if (val === 'slider') opt.classList.add('border-primary');
                if (val === 'banner') opt.classList.add('border-danger');
                if (val === 'both') opt.classList.add('border-success');
            } else {
                opt.classList.remove('active-type', 'border-primary', 'border-danger', 'border-success');
            }
        });

        if (bannerModeHint) {
            if (val === 'banner') {
                bannerModeHint.innerHTML = '<span class="badge bg-warning text-dark me-1">Chế độ Chỉ Banner</span>Không bắt buộc chọn sản phẩm. Sự kiện sẽ hiển thị tấm banner lớn độc lập.';
            } else if (val === 'both') {
                bannerModeHint.innerHTML = '<span class="badge bg-success text-white me-1">Chế độ Kết Hợp</span>Hiển thị Banner phía trên + Slider các sản phẩm đã chọn phía dưới.';
            } else {
                bannerModeHint.innerHTML = 'Hiển thị các sản phẩm đã chọn dưới dạng slider trình chiếu ngang.';
            }
        }
    }

    displayTypeOptions.forEach(opt => {
        opt.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            if (radio) {
                refreshDisplayType(radio.value);
            }
        });
    });

    const checkedType = document.querySelector('input[name="display_type"]:checked')?.value || 'slider';
    refreshDisplayType(checkedType);

    // Live preview for Banner image
    const bannerInput = document.getElementById('banner_image');
    const bannerPreviewBox = document.getElementById('bannerPreviewBox');
    const bannerPreviewImg = document.getElementById('bannerPreviewImg');
    const btnRemoveBannerPreview = document.getElementById('btnRemoveBannerPreview');

    bannerInput?.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                bannerPreviewImg.src = e.target.result;
                bannerPreviewBox.classList.remove('d-none');
            };
            reader.readAsDataURL(file);

            // Tự động chuyển kiểu hiển thị sang Banner hoặc Both nếu đang là slider
            const currentType = document.querySelector('input[name="display_type"]:checked')?.value;
            if (currentType === 'slider') {
                refreshDisplayType('both');
            }
        }
    });

    btnRemoveBannerPreview?.addEventListener('click', function() {
        if (bannerInput) bannerInput.value = '';
        if (bannerPreviewImg) bannerPreviewImg.src = '';
        bannerPreviewBox.classList.add('d-none');
    });
});
</script>
@endpush
@endsection
