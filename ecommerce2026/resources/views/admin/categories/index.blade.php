@extends('admin.layouts.app')
@section('title', 'Quản lý Danh mục')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0 fw-bold text-dark display-font"><i class="bi bi-folder-fill me-2 text-primary"></i>Quản lý Danh Mục</h2>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
        <i class="bi bi-plus-circle-fill me-1"></i> Thêm danh mục mới
    </a>
</div>

<!-- ================= PHẦN TÌM KIẾM DANH MỤC ================= -->
<div class="mb-4">
    <form action="{{ route('admin.categories.index') }}" method="GET" class="d-flex align-items-center">
        <div class="input-group shadow-sm" style="max-width: 500px;">
            <span class="input-group-text bg-white text-secondary border-end-0"><i class="bi bi-search"></i></span>
            <input type="text" 
                   name="search" 
                   class="form-control border-start-0" 
                   placeholder="Nhập tên danh mục cần tìm..." 
                   value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary fw-bold px-4">Tìm kiếm</button>
        </div>
        
        <!-- Nút hủy lọc chỉ hiện khi có từ khóa tìm kiếm -->
        @if(request('search'))
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-danger ms-3 shadow-sm fw-bold">
                <i class="bi bi-x-circle me-1"></i>Hủy lọc
            </a>
        @endif
    </form>
</div>
<!-- ========================================================== -->

<div class="card bg-white shadow-sm border-0 rounded-4 shadow-sm" style="border: 1px solid rgba(0,0,0,0.1) !important;">
    <div class="card-body p-0 overflow-hidden rounded-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-light">
                <thead class="table-light border-bottom border-secondary border-opacity-20">
                    <tr>
                        <th width="10%" class="ps-4">ID</th>
                        <th width="10%" class="text-center">Biểu Tượng</th>
                        <th width="55%">Danh mục</th>
                        <th width="25%" class="text-center pe-4">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td class="ps-4 fw-bold text-info">{{ $category->id }}</td>
                            <td class="text-center fs-4 text-danger">
                                @if($category->icon)
                                    <i class="{{ $category->icon }}"></i>
                                @else
                                    <i class="bi bi-tag"></i>
                                @endif
                            </td>
                            <td><strong class="text-dark fs-6">{{ $category->name }}</strong></td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning btn-sm rounded-pill px-3 fw-bold text-dark"><i class="bi bi-pencil-square me-1"></i> Sửa</a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold" onclick="return confirm('Bạn có chắc muốn xóa danh mục này?')">
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
</div>

<!-- Phân trang -->
<div class="d-flex justify-content-center mt-4">
    {{ $categories->links() }}
</div>
@endsection