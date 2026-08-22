@extends('admin.layouts.app')
@section('title', 'Danh sách Sản phẩm')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0 fw-bold text-dark display-font"><i class="bi bi-box-seam me-2 text-primary"></i>Danh sách Sản phẩm</h2>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
        <i class="bi bi-plus-circle-fill me-1"></i> Thêm Sản phẩm
    </a>
</div>

<!-- ================= PHẦN TÌM KIẾM SẢN PHẨM ================= -->
<div class="mb-4">
    <form action="{{ route('admin.products.index') }}" method="GET" class="d-flex align-items-center">
        <div class="input-group shadow-sm" style="max-width: 500px;">
            <span class="input-group-text bg-white text-secondary border-end-0"><i class="bi bi-search"></i></span>
            <input type="text" 
                   name="search" 
                   class="form-control border-start-0" 
                   placeholder="Nhập tên sản phẩm cần tìm..." 
                   value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary fw-bold px-4">Tìm kiếm</button>
        </div>
        
        <!-- Nút hủy lọc chỉ hiện khi có từ khóa tìm kiếm -->
        @if(request('search'))
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-danger ms-3 shadow-sm fw-bold">
                <i class="bi bi-x-circle me-1"></i>Hủy lọc
            </a>
        @endif
    </form>
</div>
<!-- ========================================================= -->

{{-- Hiển thị thông báo thành công sau khi Thêm / Sửa / Xóa --}}
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="table-responsive">
    <table class="table table-bordered table-striped align-middle shadow-sm">
        <thead class="table-light">
            <tr>
                <th style="width: 60px;" class="text-center">STT</th>
                <th style="width:120px;" class="text-center">Ảnh</th>
                <th>Tên Sản phẩm</th>
                <th>Danh mục</th>
                <th>Mô tả</th>
                <th style="width: 100px;" class="text-center">Số lượng</th>
                <th style="width: 130px;">Giá/SP</th>
                <th style="width: 180px;" class="text-center">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
            <tr>
                <!-- STT bắt đầu từ 1 và tiếp nối theo phân trang -->
                <td class="text-center fw-bold text-secondary">
                    {{ $products->firstItem() ? $products->firstItem() + $loop->index : $loop->iteration }}
                </td>
                <td class="align-middle text-center">
                    @if($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" style="height:60px; width:60px; object-fit:cover; border-radius:6px;" class="shadow-sm">
                    @else
                        <div class="bg-light d-inline-flex align-items-center justify-content-center shadow-sm" style="height:60px; width:60px; border-radius:6px;">
                            <i class="bi bi-image text-muted"></i>
                        </div>
                    @endif
                </td>
                <td><strong>{{ $product->name }}</strong></td>
                <td>
                    <span class="badge bg-info text-dark">
                        {{ $product->category->name ?? 'Chưa phân loại' }}
                    </span>
                </td>
                {{-- Cắt ngắn mô tả nếu quá dài --}}
                <td>{{ Str::limit($product->description, 50, '...') }}</td>
                <td class="text-center fw-bold {{ $product->quantity > 0 ? 'text-success' : 'text-danger' }}">
                    {{ $product->quantity }}
                </td>
                {{-- Format giá tiền theo chuẩn VNĐ --}}
                <td class="text-danger fw-bold">{{ number_format($product->price, 0, ',', '.') }} đ</td>
                <td class="text-center">
                    <div class="d-flex justify-content-center gap-1">
                        {{-- Nút Xem --}}
                        <a href="{{ route('admin.products.show', $product) }}" class="btn btn-info btn-sm text-dark" title="Xem chi tiết">Xem</a>
                        {{-- Nút Quản lý ảnh --}}
                        <a href="{{ route('admin.products.images.edit', $product) }}" class="btn btn-secondary btn-sm" title="Quản lý ảnh chi tiết">
                            <i class="bi bi-images"></i>
                        </a>
                        {{-- Nút Sửa --}}
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning btn-sm">Sửa</a>
                        {{-- Form Xóa --}}
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center text-muted py-5">
                    <i class="bi bi-box-seam fs-1 d-block mb-3 opacity-50"></i>
                    @if(request('search'))
                        Không tìm thấy sản phẩm nào khớp với từ khóa "<strong>{{ request('search') }}</strong>".
                    @else
                        Chưa có sản phẩm nào trong hệ thống.
                    @endif
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Phân trang -->
<div class="d-flex justify-content-center mt-3">
    {{ $products->links() }}
</div>
@endsection