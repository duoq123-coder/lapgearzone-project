@extends('layouts.app')
@section('title', 'Thanh toán thành công')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card card-premium border-0 shadow-lg p-4 p-md-5 text-center">
                <div class="mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 84px; height: 84px; background: rgba(78, 121, 105, 0.12); color: var(--bellroy-sage);">
                        <i class="bi bi-check-circle-fill" style="font-size: 3.2rem;"></i>
                    </div>
                </div>

                <span class="badge badge-sage px-3 py-2 rounded-pill fw-bold mb-2 align-self-center">
                    <i class="bi bi-shield-check me-1"></i> XÁC NHẬN TỰ ĐỘNG THÀNH CÔNG
                </span>

                <h2 class="fw-bold text-dark display-font mb-2">Thanh toán hoàn tất!</h2>
                <p class="text-secondary mb-4">
                    Hệ thống PayOS đã tự động ghi nhận thanh toán cho đơn hàng <strong>#{{ $order->id }}</strong> của bạn.
                </p>

                <div class="bg-light rounded-4 p-4 mb-4 text-start border border-light-subtle">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">Mã đơn hàng:</span>
                        <strong class="text-dark">#{{ $order->id }}</strong>
                    </div>
                    @if($order->payos_order_code)
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">Mã giao dịch PayOS:</span>
                        <code class="text-dark">{{ $order->payos_order_code }}</code>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">Số tiền thanh toán:</span>
                        <strong class="text-success fs-5">{{ number_format($order->total_price, 0, ',', '.') }} đ</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">Phương thức:</span>
                        <span class="badge bg-dark text-white">Cổng thanh toán PayOS (MB Bank)</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">Trạng thái đơn:</span>
                        <span class="badge badge-sage text-uppercase"><i class="bi bi-check2 me-1"></i> Đã thanh toán (paid)</span>
                    </div>
                    <hr class="my-3">
                    <div class="small text-muted">
                        <i class="bi bi-geo-alt me-1"></i> Giao đến: {{ $order->address }} (SĐT: {{ $order->phone }})
                    </div>
                </div>

                <div class="d-grid gap-2 d-sm-flex justify-content-center">
                    <a href="{{ route('welcome') }}" class="btn btn-premium px-4 py-2">
                        <i class="bi bi-house-door me-1"></i> Tiếp tục mua sắm
                    </a>
                    <a href="{{ route('profile.index') }}" class="btn btn-outline-dark px-4 py-2">
                        <i class="bi bi-bag-check me-1"></i> Quản lý đơn hàng
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
