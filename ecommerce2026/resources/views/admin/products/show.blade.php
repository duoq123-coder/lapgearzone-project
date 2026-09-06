@extends('admin.layouts.app')
@section('title', 'Chi tiết Sản phẩm - ' . $product->name)
@section('content')
<div class="card p-4">
    <div class="card-header bg-transparent border-bottom p-0 pb-3 mb-4 d-flex justify-content-between align-items-center" style="border-color: var(--border-color) !important;">
        <h2 class="serif-title mb-0 text-dark" style="font-size: 1.5rem;">
            <i class="bi bi-box-seam me-2" style="color: var(--bellroy-orange);"></i>{{ $product->name }}
        </h2>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-premium btn-sm">Quay lại danh sách</a>
    </div>
    <div class="card-body p-0">
        <table class="table mb-4">
            <tr>
                <th style="width: 200px;">Mã định danh (ID)</th>
                <td><strong class="font-monospace text-dark">#{{ $product->id }}</strong></td>
            </tr>
            <tr>
                <th>Tên sản phẩm</th>
                <td><strong class="text-dark">{{ $product->name }}</strong></td>
            </tr>
            <tr>
                <th>Danh mục</th>
                <td>
                    <span class="badge badge-terracotta">
                        {{ $product->category->name ?? 'Chưa phân loại' }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>Mô tả chi tiết</th>
                <td class="text-secondary">{{ $product->description ?? 'Chưa có mô tả' }}</td>
            </tr>
            <tr>
                <th>Tồn kho</th>
                <td>
                    @if($product->quantity > 0)
                        <span class="badge badge-sage">{{ $product->quantity }} chiếc</span>
                    @else
                        <span class="badge bg-danger bg-opacity-10 text-danger">Tạm hết</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Giá bán</th>
                <td class="fw-bold display-font text-dark fs-5">{{ number_format($product->price, 0, ',', '.') }} đ</td>
            </tr>
            <tr>
                <th>Mô hình 3D</th>
                <td>
                    @if($product->model_3d)
                        <span class="badge badge-sage"><i class="bi bi-badge-3d me-1"></i>Đã tải lên</span>
                    @else
                        <span class="badge bg-secondary bg-opacity-10 text-secondary">Chưa có</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Ngày khởi tạo</th>
                <td class="text-muted">{{ $product->created_at ? $product->created_at->format('d/m/Y H:i:s') : 'N/A' }}</td>
            </tr>
            <tr>
                <th>Ngày cập nhật</th>
                <td class="text-muted">{{ $product->updated_at ? $product->updated_at->format('d/m/Y H:i:s') : 'N/A' }}</td>
            </tr>
        </table>
        
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-premium px-4">
                <i class="bi bi-pencil me-1"></i>Sửa sản phẩm
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-premium">Quay lại</a>
        </div>
    </div>
</div>
@endsection