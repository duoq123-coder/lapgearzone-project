@extends('layouts.app')
@section('title', 'Đăng ký tài khoản')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden mt-2">
            <div class="p-4 text-dark text-center position-relative">
                <div class="position-absolute top-0 start-0 w-100 h-100" style=\"background: var(--primary-gradient);\"></div>
                <h3 class="fw-bold mb-1 position-relative"><i class="bi bi-person-plus-fill me-2 text-info"></i>Đăng Ký</h3>
                <p class="text-muted small mb-0 position-relative">Tạo tài khoản thành viên mới tại LapGearZone</p>
            </div>
            <div class="card-body p-4">
                <form action="{{ url('register') }}" method="POST">
                    @csrf
                    
                    <!-- Họ tên -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Họ và Tên</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="form-control bg-light border-start-0 @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" 
                                   placeholder="Nguyễn Văn A" 
                                   required 
                                   autofocus>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Email Address -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Địa chỉ Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   class="form-control bg-light border-start-0 @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}" 
                                   placeholder="name@example.com" 
                                   required>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-muted"></i></span>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="form-control bg-light border-start-0 @error('password') is-invalid @enderror" 
                                   placeholder="Tối thiểu 8 ký tự..." 
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
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-shield-check text-muted"></i></span>
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   class="form-control bg-light border-start-0" 
                                   placeholder="Nhập lại mật khẩu..." 
                                   required>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-premium btn-lg py-2">
                            Đăng ký ngay <i class="bi bi-chevron-right small ms-1"></i>
                        </button>
                    </div>
                </form>
                
                <div class="text-center mt-4 border-top pt-3">
                    <p class="text-muted small mb-0">Bạn đã có tài khoản? 
                        <a href="{{ route('login') }}" class="text-dark fw-bold text-decoration-none gold-underline">Đăng nhập tại đây</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection