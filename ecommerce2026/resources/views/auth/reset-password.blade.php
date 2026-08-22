@extends('layouts.app')
@section('title', 'Đặt lại mật khẩu')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden mt-4">
            <div class="p-4 bg-white text-dark shadow-sm text-center position-relative">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(79, 70, 229, 0.1) 0%, rgba(124, 58, 237, 0.15) 100%);"></div>
                <h3 class="fw-bold mb-1 position-relative"><i class="bi bi-shield-check-fill me-2 text-warning"></i>Đặt Lại Mật Khẩu</h3>
                <p class="text-muted small mb-0 position-relative">Tạo mật khẩu mới để tiếp tục</p>
            </div>
            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    
                    <!-- Email Address (Hidden) -->
                    <input type="hidden" name="email" value="{{ request('email') }}">
                    <input type="hidden" name="token" value="{{ $token }}">
                    
                    <!-- Email Display -->
                    <div class="mb-3">
                        <label for="email_display" class="form-label">Địa chỉ Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                            <input type="email" 
                                   id="email_display" 
                                   class="form-control bg-light border-start-0" 
                                   value="{{ request('email') }}" 
                                   disabled>
                        </div>
                    </div>

                    <!-- New Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu mới</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-muted"></i></span>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="form-control bg-light border-start-0 @error('password') is-invalid @enderror" 
                                   placeholder="Nhập mật khẩu mới (tối thiểu 8 ký tự)..." 
                                   required>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-muted"></i></span>
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   class="form-control bg-light border-start-0 @error('password_confirmation') is-invalid @enderror" 
                                   placeholder="Nhập lại mật khẩu..." 
                                   required>
                            @error('password_confirmation')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg fw-semibold rounded-3">
                            <i class="bi bi-check-circle-fill me-2"></i>Đặt lại mật khẩu
                        </button>
                    </div>

                    <!-- Back to Login -->
                    <div class="mt-3 text-center">
                        <p class="text-muted small">Quay lại
                            <a href="{{ route('login') }}" class="text-decoration-none fw-semibold text-primary">Đăng nhập</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
