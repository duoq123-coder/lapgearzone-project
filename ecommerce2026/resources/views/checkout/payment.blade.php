@extends('layouts.app')
@section('title', 'Thanh toán đơn hàng #' . $order->id)

@section('content')
<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card card-premium border-0 shadow-lg p-4 p-md-5">
                <div class="card-body p-0">
                    <div class="mb-3">
                        <span class="badge badge-sage px-3 py-2 rounded-pill fw-bold" id="statusBadge">
                            <i class="bi bi-clock-history me-1"></i> ĐANG CHỜ THANH TOÁN
                        </span>
                    </div>

                    <h3 class="fw-bold text-dark display-font mb-2">Đơn hàng #{{ $order->id }}</h3>
                    <p class="text-secondary small mb-4">
                        Tổng tiền: <strong class="text-danger fs-5">{{ number_format($order->total_price, 0, ',', '.') }} đ</strong>
                    </p>

                    @php
                        $amount = intval($order->total_price);
                        $addInfo = 'Thanh toan DH' . $order->id;
                    @endphp

                    <!-- Thẻ cổng thanh toán PayOS -->
                    <div class="p-4 rounded-4 bg-light border border-light-subtle mb-4">
                        <div class="mb-3">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-white shadow-sm" style="width: 64px; height: 64px; color: var(--bellroy-orange);">
                                <i class="bi bi-credit-card-2-front-fill fs-2"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">Cổng thanh toán PayOS</h5>
                        <p class="text-muted small mb-3">Tự động đối soát và xác nhận đơn hàng ngay khi nhận được thanh toán.</p>

                        @if(!empty($payOSCheckoutUrl))
                        <a href="{{ $payOSCheckoutUrl }}" class="btn btn-premium btn-lg w-100 py-3 shadow-sm mb-2" id="btnPayOSCheckout">
                            <i class="bi bi-box-arrow-up-right me-2"></i> Chuyển đến Cổng thanh toán PayOS
                        </a>
                        <div class="text-muted small" style="font-size: 0.82rem;">
                            Đang tự động chuyển hướng trong <span id="countdown">3</span> giây...
                        </div>
                        @endif
                    </div>

                    <div class="alert alert-info border-0 rounded-4 text-start small mb-4" style="background-color: var(--bellroy-sage-subtle); color: var(--bellroy-sage);">
                        <div class="fw-bold mb-1"><i class="bi bi-info-circle-fill me-1"></i> Thông tin giao dịch PayOS:</div>
                        <div>• Ngân hàng: <strong>{{ \App\Models\Setting::getValue('bank_name', 'MB Bank (Quân Đội)') }}</strong></div>
                        <div>• Số tài khoản: <strong>{{ \App\Models\Setting::getValue('bank_account_number', '03468844158888') }}</strong></div>
                        <div>• Chủ tài khoản: <strong>{{ \App\Models\Setting::getValue('bank_account_name', 'NGUYEN QUY DUONG') }}</strong></div>
                        <div>• Số tiền: <strong>{{ number_format($amount, 0, ',', '.') }} đ</strong></div>
                        <div>• Nội dung chuyển khoản: <strong class="text-danger">{{ $addInfo }}</strong></div>
                        <div class="mt-2 text-muted" style="font-size: 0.8rem;">
                            <em>* Hệ thống PayOS sẽ tự động nhận diện và kích hoạt đơn hàng trong vài giây sau khi chuyển tiền.</em>
                        </div>
                    </div>

                    <div id="pollingStatus" class="d-flex align-items-center justify-content-center gap-2 text-secondary small mb-3">
                        <div class="spinner-border spinner-border-sm text-warning" role="status"></div>
                        <span>Đang lắng nghe giao dịch thanh toán...</span>
                    </div>

                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('welcome') }}" class="btn btn-outline-secondary rounded-pill px-4">
                            Về trang chủ
                        </a>
                        <a href="{{ route('profile.index') }}" class="btn btn-outline-dark rounded-pill px-4">
                            Đơn hàng của tôi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    @if(!empty($payOSCheckoutUrl))
    // Tự động chuyển hướng sau 3 giây nếu người dùng chưa bấm
    let timeLeft = 3;
    const countdownEl = document.getElementById('countdown');
    const timer = setInterval(function() {
        timeLeft--;
        if (countdownEl) countdownEl.innerText = timeLeft;
        if (timeLeft <= 0) {
            clearInterval(timer);
            window.location.href = "{{ $payOSCheckoutUrl }}";
        }
    }, 1000);
    @endif

    // Tự động kiểm tra trạng thái thanh toán đơn hàng mỗi 3 giây
    const orderId = {{ $order->id }};
    const checkStatusUrl = "{{ route('api.payos.check-status', $order->id) }}";
    const successUrl = "{{ route('checkout.payos.success', ['order_id' => $order->id]) }}";

    let checkInterval = setInterval(function() {
        fetch(checkStatusUrl)
            .then(res => res.json())
            .then(data => {
                if (data.is_paid) {
                    clearInterval(checkInterval);
                    const pollingEl = document.getElementById('pollingStatus');
                    const badgeEl = document.getElementById('statusBadge');
                    if (pollingEl) {
                        pollingEl.innerHTML = '<span class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i> Đã thanh toán thành công! Đang chuyển hướng...</span>';
                    }
                    if (badgeEl) {
                        badgeEl.className = 'badge bg-success px-3 py-2 rounded-pill fw-bold';
                        badgeEl.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> ĐÃ THANH TOÁN';
                    }
                    setTimeout(function() {
                        window.location.href = successUrl;
                    }, 1200);
                }
            })
            .catch(err => console.log('Checking status...'));
    }, 3000);
</script>
@endpush
