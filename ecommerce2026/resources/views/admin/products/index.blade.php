@extends('admin.layouts.app')
@section('title', 'Danh sách Sản phẩm - Admin')

@push('styles')
<style>
    [data-bs-theme="light"] .admin-products-table tbody td {
        background-color: #ffffff !important;
        color: #44403c !important;
    }

    [data-bs-theme="light"] .admin-products-table tbody .text-muted {
        color: #6e6b66 !important;
    }

    [data-bs-theme="light"] .admin-products-table tbody .text-dark,
    [data-bs-theme="light"] .admin-products-table tbody strong {
        color: #232220 !important;
    }

    [data-bs-theme="light"] .admin-products-table .badge-terracotta {
        background-color: #fbeee8 !important;
        color: #a43d1a !important;
        border: 1px solid #efc9b9;
    }

    [data-bs-theme="light"] .admin-products-table .badge-sage {
        background-color: #edf5f1 !important;
        color: #356b59 !important;
        border: 1px solid #bdd9ce;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0 fw-bold text-dark display-font"><i class="bi bi-box-seam me-2" style="color: var(--bellroy-orange);"></i>Danh sách Sản phẩm</h2>
        <p class="text-secondary small mb-0">Quản lý kho hàng và danh mục thiết bị công nghệ</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.tags.index') }}" class="btn btn-outline-premium px-3">
            <i class="bi bi-tags-fill me-1"></i> Quản lý Tags
        </a>
        <a href="{{ route('admin.products.create') }}" class="btn btn-premium px-4">
            <i class="bi bi-plus-circle-fill me-1"></i> Thêm Sản phẩm
        </a>
    </div>
</div>

<!-- ================= PHẦN TÌM KIẾM SẢN PHẨM ================= -->
<div class="mb-4">
    <form action="{{ route('admin.products.index') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap">
        <div class="input-group" style="max-width: 780px;">
            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
            <input type="text" 
                   name="search" 
                   class="form-control border-start-0" 
                   placeholder="Tìm kiếm theo tên sản phẩm..." 
                   value="{{ request('search') }}">
            
            <select name="category" class="form-select border-start-0 bg-white" style="max-width: 200px;">
                <option value="">- Tất cả danh mục -</option>
                @if(isset($categories))
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                @endif
            </select>

            <select name="tag" class="form-select border-start-0 bg-white" style="max-width: 170px;">
                <option value="">- Tất cả Tags -</option>
                @if(isset($tags))
                    @foreach($tags as $t)
                        <option value="{{ $t->slug }}" {{ request('tag') == $t->slug ? 'selected' : '' }}>
                            {{ $t->name }}
                        </option>
                    @endforeach
                @endif
            </select>

            <button type="submit" class="btn btn-dark px-4 fw-bold">Lọc</button>
        </div>
        
        @if(request('search') || request('category') || request('tag'))
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-premium">
                <i class="bi bi-x-circle me-1"></i>Hủy lọc
            </a>
        @endif
    </form>
</div>
<!-- ========================================================= -->



<div class="card overflow-hidden">
    <div class="table-responsive">
        <table class="table admin-products-table align-middle mb-0">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th class="text-center" style="width: 70px;">Ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Mô tả ngắn</th>
                    <th class="text-center">Kho</th>
                    <th>Giá bán</th>
                    <th class="text-center" style="width: 170px;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td class="text-center text-muted fw-bold font-monospace">
                        {{ $products->firstItem() ? $products->firstItem() + $loop->index : $loop->iteration }}
                    </td>
                    <td class="align-middle text-center">
                        @if($product->image)
                            <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" style="height: 52px; width: 52px; object-fit: contain; background: var(--surface-muted); border-radius: 2px;" class="border p-1">
                        @else
                            <div class="d-inline-flex align-items-center justify-content-center border" style="height: 52px; width: 52px; border-radius: 2px; background: var(--surface-muted);">
                                <i class="bi bi-laptop text-muted"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <strong class="text-dark d-block">{{ $product->name }}</strong>
                        <div class="d-flex flex-wrap align-items-center gap-1 mt-1">
                            @if($product->tags && $product->tags->count() > 0)
                                @foreach($product->tags as $t)
                                    <a href="{{ route('admin.products.index', ['tag' => $t->slug]) }}" 
                                       class="badge text-decoration-none" 
                                       style="background: rgba(205, 76, 32, 0.1); color: #CD4C20; border: 1px solid rgba(205, 76, 32, 0.25); font-size: 10px; font-weight: 600;"
                                       title="Lọc sản phẩm theo tag {{ $t->name }}">
                                        #{{ $t->name }}
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-terracotta">
                            {{ $product->category->name ?? 'Chưa phân loại' }}
                        </span>
                    </td>
                    <td class="text-muted small">{{ Str::limit($product->description, 50, '...') }}</td>
                    <td class="text-center fw-bold">
                        @if($product->quantity > 0)
                            <span class="badge badge-sage font-monospace">{{ $product->quantity }}</span>
                        @else
                            <span class="badge bg-danger bg-opacity-10 text-danger">Hết</span>
                        @endif
                    </td>
                    <td class="fw-bold font-monospace text-dark text-nowrap">{{ number_format($product->price, 0, ',', '.') }} đ</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center align-items-center gap-1 flex-wrap">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline-premium btn-sm">Sửa</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-link text-danger text-decoration-none fw-bold">Xóa</button>
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
</div>

<!-- Phân trang -->
<div class="d-flex justify-content-center mt-4">
    {{ $products->links() }}
</div>
@endsection
