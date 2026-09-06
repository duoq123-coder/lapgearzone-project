@extends('admin.layouts.app')
@section('title', 'Quản lý Tin tức Công nghệ - Admin')

@push('styles')
<style>
    .admin-news-thumb {
        width: 100px;
        aspect-ratio: 16 / 9;
        object-fit: cover;
        border-radius: 2px;
        box-shadow: 2px 2px 0px rgba(0, 0, 0, 0.1);
        border: 1px solid var(--border-color);
    }
    .badge-category {
        background-color: #fbeee8 !important;
        color: #a43d1a !important;
        border: 1px solid #efc9b9;
        font-weight: 600;
        font-size: 0.75rem;
    }
    [data-bs-theme="dark"] .badge-category {
        background-color: rgba(205, 76, 32, 0.2) !important;
        color: #ff8c5a !important;
        border-color: rgba(205, 76, 32, 0.4);
    }
</style>
@endpush

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h2 class="mb-0 fw-bold text-dark display-font">
            <i class="bi bi-newspaper me-2" style="color: var(--bellroy-orange);"></i>Quản lý Tin tức Công nghệ
        </h2>
        <p class="text-secondary small mb-0">Đăng tải, cập nhật và quản lý các bài viết xu hướng hiển thị ngoài trang chủ</p>
    </div>
    <a href="{{ route('admin.news.create') }}" class="btn btn-premium px-4">
        <i class="bi bi-plus-circle-fill me-1"></i> Viết bài mới
    </a>
</div>

<!-- ================= PHẦN TÌM KIẾM & BỘ LỌC ================= -->
<div class="mb-4">
    <form action="{{ route('admin.news.index') }}" method="GET" class="d-flex flex-wrap align-items-center gap-2">
        <div class="input-group" style="max-width: 400px;">
            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
            <input type="text" 
                   name="search" 
                   class="form-control border-start-0" 
                   placeholder="Tìm theo tiêu đề, thể loại..." 
                   value="{{ request('search') }}">
        </div>

        <select name="category" class="form-select bg-white" style="max-width: 200px;">
            <option value="">- Tất cả thể loại -</option>
            @if(isset($categories))
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                        {{ $cat }}
                    </option>
                @endforeach
            @endif
        </select>

        <select name="status" class="form-select bg-white" style="max-width: 170px;">
            <option value="">- Tất cả trạng thái -</option>
            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Đang hiển thị</option>
            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Bản nháp (Ẩn)</option>
        </select>

        <button type="submit" class="btn btn-dark px-4 fw-bold">Lọc</button>

        @if(request('search') || request('category') || request('status'))
            <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-x-circle me-1"></i>Hủy lọc
            </a>
        @endif
    </form>
</div>

<!-- ================= DANH SÁCH BÀI VIẾT ================= -->
<div class="card overflow-hidden border-0 shadow-sm rounded-4">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;" class="text-center">STT</th>
                    <th style="width: 120px;" class="text-center">Ảnh bìa</th>
                    <th>Tiêu đề &amp; Đoạn trích</th>
                    <th style="width: 160px;">Thể loại</th>
                    <th style="width: 140px;" class="text-center">Trạng thái</th>
                    <th style="width: 100px;" class="text-center">Lượt xem</th>
                    <th style="width: 160px;" class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($news as $item)
                <tr>
                    <td class="text-center fw-bold text-secondary">
                        {{ $news->firstItem() ? $news->firstItem() + $loop->index : $loop->iteration }}
                    </td>
                    <td class="text-center">
                        <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="admin-news-thumb">
                    </td>
                    <td>
                        <div class="fw-bold text-dark mb-1" style="font-size: 0.98rem;">
                            {{ $item->title }}
                        </div>
                        <p class="text-muted small mb-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; max-width: 500px;">
                            {{ $item->summary }}
                        </p>
                        <div class="small text-secondary d-flex align-items-center gap-3">
                            <span><i class="bi bi-person me-1"></i>{{ $item->author_name }}</span>
                            <span><i class="bi bi-clock me-1"></i>{{ $item->read_time }}</span>
                            <span><i class="bi bi-calendar3 me-1"></i>{{ $item->formatted_date }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-category rounded-pill px-3 py-1.5">
                            {{ $item->category }}
                        </span>
                    </td>
                    <td class="text-center">
                        <form action="{{ route('admin.news.togglePublish', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $item->is_published ? 'btn-success' : 'btn-secondary' }} rounded-pill px-3 py-1 text-nowrap" title="Bấm để đổi trạng thái">
                                <i class="bi {{ $item->is_published ? 'bi-check-circle-fill' : 'bi-eye-slash-fill' }} me-1"></i>
                                {{ $item->is_published ? 'Hiển thị' : 'Đang ẩn' }}
                            </button>
                        </form>
                    </td>
                    <td class="text-center fw-semibold text-secondary">
                        <i class="bi bi-eye me-1"></i>{{ number_format($item->views_count) }}
                    </td>
                    <td class="text-center text-nowrap">
                        <div class="d-inline-flex gap-1">
                            <!-- Xem thử bài viết ngoài client -->
                            <a href="{{ route('news.show', $item->slug) }}" target="_blank" class="btn btn-sm btn-light border rounded-circle p-2 text-info" title="Xem trước bài viết" style="width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center;">
                                <i class="bi bi-box-arrow-up-right"></i>
                            </a>

                            <!-- Sửa bài viết -->
                            <a href="{{ route('admin.news.edit', $item->id) }}" class="btn btn-sm btn-light border rounded-circle p-2 text-primary" title="Chỉnh sửa" style="width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center;">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <!-- Xóa bài viết -->
                            <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này không? Hành động này không thể hoàn tác!');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border rounded-circle p-2 text-danger" title="Xóa bài viết" style="width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="bi bi-newspaper text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2 mb-3">Chưa có bài viết tin tức nào được đăng tải.</p>
                        <a href="{{ route('admin.news.create') }}" class="btn btn-premium btn-sm">
                            <i class="bi bi-plus-circle me-1"></i> Đăng bài viết đầu tiên
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($news->hasPages())
    <div class="card-footer bg-white border-0 py-3 d-flex justify-content-center">
        {{ $news->links() }}
    </div>
    @endif
</div>
@endsection
