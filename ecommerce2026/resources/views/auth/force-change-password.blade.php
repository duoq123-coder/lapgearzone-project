@extends('layouts.app')
@section('title', 'Đổi mật khẩu bảo mật')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden mt-4">
            <div class="p-4 text-white text-center position-relative" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                <h3 class="fw-bold mb-1 position-relative"><i class="bi bi-shield-lock-fill me-2 text-warning"></i>Bảo Mật Tài Khoản</h3>
                <p class="small mb-0 position-relative">Bạn cần đổi mật khẩu mặc định để tiếp tục.</p>
            </div>
            <div class="card-body p-4 bg-light">
                @if(session('warning'))
                    <div class="alert alert-warning fw-bold">{{ session('warning') }}</div>
                @endif
                <form action="{{ route('password.update.force') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="new_password" class="form-label fw-bold text-dark">Mật khẩu mới <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-key-fill text-muted"></i></span>
                            <input type="password" class="form-control border-start-0 @error('new_password') is-invalid @enderror" id="new_password" name="new_password" required placeholder="Tối thiểu 8 ký tự">
                            @error('new_password')
                                <div class="invalid-feedback fw-bold">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="new_password_confirmation" class="form-label fw-bold text-dark">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-check-circle-fill text-muted"></i></span>
                            <input type="password" class="form-control border-start-0" id="new_password_confirmation" name="new_password_confirmation" required placeholder="Nhập lại mật khẩu mới">
                        </div>
                    </div>

                    <div class="text-center mb-2">
                        <button type="submit" class="btn btn-danger px-4 py-2 w-100 fw-bold shadow-sm">
                            <i class="bi bi-check-lg me-1"></i> Đổi Mật Khẩu & Tiếp Tục
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
