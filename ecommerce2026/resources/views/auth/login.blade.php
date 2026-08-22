@extends('layouts.app')
@section('title', 'Đăng nhập')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden mt-4">
            <div class="p-4 text-dark text-center position-relative">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, #fbb870ff);"></div>
                <h3 class="fw-bold mb-1 position-relative"><i class="bi bi-shield-lock-fill me-2 text-warning"></i>Đăng Nhập</h3>
                <p class="text-muted small mb-0 position-relative">Truy cập vào hệ thống LapGearZone</p>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="login_id" class="form-label fw-bold text-dark">Email hoặc CCCD <span class="text-danger">*</span></label>
                        <div class="input-group input-group-lg shadow-sm">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-person"></i></span>
                            <input type="text" class="form-control border-start-0 @error('login_id') is-invalid @enderror" id="login_id" name="login_id" value="{{ old('login_id') }}" required autofocus placeholder="Nhập email hoặc CCCD của bạn">
                            @error('login_id')
                                <div class="invalid-feedback fw-bold">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="mb-2">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-muted"></i></span>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="form-control bg-light border-start-0 @error('password') is-invalid @enderror" 
                                   placeholder="Nhập mật khẩu..." 
                                   required>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Forgot Password Link -->
                    <div class="mb-4 text-end">
                        <a href="{{ route('password.request') }}" class="text-decoration-none text-danger small fw-semibold">
                            <i class="bi bi-question-circle-fill me-1"></i>Quên mật khẩu?
                        </a>
                    </div>

                    <!-- Submit Button (Thu gọn vừa vặn & căn giữa) -->
                    <div class="text-center mb-3">
                        <button type="submit" class="btn btn-premium px-4 py-2" style="min-width: 180px;">
                            Đăng nhập <i class="bi bi-chevron-right small ms-1"></i>
                        </button>
                    </div>
                </form>
                
                
            </div>
        </div>
    </div>
</div>
@endsection