@extends('layouts.app')
@section('title', 'Giỏ hàng - Cửa Hàng Công Nghệ')
@section('content')
<div class="container py-4 animate-slide-up">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="small fw-bold text-secondary text-uppercase" style="letter-spacing: 0.8px; font-size: 0.75rem;">ĐƠN HÀNG CỦA BẠN</span>
                    <h2 class="serif-title mb-0 text-dark" style="font-size: 1.8rem;">
                        Giỏ Hàng
                    </h2>
                </div>
                @if(!$cartItems->isEmpty())
                <a href="{{ route('products.index') }}" class="btn btn-outline-premium">
                    <i class="bi bi-arrow-left me-2"></i>Tiếp tục mua sắm
                </a>
                @endif
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 p-3 mb-4" style="background-color: var(--bellroy-orange-subtle); color: var(--bellroy-orange); border-radius: 2px;" role="alert">
            @foreach ($errors->all() as $error)
                <div><i class="bi bi-exclamation-circle-fill me-2"></i>{{ $error }}</div>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif



    @if ($cartItems->isEmpty())
        <!-- Empty Cart -->
        <div class="row">
            <div class="col-md-12">
                <div class="card card-premium text-center py-5">
                    <div class="card-body py-5">
                        <div class="mb-4">
                            <i class="bi bi-bag text-muted" style="font-size: 3.5rem; opacity: 0.4;"></i>
                        </div>
                        <h4 class="serif-title mb-2 text-dark">Giỏ hàng của bạn đang trống</h4>
                        <p class="text-muted mb-4" style="font-size: 0.92rem;">Chưa có sản phẩm nào trong giỏ hàng. Hãy khám phá các thiết bị công nghệ mới nhất!</p>
                        <a href="{{ route('products.index') }}" class="btn btn-premium px-4 py-2.5">
                            Khám phá sản phẩm <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Cart Items -->
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card card-premium">
                    <div class="card-header bg-white border-bottom p-3 p-md-4" style="border-color: var(--border-color) !important;">
                        <h5 class="mb-0 fw-bold text-dark" style="font-size: 1rem;">
                            <i class="bi bi-bag-check me-2" style="color: var(--bellroy-orange);"></i>Chi tiết đơn hàng ({{ $cartItems->count() }} mặt hàng)
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th style="padding-left: 20px;">Sản phẩm</th>
                                        <th>Giá</th>
                                        <th>Số lượng</th>
                                        <th>Thành tiền</th>
                                        <th style="padding-right: 20px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cartItems as $item)
                                        <tr class="align-middle">
                                            <!-- Product Info -->
                                            <td style="padding-left: 20px;">
                                                <div class="d-flex align-items-center">
                                                    @php
                                                        $imageUrl = $item->product->images()->first()?->image_path ?? $item->product->image;
                                                    @endphp
                                                    @if (!empty($imageUrl))
                                                        <img src="{{ asset('storage/'.$imageUrl) }}" 
                                                             alt="{{ $item->product->name }}" 
                                                             class="me-3 p-1 border" 
                                                             style="width: 80px; height: 80px; object-fit: contain; background: #f8f6f2; border-color: var(--border-color) !important; border-radius: 2px;">
                                                    @else
                                                        <div class="me-3 d-flex align-items-center justify-content-center border" 
                                                             style="width: 80px; height: 80px; background: #f8f6f2; border-color: var(--border-color) !important; border-radius: 2px;">
                                                            <i class="bi bi-laptop text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-1 fw-bold">
                                                            <a href="{{ route('products.show', $item->product->id) }}" 
                                                               class="text-decoration-none text-dark" style="font-size: 0.92rem;">
                                                                {{ $item->product->name }}
                                                            </a>
                                                        </h6>
                                                        <span class="badge badge-terracotta" style="font-size: 0.68rem;">{{ $item->product->category?->name }}</span>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Price -->
                                            <td>
                                                <span class="fw-semibold text-dark" style="font-size: 0.92rem;">{{ number_format($item->product->price, 0, ',', '.') }} đ</span>
                                            </td>

                                            <!-- Quantity -->
                                            <td>
                                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex align-items-center gap-2">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="number" 
                                                           name="quantity" 
                                                           min="1" 
                                                           max="{{ $item->product->quantity }}" 
                                                           value="{{ $item->quantity }}"
                                                           class="form-control form-control-sm text-center" 
                                                           style="width: 65px; border-radius: 2px; font-family: 'Space Mono', monospace; font-weight: 700; border: 1.5px solid var(--border-color);"
                                                           onchange="this.form.submit()">
                                                </form>
                                            </td>

                                            <!-- Subtotal -->
                                            <td>
                                                <span class="fw-bold text-dark display-font" style="font-size: 0.95rem;">
                                                    {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }} đ
                                                </span>
                                            </td>

                                            <!-- Remove -->
                                            <td style="padding-right: 20px;" class="text-end">
                                                <form action="{{ route('cart.remove', $item->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-link text-muted p-0 text-decoration-none" 
                                                            onclick="return confirm('Bạn chắc chắn muốn xóa sản phẩm này?')"
                                                            title="Xóa khỏi giỏ"
                                                            style="font-size: 1.1rem;">
                                                        <i class="bi bi-x-circle"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top p-3" style="border-color: var(--border-color) !important;">
                        <div class="d-flex justify-content-between align-items-center">
                            <form action="{{ route('cart.clear') }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-link text-danger text-decoration-none fw-bold" 
                                        onclick="return confirm('Bạn chắc chắn muốn xóa toàn bộ giỏ hàng?')">
                                    <i class="bi bi-trash me-1"></i>Xóa toàn bộ giỏ hàng
                                </button>
                            </form>
                            <small class="text-muted">Tổng: {{ $cartItems->sum('quantity') }} sản phẩm</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary & Checkout -->
            <div class="col-lg-4">
                <div class="card card-premium cart-summary-sticky">
                    <div class="card-header bg-white border-bottom p-3 p-md-4" style="border-color: var(--border-color) !important;">
                        <h5 class="mb-0 fw-bold text-dark" style="font-size: 1rem;"><i class="bi bi-receipt me-2" style="color: var(--bellroy-orange);"></i>Tóm tắt đơn hàng</h5>
                    </div>
                    <div class="card-body p-4">
                        <!-- Summary Details -->
                        <div class="mb-3 pb-3 border-bottom" style="border-color: var(--border-color) !important;">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted" style="font-size: 0.9rem;">Tổng số lượng:</span>
                                <span class="fw-semibold text-dark">{{ $cartCount }} sản phẩm</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted" style="font-size: 0.9rem;">Tạm tính:</span>
                                <span class="fw-semibold text-dark">{{ number_format($total, 0, ',', '.') }} đ</span>
                            </div>
                            @if(isset($discountAmount) && $discountAmount > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted" style="font-size: 0.9rem;">Giảm giá <span class="badge badge-sage">{{ $couponCode }}</span>:</span>
                                <span class="fw-bold" style="color: var(--bellroy-sage);">- {{ number_format($discountAmount, 0, ',', '.') }} đ</span>
                            </div>
                            @endif
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted" style="font-size: 0.9rem;">Phí vận chuyển:</span>
                                <span class="fw-bold" style="color: var(--bellroy-sage);">Miễn phí</span>
                            </div>
                        </div>

                        <!-- Coupon Form -->
                        <div class="mb-4 pb-3 border-bottom" style="border-color: var(--border-color) !important;">
                            <label class="form-label fw-bold small text-dark mb-2"><i class="bi bi-ticket-perforated me-1" style="color: var(--bellroy-orange);"></i>Mã giảm giá / Voucher</label>
                            @if(isset($couponCode) && $couponCode != '')
                                <form action="{{ route('cart.removeCoupon') }}" method="POST" class="d-flex align-items-center justify-content-between p-2 border" style="background-color: var(--bellroy-sage-subtle); border-color: var(--border-color) !important; border-radius: 2px;">
                                    @csrf
                                    <div class="fw-bold" style="color: var(--bellroy-sage); font-size: 0.88rem; font-family: 'Space Grotesk', sans-serif;"><i class="bi bi-check-circle-fill me-1"></i>{{ $couponCode }}</div>
                                    <button type="submit" class="btn btn-sm btn-link text-danger text-decoration-none fw-bold p-0">Gỡ bỏ</button>
                                </form>
                            @else
                                <form action="{{ route('cart.applyCoupon') }}" method="POST" id="applyCouponForm">
                                    @csrf
                                    <input type="hidden" name="coupon_code" id="coupon_code_input" required>
                                    @if(isset($availableCoupons) && $availableCoupons->count() > 0)
                                        <div class="d-grid">
                                            <button type="button" class="btn btn-outline-premium fw-bold" data-bs-toggle="modal" data-bs-target="#couponsModal">
                                                <i class="bi bi-tags me-2" style="color: var(--bellroy-orange);"></i> Chọn mã ưu đãi
                                            </button>
                                        </div>
                                    @else
                                        <div class="alert bg-light border mb-0 text-center small p-2" style="border-color: var(--border-color) !important;">
                                            <i class="bi bi-info-circle me-1"></i> Chưa có voucher phù hợp
                                        </div>
                                    @endif
                                </form>
                            @endif
                        </div>

                        <!-- Total -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold fs-5 text-dark">Tổng thanh toán:</span>
                                <span class="fw-bold display-font text-dark" style="font-size: 1.5rem; color: var(--text-main);">
                                    {{ isset($finalTotal) ? number_format($finalTotal, 0, ',', '.') : number_format($total, 0, ',', '.') }} đ
                                </span>
                            </div>
                            <small class="text-muted d-block text-end" style="font-size: 0.78rem;">(Đã bao gồm thuế GTGT &amp; vận chuyển)</small>
                        </div>

                        <!-- Checkout Button -->
                        <div class="d-grid gap-2">
                            <a href="{{ route('checkout.index') }}" class="btn btn-premium btn-lg justify-content-center text-center">
                                <i class="bi bi-credit-card me-2"></i>Tiến hành thanh toán
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .cart-summary-sticky {
        position: sticky;
        top: 90px;
        z-index: 1010;
    }

    @media (max-width: 991px) {
        .cart-summary-sticky {
            position: static;
            margin-top: 1.5rem;
        }
    }
</style>

<!-- Modal Chọn Mã Giảm Giá -->
@if(isset($availableCoupons) && $availableCoupons->count() > 0)
<div class="modal fade" id="couponsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header bg-white border-bottom p-3 px-4" style="border-color: var(--border-color) !important;">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-ticket-perforated me-2" style="color: var(--bellroy-orange);"></i>Mã giảm giá khả dụng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="list-group list-group-flush">
                    @foreach($availableCoupons as $c)
                        @php
                            $canApply = $total >= $c->min_order_value;
                        @endphp
                        <div class="list-group-item p-3 px-4 {{ !$canApply ? 'bg-light text-muted' : '' }}">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge badge-terracotta fs-6 font-monospace" style="letter-spacing: 1px;">{{ $c->code }}</span>
                                @if($canApply)
                                    <button type="button" class="btn btn-sm btn-dark fw-bold px-3 rounded-pill" onclick="selectCoupon('{{ $c->code }}')">Chọn mã</button>
                                @else
                                    <span class="badge bg-secondary rounded-pill">Chưa đủ ĐK</span>
                                @endif
                            </div>
                            <p class="mb-1 fw-bold text-dark">
                                Giảm {{ $c->type == 'percent' ? $c->value.'%' : number_format($c->value, 0, ',', '.').' đ' }}
                            </p>
                            @if($c->type == 'percent' && $c->max_discount_amount)
                                <small class="d-block text-muted">Giảm tối đa: {{ number_format($c->max_discount_amount, 0, ',', '.') }} đ</small>
                            @endif
                            <small class="d-block mb-1 text-muted">Đơn tối thiểu: {{ number_format($c->min_order_value, 0, ',', '.') }} đ</small>
                            @if($c->ends_at)
                                <small class="d-block text-muted"><i class="bi bi-clock me-1"></i>Hạn dùng: {{ $c->ends_at->format('H:i d/m/Y') }}</small>
                            @endif
                            @if($c->usage_limit)
                                <small class="d-block text-secondary">Đã dùng: {{ $c->used }}/{{ $c->usage_limit }}</small>
                            @endif
                            @if(!$canApply)
                                <small class="d-block mt-1" style="color: var(--bellroy-orange);"><i class="bi bi-exclamation-circle me-1"></i>Mua thêm {{ number_format($c->min_order_value - $total, 0, ',', '.') }} đ để áp dụng</small>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function selectCoupon(code) {
        document.getElementById('coupon_code_input').value = code;
        var modalEl = document.getElementById('couponsModal');
        var modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
        modal.hide();
        setTimeout(() => {
            document.getElementById('applyCouponForm').submit();
        }, 100);
    }
</script>
@endif

@endsection
