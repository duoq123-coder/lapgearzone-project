@extends('layouts.app')
@section('title', 'Quên mật khẩu')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden mt-4">
            <div class="p-4 bg-white text-dark shadow-sm text-center position-relative">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(79, 70, 229, 0.1) 0%, rgba(124, 58, 237, 0.15) 100%);"></div>
                <h3 class="fw-bold mb-1 position-relative"><i class="bi bi-question-circle-fill me-2 text-warning"></i>Quên Mật Khẩu?</h3>
                <p class="text-muted small mb-0 position-relative">Nhập email để đặt lại mật khẩu</p>
            </div>
            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('password.email') }}" method="POST">
                    @csrf
                    
                    <!-- Email Address -->
                    <div class="mb-4">
                        <label for="email" class="form-label">Địa chỉ Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   class="form-control bg-light border-start-0 @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}" 
                                   placeholder="name@example.com" 
                                   required 
                                   autofocus>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg fw-semibold rounded-3">
                            <i class="bi bi-send-fill me-2"></i>Nhận Link Reset
                        </button>
                    </div>

                <!-- Help Text -->
                <div class="alert alert-info alert-sm mt-4" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <small>Link reset mật khẩu sẽ được gửi đến email của bạn. Vui lòng kiểm tra email (và spam folder) để tìm link.</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
