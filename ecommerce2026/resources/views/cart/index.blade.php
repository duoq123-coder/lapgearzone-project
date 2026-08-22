@extends('layouts.app')
@section('title', 'Giỏ hàng')
@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex align-items-center justify-content-between">
                <h2 class="fw-bold mb-0">
                    <i class="bi bi-cart3 me-2 text-primary"></i>Giỏ Hàng Của Bạn
                </h2>
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-2"></i>Tiếp tục mua sắm
                </a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            @foreach ($errors->all() as $error)
                <div><i class="bi bi-exclamation-circle-fill me-2"></i>{{ $error }}</div>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($cartItems->isEmpty())
        <!-- Empty Cart -->
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm text-center py-5">
                    <div class="card-body">
                        <div class="mb-4">
                            <i class="bi bi-bag-x text-muted" style="font-size: 4rem;"></i>
                        </div>
                        <h4 class="fw-bold mb-2">Giỏ hàng trống rỗng</h4>
                        <p class="text-muted mb-4">Chưa có sản phẩm nào trong giỏ hàng. Hãy bắt đầu mua sắm 1 số sản phẩm nào!</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-shop me-2"></i>Quay lại cửa hàng
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Cart Items -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light border-bottom">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-list-check me-2"></i>Chi tiết đơn hàng</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Giá</th>
                                        <th>Số lượng</th>
                                        <th>Thành tiền</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cartItems as $item)
                                        <tr class="align-middle">
                                            <!-- Product Info -->
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @php
                                                        $imageUrl = $item->product->images()->first()?->image_path ?? $item->product->image;
                                                    @endphp
                                                    @if (!empty($imageUrl))
                                                        <img src="{{ asset('storage/'.$imageUrl) }}" 
                                                             alt="{{ $item->product->name }}" 
                                                             class="rounded me-3" 
                                                             style="width: 200px; height: 200px; object-fit: cover;">
                                                    @else
                                                        <div class="rounded me-3 bg-light d-flex align-items-center justify-content-center" 
                                                             style="width: 200px; height: 200px;">
                                                            <i class="bi bi-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-1 fw-semibold">
                                                            <a href="{{ route('products.show', $item->product->id) }}" 
                                                               class="text-decoration-none text-dark">
                                                                {{ $item->product->name }}
                                                            </a>
                                                        </h6>
                                                        <small class="text-muted">{{ $item->product->category?->name }}</small>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Price -->
                                            <td>
                                                <span class="fw-semibold">{{ number_format($item->product->price, 0, ',', '.') }}đ</span>
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
                                                           class="form-control form-control-sm" 
                                                           style="width: 70px;"
                                                           onchange="this.form.submit()">
                                                </form>
                                            </td>

                                            <!-- Subtotal -->
                                            <td>
                                                <span class="fw-bold text-danger">
                                                    {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}đ
                                                </span>
                                            </td>

                                            <!-- Remove -->
                                            <td>
                                                <form action="{{ route('cart.remove', $item->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                            onclick="return confirm('Bạn chắc chắn muốn xóa sản phẩm này?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-light border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <form action="{{ route('cart.clear') }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" 
                                        onclick="return confirm('Bạn chắc chắn muốn xóa toàn bộ giỏ hàng?')">
                                    <i class="bi bi-trash me-2"></i>Xóa tất cả
                                </button>
                            </form>
                            <small class="text-muted">Tổng cộng: {{ $cartItems->count() }} sản phẩm</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary & Checkout -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-header bg-light border-bottom">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-calculator me-2"></i>Tóm tắt đơn hàng</h5>
                    </div>
                    <div class="card-body">
                        <!-- Summary Details -->
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Tổng sản phẩm:</span>
                                <span class="fw-semibold">{{ $cartCount }} sản phẩm</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Tạm tính:</span>
                                <span class="fw-semibold">{{ number_format($total, 0, ',', '.') }}đ</span>
                            </div>
                            @if(isset($discountAmount) && $discountAmount > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Giảm giá <span class="badge bg-success">{{ $couponCode }}</span>:</span>
                                <span class="fw-bold text-success">- {{ number_format($discountAmount, 0, ',', '.') }}đ</span>
                            </div>
                            @endif
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Phí vận chuyển:</span>
                                <span class="fw-semibold text-success">Miễn phí</span>
                            </div>
                        </div>

                        <!-- Coupon Form -->
                        <div class="mb-4 pb-3 border-bottom">
                            <label class="form-label fw-bold small text-dark"><i class="bi bi-ticket-perforated me-1"></i>Mã giảm giá</label>
                            @if(isset($couponCode) && $couponCode != '')
                                <form action="{{ route('cart.removeCoupon') }}" method="POST" class="d-flex align-items-center justify-content-between p-2 border rounded-3 bg-light">
                                    @csrf
                                    <div class="fw-bold text-success"><i class="bi bi-check-circle-fill me-1"></i>{{ $couponCode }}</div>
                                    <button type="submit" class="btn btn-sm btn-outline-danger border-0">Hủy</button>
                                </form>
                            @else
                                <form action="{{ route('cart.applyCoupon') }}" method="POST">
                                    @csrf
                                    <div class="input-group">
                                        <input type="text" name="coupon_code" class="form-control text-uppercase" placeholder="Nhập mã giảm giá..." required>
                                        <button class="btn btn-dark" type="submit">Áp dụng</button>
                                    </div>
                                </form>
                            @endif
                        </div>

                        <!-- Total -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold fs-5">Tổng cộng:</span>
                                <span class="text-danger fw-bold" style="font-size: 1.5rem;">{{ isset($finalTotal) ? number_format($finalTotal, 0, ',', '.') : number_format($total, 0, ',', '.') }}đ</span>
                            </div>
                        </div>

                        <!-- Checkout Button -->
                        <div class="d-grid gap-2">
                            <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg fw-semibold">
                                <i class="bi bi-credit-card me-2"></i>Tiến hành thanh toán
                            </a>
                        </div>

                        <!-- Info -->
                        <div class="alert alert-info alert-sm mt-3 mb-0" role="alert">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            <small>Bạn sẽ hoàn tất thanh toán ở bước tiếp theo.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .alert-sm {
        padding: 0.75rem;
        font-size: 0.875rem;
    }

    .sticky-top {
        z-index: 100;
    }

    @media (max-width: 991px) {
        .sticky-top {
            position: static;
            margin-top: 2rem;
        }
    }
</style>
@endsection
