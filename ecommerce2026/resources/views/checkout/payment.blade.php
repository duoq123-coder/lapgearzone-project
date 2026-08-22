@extends('layouts.app')
@section('title', 'Thanh toán')
@section('content')
<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-body p-5">
                    <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                    <h3 class="mt-3 mb-4">Đơn hàng đã được tạo thành công!</h3>
                    <p class="text-muted mb-4">Mã đơn hàng của bạn là <strong>#{{ $order->id }}</strong>. Vui lòng thanh toán theo mã QR bên dưới để chúng tôi xử lý đơn hàng.</p>
                    
                    @php
                        $bankId = 'MB'; // Mã ngân hàng (ví dụ: MB, VCB, TCB...)
                        $accountNo = '03468844158888'; // Số tài khoản
                        $accountName = 'NGUYEN QUY DUONG'; // Tên chủ tài khoản
                        $amount = intval($order->total_price);
                        $addInfo = 'Thanh toan don hang ' . $order->id;
                        
                        // Tạo link VietQR API
                        $qrUrl = "https://img.vietqr.io/image/{$bankId}-{$accountNo}-compact2.png?amount={$amount}&addInfo=" . urlencode($addInfo) . "&accountName=" . urlencode($accountName);
                    @endphp

                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Quét mã QR để thanh toán</h5>
                        <img src="{{ $qrUrl }}" alt="QR Code" class="img-fluid border rounded p-2" style="max-width: 350px;">
                    </div>
                    <div class="alert alert-info">
                        <strong>Lưu ý:</strong> Nội dung chuyển khoản là <strong>"{{ $addInfo }}"</strong>. Hệ thống (hoặc Admin) sẽ xác nhận sau khi nhận được thanh toán.
                    </div>
                    
                    <a href="{{ route('welcome') }}" class="btn btn-outline-primary mt-3">Về trang chủ</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
