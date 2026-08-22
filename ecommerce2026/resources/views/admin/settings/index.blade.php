@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-3">
            @include('admin.sidebar')
        </div>
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Cài đặt thanh toán</h5>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="qr_code" class="form-label">Mã QR Thanh toán cá nhân</label>
                            <input type="file" name="qr_code" id="qr_code" class="form-control @error('qr_code') is-invalid @enderror" required>
                            @error('qr_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if(isset($qrCode) && $qrCode->value)
                            <div class="mb-3">
                                <label class="form-label d-block">Mã QR hiện tại:</label>
                                <img src="{{ asset('storage/' . $qrCode->value) }}" alt="QR Code" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        @endif

                        <button type="submit" class="btn btn-primary">Cập nhật mã QR</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
