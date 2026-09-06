@extends('layouts.app')
@section('title', 'Đặt lại mật khẩu - Cửa Hàng Công Nghệ')
@section('content')
<div class="container py-5 animate-slide-up">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card card-premium overflow-hidden shadow-sm">
                <div class="p-4 text-white text-center position-relative" style="background: var(--bellroy-charcoal);">
                    <h3 class="serif-title mb-1 text-white" style="font-size: 1.6rem;"><i class="bi bi-key-fill me-2" style="color: var(--bellroy-orange);"></i>Tạo Mật Khẩu Mới</h3>
                    <p class="small mb-0" style="color: #a8a49c;">Thiết lập mật khẩu an toàn mới cho tài khoản của bạn</p>
                </div>
                <div class="card-body p-4 p-md-4">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show rounded-3 p-3 mb-3" style="background-color: var(--bellroy-orange-subtle); color: var(--bellroy-orange);" role="alert">
                            @foreach ($errors->all() as $error)
                                <div><i class="bi bi-exclamation-circle-fill me-1"></i>{{ $error }}</div>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show rounded-3 p-3 mb-3" style="background-color: var(--bellroy-orange-subtle); color: var(--bellroy-orange);" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show rounded-3 p-3 mb-3" style="background-color: var(--bellroy-sage-subtle); color: var(--bellroy-sage);" role="alert">
                            <i class="bi bi-check-circle-fill me-1"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(isset($email))
                        <div class="p-3 rounded-3 text-center mb-4 border" style="background-color: #faf9f6; border-color: var(--border-color) !important;">
                            <span class="text-muted small d-block">Đang đổi mật khẩu cho tài khoản:</span>
                            <strong class="text-dark fs-6">{{ $email }}</strong>
                        </div>
                    @endif

                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf
                        
                        <!-- New Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold text-dark small">
                                Mật khẩu mới <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" 
                                       name="password" 
                                       id="password" 
                                       class="form-control border-start-0 @error('password') is-invalid @enderror" 
                                       placeholder="Tối thiểu 8 ký tự..." 
                                       required
                                       autofocus>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1 fw-semibold">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-bold text-dark small">
                                Xác nhận mật khẩu mới <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-shield-lock-fill"></i></span>
                                <input type="password" 
                                       name="password_confirmation" 
                                       id="password_confirmation" 
                                       class="form-control border-start-0" 
                                       placeholder="Nhập lại mật khẩu mới..." 
                                       required>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-premium py-2.5 justify-content-center text-center">
                                <i class="bi bi-check2-circle me-2"></i>Lưu mật khẩu mới
                            </button>
                        </div>

                        <!-- Back to Login -->
                        <div class="mt-3 text-center border-top pt-3" style="border-color: var(--border-color) !important;">
                            <a href="{{ route('login') }}" class="text-decoration-none fw-bold small" style="color: var(--text-main);">
                                <i class="bi bi-arrow-left me-1"></i>Hủy và quay lại Đăng nhập
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
