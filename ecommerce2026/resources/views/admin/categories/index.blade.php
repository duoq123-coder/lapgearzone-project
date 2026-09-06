@extends('admin.layouts.app')
@section('title', 'Quản lý Danh mục - Admin')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0 fw-bold text-dark display-font"><i class="bi bi-folder me-2" style="color: var(--bellroy-orange);"></i>Quản lý Danh Mục</h2>
        <p class="text-secondary small mb-0">Phân loại các dòng sản phẩm thiết bị trên hệ thống</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-premium px-4">
        <i class="bi bi-plus-circle-fill me-1"></i> Thêm danh mục mới
    </a>
</div>

<!-- ================= PHẦN TÌM KIẾM DANH MỤC ================= -->
<div class="mb-4">
    <form action="{{ route('admin.categories.index') }}" method="GET" class="d-flex align-items-center gap-2">
        <div class="input-group" style="max-width: 480px;">
            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
            <input type="text" 
                   name="search" 
                   class="form-control border-start-0" 
                   placeholder="Nhập tên danh mục cần tìm..." 
                   value="{{ request('search') }}">
            <button type="submit" class="btn btn-dark px-4 fw-bold">Tìm kiếm</button>
        </div>
        
        @if(request('search'))
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-premium">
                <i class="bi bi-x-circle me-1"></i>Hủy lọc
            </a>
        @endif
    </form>
</div>
<!-- ========================================================== -->

<div class="card overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th width="10%" class="text-center">STT</th>
                    <th width="12%" class="text-center">Biểu Tượng</th>
                    <th width="53%">Tên Danh Mục</th>
                    <th width="25%" class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td class="fw-bold text-secondary text-center">
                            {{ method_exists($categories, 'firstItem') && $categories->firstItem() ? $categories->firstItem() + $loop->index : $loop->iteration }}
                        </td>
                        <td class="text-center fs-4" style="color: var(--bellroy-orange);">
                            @if($category->icon)
                                <i class="{{ $category->icon }}"></i>
                            @else
                                <i class="bi bi-tag"></i>
                            @endif
                        </td>
                        <td><strong class="text-dark fs-6">{{ $category->name }}</strong></td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-outline-premium btn-sm"><i class="bi bi-pencil-square me-1"></i> Sửa</a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-link text-danger text-decoration-none fw-bold" onclick="return confirm('Bạn có chắc muốn xóa danh mục này?')">
                                        <i class="bi bi-trash me-1"></i> Xóa
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-5">
                            <i class="bi bi-folder-x fs-1 d-block mb-3 opacity-50"></i>
                            @if(request('search'))
                                Không tìm thấy danh mục nào khớp với từ khóa "<strong>{{ request('search') }}</strong>".
                            @else
                                Chưa có danh mục nào trong CSDL.
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
    {{ $categories->links() }}
</div>
@endsection
