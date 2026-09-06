@extends('layouts.app')

@section('title', 'Góp ý của khách hàng - LapGearZone')

@section('content')
<div class="container py-4 animate-slide-up">
    <!-- Header Page -->
    <div class="text-center mb-5">
        <span class="badge badge-terracotta mb-2">LẮNG NGHE &amp; ĐỒNG HÀNH</span>
        <h1 class="serif-title mb-2 text-dark" style="font-size: 2.3rem;">
            Góp Ý &amp; Phản Hồi Khách Hàng
        </h1>
        <p class="text-muted mx-auto" style="max-width: 650px; font-size: 0.95rem; line-height: 1.7;">
            Ý kiến của bạn là động lực giúp LapGearZone ngày càng hoàn thiện trải nghiệm. Hãy chia sẻ cảm nhận hoặc đề xuất, chúng tôi sẽ phản hồi sớm nhất!
        </p>
    </div>

    <!-- 1. Form Gửi Góp Ý -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-8 col-xl-7">
            <div class="card card-premium p-4 p-md-5">
                <div class="d-flex align-items-center gap-2 mb-4 pb-2 border-bottom" style="border-color: var(--border-color) !important;">
                    <i class="bi bi-pencil-square fs-4" style="color: var(--bellroy-orange);"></i>
                    <h4 class="serif-title mb-0" style="font-size: 1.3rem; color: var(--text-main);">
                        Gửi phản hồi / Góp ý mới
                    </h4>
                </div>

                <form action="{{ route('contact.store') }}" method="POST" id="contactForm" novalidate>
                    @csrf
                    <!-- Họ và tên -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small" style="color: var(--text-main);">Họ và tên quý khách <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" placeholder="Nhập họ và tên..." value="{{ old('name', Auth::check() ? Auth::user()->name : '') }}" required style="font-size: 0.92rem; background-color: var(--card-bg); color: var(--text-main); border-color: var(--border-color);">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Số điện thoại -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small" style="color: var(--text-main);">
                            Số điện thoại liên hệ <span class="text-danger">*</span>
                        </label>
                        <input type="tel" name="phone" id="phoneInput" 
                               class="form-control form-control-lg @error('phone') is-invalid @enderror" 
                               placeholder="VD: 0345678901" 
                               value="{{ old('phone', Auth::check() ? (Auth::user()->phone ?? '') : '') }}" 
                               required 
                               pattern="^(0[35789])[0-9]{8}$"
                               maxlength="10"
                               style="font-size: 0.92rem; background-color: var(--card-bg); color: var(--text-main); border-color: var(--border-color);">
                        @error('phone')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div class="invalid-feedback" id="phoneError" style="display: none;">
                            Số điện thoại không hợp lệ (Vui lòng nhập SĐT Việt Nam gồm 10 chữ số).
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small" style="color: var(--text-main);">
                            Email nhận phản hồi <span class="text-danger">*</span>
                        </label>
                        <input type="email" name="email" id="emailInput" 
                               class="form-control form-control-lg @error('email') is-invalid @enderror" 
                               placeholder="VD: email@example.com" 
                               value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}" 
                               required 
                               style="font-size: 0.92rem; background-color: var(--card-bg); color: var(--text-main); border-color: var(--border-color);">
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div class="invalid-feedback" id="emailError" style="display: none;">
                            Địa chỉ email không đúng định dạng.
                        </div>
                    </div>

                    <!-- Nội dung góp ý -->
                    <div class="mb-4">
                        <label class="form-label fw-bold small" style="color: var(--text-main);">Nội dung góp ý / Ý kiến đóng góp <span class="text-danger">*</span></label>
                        <textarea name="message" class="form-control @error('message') is-invalid @enderror" rows="4" placeholder="Chia sẻ trải nghiệm sử dụng, thắc mắc dịch vụ hoặc đề xuất sản phẩm..." required style="font-size: 0.92rem; resize: none; background-color: var(--card-bg); color: var(--text-main); border-color: var(--border-color);">{{ old('message') }}</textarea>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-premium w-100 py-3 justify-content-center" id="contactSubmitBtn">
                        <i class="bi bi-send me-2"></i> Gửi ý kiến đóng góp
                    </button>
                </form>
            </div>
        </div>
    </div>


    <!-- 2. Danh Sách Ý Kiến Đã Góp Ý & Phản Hồi Từ Admin -->
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            <div class="card card-premium p-4 p-md-5">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4 pb-3 border-bottom" style="border-color: var(--border-color) !important;">
                    <div>
                        <h4 class="serif-title mb-1 text-dark" style="font-size: 1.3rem;">
                            <i class="bi bi-chat-dots text-dark me-2"></i>Lịch Sử Góp Ý &amp; Phản Hồi
                        </h4>
                        <p class="text-muted small mb-0">Theo dõi tiến độ giải đáp trực tiếp từ Ban Quản Trị</p>
                    </div>
                    @if(isset($userContacts) && $userContacts->isNotEmpty())
                        <span class="badge badge-terracotta">
                            {{ $userContacts->count() }} ý kiến đã gửi
                        </span>
                    @endif
                </div>

                @if(isset($userContacts) && $userContacts->isNotEmpty())
                    <div class="d-flex flex-column gap-3">
                        @foreach($userContacts as $item)
                            <div class="border rounded-3 p-4" style="background: var(--card-bg); border-color: var(--border-color) !important;">
                                <!-- Tiêu đề góp ý -->
                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 pb-2 border-bottom" style="border-color: var(--border-color-subtle) !important;">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-light text-dark border rounded-pill px-2 py-1 font-monospace" style="font-size: 0.75rem;">
                                            #{{ $item->id }}
                                        </span>
                                        <span class="text-muted small">
                                            <i class="bi bi-clock me-1"></i>{{ $item->created_at->format('d/m/Y H:i') }}
                                        </span>
                                    </div>

                                    @if($item->admin_reply)
                                        <span class="badge badge-sage">
                                            <i class="bi bi-check-circle-fill me-1"></i>Đã giải đáp
                                        </span>
                                    @else
                                        <span class="badge badge-terracotta">
                                            <i class="bi bi-hourglass-split me-1"></i>Đang xử lý
                                        </span>
                                    @endif
                                </div>

                                <!-- Nội dung góp ý của khách -->
                                <div class="mb-3">
                                    <div class="text-secondary small fw-bold mb-1">
                                        Ý kiến của bạn:
                                    </div>
                                    <div class="p-3 rounded-3" style="background-color: var(--surface-muted); color: var(--text-main) !important; font-size: 0.92rem; line-height: 1.6; border: 1px solid var(--border-color);">
                                        {{ $item->message }}
                                    </div>
                                </div>

                                <!-- Phản hồi của Admin (nếu có) -->
                                @if($item->admin_reply)
                                    <div class="p-3 p-md-4 rounded-3 text-white shadow-sm mt-3" style="background: var(--bellroy-charcoal);">
                                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom" style="border-color: rgba(255,255,255,0.1) !important;">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-patch-check-fill" style="color: var(--bellroy-orange);"></i>
                                                <span class="fw-bold text-white small">Phản hồi từ Ban Quản Trị:</span>
                                            </div>
                                            <span class="small" style="color: #a8a49c;">
                                                {{ $item->admin_replied_at ? $item->admin_replied_at->format('d/m/Y H:i') : '' }}
                                            </span>
                                        </div>
                                        <p class="mb-0 text-light" style="font-size: 0.9rem; line-height: 1.7; white-space: pre-line;">{{ $item->admin_reply }}</p>
                                    </div>
                                @else
                                    <div class="alert rounded-3 border-0 d-flex align-items-center gap-2 mb-0 py-2 px-3 small" style="background-color: var(--bellroy-sage-subtle); color: var(--bellroy-sage);">
                                        <i class="bi bi-info-circle-fill"></i>
                                        <span>Góp ý của bạn đã được ghi nhận. Ban Quản Trị sẽ phản hồi sớm nhất tại đây.</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Trạng thái chưa có góp ý nào -->
                    <div class="text-center py-5 text-muted">
                        <div class="mb-3">
                            <i class="bi bi-chat-square-text opacity-40 display-4"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Chưa có ý kiến góp ý nào được lưu</h6>
                        <p class="small text-muted mb-0">Sau khi bạn gửi góp ý qua form ở trên, toàn bộ nội dung và phản hồi từ Admin sẽ được lưu lại tại khu vực này.</p>
                    </div>
                @endif
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