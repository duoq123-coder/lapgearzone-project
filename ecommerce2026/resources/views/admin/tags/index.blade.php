@extends('admin.layouts.app')
@section('title', 'Quản lý Tags Sản Phẩm - Admin')

@push('styles')
<style>
    .tag-badge-preview {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.02em;
        background: rgba(205, 76, 32, 0.1);
        color: #CD4C20;
        border: 1px solid rgba(205, 76, 32, 0.3);
    }
    [data-bs-theme="dark"] .tag-badge-preview {
        background: rgba(205, 76, 32, 0.18);
        color: #ff7d50;
        border-color: rgba(205, 76, 32, 0.45);
    }
    .tag-type-badge {
        font-size: 0.75rem;
        padding: 3px 8px;
        border-radius: 3px;
        font-weight: 500;
    }
</style>
@endpush

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h2 class="mb-0 fw-bold text-dark display-font">
            <i class="bi bi-tags-fill me-2" style="color: var(--bellroy-orange);"></i>Quản lý Tags Sản Phẩm
        </h2>
        <p class="text-secondary small mb-0">Quản lý các thông số cấu hình và tính năng (VD: RTX 3060, RTX 3070, Core i7, OLED, 144Hz...) để gán vào sản phẩm</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary px-3">
            <i class="bi bi-arrow-left me-1"></i> Về danh sách SP
        </a>
    </div>
</div>


