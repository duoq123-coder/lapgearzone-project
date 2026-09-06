@extends('layouts.app')
@section('title', 'Thông tin thanh toán - Cửa Hàng Công Nghệ')
@section('content')
<div class="container py-4 animate-slide-up">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="font-size: 0.88rem;">
            <li class="breadcrumb-item"><a href="{{ route('welcome') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="text-decoration-none text-muted">Giỏ hàng</a></li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Thanh toán</li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card card-premium">
                <div class="card-header bg-white border-bottom p-3 p-md-4" style="border-color: var(--border-color) !important;">
                    <h4 class="serif-title mb-0 text-dark" style="font-size: 1.4rem;">
                        <i class="bi bi-geo-alt me-2" style="color: var(--bellroy-orange);"></i>Thông tin giao hàng
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('checkout.process') }}" method="POST">
                        @csrf
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-bold text-dark small">Họ và tên người nhận <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required placeholder="Ví dụ: Nguyễn Văn A">
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-bold text-dark small">Số điện thoại liên hệ <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required placeholder="Ví dụ: 0987654321">
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="address" class="form-label fw-bold text-dark small">Địa chỉ nhận hàng chi tiết <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" required placeholder="Số nhà, ngõ, tên đường, phường/xã, quận/huyện, tỉnh/thành phố">{{ old('address', auth()->user()->address) }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark mb-3 small text-uppercase" style="letter-spacing: 0.5px;">Phương thức thanh toán</label>
                            
                            <div class="card mb-3 p-1" style="border: 1px solid var(--border-color); background-color: #faf9f6; border-radius: 12px;">
                                <div class="card-body p-3">
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input mt-0 me-3" type="radio" name="payment_method" id="payment_payos" value="payos" checked style="cursor: pointer;">
                                        <label class="form-check-label w-100 cursor-pointer" for="payment_payos" style="cursor: pointer;">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span class="fw-bold text-dark d-flex align-items-center flex-wrap gap-2" style="font-size: 0.95rem;">
                                                        <span>Thanh toán trực tuyến qua Cổng PayOS</span>
                                                        <span class="badge badge-sage" style="font-size: 0.7rem;"><i class="bi bi-lightning-fill me-1"></i>Tự động xác nhận 3s</span>
                                                    </span>
                                                    <span class="small text-muted">Chuyển khoản ngân hàng tự động, hệ thống kích hoạt đơn hàng ngay khi nhận được thanh toán</span>
                                                </div>
                                                <i class="bi bi-credit-card-2-front fs-3" style="color: var(--bellroy-orange);"></i>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card mb-3 p-1" style="border: 1px solid var(--border-color); background-color: #faf9f6; border-radius: 12px;">
                                <div class="card-body p-3">
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input mt-0 me-3" type="radio" name="payment_method" id="payment_cod_install" value="cod_install" style="cursor: pointer;">
                                        <label class="form-check-label w-100 cursor-pointer" for="payment_cod_install" style="cursor: pointer;">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span class="fw-bold text-dark d-block" style="font-size: 0.95rem;">Giao hàng tận nơi &amp; Thanh toán khi nhận hàng (COD)</span>
                                                    <span class="small text-muted">Nhân viên giao máy kiểm tra tận tay, thu tiền mặt hoặc chuyển khoản tại chỗ</span>
                                                </div>
                                                <i class="bi bi-box-seam fs-3" style="color: var(--bellroy-sage);"></i>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @error('payment_method')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-premium btn-lg py-3 justify-content-center">
                                Xác nhận đặt hàng <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card card-premium">
                <div class="card-header bg-white border-bottom p-3 p-md-4" style="border-color: var(--border-color) !important;">
                    <h5 class="mb-0 fw-bold text-dark" style="font-size: 1rem;"><i class="bi bi-receipt me-2" style="color: var(--bellroy-orange);"></i>Đơn hàng của bạn</h5>
                </div>
                <div class="card-body p-4">
                    @foreach($cartItems as $item)
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-2" style="border-color: var(--border-color-subtle) !important;">
                        <div class="pe-2">
                            <span class="fw-semibold text-dark d-block" style="font-size: 0.88rem;">{{ $item->product->name }}</span>
                            <small class="text-muted">SL: {{ $item->quantity }} x {{ number_format($item->product->price, 0, ',', '.') }} đ</small>
                        </div>
                        <span class="fw-bold text-dark" style="font-size: 0.88rem; white-space: nowrap;">{{ number_format($item->product->price * $item->quantity, 0, ',', '.') }} đ</span>
                    </div>
                    @endforeach

                    <div class="d-flex justify-content-between mb-2 text-muted" style="font-size: 0.88rem;">
                        <span>Tạm tính:</span>
                        <span class="text-dark fw-semibold">{{ number_format($total, 0, ',', '.') }} đ</span>
                    </div>

                    @if(isset($discountAmount) && $discountAmount > 0)
                    <div class="d-flex justify-content-between mb-2" style="font-size: 0.88rem;">
                        <span>Giảm giá (<span class="badge badge-sage">{{ $couponCode }}</span>):</span>
                        <span class="fw-bold" style="color: var(--bellroy-sage);">-{{ number_format($discountAmount, 0, ',', '.') }} đ</span>
                    </div>
                    @endif

                    <div class="d-flex justify-content-between mb-3 text-muted" style="font-size: 0.88rem;">
                        <span>Vận chuyển:</span>
                        <span class="fw-bold" style="color: var(--bellroy-sage);">Miễn phí</span>
                    </div>

                    <hr style="border-color: var(--border-color);">

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="fw-bold fs-5 text-dark">Tổng cộng:</span>
                        <span class="fw-bold display-font text-dark" style="font-size: 1.4rem;">
                            {{ isset($finalTotal) ? number_format($finalTotal, 0, ',', '.') : number_format($total, 0, ',', '.') }} đ
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
