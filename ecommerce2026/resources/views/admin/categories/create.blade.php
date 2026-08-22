@extends('admin.layouts.app')
@section('title', 'Thêm danh mục mới')
@section('content')
<div class="container">
<h2>Thêm danh mục mới</h2>
<form action="{{ route('admin.categories.store') }}" method="POST">
@csrf
<div class="mb-3">
<label for="name" class="form-label">Tên danh mục</label>
<input type="text" class="form-control" id="name" name="name" required style="border: 1px solid #CBD5E1;">
</div>
<div class="mb-3">
<label for="icon" class="form-label">Icon (Class của Bootstrap Icon, VD: bi-laptop)</label>
<div class="input-group">
<span class="input-group-text"><i class="bi bi-info-circle"></i></span>
<input type="text" class="form-control" id="icon" name="icon" placeholder="bi-laptop" style="border: 1px solid #CBD5E1;">
</div>
<small class="text-muted">Xem danh sách Icon tại: <a href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</a></small>
</div>
<button type="submit" class="btn btn-success">Lưu danh mục</button>
<a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Quay lại</a>
</form>
</div>
@endsection