@extends('layouts.app')

@section('title', 'Góp ý của khách hàng')

@section('content')
<div class="container py-4 animate-slide-up">
    <!-- Header Page with Accent Gradient -->
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold mb-2 display-font" style="background: linear-gradient(135deg, #1B2838 0%, #F5B041 50%, #D4941F 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
            <i class="bi bi-chat-square-heart-fill me-2 text-primary" style="background: none; -webkit-text-fill-color: initial;"></i>Góp Ý Của Khách Hàng
        </h1>
        <p class="lead text-secondary mx-auto" style="max-width: 600px; font-size: 1.05rem;">
            Ý kiến của bạn là động lực giúp Shop chúng mình ngày càng phát triển. Hãy gửi những trải nghiệm hoặc góp ý của bạn nhé!
        </p>
    </div>

    <div class="row g-4">
    

        <!-- Cột phải: Form Gửi Góp Ý -->
        <div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card card-premium p-4">
            <h4 class="fw-bold mb-4 text-dark display-font gold-underline" style="letter-spacing: -0.3px;">
                <i class="bi bi-pencil-square text-primary me-2"></i>Gửi phản hồi của bạn
            </h4>
            <form action="{{ route('contact.store') }}" method="POST" id="contactForm" novalidate>
                @csrf
                <!-- Họ và tên -->
                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary small">Họ và tên quý khách</label>
                    <input type="text" name="name" class="form-control form-control-lg border-2 @error('name') is-invalid @enderror" placeholder="Nhập họ và tên..." value="{{ old('name', Auth::check() ? Auth::user()->name : '') }}" required style="border-color: #eaeaea; background-color: #ffffff; color: #222222; border-radius: 12px; font-size: 0.95rem;">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Số điện thoại -->
                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary small">
                        <i class="bi bi-telephone me-1 text-primary"></i>Số điện thoại
                    </label>
                    <input type="tel" name="phone" id="phoneInput" 
                           class="form-control form-control-lg border-2 @error('phone') is-invalid @enderror" 
                           placeholder="VD: 0345678901" 
                           value="{{ old('phone', Auth::check() ? (Auth::user()->phone ?? '') : '') }}" 
                           required 
                           pattern="^(0[35789])[0-9]{8}$"
                           maxlength="10"
                           style="border-color: #eaeaea; background-color: #ffffff; color: #222222; border-radius: 12px; font-size: 0.95rem;">
                    @error('phone')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <div class="invalid-feedback" id="phoneError" style="display: none;">
                        Số điện thoại không hợp lệ. Vui lòng nhập SĐT Việt Nam (VD: 0345678901).
                    </div>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary small">
                        <i class="bi bi-envelope me-1 text-primary"></i>Email liên hệ
                    </label>
                    <input type="email" name="email" id="emailInput" 
                           class="form-control form-control-lg border-2 @error('email') is-invalid @enderror" 
                           placeholder="VD: email@example.com" 
                           value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}" 
                           required 
                           style="border-color: #eaeaea; background-color: #ffffff; color: #222222; border-radius: 12px; font-size: 0.95rem;">
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <div class="invalid-feedback" id="emailError" style="display: none;">
                        Địa chỉ email không hợp lệ.
                    </div>
                </div>

                <!-- Nội dung góp ý -->
                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary small">Nội dung góp ý / Ý kiến đóng góp</label>
                    <textarea name="message" class="form-control border-2 @error('message') is-invalid @enderror" rows="4" placeholder="Chia sẻ trải nghiệm hoặc đề xuất chi tiết của bạn tại đây..." required style="border-color: #eaeaea; background-color: #ffffff; color: #222222; border-radius: 12px; font-size: 0.95rem; resize: none;">{{ old('message') }}</textarea>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-premium w-100 py-3 justify-content-center mt-2" id="contactSubmitBtn">
                    <i class="bi bi-send-fill me-2"></i> Gửi góp ý ngay
                </button>
            </form>
        </div>
    </div>
</div>
    </div>
</div>

@push('scripts')
<script>
    (function() {
        const phoneInput = document.getElementById('phoneInput');
        const emailInput = document.getElementById('emailInput');
        const phoneError = document.getElementById('phoneError');
        const emailError = document.getElementById('emailError');
        const form = document.getElementById('contactForm');

        const phoneRegex = /^(0[35789])[0-9]{8}$/;
        const emailRegex = /^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/;

        function validatePhone() {
            const val = phoneInput.value.trim();
            if (val && !phoneRegex.test(val)) {
                phoneInput.classList.add('is-invalid');
                phoneInput.classList.remove('is-valid');
                phoneError.style.display = 'block';
                return false;
            } else if (val) {
                phoneInput.classList.remove('is-invalid');
                phoneInput.classList.add('is-valid');
                phoneError.style.display = 'none';
                return true;
            } else {
                phoneInput.classList.remove('is-invalid', 'is-valid');
                phoneError.style.display = 'none';
                return false;
            }
        }

        function validateEmail() {
            const val = emailInput.value.trim();
            if (val && !emailRegex.test(val)) {
                emailInput.classList.add('is-invalid');
                emailInput.classList.remove('is-valid');
                emailError.style.display = 'block';
                return false;
            } else if (val) {
                emailInput.classList.remove('is-invalid');
                emailInput.classList.add('is-valid');
                emailError.style.display = 'none';
                return true;
            } else {
                emailInput.classList.remove('is-invalid', 'is-valid');
                emailError.style.display = 'none';
                return false;
            }
        }

        phoneInput.addEventListener('input', validatePhone);
        phoneInput.addEventListener('blur', validatePhone);
        emailInput.addEventListener('input', validateEmail);
        emailInput.addEventListener('blur', validateEmail);

        form.addEventListener('submit', function(e) {
            const phoneOk = validatePhone();
            const emailOk = validateEmail();
            if (!phoneOk || !emailOk) {
                e.preventDefault();
                if (!phoneOk) phoneInput.focus();
                else if (!emailOk) emailInput.focus();
            }
        });
    })();
</script>
@endpush
@endsection