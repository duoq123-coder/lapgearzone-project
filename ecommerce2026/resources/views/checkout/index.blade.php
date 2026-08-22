@extends('layouts.app')
@section('title', 'Thông tin thanh toán')
@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h4 class="mb-0">Thông tin giao hàng</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('checkout.process') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Họ và tên</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-4">
                            <label for="address" class="form-label fw-bold">Địa chỉ giao hàng</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" required placeholder="Nhập địa chỉ nhận hàng và lắp đặt">{{ old('address') }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold mb-3">Phương thức thanh toán</label>
                            
                            <div class="card border border-primary mb-2" style="background-color: #f8fbff;">
                                <div class="card-body p-3">
                                    <div class="form-check">
                                        <input class="form-check-input mt-2" type="radio" name="payment_method" id="payment_vnpay" value="vnpay" checked>
                                        <label class="form-check-label ms-2 w-100" for="payment_vnpay">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span class="fw-bold text-dark d-block">Thanh toán qua cổng VNPay</span>
                                                    <span class="small text-muted">Thanh toán trực tuyến an toàn</span>
                                                </div>
                                                <i class="bi bi-credit-card-2-front fs-3 text-primary"></i>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card border border-success mb-2" style="background-color: #f4fff8;">
                                <div class="card-body p-3">
                                    <div class="form-check">
                                        <input class="form-check-input mt-2" type="radio" name="payment_method" id="payment_cod_install" value="cod_install">
                                        <label class="form-check-label ms-2 w-100" for="payment_cod_install">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span class="fw-bold text-dark d-block">Giao hàng, lắp đặt và thanh toán tại nhà</span>
                                                    <span class="small text-muted">Nhân viên LapGearZone sẽ mang hàng đến lắp đặt và thu tiền mặt/chuyển khoản</span>
                                                </div>
                                                <i class="bi bi-box-seam fs-3 text-success"></i>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @error('payment_method')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Tiếp tục tới thanh toán</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Đơn hàng của bạn</h5>
                </div>
                <div class="card-body">
                    @foreach($cartItems as $item)
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                        <div>
                            <span class="fw-semibold">{{ $item->product->name }}</span>
                            <div class="text-muted small">Số lượng: {{ $item->quantity }}</div>
                        </div>
                        <span>{{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}đ</span>
                    </div>
                    @endforeach
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                        <span>Tạm tính:</span>
                        <span>{{ number_format($total, 0, ',', '.') }}đ</span>
                    </div>
                    @if(isset($discountAmount) && $discountAmount > 0)
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                        <span>Giảm giá <span class="badge bg-success">{{ $couponCode }}</span>:</span>
                        <span class="text-success fw-bold">-{{ number_format($discountAmount, 0, ',', '.') }}đ</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between fw-bold fs-5 mt-3">
                        <span>Tổng cộng:</span>
                        <span class="text-danger">{{ isset($finalTotal) ? number_format($finalTotal, 0, ',', '.') : number_format($total, 0, ',', '.') }}đ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
