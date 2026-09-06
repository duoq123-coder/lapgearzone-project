@extends('layouts.app')
@section('title', 'Xác minh mã OTP - Cửa Hàng Công Nghệ')
@section('content')
<div class="container py-5 animate-slide-up">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card card-premium overflow-hidden shadow-sm">
                <div class="p-4 text-white text-center position-relative" style="background: var(--bellroy-charcoal);">
                    <h3 class="serif-title mb-1 text-white" style="font-size: 1.6rem;"><i class="bi bi-shield-check me-2" style="color: var(--bellroy-orange);"></i>Xác Minh OTP</h3>
                    <p class="small mb-0" style="color: #a8a49c;">Nhập mã xác nhận 6 chữ số đã gửi về email</p>
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

                    <div class="p-3 rounded-3 text-center mb-4 border" style="background-color: #faf9f6; border-color: var(--border-color) !important;">
                        <span class="text-muted small d-block">Mã xác nhận gửi tới:</span>
                        <strong class="text-dark fs-6">{{ $email }}</strong>
                        <div class="mt-1">
                            <a href="{{ route('password.request') }}" class="text-decoration-none small" style="color: var(--bellroy-orange);">
                                <i class="bi bi-pencil-square me-1"></i>Đổi email khác
                            </a>
                        </div>
                    </div>

                    <form action="{{ route('password.verify') }}" method="POST">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">

                        <!-- Reset Code (6 individual boxes OTP) -->
                        <div class="mb-4 text-center">
                            <label class="form-label fw-bold text-dark mb-2 small text-uppercase" style="letter-spacing: 0.5px;">
                                Mã xác nhận (6 chữ số) <span class="text-danger">*</span>
                            </label>
                            
                            <div class="otp-container d-flex justify-content-center gap-2 mb-2">
                                <input type="text" class="otp-input form-control text-center font-monospace fw-bold" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="one-time-code" required style="width: 45px; height: 50px; font-size: 24px; border-radius: 10px;">
                                <input type="text" class="otp-input form-control text-center font-monospace fw-bold" maxlength="1" pattern="[0-9]" inputmode="numeric" required style="width: 45px; height: 50px; font-size: 24px; border-radius: 10px;">
                                <input type="text" class="otp-input form-control text-center font-monospace fw-bold" maxlength="1" pattern="[0-9]" inputmode="numeric" required style="width: 45px; height: 50px; font-size: 24px; border-radius: 10px;">
                                <input type="text" class="otp-input form-control text-center font-monospace fw-bold" maxlength="1" pattern="[0-9]" inputmode="numeric" required style="width: 45px; height: 50px; font-size: 24px; border-radius: 10px;">
                                <input type="text" class="otp-input form-control text-center font-monospace fw-bold" maxlength="1" pattern="[0-9]" inputmode="numeric" required style="width: 45px; height: 50px; font-size: 24px; border-radius: 10px;">
                                <input type="text" class="otp-input form-control text-center font-monospace fw-bold" maxlength="1" pattern="[0-9]" inputmode="numeric" required style="width: 45px; height: 50px; font-size: 24px; border-radius: 10px;">
                            </div>

                            <input type="hidden" name="reset_code" id="reset_code">

                            @error('reset_code')
                                <div class="text-danger small mt-2 fw-semibold">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted small mt-2">
                                <i class="bi bi-clock-history me-1"></i>Mã có hiệu lực trong 15 phút.
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-premium py-2.5 justify-content-center text-center">
                                <i class="bi bi-check2-circle me-2"></i>Xác minh &amp; Tiếp tục
                            </button>
                        </div>
                    </form>

                    <!-- Gửi lại mã -->
                    <form action="{{ route('password.email') }}" method="POST" class="text-center mt-3 pt-3 border-top" style="border-color: var(--border-color) !important;">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        <span class="text-muted small">Chưa nhận được mã? </span>
                        <button type="submit" class="btn btn-link p-0 fw-bold text-decoration-none small" style="color: var(--bellroy-orange);">
                            Gửi lại mã mới
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const inputs = document.querySelectorAll(".otp-input");
    const hiddenInput = document.getElementById("reset_code");

    function updateHiddenInput() {
        let value = "";
        inputs.forEach(input => {
            value += input.value;
        });
        hiddenInput.value = value;
    }

    inputs.forEach((input, index) => {
        input.addEventListener("input", (e) => {
            const val = e.target.value;
            if (/[^0-9]/.test(val)) {
                e.target.value = "";
                return;
            }
            if (val && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
            updateHiddenInput();
        });

        input.addEventListener("keydown", (e) => {
            if (e.key === "Backspace" && !input.value && index > 0) {
                inputs[index - 1].focus();
            }
        });

        input.addEventListener("paste", (e) => {
            e.preventDefault();
            const pasteData = e.clipboardData.getData("text").trim();
            if (/^\d{6}$/.test(pasteData)) {
                inputs.forEach((inp, idx) => {
                    inp.value = pasteData[idx];
                });
                inputs[inputs.length - 1].focus();
                updateHiddenInput();
            }
        });
    });
});
</script>
@endsection