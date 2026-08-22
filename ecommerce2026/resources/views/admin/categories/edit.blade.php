@extends('admin.layouts.app')
@section('title', 'Chỉnh sửa danh mục')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center rounded-top">
                <h4 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Chỉnh Sửa Danh Mục</h4>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-dark btn-sm">Quay lại</a>
            </div>
            <div class="card-body p-4">
                <!-- Hiển thị lỗi Validate nếu có -->
                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $category->name) }}" required style="border: 1px solid #CBD5E1;">
                    </div>
                    
                    <div class="mb-3">
                        <label for="icon" class="form-label">Icon (Class của Bootstrap Icon, VD: bi-laptop)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="{{ $category->icon ?? 'bi bi-info-circle' }}"></i></span>
                            <input type="text" class="form-control" id="icon" name="icon" value="{{ old('icon', $category->icon) }}" placeholder="bi-laptop" style="border: 1px solid #CBD5E1;">
                        </div>
                        <small class="text-muted">Xem danh sách Icon tại: <a href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</a></small>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary me-2">Cập nhật</button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection