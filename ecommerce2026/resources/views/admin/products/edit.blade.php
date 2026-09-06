@extends('admin.layouts.app')
@section('title', 'Cập nhật Sản phẩm - Admin')

@push('styles')
<style>
    .btn-tag-inactive {
        background-color: #ffffff;
        color: #4b5563;
        border: 1px solid #d1d5db;
        transition: all 0.15s ease;
        font-weight: 500;
        font-size: 0.82rem;
        padding: 5px 12px;
        border-radius: 6px;
    }
    .btn-tag-inactive:hover {
        background-color: #f3f4f6;
        border-color: #9ca3af;
        color: #111827;
    }
    .btn-tag-active {
        background-color: #1a1a1e !important;
        color: #ff9e75 !important;
        border: 1px solid #CD4C20 !important;
        box-shadow: 0 2px 6px rgba(205, 76, 32, 0.25);
        font-weight: 600;
        font-size: 0.82rem;
        padding: 5px 12px;
        border-radius: 6px;
    }
    [data-bs-theme="dark"] .btn-tag-inactive {
        background-color: #232328;
        color: #9ca3af;
        border-color: #3f3f46;
    }
    [data-bs-theme="dark"] .btn-tag-inactive:hover {
        background-color: #2e2e34;
        color: #f3f4f6;
    }
    [data-bs-theme="dark"] .btn-tag-active {
        background-color: #26211f !important;
        color: #ff7d50 !important;
        border: 1px solid #ff7d50 !important;
    }
</style>
@endpush

