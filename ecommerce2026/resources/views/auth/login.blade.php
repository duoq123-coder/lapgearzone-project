@extends('layouts.app')

@php
    // Tự động kiểm tra xem đang ở route register hay có lỗi từ form register không
    $isRegister = request()->routeIs('register') || $errors->has('name') || $errors->has('password_confirmation');
    $pageTitle = $isRegister ? 'Đăng ký tài khoản' : 'Đăng nhập';
@endphp

@section('title', $pageTitle . ' - Cửa Hàng Công Nghệ')

@push('styles')
<style>
    :root {
        --anim-duration: 0.6s;
    }
    
    .sliding-auth-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 200px);
    }

    .auth-container {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 14px 28px rgba(0,0,0,0.1), 0 10px 10px rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
        width: 100%;
        max-width: 900px;
        min-height: 550px;
    }

    .form-container {
        position: absolute;
        top: 0;
        height: 100%;
        transition: all var(--anim-duration) ease-in-out;
    }
    
    .sign-in-container {
        left: 0;
        width: 50%;
        z-index: 2;
    }
    
    .auth-container.right-panel-active .sign-in-container {
        transform: translateX(100%);
        opacity: 0;
    }
    
    .sign-up-container {
        left: 0;
        width: 50%;
        opacity: 0;
        z-index: 1;
    }
    
    .auth-container.right-panel-active .sign-up-container {
        transform: translateX(100%);
        opacity: 1;
        z-index: 5;
        animation: show var(--anim-duration);
    }
    
    @keyframes show {
        0%, 49.99% { opacity: 0; z-index: 1; }
        50%, 100% { opacity: 1; z-index: 5; }
    }
    
    .overlay-container {
        position: absolute;
        top: 0;
        left: 50%;
        width: 50%;
        height: 100%;
        overflow: hidden;
        transition: transform var(--anim-duration) ease-in-out;
        z-index: 100;
    }
    
    .auth-container.right-panel-active .overlay-container {
        transform: translateX(-100%);
    }
    
    .overlay {
        background: var(--bellroy-charcoal);
        background: linear-gradient(to right, #2a2d34, var(--bellroy-charcoal));
        background-repeat: no-repeat;
        background-size: cover;
        background-position: 0 0;
        color: #FFFFFF;
        position: relative;
        left: -100%;
        height: 100%;
        width: 200%;
        transform: translateX(0);
        transition: transform var(--anim-duration) ease-in-out;
    }
    
    .auth-container.right-panel-active .overlay {
        transform: translateX(50%);
    }
    
    .overlay-panel {
        position: absolute;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding: 0 40px;
        text-align: center;
        top: 0;
        height: 100%;
        width: 50%;
        transform: translateX(0);
        transition: transform var(--anim-duration) ease-in-out;
    }
    
    .overlay-left {
        transform: translateX(-20%);
    }
    
    .auth-container.right-panel-active .overlay-left {
        transform: translateX(0);
    }
    
    .overlay-right {
        right: 0;
        transform: translateX(0);
    }
    
    .auth-container.right-panel-active .overlay-right {
        transform: translateX(20%);
    }
    
    .btn-ghost {
        border-radius: 8px;
        border: 1px solid #FFFFFF;
        background-color: transparent;
        color: #FFFFFF;
        font-weight: bold;
        padding: 12px 45px;
        letter-spacing: 1px;
        text-transform: uppercase;
        transition: 0.3s ease-in-out;
    }
    
    .btn-ghost:hover {
        background-color: var(--bellroy-orange);
        border-color: var(--bellroy-orange);
    }

    .mobile-switch {
        display: none;
    }

    /* Responsive cho điện thoại (tắt trượt, chuyển sang đổi tab) */
    @media (max-width: 768px) {
        .auth-container {
            min-height: auto;
            overflow: visible;
            box-shadow: none;
        }
        .overlay-container {
            display: none;
        }
        .form-container {
            position: relative;
            width: 100%;
            opacity: 1;
            transform: none !important;
            transition: none;
            padding: 15px !important;
        }
        .sign-up-container { display: none; }
        .auth-container.right-panel-active .sign-up-container { display: block; }
        .auth-container.right-panel-active .sign-in-container { display: none; }
        .mobile-switch { display: block; }
    }
</style>
@endpush

@section('content')
<div class="container py-4 py-md-5 animate-slide-up">
    <div class="sliding-auth-wrapper">
        <div class="auth-container {{ $isRegister ? 'right-panel-active' : '' }}" id="authContainer">
            
            <!-- FORM ĐĂNG KÝ -->
            <div class="form-container sign-up-container">
                <form action="{{ url('register') }}" method="POST" class="d-flex flex-column justify-content-center h-100 p-3 p-md-5 bg-white">
                    @csrf
                    <h3 class="serif-title mb-4 text-center" style="color: var(--bellroy-charcoal); font-size: 1.8rem;">
                        <i class="bi bi-person-plus me-2" style="color: var(--bellroy-orange);"></i>Tạo Tài Khoản
                    </h3>
                    
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" class="form-control bg-light border-start-0 @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Họ và Tên" required>
                        </div>
                        @error('name') <div class="text-danger small mt-1 fw-bold">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control bg-light border-start-0 @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Địa chỉ Email" required>
                        </div>
                        @error('email') <div class="text-danger small mt-1 fw-bold">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-key"></i></span>
                            <input type="password" name="password" id="reg_password" class="form-control bg-light border-x-0 @error('password') is-invalid @enderror" placeholder="Mật khẩu (Tối thiểu 8 ký tự)" required>
                            <button type="button" class="input-group-text bg-light border-start-0 text-muted toggle-password" data-target="reg_password" title="Hiện/Ẩn mật khẩu" style="cursor:pointer;">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password') <div class="text-danger small mt-1 fw-bold">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-shield-check"></i></span>
                            <input type="password" name="password_confirmation" id="reg_password_confirmation" class="form-control bg-light border-x-0" placeholder="Xác nhận mật khẩu" required>
                            <button type="button" class="input-group-text bg-light border-start-0 text-muted toggle-password" data-target="reg_password_confirmation" title="Hiện/Ẩn mật khẩu" style="cursor:pointer;">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-premium w-100 py-2.5">
                        Đăng ký tài khoản <i class="bi bi-arrow-right ms-1"></i>
                    </button>

                    <!-- Chuyển form trên Mobile -->
                    <div class="text-center mt-4 mobile-switch pt-3 border-top">
                        <span class="text-muted small">Đã có tài khoản? </span>
                        <a href="javascript:void(0)" class="fw-bold text-decoration-none small trigger-login" style="color: var(--bellroy-orange);">Đăng nhập ngay</a>
                    </div>
                </form>
            </div>

            <!-- FORM ĐĂNG NHẬP -->
            <div class="form-container sign-in-container">
                <form action="{{ route('login') }}" method="POST" class="d-flex flex-column justify-content-center h-100 p-3 p-md-5 bg-white">
                    @csrf
                    <h3 class="serif-title mb-4 text-center" style="color: var(--bellroy-charcoal); font-size: 1.8rem;">
                        <i class="bi bi-shield-lock me-2" style="color: var(--bellroy-orange);"></i>Đăng Nhập
                    </h3>

                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                            <input type="text" name="login_id" class="form-control bg-light border-start-0 @error('login_id') is-invalid @enderror" value="{{ old('login_id') }}" placeholder="CCCD, Email hoặc SĐT" required>
                        </div>
                        @error('login_id') <div class="text-danger small mt-1 fw-bold">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-key"></i></span>
                            <input type="password" name="password" id="login_password" class="form-control bg-light border-x-0 @error('password') is-invalid @enderror" placeholder="Nhập mật khẩu" required>
                            <button type="button" class="input-group-text bg-light border-start-0 text-muted toggle-password" data-target="login_password" title="Hiện/Ẩn mật khẩu" style="cursor:pointer;">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password') <div class="text-danger small mt-1 fw-bold">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4 text-end">
                        <a href="{{ route('password.request') }}" class="text-decoration-none small fw-semibold" style="color: var(--bellroy-orange);">Quên mật khẩu?</a>
                    </div>

                    <button type="submit" class="btn btn-premium w-100 py-2.5">
                        Đăng nhập <i class="bi bi-arrow-right ms-1"></i>
                    </button>

                    <!-- Chuyển form trên Mobile -->
                    <div class="text-center mt-4 mobile-switch pt-3 border-top">
                        <span class="text-muted small">Chưa có tài khoản? </span>
                        <a href="javascript:void(0)" class="fw-bold text-decoration-none small trigger-register" style="color: var(--bellroy-orange);">Đăng ký ngay</a>
                    </div>
                </form>
            </div>

            <!-- OVERLAY (LỚP TRƯỢT ĐỂ CHUYỂN ĐỔI) -->
            <div class="overlay-container d-none d-md-block">
                <div class="overlay">
                    
                    <!-- BẢNG BÊN TRÁI -->
                    <div class="overlay-panel overlay-left">
                        <!-- Đã thay Icon CPU bằng Logo của bạn ở đây -->
                        <div class="text-center mb-4">
                            <img src="{{ asset('images/logo.png') }}" alt="lapgearzone" height="60" style="border-radius: 6px; filter: brightness(0) invert(1);">
                        </div>
                        <h2 class="serif-title mb-3 fw-bold text-white">Chào mừng bạn đến với LapGearZone!</h2>
                        <p class="mb-4 opacity-75 px-2">Đăng nhập để tiếp tục mua sắm, theo dõi tiến độ sửa chữa thiết bị và quản lý sổ bảo hành của bạn.</p>
                        
                        <button class="btn btn-ghost trigger-login">Đăng Nhập Ngay</button>
                    </div>
                    
                    <!-- BẢNG BÊN PHẢI -->
                    <div class="overlay-panel overlay-right">
                        <div class="text-center mb-3">
                            <i class="bi bi-stars" style="font-size: 2.5rem; color: var(--bellroy-orange);"></i>
                        </div>
                        <h2 class="serif-title mb-3 fw-bold text-white">Khách hàng mới?</h2>
                        <p class="mb-3 opacity-75">Tạo tài khoản ngay hôm nay để nhận các đặc quyền dành riêng cho thành viên.</p>
                        
                        <!-- Danh sách các đặc quyền -->
                        <ul class="list-unstyled text-start mb-4 mx-auto opacity-75" style="max-width: 250px; font-size: 0.9rem;">
                            <li class="mb-2"><i class="bi bi-check2-circle me-2 fw-bold" style="color: var(--bellroy-orange);"></i>Dễ dàng đặt lịch sửa chữa laptop</li>
                            <li class="mb-2"><i class="bi bi-check2-circle me-2 fw-bold" style="color: var(--bellroy-orange);"></i>Tra cứu lịch sử bảo hành trực tuyến</li>
                        </ul>

                        <button class="btn btn-ghost trigger-register">Tạo Tài Khoản</button>
                    </div>
                    
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.getElementById('authContainer');
        const triggerRegister = document.querySelectorAll('.trigger-register');
        const triggerLogin = document.querySelectorAll('.trigger-login');

        // Hàm thay đổi URL trên thanh địa chỉ mà không cần tải lại trang
        const updateUrl = (url) => {
            if (window.history.pushState) {
                window.history.pushState({}, '', url);
            }
        };

        // Bắt sự kiện bấm nút Đăng ký
        triggerRegister.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                container.classList.add("right-panel-active");
                document.title = 'Đăng ký tài khoản - Cửa Hàng Công Nghệ';
                updateUrl('{{ route("register") }}');
            });
        });

        // Bắt sự kiện bấm nút Đăng nhập
        triggerLogin.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                container.classList.remove("right-panel-active");
                document.title = 'Đăng nhập - Cửa Hàng Công Nghệ';
                updateUrl('{{ route("login") }}');
            });
        });

        // Toggle hiện/ẩn mật khẩu
        document.querySelectorAll('.toggle-password').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');
                if (!input) return;
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                    this.title = 'Ẩn mật khẩu';
                } else {
                    input.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                    this.title = 'Hiện mật khẩu';
                }
            });
        });
    });
</script>
@endpush