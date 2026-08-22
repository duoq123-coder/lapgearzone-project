@extends('layouts.app')
@section('title', 'Quản lý ảnh - ' . $product->name)
@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h2 class="fw-bold mb-1">
                        <i class="bi bi-image me-2 text-primary"></i>Quản lý ảnh chi tiết
                    </h2>
                    <p class="text-muted mb-0">Sản phẩm: <strong>{{ $product->name }}</strong></p>
                </div>
                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-secondary">
                    <i class="bi bi-pencil me-2"></i>Chỉnh sửa sản phẩm
                </a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            @foreach ($errors->all() as $error)
                <div><i class="bi bi-exclamation-circle-fill me-2"></i>{{ $error }}</div>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Upload Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-light border-bottom">
            <h5 class="mb-0 fw-bold"><i class="bi bi-cloud-upload me-2"></i>Tải lên ảnh chi tiết</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.products.images.store', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label for="gallery_image_input" class="form-label fw-bold">Chọn ảnh (Sẽ được căn chỉnh 1:1)</label>
                    <div class="input-group">
                        <input type="file" 
                               id="gallery_image_input" 
                               class="form-control @error('image_base64') is-invalid @enderror" 
                               accept="image/*" 
                               required>
                        <input type="hidden" name="image_base64" id="gallery_image_base64">
                        <button type="submit" class="btn btn-primary fw-semibold">
                            <i class="bi bi-upload me-1"></i>Tải lên
                        </button>
                    </div>
                    <small class="text-muted d-block mt-2">
                        Định dạng hỗ trợ: JPG, PNG, GIF, WebP. Tối đa 2MB/ảnh.
                    </small>
                    @error('image_base64')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </form>
        </div>
    </div>

    <!-- Images List -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-image-alt me-2"></i>Ảnh chi tiết ({{ $images->count() }})</h5>
                @if ($images->isNotEmpty())
                    <small class="text-muted">Kéo để sắp xếp lại thứ tự</small>
                @endif
            </div>
        </div>
        
        @if ($images->isEmpty())
            <div class="card-body text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                </div>
                <p class="text-muted mb-0">Chưa có ảnh chi tiết. Hãy tải lên ảnh đầu tiên!</p>
            </div>
        @else
            <div class="card-body p-4">
                <div class="row g-3" id="imagesContainer">
                    @foreach ($images as $image)
                        <div class="col-md-4 col-lg-3 image-item" data-image-id="{{ $image->id }}">
                            <div class="card h-100 border shadow-sm" style="position: relative;">
                                <!-- Image -->
                                <div class="position-relative bg-light" style="height: 200px; overflow: hidden;">
                                    <img src="{{ asset('storage/'.$image->image_path) }}" 
                                         alt="Product image" 
                                         class="w-100 h-100" 
                                         style="object-fit: cover;">
                                    
                                    <!-- Primary Badge -->
                                    @if ($image->is_primary)
                                        <span class="badge bg-success position-absolute top-2 start-2">
                                            <i class="bi bi-star-fill me-1"></i>Chính
                                        </span>
                                    @endif

                                    <!-- Overlay Controls -->
                                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-white shadow-sm bg-opacity-0 d-flex align-items-center justify-content-center gap-2 transition-all" 
                                         style="opacity: 0; transition: opacity 0.3s; border-radius: 0.375rem;"
                                         onmouseover="this.style.opacity='0.7'"
                                         onmouseout="this.style.opacity='0'">
                                        
                                        @if (!$image->is_primary)
                                            <form action="{{ route('admin.product-images.primary', $image->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-warning" title="Đặt làm ảnh chính">
                                                    <i class="bi bi-star-fill"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('admin.product-images.destroy', $image->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Xóa ảnh"
                                                    onclick="return confirm('Bạn chắc chắn muốn xóa ảnh này?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Info -->
                                <div class="card-body p-2 flex-grow-1 d-flex flex-column">
                                    <small class="text-muted d-block mb-2">
                                        <i class="bi bi-hash me-1"></i>ID: {{ $image->id }}
                                    </small>
                                    <small class="text-muted">
                                        <i class="bi bi-sort-up me-1"></i>Thứ tự: <span class="order-value">{{ $image->order }}</span>
                                    </small>
                                </div>

                                <!-- Drag Handle -->
                                <div class="position-absolute top-50 end-2 translate-middle-y cursor-move" style="cursor: grab;">
                                    <i class="bi bi-grip-vertical text-muted" style="font-size: 1.2rem;"></i>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Save Order Button -->
                <div class="mt-4 text-end">
                    <button type="button" class="btn btn-primary" id="saveOrderBtn" style="display: none;">
                        <i class="bi bi-check-circle me-1"></i>Lưu thứ tự ảnh
                    </button>
                </div>
            </div>
        @endif
    </div>

    <!-- Back Button -->
    <div class="text-center mt-5">
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Quay lại danh sách sản phẩm
        </a>
    </div>
</div>

<!-- Sortable JS for Drag & Drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
    // Enable drag and drop sorting
    const container = document.getElementById('imagesContainer');
    const saveOrderBtn = document.getElementById('saveOrderBtn');

    if (container) {
        Sortable.create(container, {
            animation: 150,
            ghostClass: 'bg-light opacity-50',
            dragClass: 'shadow-lg',
            onEnd: function() {
                saveOrderBtn.style.display = 'block';
                updateOrderDisplay();
            }
        });
    }

    function updateOrderDisplay() {
        const items = document.querySelectorAll('.image-item');
        items.forEach((item, index) => {
            const orderValue = item.querySelector('.order-value');
            if (orderValue) {
                orderValue.textContent = index;
            }
        });
            ghostClass: 'bg-light',
            onEnd: function (evt) {
                var order = [];
                container.querySelectorAll('.image-item').forEach(function(el, index) {
                    order.push({
                        id: el.dataset.imageId,
                        position: index
                    });
                });

                // Gửi request cập nhật thứ tự
                fetch("{{ route('admin.products.images.reorder', $product->id) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                } else {
                    alert('Có lỗi: ' + data.message);
                }
            } catch (error) {
                alert('Có lỗi xảy ra: ' + error.message);
            }
        });
    }
</script>

<style>
    .cursor-move {
        cursor: grab;
    }
    
    .cursor-move:active {
        cursor: grabbing;
    }

    .image-item img:hover {
        transform: scale(1.05);
        transition: transform 0.3s ease;
    }

    .transition-all {
        transition: all 0.3s ease;
    }
</style>
@endsection
