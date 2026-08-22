@extends('admin.layouts.app')
@section('title', 'Chi tiết Sản phẩm')
@section('content')
<div class="card">
<div class="card-header d-flex justify-content-between align-items-center">
<h2 class="mb-0">Chi tiết Sản phẩm: {{ $product->name }}</h2>
<a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">Quay lại danh sách</a>
</div>
<div class="card-body">
    <table class="table table-bordered">
<tr>
<th style="width: 200px;" class="table-light">ID</th>
<td>{{ $product->id }}</td>
</tr>
<tr>
<th class="table-light">Tên sản phẩm</th>
<td><strong>{{ $product->name }}</strong></td>
</tr>
<tr>
<th class="table-light">Danh mục</th>
<td>
<span class="badge bg-info text-dark">
{{ $product->category->name ?? 'Chưa phân loại' }}
</span>
</td>
</tr>
<tr>
<th class="table-light">Mô tả</th>
<td>{{ $product->description ?? 'Không có mô tả' }}</td>
</tr>
<tr>
<th class="table-light">Số lượng</th>
<td>{{ $product->quantity }}</td>
</tr>
<tr>
<th class="table-light">Giá tiền</th>
<td>{{ number_format($product->price, 0, ',', '.') }} đ</td>
</tr>
<tr>
<th class="table-light">Ngày tạo</th>
<td>{{ $product->created_at ? $product->created_at->format('d/m/Y H:i:s') : 'N/A' }}</td>
</tr>
<tr>
    <th class="table-light">Ngày cập nhật</th>
<td>{{ $product->updated_at ? $product->updated_at->format('d/m/Y H:i:s') : 'N/A' }}</td>
</tr>
</table>
<div class="mt-4">
<a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning me-2">Sửa sản phẩm</a>
<a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Quay lại</a>
</div>
</div>
</div>
@endsection