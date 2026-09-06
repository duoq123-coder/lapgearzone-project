@extends('layouts.app')
@section('title', 'Hủy thanh toán đơn hàng')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card card-premium border-0 shadow-lg p-4 p-md-5 text-center">
                <div class="mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 84px; height: 84px; background: rgba(205, 76, 32, 0.1); color: var(--bellroy-orange);">
                        <i class="bi bi-x-circle-fill" style="font-size: 3.2rem;"></i>
                    </div>
                </div>

                <h2 class="fw-bold text-dark display-font mb-2">Giao dịch chưa hoàn tất</h2>
                <p class="text-secondary mb-4">
                    Bạn vừa hủy thanh toán hoặc giao dịch chưa được xác nhận trên cổng PayOS. Đơn hàng của bạn vẫn được lưu lại dưới dạng chưa thanh toán.
                </p>

                @if($order)
                <div class="bg-light rounded-4 p-3 mb-4 text-start border border-light-subtle">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-secondary">Mã đơn hàng:</span>
                        <strong class="text-dark">#{{ $order->id }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Cần thanh toán:</span>
                        <strong class="text-danger">{{ number_format($order->total_price, 0, ',', '.') }} đ</strong>
                    </div>
                </div>

                <div class="d-grid gap-2 d-sm-flex justify-content-center">
                    <a href="{{ route('checkout.payment', $order->id) }}" class="btn btn-premium px-4 py-2">
                        <i class="bi bi-arrow-repeat me-1"></i> Thử thanh toán lại
                    </a>
                    <a href="{{ route('welcome') }}" class="btn btn-outline-secondary px-4 py-2">
                        <i class="bi bi-house-door me-1"></i> Về trang chủ
                    </a>
                </div>
                @else
                <div class="d-grid gap-2 d-sm-flex justify-content-center">
                    <a href="{{ route('cart.index') }}" class="btn btn-premium px-4 py-2">
                        <i class="bi bi-cart3 me-1"></i> Quay lại giỏ hàng
                    </a>
                    <a href="{{ route('welcome') }}" class="btn btn-outline-secondary px-4 py-2">
                        Về trang chủ
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
