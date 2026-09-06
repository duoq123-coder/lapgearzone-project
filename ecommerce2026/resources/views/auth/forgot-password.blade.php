@extends('layouts.app')
@section('title', 'Quên mật khẩu - Cửa Hàng Công Nghệ')
@section('content')
<div class="container py-5 animate-slide-up">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card card-premium overflow-hidden shadow-sm">
                <div class="p-4 text-white text-center position-relative" style="background: var(--bellroy-charcoal);">
                    <h3 class="serif-title mb-1 text-white" style="font-size: 1.6rem;"><i class="bi bi-key me-2" style="color: var(--bellroy-orange);"></i>Quên Mật Khẩu</h3>
                    <p class="small mb-0" style="color: #a8a49c;">Nhập email để nhận mã OTP khôi phục tài khoản</p>
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

                    <form action="{{ route('password.email') }}" method="POST">
                        @csrf
                        
                        <!-- Email Address -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold text-dark small">
                                Địa chỉ Email của bạn <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       class="form-control border-start-0 @error('email') is-invalid @enderror" 
                                       value="{{ old('email', session('password_reset_email')) }}" 
                                       placeholder="email@example.com" 
                                       required 
                                       autofocus>
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1 fw-semibold">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted small mt-2">
                                <i class="bi bi-info-circle me-1"></i>Mã OTP gồm 6 số sẽ được gửi đến email của bạn.
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-premium py-2.5 justify-content-center text-center">
                                <i class="bi bi-send me-2"></i>Gửi mã xác nhận OTP
                            </button>
                        </div>

                        <!-- Quay lại đăng nhập -->
                        <div class="text-center mt-3 pt-3 border-top" style="border-color: var(--border-color) !important;">
                            <a href="{{ route('login') }}" class="text-decoration-none small fw-bold" style="color: var(--text-main);">
                                <i class="bi bi-arrow-left me-1"></i>Quay lại Đăng nhập
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
