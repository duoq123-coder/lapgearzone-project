@extends('admin.layouts.app')
@section('title', 'Thêm Sản phẩm')
@section('content')
<div class="card">
    <div class="card-header">
        <h2 class="mb-0">Thêm Sản phẩm Mới</h2>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <strong class="d-block mb-1">Đã có lỗi xảy ra, vui lòng kiểm tra lại dữ liệu:</strong>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Ảnh sản phẩm -->
            <div class="mb-3">
                <label for="product_image_input" class="form-label">Ảnh sản phẩm (Sẽ được căn chỉnh 1:1)</label>
                <input type="file" id="product_image_input" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                <input type="hidden" name="image_base64" id="product_image_base64">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Tên sản phẩm -->
            <div class="mb-3">
                <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                <input type="text"
                       id="name"
                       name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}"
                       required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Danh mục -->
            <div class="mb-3">
                <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                <select id="category_id"
                        name="category_id"
                        class="form-select shadow-sm border-secondary border-opacity-25 @error('category_id') is-invalid @enderror"
                        required>
                    <option value="" class="fw-bold text-primary">-- Chọn danh mục --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
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
                <label for="description" class="form-label">Mô tả</label>
                <textarea id="description"
                          name="description"
                          class="form-control @error('description') is-invalid @enderror"
                          rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Giá tiền -->
            <div class="mb-3">
                <label for="price" class="form-label">Giá tiền <span class="text-danger">*</span></label>
                <input type="number"
                       step="0.01"
                       id="price"
                       name="price"
                       class="form-control @error('price') is-invalid @enderror"
                       value="{{ old('price') }}"
                       required
                       min="0">
                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Ghi chú tồn kho -->
            <div class="alert alert-info py-2 px-3 small d-flex align-items-center gap-2">
                <i class="bi bi-info-circle-fill fs-6 text-primary"></i>
                <span>Sản phẩm sau khi tạo sẽ có số lượng tồn kho mặc định là <strong>0</strong>. Bạn hãy tạo phiếu <strong>Nhập kho</strong> để tăng số lượng.</span>
            </div>

            <!-- Các nút thao tác -->
            <div class="mt-4">
                <button type="submit" class="btn btn-success me-2">Lưu lại</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Quay lại</a>
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
        initImageCropper('product_image_input', 'product_image_base64', 1);
    });

    $(document).ready(function() {
        $('#category_id').select2({
            placeholder: "-- Chọn danh mục --",
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endpush