@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <div class="fw-bold mb-1"><i class="bi bi-exclamation-octagon-fill me-1"></i> Có lỗi xảy ra:</div>
        <ul class="mb-0 ps-3 small">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4">
    <!-- CỘT TRÁI: FORM TẠO TAG MỚI -->
    <div class="col-12 col-lg-4">
        <div class="card sticky-top" style="top: 20px;">
            <div class="card-header bg-transparent border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold d-flex align-items-center">
                    <i class="bi bi-plus-circle-fill me-2" style="color: var(--bellroy-orange);"></i>Thêm Tag Mới
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.tags.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold small text-secondary">TÊN TAG <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               placeholder="VD: RTX 3060, Core i7, OLED..." 
                               value="{{ old('name') }}" 
                               required>
                        <div class="form-text small">Tên tag sẽ hiển thị trên bộ lọc trang chủ và thẻ sản phẩm.</div>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="type" class="form-label fw-bold small text-secondary">PHÂN LOẠI TAG</label>
                        <select name="type" id="type" class="form-select">
                            <option value="hardware" {{ old('type') == 'hardware' ? 'selected' : '' }}>Phần cứng (GPU, CPU, RAM...)</option>
                            <option value="display" {{ old('type') == 'display' ? 'selected' : '' }}>Màn hình (OLED, 144Hz, 2K...)</option>
                            <option value="feature" {{ old('type') == 'feature' ? 'selected' : '' }}>Tính năng (Mỏng nhẹ, Pin trâu...)</option>
                            <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>Chung / Khác</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-premium w-100 py-2 fw-bold">
                        <i class="bi bi-plus-lg me-1"></i> Lưu Tag Mới
                    </button>
                </form>


            </div>
        </div>
    </div>

    <!-- CỘT PHẢI: DANH SÁCH TAG ĐÃ TẠO -->
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header bg-transparent border-bottom py-3">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <h5 class="card-title mb-0 fw-bold d-flex align-items-center">
                        <i class="bi bi-list-stars me-2" style="color: var(--bellroy-orange);"></i>Danh Sách Tags
                        <span class="badge bg-secondary ms-2" style="font-size: 0.75rem;">{{ $tags->total() }}</span>
                    </h5>
                    
                    <!-- Search bar -->
                    <form action="{{ route('admin.tags.index') }}" method="GET" class="d-flex gap-2" style="max-width: 320px;">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" class="form-control" placeholder="Tìm tag..." value="{{ request('search') }}">
                            <button class="btn btn-dark" type="submit"><i class="bi bi-search"></i></button>
                            @if(request('search'))
                                <a href="{{ route('admin.tags.index') }}" class="btn btn-outline-secondary" title="Hủy tìm kiếm"><i class="bi bi-x"></i></a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th>Tên Tag</th>
                            <th>Đường dẫn (Slug)</th>
                            <th>Phân loại</th>
                            <th class="text-center" style="width: 120px;">Số Laptop</th>
                            <th class="text-center" style="width: 140px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tags as $tag)
                            <tr>
                                <td class="text-center text-muted small font-monospace">
                                    {{ $tags->firstItem() ? $tags->firstItem() + $loop->index : $loop->iteration }}
                                </td>
                                <td>
                                    <span class="tag-badge-preview">
                                        <i class="bi bi-tag-fill me-1" style="font-size: 11px;"></i>{{ $tag->name }}
                                    </span>
                                </td>
                                <td>
                                    <code class="small text-muted">{{ $tag->slug }}</code>
                                </td>
                                <td>
                                    @if($tag->type == 'hardware')
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 tag-type-badge">Phần cứng</span>
                                    @elseif($tag->type == 'display')
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 tag-type-badge">Màn hình</span>
                                    @elseif($tag->type == 'feature')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 tag-type-badge">Tính năng</span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 tag-type-badge">Khác</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.products.index', ['tag' => $tag->slug]) }}" class="badge {{ $tag->products_count > 0 ? 'bg-success bg-opacity-10 text-success border border-success border-opacity-25' : 'bg-secondary bg-opacity-10 text-secondary' }} text-decoration-none px-2 py-1" title="Xem các sản phẩm có tag này">
                                        {{ $tag->products_count }} SP
                                    </a>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center gap-1">
                                        <!-- Nút Sửa (mở modal) -->
                                        <button type="button" 
                                                class="btn btn-outline-premium btn-sm py-1 px-2" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editTagModal-{{ $tag->id }}"
                                                title="Chỉnh sửa tag">
                                            <i class="bi bi-pencil-square"></i> Sửa
                                        </button>

                                        <!-- Nút Xóa -->
                                        <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tag \'{{ $tag->name }}\'? Hành động này sẽ gỡ tag khỏi tất cả sản phẩm liên quan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-link text-danger text-decoration-none fw-bold p-1" title="Xóa tag">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Modal Sửa Tag -->
                                    <div class="modal fade text-start" id="editTagModal-{{ $tag->id }}" tabindex="-1" aria-labelledby="editTagModalLabel-{{ $tag->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.tags.update', $tag) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title fw-bold" id="editTagModalLabel-{{ $tag->id }}">
                                                            <i class="bi bi-pencil-square me-1" style="color: var(--bellroy-orange);"></i> Sửa Tag: {{ $tag->name }}
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold small text-secondary">TÊN TAG <span class="text-danger">*</span></label>
                                                            <input type="text" name="name" class="form-control" value="{{ $tag->name }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold small text-secondary">PHÂN LOẠI TAG</label>
                                                            <select name="type" class="form-select">
                                                                <option value="hardware" {{ $tag->type == 'hardware' ? 'selected' : '' }}>Phần cứng (GPU, CPU, RAM...)</option>
                                                                <option value="display" {{ $tag->type == 'display' ? 'selected' : '' }}>Màn hình (OLED, 144Hz, 2K...)</option>
                                                                <option value="feature" {{ $tag->type == 'feature' ? 'selected' : '' }}>Tính năng (Mỏng nhẹ, Pin trâu...)</option>
                                                                <option value="general" {{ $tag->type == 'general' ? 'selected' : '' }}>Chung / Khác</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
                                                        <button type="submit" class="btn btn-premium btn-sm px-3 fw-bold">Cập nhật</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="bi bi-tags fs-1 d-block mb-2 opacity-50"></i>
                                    @if(request('search'))
                                        Không tìm thấy tag nào khớp với từ khóa "<strong>{{ request('search') }}</strong>".
                                    @else
                                        Chưa có tag nào trong hệ thống. Hãy thêm tag mới ở form bên trái!
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tags->hasPages())
                <div class="card-footer bg-transparent border-top py-3 d-flex justify-content-center">
                    {{ $tags->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
