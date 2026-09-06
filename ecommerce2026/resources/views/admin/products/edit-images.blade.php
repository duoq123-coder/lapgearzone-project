@extends('admin.layouts.app')
@section('title', 'Quản lý ảnh - ' . $product->name)
@section('content')
<div class="container-fluid p-0 animate-slide-up">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="serif-title mb-1 text-dark" style="font-size: 1.6rem;">
                        <i class="bi bi-images me-2" style="color: var(--bellroy-orange);"></i>Quản lý ảnh chi tiết
                    </h2>
                    <p class="text-muted mb-0 small">Sản phẩm: <strong class="text-dark">{{ $product->name }}</strong></p>
                </div>
                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-outline-premium">
                    <i class="bi bi-pencil me-1"></i>Sửa thông tin sản phẩm
                </a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3 p-3 mb-4" style="background-color: var(--bellroy-orange-subtle); color: var(--bellroy-orange);" role="alert">
            @foreach ($errors->all() as $error)
                <div><i class="bi bi-exclamation-circle-fill me-2"></i>{{ $error }}</div>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif



    <!-- Upload Section -->
    <div class="card p-4 mb-4">
        <div class="card-header bg-transparent border-bottom p-0 pb-3 mb-3" style="border-color: var(--border-color) !important;">
            <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-cloud-upload me-2" style="color: var(--bellroy-orange);"></i>Tải lên ảnh chi tiết</h5>
        </div>
        <div class="card-body p-0">
            <form action="{{ route('admin.products.images.store', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label for="gallery_image_input" class="form-label fw-bold text-dark small">Chọn tệp ảnh (Tỉ lệ vuông 1:1)</label>
                    <div class="input-group">
                        <input type="file" 
                               id="gallery_image_input" 
                               class="form-control @error('image_base64') is-invalid @enderror" 
                               accept="image/*" 
                               required>
                        <input type="hidden" name="image_base64" id="gallery_image_base64">
                        <button type="submit" class="btn btn-premium px-4">
                            <i class="bi bi-upload me-1"></i>Tải lên
                        </button>
                    </div>
                    <small class="text-muted d-block mt-2">
                        Định dạng hỗ trợ: JPG, PNG, GIF, WebP. Tối đa 5MB/ảnh.
                    </small>
                    @error('image_base64')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </form>
        </div>
    </div>

    <!-- Images List -->
    <div class="card p-4">
        <div class="card-header bg-transparent border-bottom p-0 pb-3 mb-4" style="border-color: var(--border-color) !important;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-card-image me-2" style="color: var(--bellroy-orange);"></i>Danh sách ảnh chi tiết ({{ $images->count() }})</h5>
                @if ($images->isNotEmpty())
                    <span class="badge badge-terracotta">Kéo thả để sắp xếp vị trí</span>
                @endif
            </div>
        </div>
        
        @if ($images->isEmpty())
            <div class="text-center py-5 text-muted">
                <div class="mb-3">
                    <i class="bi bi-image" style="font-size: 3rem; opacity: 0.4;"></i>
                </div>
                <p class="mb-0">Chưa có ảnh chi tiết nào. Hãy tải lên ảnh đầu tiên!</p>
            </div>
        @else
            <div class="row g-3" id="imagesContainer">
                @foreach ($images as $image)
                    <div class="col-md-4 col-lg-3 image-item" data-image-id="{{ $image->id }}">
                        <div class="card h-100 border p-2" style="position: relative; background: #ffffff;">
                            <!-- Image -->
                            <div class="position-relative d-flex align-items-center justify-content-center rounded-3 overflow-hidden p-2" style="height: 180px; background: #f8f6f2;">
                                <img src="{{ asset('storage/'.$image->image_path) }}" 
                                     alt="Product image" 
                                     class="img-fluid" 
                                     style="max-height: 160px; object-fit: contain;">
                                
                                <!-- Primary Badge -->
                                @if ($image->is_primary)
                                    <span class="badge badge-sage position-absolute top-2 start-2">
                                        <i class="bi bi-star-fill me-1"></i>Ảnh chính
                                    </span>
                                @endif
                            </div>

                            <!-- Controls -->
                            <div class="d-flex justify-content-between align-items-center mt-2 px-1">
                                <div>
                                    @if (!$image->is_primary)
                                        <form action="{{ route('admin.product-images.primary', $image->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-premium py-1 px-2" title="Đặt làm ảnh chính">
                                                <i class="bi bi-star me-1"></i>Đặt chính
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge badge-sage">Ảnh đại diện</span>
                                    @endif
                                </div>

                                <form action="{{ route('admin.product-images.destroy', $image->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0 text-decoration-none" title="Xóa ảnh"
                                            onclick="return confirm('Bạn chắc chắn muốn xóa ảnh này?')">
                                        <i class="bi bi-trash fs-6"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Save Order Button -->
            <div class="mt-4 text-end">
                <button type="button" class="btn btn-premium" id="saveOrderBtn" style="display: none;">
                    <i class="bi bi-check-circle me-1"></i>Lưu thứ tự ảnh
                </button>
            </div>
        @endif
    </div>

    <!-- Back Button -->
    <div class="text-center mt-4">
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-premium">
            <i class="bi bi-arrow-left me-2"></i>Quay lại danh sách sản phẩm
        </a>
    </div>
</div>

<!-- Sortable JS for Drag & Drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        if(typeof initImageCropper === 'function') {
            initImageCropper('gallery_image_input', 'gallery_image_base64', 1);
        }

        const container = document.getElementById('imagesContainer');
        const saveOrderBtn = document.getElementById('saveOrderBtn');

        if (container) {
            Sortable.create(container, {
                animation: 150,
                ghostClass: 'opacity-50',
                onEnd: function() {
                    if(saveOrderBtn) saveOrderBtn.style.display = 'inline-block';
                }
            });
        }

        if(saveOrderBtn) {
            saveOrderBtn.addEventListener('click', async function() {
                const order = [];
                container.querySelectorAll('.image-item').forEach(function(el, index) {
                    order.push({
                        id: el.dataset.imageId,
                        position: index
                    });
                });

                try {
                    const res = await fetch("{{ route('admin.products.images.reorder', $product->id) }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ order: order })
                    });
                    const data = await res.json();
                    if(res.ok) {
                        alert('Đã cập nhật thứ tự ảnh thành công!');
                        saveOrderBtn.style.display = 'none';
                    } else {
                        alert('Có lỗi: ' + (data.message || 'Lỗi lưu thứ tự'));
                    }
                } catch (e) {
                    alert('Lỗi kết nối máy chủ');
                }
            });
        }
    });
</script>
@endsection
