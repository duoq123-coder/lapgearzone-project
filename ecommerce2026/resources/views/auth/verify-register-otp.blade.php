@extends('layouts.app')

@section('title', 'Xác nhận đăng ký – LapGearZone')

@push('styles')
<style>
    .otp-wrapper {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 15px;
    }

    .otp-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.10), 0 4px 20px rgba(0, 0, 0, 0.06);
        width: 100%;
        max-width: 480px;
        overflow: hidden;
    }

    .otp-card-header {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        padding: 36px 30px 30px;
        text-align: center;
        position: relative;
    }

    .otp-card-header::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 30px;
        background: #ffffff;
        border-radius: 30px 30px 0 0;
    }

    .otp-icon-ring {
        width: 72px;
        height: 72px;
        background: rgba(255, 107, 53, 0.15);
        border: 2px solid rgba(255, 107, 53, 0.4);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        font-size: 30px;
        animation: pulseRing 2s ease-in-out infinite;
    }

    @keyframes pulseRing {
        0%, 100% { box-shadow: 0 0 0 0 rgba(255, 107, 53, 0.3); }
        50%       { box-shadow: 0 0 0 10px rgba(255, 107, 53, 0); }
    }

    .otp-card-body {
        padding: 10px 36px 36px;
    }

    /* 6 ô nhập OTP */
    .otp-inputs {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin: 28px 0 24px;
    }

    .otp-digit {
        width: 52px;
        height: 60px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 24px;
        font-weight: 700;
        text-align: center;
        color: #1a1a2e;
        background: #f9f9f9;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.15s ease;
        outline: none;
        /* Tắt spinner số */
        -moz-appearance: textfield;
        appearance: textfield;
    }
    .otp-digit::-webkit-outer-spin-button,
    .otp-digit::-webkit-inner-spin-button { display: none; }

    .otp-digit:focus {
        border-color: #ff6b35;
        box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.15);
        background: #fff;
        transform: translateY(-2px);
    }

    .otp-digit.filled {
        border-color: #ff6b35;
        background: #fff8f5;
    }

    /* Countdown */
    .countdown-bar-wrap {
        background: #f0f0f0;
        border-radius: 999px;
        height: 5px;
        overflow: hidden;
        margin-bottom: 8px;
    }

    .countdown-bar {
        height: 100%;
        background: linear-gradient(90deg, #ff6b35, #f7931e);
        border-radius: 999px;
        transition: width 1s linear;
    }

    .countdown-text {
        font-size: 13px;
        color: #888;
        text-align: center;
        margin-bottom: 20px;
    }

    .countdown-text span {
        font-weight: 700;
        color: #ff6b35;
    }

    .countdown-text.expired span {
        color: #dc3545;
    }

    /* Btn Submit */
    .btn-otp-submit {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #ff6b35, #f7931e);
        border: none;
        border-radius: 10px;
        color: #fff;
        font-size: 15px;
        font-weight: 700;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.2s, box-shadow 0.2s;
        box-shadow: 0 4px 15px rgba(255, 107, 53, 0.35);
    }

    .btn-otp-submit:hover:not(:disabled) {
        opacity: 0.92;
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(255, 107, 53, 0.45);
    }

    .btn-otp-submit:disabled {
        opacity: 0.55;
        cursor: not-allowed;
        transform: none;
    }

    /* Resend */
    .resend-area {
        text-align: center;
        margin-top: 20px;
        font-size: 14px;
        color: #888;
    }

    .resend-area form { display: inline; }

    .btn-resend {
        background: none;
        border: none;
        color: #ff6b35;
        font-weight: 600;
        cursor: pointer;
        padding: 0;
        text-decoration: underline;
        font-size: 14px;
        transition: opacity 0.2s;
    }
    .btn-resend:hover { opacity: 0.75; }
    .btn-resend:disabled { color: #aaa; cursor: not-allowed; text-decoration: none; }

    .email-badge {
        background: #f4f4f6;
        border-radius: 6px;
        padding: 6px 14px;
        font-size: 14px;
        color: #333;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 4px;
    }
</style>
@endpush

@section('content')
<div class="otp-wrapper animate-slide-up">
    <div class="otp-card">

        {{-- Header --}}
        <div class="otp-card-header">
            <div class="otp-icon-ring">📧</div>
            <h4 style="color:#fff; font-weight:800; margin:0 0 6px; font-size:1.3rem;">Xác nhận Email</h4>
            <p style="color:rgba(255,255,255,0.65); margin:0; font-size:13px;">Mã OTP đã được gửi tới</p>
            <div class="email-badge mt-2">{{ $email }}</div>
        </div>

        {{-- Body --}}
        <div class="otp-card-body">

            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="alert alert-success py-2 px-3 rounded-3 mb-3 d-flex align-items-center gap-2" style="font-size:14px;">
                    <i class="bi bi-check-circle-fill text-success"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger py-2 px-3 rounded-3 mb-3 d-flex align-items-center gap-2" style="font-size:14px;">
                    <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                </div>
            @endif
            @if ($errors->has('otp'))
                <div class="alert alert-danger py-2 px-3 rounded-3 mb-3" style="font-size:14px;">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $errors->first('otp') }}
                </div>
            @endif

            <p class="text-center text-muted mb-0" style="font-size:14px; line-height:1.6;">
                Nhập mã <strong>6 chữ số</strong> từ email của bạn.<br>
                Mã có hiệu lực trong <strong style="color:#ff6b35;">10 phút</strong>.
            </p>

            {{-- OTP Form --}}
            <form action="{{ route('register.verify.otp') }}" method="POST" id="otpForm">
                @csrf

                {{-- 6 ô số --}}
                <div class="otp-inputs" id="otpInputs">
                    @for($i = 1; $i <= 6; $i++)
                        <input
                            type="tel"
                            maxlength="1"
                            class="otp-digit"
                            id="otp_digit_{{ $i }}"
                            autocomplete="off"
                            inputmode="numeric"
                            pattern="[0-9]"
                        >
                    @endfor
                </div>

                {{-- Hidden input gom OTP --}}
                <input type="hidden" name="otp" id="otpHidden">

                {{-- Countdown bar --}}
                <div class="countdown-bar-wrap">
                    <div class="countdown-bar" id="countdownBar" style="width: 100%;"></div>
                </div>
                <div class="countdown-text" id="countdownText">
                    Còn lại: <span id="countdownDisplay">10:00</span>
                </div>

                <button type="submit" class="btn-otp-submit" id="submitBtn" disabled>
                    <i class="bi bi-shield-check me-2"></i>Xác nhận tài khoản
                </button>
            </form>

            {{-- Gửi lại OTP --}}
            <div class="resend-area">
                Chưa nhận được?
                <form action="{{ route('register.resend.otp') }}" method="POST" id="resendForm">
                    @csrf
                    <button type="submit" class="btn-resend" id="resendBtn" disabled>
                        Gửi lại mã
                    </button>
                </form>
            </div>

            {{-- Back to register --}}
            <div class="text-center mt-4 pt-3 border-top">
                <a href="{{ route('register') }}" class="text-muted text-decoration-none" style="font-size:13px;">
                    <i class="bi bi-arrow-left me-1"></i>Quay lại đăng ký
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ─── Biến ───────────────────────────────────────────
    const digits       = document.querySelectorAll('.otp-digit');
    const otpHidden    = document.getElementById('otpHidden');
    const submitBtn    = document.getElementById('submitBtn');
    const resendBtn    = document.getElementById('resendBtn');
    const countdownBar = document.getElementById('countdownBar');
    const countdownTxt = document.getElementById('countdownDisplay');
    const countdownDiv = document.getElementById('countdownText');

    const TOTAL_SECONDS = 10 * 60; // 10 phút
    const RESEND_DELAY  = 60;       // 60 giây mới cho gửi lại
    let   secondsLeft   = TOTAL_SECONDS;
    let   resendLeft    = RESEND_DELAY;
    let   timerExpired  = false;

    // ─── Focus ô đầu tiên ─────────────────────────────
    digits[0].focus();

    // ─── OTP Input Logic ──────────────────────────────
    digits.forEach(function (input, idx) {
        input.addEventListener('input', function (e) {
            // Chỉ cho phép số
            this.value = this.value.replace(/\D/g, '').slice(-1);

            if (this.value) {
                this.classList.add('filled');
                if (idx < digits.length - 1) digits[idx + 1].focus();
            } else {
                this.classList.remove('filled');
            }
            syncOtp();
        });

        input.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace' && !this.value && idx > 0) {
                digits[idx - 1].value = '';
                digits[idx - 1].classList.remove('filled');
                digits[idx - 1].focus();
                syncOtp();
            }
            // Paste handler (xử lý dán chuỗi 6 số)
            if (e.key === 'v' && (e.ctrlKey || e.metaKey)) return;
        });

        input.addEventListener('paste', function (e) {
            e.preventDefault();
            const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
            if (pasted.length === 6) {
                digits.forEach(function (d, i) {
                    d.value = pasted[i] || '';
                    d.classList.toggle('filled', !!d.value);
                });
                digits[5].focus();
                syncOtp();
            }
        });
    });

    function syncOtp() {
        const val = Array.from(digits).map(d => d.value).join('');
        otpHidden.value = val;
        submitBtn.disabled = (val.length !== 6 || timerExpired);
    }

    // ─── Countdown timer ──────────────────────────────
    const timerInterval = setInterval(function () {
        secondsLeft--;
        resendLeft  = Math.max(0, resendLeft - 1);

        // Cập nhật progress bar
        const pct = (secondsLeft / TOTAL_SECONDS) * 100;
        countdownBar.style.width = pct + '%';

        if (pct <= 30) {
            countdownBar.style.background = 'linear-gradient(90deg, #dc3545, #ff6b35)';
        }

        // Cập nhật text
        const m = Math.floor(secondsLeft / 60);
        const s = secondsLeft % 60;
        countdownTxt.textContent = m + ':' + String(s).padStart(2, '0');

        // Cho phép gửi lại sau RESEND_DELAY giây
        if (resendLeft <= 0) {
            resendBtn.disabled = false;
        }

        // Hết thời gian
        if (secondsLeft <= 0) {
            clearInterval(timerInterval);
            timerExpired = true;
            submitBtn.disabled = true;
            countdownDiv.classList.add('expired');
            countdownTxt.textContent = 'Hết hạn!';
            countdownBar.style.width = '0%';
        }
    }, 1000);

    // ─── Auto submit khi nhập đủ 6 số ────────────────
    document.getElementById('otpForm').addEventListener('input', function () {
        const val = Array.from(digits).map(d => d.value).join('');
        if (val.length === 6 && !timerExpired) {
            // Delay nhỏ để user thấy số cuối trước khi submit
            setTimeout(function () {
                if (Array.from(digits).map(d => d.value).join('').length === 6) {
                    document.getElementById('otpForm').submit();
                }
            }, 400);
        }
    });
});
</script>
@endpush
