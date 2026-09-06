@extends('layouts.app')
@section('title', 'Đổi mật khẩu bảo mật - Cửa Hàng Công Nghệ')

@section('content')
<div class="container py-5 animate-slide-up">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card card-premium overflow-hidden shadow-sm">
                <div class="p-4 text-white text-center position-relative" style="background: var(--bellroy-charcoal);">
                    <h3 class="serif-title mb-1 text-white" style="font-size: 1.6rem;"><i class="bi bi-shield-lock me-2" style="color: var(--bellroy-orange);"></i>Bảo Mật Tài Khoản</h3>
                    <p class="small mb-0" style="color: #a8a49c;">Vui lòng đổi mật khẩu mặc định để tiếp tục sử dụng hệ thống</p>
                </div>
                <div class="card-body p-4 p-md-4">
                    @if(session('warning'))
                        <div class="alert alert-warning fw-bold small p-3 rounded-3 mb-3">{{ session('warning') }}</div>
                    @endif
                    <form action="{{ route('password.update.force') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="new_password" class="form-label fw-bold text-dark small">Mật khẩu mới <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-key"></i></span>
                                <input type="password" class="form-control border-start-0 @error('new_password') is-invalid @enderror" id="new_password" name="new_password" required placeholder="Tối thiểu 8 ký tự">
                                @error('new_password')
                                    <div class="invalid-feedback fw-bold">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="new_password_confirmation" class="form-label fw-bold text-dark small">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-check-circle"></i></span>
                                <input type="password" class="form-control border-start-0" id="new_password_confirmation" name="new_password_confirmation" required placeholder="Nhập lại mật khẩu mới">
                            </div>
                        </div>

                        <div class="d-grid mb-2">
                            <button type="submit" class="btn btn-premium py-2.5 justify-content-center text-center">
                                <i class="bi bi-check-lg me-1"></i> Cập Nhật Mật Khẩu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