@section('content')
<div class="card p-4">
    <div class="card-header bg-transparent border-bottom p-0 pb-3 mb-4" style="border-color: var(--border-color) !important;">
        <h2 class="serif-title mb-0 text-dark" style="font-size: 1.6rem;"><i class="bi bi-pencil-square me-2" style="color: var(--bellroy-orange);"></i>Cập nhật Sản phẩm</h2>
    </div>
    <div class="card-body p-0">
        @if ($errors->any())
            <div class="alert alert-danger mb-4 rounded-3 p-3" style="background-color: var(--bellroy-orange-subtle); color: var(--bellroy-orange);">
                <strong class="d-block mb-1">Đã có lỗi xảy ra, vui lòng kiểm tra lại dữ liệu:</strong>
                <ul class="mb-0 ps-3 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Ảnh sản phẩm hiện tại -->
            @if($product->image)
                <div class="mb-3 text-center p-3 rounded-3 border" style="background-color: #f8f6f2; border-color: var(--border-color) !important;">
                    <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded mb-2" style="max-height: 180px; object-fit: contain;">
                    <div class="d-flex justify-content-center">
                        <button type="button" id="delete-image-btn" class="btn btn-outline-danger btn-sm" data-url="{{ route('admin.products.image.destroy', $product) }}">
                            <i class="bi bi-trash me-1"></i>Xóa ảnh đại diện
                        </button>
                    </div>
                </div>
            @endif

            <div class="mb-3">
                <label for="product_image_input" class="form-label fw-bold text-dark small">Thay đổi ảnh đại diện (Tỉ lệ 1:1)</label>
                <input type="file" id="product_image_input" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                <input type="hidden" name="image_base64" id="product_image_base64">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Cập nhật file 3D -->
            <div class="mb-3">
                <label for="model_3d" class="form-label fw-bold text-dark small">Cập nhật mô hình 3D (.glb)</label>
                @if($product->model_3d)
                    <div class="mb-2 text-success small fw-bold">
                        <i class="bi bi-check-circle-fill me-1"></i> Sản phẩm này đã có file 3D.
                    </div>
                @endif
                <input type="file" 
                       id="model_3d" 
                       name="model_3d" 
                       class="form-control @error('model_3d') is-invalid @enderror" 
                       accept=".glb">
                <small class="text-muted">Bỏ trống nếu không muốn thay đổi. Chỉ hỗ trợ .glb</small>
                @error('model_3d')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Tên sản phẩm -->
            <div class="mb-3">
                <label for="name" class="form-label fw-bold text-dark small">Tên sản phẩm <span class="text-danger">*</span></label>
                <input type="text"
                       id="name"
                       name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $product->name) }}"
                       required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Danh mục -->
            <div class="mb-3">
                <label for="category_id" class="form-label fw-bold text-dark small">Danh mục <span class="text-danger">*</span></label>
                <select id="category_id"
                        name="category_id"
                        class="form-select @error('category_id') is-invalid @enderror"
                        required>
                    <option value="">-- Chọn danh mục --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Mô tả -->
            <div class="mb-3">
                <label for="description" class="form-label fw-bold text-dark small">Mô tả sản phẩm</label>
                <textarea id="description"
                          name="description"
                          class="form-control @error('description') is-invalid @enderror"
                          rows="4">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Giá tiền -->
            <div class="mb-4">
                <label for="price" class="form-label fw-bold text-dark small">Giá bán niêm yết (VNĐ) <span class="text-danger">*</span></label>
                <input type="number"
                       step="1"
                       id="price"
                       name="price"
                       class="form-control @error('price') is-invalid @enderror"
                       value="{{ old('price', $product->price) }}"
                       required
                       min="0">
                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Tags Thông số & Cấu hình -->
            <div class="mb-4 p-3 rounded-3 border" style="background-color: var(--surface-muted, #f8f9fa); border-color: var(--border-color) !important;">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                    <label class="form-label fw-bold text-dark small mb-0">
                        <i class="bi bi-tags-fill me-1" style="color: var(--bellroy-orange);"></i> Tags Thông số & Cấu hình (GPU, CPU, Màn hình...)
                    </label>
                    <a href="{{ route('admin.tags.index') }}" target="_blank" class="small text-decoration-none fw-bold" style="color: var(--bellroy-orange);">
                        <i class="bi bi-gear-fill me-1"></i>Quản lý toàn bộ Tags <i class="bi bi-box-arrow-up-right ms-1"></i>
                    </a>
                </div>
                <div class="form-text text-muted small mb-3">
                    Chọn các thông số cấu hình áp dụng cho laptop này (VD: <code>RTX 3060</code>, <code>RTX 3070</code>, <code>Core i7</code>, <code>OLED</code>...). Người dùng có thể click lọc theo tag ngay tại trang chủ.
                </div>

                <!-- Danh sách tags hiện có -->
                <div id="tag-cloud" class="d-flex flex-wrap gap-2 mb-3">
                    @php
                        $selectedTags = old('tags', $product->tags->pluck('id')->toArray());
                    @endphp
                    @forelse($tags as $t)
                        @php $isSelected = in_array($t->id, $selectedTags); @endphp
                        <label class="tag-chip-label btn btn-sm {{ $isSelected ? 'btn-tag-active' : 'btn-tag-inactive' }}" 
                               data-tag-id="{{ $t->id }}" 
                               style="cursor: pointer; user-select: none;">
                            <input type="checkbox" name="tags[]" value="{{ $t->id }}" class="d-none tag-checkbox" {{ $isSelected ? 'checked' : '' }}>
                            <i class="bi {{ $isSelected ? 'bi-check-circle-fill text-warning' : 'bi-plus-circle' }} tag-icon me-1"></i>
                            <span class="tag-name">{{ $t->name }}</span>
                        </label>
                    @empty
                        <span class="text-muted small py-1" id="no-tags-msg">Chưa có tag nào. Hãy nhập tên tag mới ở ô bên dưới để tạo nhanh!</span>
                    @endforelse
                </div>

                <!-- Ô tạo nhanh Tag -->
                <div class="d-flex align-items-center gap-2" style="max-width: 480px;">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="bi bi-plus-lg"></i></span>
                        <input type="text" id="quick-tag-name" class="form-control" placeholder="Nhập tên tag mới (VD: RTX 3070, i7 13700H)...">
                        <button type="button" id="btn-quick-add-tag" class="btn btn-dark fw-bold px-3">
                            <span class="spinner-border spinner-border-sm d-none me-1" id="quick-tag-spinner"></span>
                            + Thêm Tag Nhanh
                        </button>
                    </div>
                </div>
                <div id="quick-tag-feedback" class="small mt-1 d-none"></div>
            </div>

            <!-- Thao tác -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-premium px-4">Cập nhật thay đổi</button>
                <a href="{{ route('admin.products.images.edit', $product->id) }}" class="btn btn-dark">
                    <i class="bi bi-images me-1"></i>Quản lý ảnh chi tiết
                </a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-premium">Quay lại</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    if(typeof initImageCropper === 'function') {
        initImageCropper('product_image_input', 'product_image_base64', 1);
    }

    var btn = document.getElementById('delete-image-btn');
    if (btn) {
        btn.addEventListener('click', function () {
            if (!confirm('Bạn có chắc chắn muốn xóa ảnh này?')) return;
            var url = this.dataset.url;
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(function (res) {
                if (!res.ok) throw new Error('Network response was not ok');
                return res.json().catch(function(){ return {}; });
            }).then(function () {
                window.location.reload();
            }).catch(function (err) {
                alert('Không thể xóa ảnh: ' + err.message);
            });
        });
    }
});

$(document).ready(function() {
    $('#category_id').select2({
        placeholder: "-- Chọn danh mục --",
        allowClear: true,
        width: '100%'
    });

    // Toggle tag chip selection
    $(document).on('click', '.tag-chip-label', function(e) {
        var checkbox = $(this).find('.tag-checkbox');
        var isChecked = !checkbox.prop('checked');
        checkbox.prop('checked', isChecked);

        if (isChecked) {
            $(this).removeClass('btn-tag-inactive').addClass('btn-tag-active');
            $(this).find('.tag-icon').removeClass('bi-plus-circle').addClass('bi-check-circle-fill text-warning');
        } else {
            $(this).removeClass('btn-tag-active').addClass('btn-tag-inactive');
            $(this).find('.tag-icon').removeClass('bi-check-circle-fill text-warning').addClass('bi-plus-circle');
        }
    });

    // Quick Add Tag AJAX
    function submitQuickTag() {
        var tagName = $('#quick-tag-name').val().trim();
        if (!tagName) return;

        var btn = $('#btn-quick-add-tag');
        var spinner = $('#quick-tag-spinner');
        var feedback = $('#quick-tag-feedback');

        btn.prop('disabled', true);
        spinner.removeClass('d-none');
        feedback.addClass('d-none').removeClass('text-success text-danger');

        $.ajax({
            url: '{{ route('admin.tags.quickStore') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                name: tagName
            },
            dataType: 'json',
            success: function(res) {
                btn.prop('disabled', false);
                spinner.addClass('d-none');
                $('#quick-tag-name').val('');

                if (res.success && res.tag) {
                    var tag = res.tag;
                    var existingLabel = $('.tag-chip-label[data-tag-id="' + tag.id + '"]');

                    if (existingLabel.length > 0) {
                        existingLabel.find('.tag-checkbox').prop('checked', true);
                        existingLabel.removeClass('btn-tag-inactive').addClass('btn-tag-active');
                        existingLabel.find('.tag-icon').removeClass('bi-plus-circle').addClass('bi-check-circle-fill text-warning');
                        feedback.text('Tag "' + tag.name + '" đã tồn tại và đã được chọn!').addClass('text-success').removeClass('d-none');
                    } else {
                        $('#no-tags-msg').remove();
                        var newChipHtml = '<label class="tag-chip-label btn btn-sm btn-tag-active" data-tag-id="' + tag.id + '" style="cursor: pointer; user-select: none;">' +
                            '<input type="checkbox" name="tags[]" value="' + tag.id + '" class="d-none tag-checkbox" checked>' +
                            '<i class="bi bi-check-circle-fill text-warning tag-icon me-1"></i>' +
                            '<span class="tag-name">' + tag.name + '</span>' +
                            '</label>';
                        $('#tag-cloud').append(newChipHtml);
                        feedback.text('Đã tạo tag "' + tag.name + '" và tự động chọn!').addClass('text-success').removeClass('d-none');
                    }
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false);
                spinner.addClass('d-none');
                var errMsg = 'Có lỗi xảy ra khi tạo tag.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errMsg = xhr.responseJSON.message;
                }
                feedback.text(errMsg).addClass('text-danger').removeClass('d-none');
            }
        });
    }

    $('#btn-quick-add-tag').on('click', submitQuickTag);
    $('#quick-tag-name').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            submitQuickTag();
        }
    });
});
</script>
@endpush