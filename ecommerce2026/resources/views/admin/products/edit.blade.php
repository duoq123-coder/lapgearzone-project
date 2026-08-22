@extends('admin.layouts.app')
@section('title', 'Sửa Sản phẩm')
@section('content')
<div class="card">
    <div class="card-header">
        <h2 class="mb-0">Cập nhật Sản phẩm</h2>
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

        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Ảnh sản phẩm hiện tại -->
            @if($product->image)
                <div class="mb-3 text-center">
                    <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded mb-2" style="max-height:200px; object-fit:contain; width:100%;">
                    <div class="d-flex justify-content-center">
                        <button type="button" id="delete-image-btn" class="btn btn-danger btn-sm" data-url="{{ route('admin.products.image.destroy', $product) }}">
                            <i class="bi bi-trash me-1"></i>Xóa ảnh
                        </button>
                    </div>
                </div>
            @endif

            <div class="mb-3">
                <label for="product_image_input" class="form-label">Thay đổi ảnh sản phẩm (Sẽ được căn chỉnh 1:1)</label>
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
                       value="{{ old('name', $product->name) }}"
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
                <label for="description" class="form-label">Mô tả</label>
                <textarea id="description"
                          name="description"
                          class="form-control @error('description') is-invalid @enderror"
                          rows="3">{{ old('description', $product->description) }}</textarea>
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
                       value="{{ old('price', $product->price) }}"
                       required
                       min="0">
                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Thao tác -->
            <div class="mt-4">
                <button type="submit" class="btn btn-primary me-2">Cập nhật</button>
                <a href="{{ route('admin.products.images.edit', $product->id) }}" class="btn btn-info me-2 text-white">
                    <i class="bi bi-image me-1"></i>Quản lý ảnh chi tiết
                </a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Quay lại</a>
            </div>
        </form>

        @push('scripts')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            initImageCropper('product_image_input', 'product_image_base64', 1);

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
        });
        </script>
        @endpush
    </div>
</div>
@endsection