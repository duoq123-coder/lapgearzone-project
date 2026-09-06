@extends('admin.layouts.app')
@section('title', 'Thêm danh mục mới - Admin')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card p-4">
            <div class="card-header bg-transparent border-bottom p-0 pb-3 mb-4 d-flex justify-content-between align-items-center" style="border-color: var(--border-color) !important;">
                <h4 class="serif-title mb-0 text-dark" style="font-size: 1.4rem;">
                    <i class="bi bi-plus-circle me-2" style="color: var(--bellroy-orange);"></i>Thêm danh mục mới
                </h4>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-premium btn-sm">Quay lại</a>
            </div>
            <div class="card-body p-0">
                @if ($errors->any())
                    <div class="alert alert-danger mb-4 rounded-3 p-3" style="background-color: var(--bellroy-orange-subtle); color: var(--bellroy-orange);">
                        <ul class="mb-0 ps-3 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold text-dark small">Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Ví dụ: Laptop Gaming, Phụ kiện..." required>
                    </div>
                    <div class="mb-4">
                        <label for="icon" class="form-label fw-bold text-dark small">Biểu tượng Icon (Class Bootstrap Icon)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted"><i class="bi bi-tag"></i></span>
                            <input type="text" class="form-control" id="icon" name="icon" placeholder="Ví dụ: bi-laptop, bi-phone, bi-headphones">
                        </div>
                        <small class="text-muted mt-1 d-block">Xem danh sách Icon tại: <a href="https://icons.getbootstrap.com/" target="_blank" class="text-decoration-none" style="color: var(--bellroy-orange);">Bootstrap Icons</a></small>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-premium px-4">Lưu danh mục</button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-premium">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection