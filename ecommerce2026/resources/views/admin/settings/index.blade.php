@extends('admin.layouts.app')
@section('title', 'Cài đặt Cổng thanh toán PayOS - Admin')

@section('content')
<div class="mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h2 class="mb-1 fw-bold text-dark display-font">
                <i class="bi bi-wallet2 me-2" style="color: var(--bellroy-orange);"></i>Cài đặt Cổng Thanh Toán Tự Động
            </h2>
            <p class="text-secondary small mb-0">Tích hợp cổng thanh toán tự động <strong>PayOS (MB Bank)</strong> để tự động nhận tiền và kích hoạt đơn hàng trong 3 giây.</p>
        </div>
        <div>
            @if($isConfigured)
                <span class="badge badge-sage font-monospace px-3 py-2">
                    <i class="bi bi-check-circle-fill me-1"></i> PayOS: Đang kết nối
                </span>
            @else
                <span class="badge badge-terracotta font-monospace px-3 py-2">
                    <i class="bi bi-exclamation-circle-fill me-1"></i> PayOS: Chưa hoàn tất cấu hình
                </span>
            @endif
        </div>
    </div>
</div>


<div class="row g-4">
    <!-- Cột trái: Cấu hình Khóa API PayOS -->
    <div class="col-lg-7">
        <div class="card p-4 p-lg-5 mb-4">
            <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                <div>
                    <h5 class="fw-bold text-dark mb-1">
                        <i class="bi bi-key-fill text-warning me-2"></i>Thông tin tích hợp PayOS
                    </h5>
                    <span class="text-muted small">Lấy từ mục <strong>Kênh thanh toán &rarr; Thông tin tích hợp</strong> trên <a href="https://my.payos.vn" target="_blank" class="text-decoration-none text-primary">my.payos.vn <i class="bi bi-box-arrow-up-right small"></i></a></span>
                </div>
                <img src="https://payos.vn/wp-content/uploads/2023/07/payos-logo.svg" alt="PayOS Logo" height="28" class="d-none d-sm-block" onerror="this.style.display='none';">
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf

                <!-- Client ID -->
                <div class="mb-3">
                    <label for="payos_client_id" class="form-label fw-bold text-dark small">
                        Client ID <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-person-badge"></i></span>
                        <input type="text" 
                               class="form-control font-monospace @error('payos_client_id') is-invalid @enderror" 
                               id="payos_client_id" 
                               name="payos_client_id" 
                               value="{{ old('payos_client_id', $payosClientId) }}" 
                               placeholder="Ví dụ: 6c1e889c-a7fe-11f1-8d57-0242ac110002" 
                               required>
                    </div>
                    <div class="form-text small">Bấm biểu tượng copy cạnh dòng <em>Client ID</em> trên màn hình PayOS và dán vào đây.</div>
                    @error('payos_client_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <!-- API Key -->
                <div class="mb-3">
                    <label for="payos_api_key" class="form-label fw-bold text-dark small">
                        API Key <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-shield-lock"></i></span>
                        <input type="password" 
                               class="form-control font-monospace @error('payos_api_key') is-invalid @enderror" 
                               id="payos_api_key" 
                               name="payos_api_key" 
                               value="{{ old('payos_api_key', $payosApiKey) }}" 
                               placeholder="Dán mã Api Key từ PayOS" 
                               required>
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('payos_api_key', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <div class="form-text small">Mã API bí mật để hệ thống liên lạc bảo mật với máy chủ PayOS.</div>
                    @error('payos_api_key')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <!-- Checksum Key -->
                <div class="mb-4">
                    <label for="payos_checksum_key" class="form-label fw-bold text-dark small">
                        Checksum Key <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-hash"></i></span>
                        <input type="password" 
                               class="form-control font-monospace @error('payos_checksum_key') is-invalid @enderror" 
                               id="payos_checksum_key" 
                               name="payos_checksum_key" 
                               value="{{ old('payos_checksum_key', $payosChecksumKey) }}" 
                               placeholder="Dán mã Checksum Key từ PayOS" 
                               required>
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('payos_checksum_key', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <div class="form-text small">Khóa mã hóa HMAC-SHA256 để xác thực dữ liệu giao dịch chống giả mạo.</div>
                    @error('payos_checksum_key')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <hr class="my-4">
                <h6 class="fw-bold text-dark mb-3">
                    <i class="bi bi-bank text-primary me-2"></i>Thông tin Tài khoản Ngân hàng nhận tiền
                </h6>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="bank_name" class="form-label fw-bold text-dark small">Tên Ngân Hàng</label>
                        <input type="text" class="form-control" id="bank_name" name="bank_name" value="{{ old('bank_name', $bankName) }}" placeholder="MB Bank (Quân Đội)">
                    </div>
                    <div class="col-md-6">
                        <label for="bank_account_number" class="form-label fw-bold text-dark small">Số tài khoản <span class="text-danger">*</span></label>
                        <input type="text" class="form-control font-monospace" id="bank_account_number" name="bank_account_number" value="{{ old('bank_account_number', $bankAccountNumber) }}" placeholder="03468844158888" required>
                    </div>
                    <div class="col-12">
                        <label for="bank_account_name" class="form-label fw-bold text-dark small">Chủ tài khoản</label>
                        <input type="text" class="form-control text-uppercase" id="bank_account_name" name="bank_account_name" value="{{ old('bank_account_name', $bankAccountName) }}" placeholder="NGUYEN QUY DUONG">
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-premium px-4 py-2">
                        <i class="bi bi-save2 me-1"></i> Lưu cấu hình PayOS &amp; Ngân hàng
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Cột phải: Hướng dẫn & Cấu hình Webhook URL -->
    <div class="col-lg-5">
        <!-- Card Webhook URL -->
        <div class="card p-4 mb-4">
            <h5 class="fw-bold text-dark mb-2">
                <i class="bi bi-broadcast text-primary me-2"></i>Đường dẫn Webhook URL
            </h5>
            <p class="text-secondary small mb-3">
                Dán đường dẫn này vào ô <strong>"Webhook url"</strong> trên bảng điều khiển PayOS để nhận thông báo thanh toán tự động:
            </p>

            <div class="input-group mb-3">
                <input type="text" class="form-control font-monospace bg-white" id="webhookUrlInput" value="{{ $webhookUrl }}" readonly>
                <button class="btn btn-outline-primary" type="button" onclick="copyWebhookUrl(this)" title="Sao chép">
                    <i class="bi bi-clipboard me-1"></i> Sao chép
                </button>
            </div>

            <!-- Form Xác nhận Webhook URL với PayOS API -->
            <form action="{{ route('admin.settings.confirmPayOSWebhook') }}" method="POST">
                @csrf
                <input type="hidden" name="webhook_url" value="{{ $webhookUrl }}">
                <button type="submit" class="btn btn-outline-success btn-sm w-100 py-2 fw-bold" {{ !$isConfigured ? 'disabled' : '' }}>
                    <i class="bi bi-patch-check-fill me-1"></i> Xác nhận Webhook với PayOS
                </button>
                @if(!$isConfigured)
                    <div class="form-text small text-muted text-center mt-1">Cần lưu 3 khóa API trước khi xác nhận Webhook.</div>
                @endif
            </form>
        </div>

        <!-- Hướng dẫn 3 bước thiết lập PayOS -->
        <div class="card p-4">
            <h6 class="fw-bold text-dark text-uppercase mb-3 font-monospace" style="letter-spacing: 0.5px; font-size: 0.85rem;">
                <i class="bi bi-lightbulb-fill text-warning me-1"></i> Hướng dẫn hoàn tất trong 1 phút
            </h6>

            <ol class="list-unstyled d-flex flex-column gap-3 mb-0 small text-secondary">
                <li class="d-flex align-items-start gap-2">
                    <span class="badge bg-dark text-white d-flex align-items-center justify-content-center font-monospace" style="width: 22px; height: 22px; min-width: 22px;">1</span>
                    <div>
                        <strong>Sao chép 3 khóa API từ PayOS:</strong><br>
                        Trên màn hình PayOS (như hình bạn gửi), bấm nút copy ở <strong>Client ID</strong>, <strong>Api Key</strong>, <strong>Checksum Key</strong> rồi dán vào cột bên trái.
                    </div>
                </li>
                <li class="d-flex align-items-start gap-2">
                    <span class="badge bg-dark text-white d-flex align-items-center justify-content-center font-monospace" style="width: 22px; height: 22px; min-width: 22px;">2</span>
                    <div>
                        <strong>Lưu cài đặt Webhook URL:</strong><br>
                        Bấm nút <em>"Sao chép"</em> ở ô Webhook URL bên trên, dán vào ô <strong>Webhook url</strong> trên trang PayOS và lưu lại.
                    </div>
                </li>
                <li class="d-flex align-items-start gap-2">
                    <span class="badge bg-dark text-white d-flex align-items-center justify-content-center font-monospace" style="width: 22px; height: 22px; min-width: 22px;">3</span>
                    <div>
                        <strong>Thanh toán tự động 100%:</strong><br>
                        Khi khách đặt hàng và hoàn tất thanh toán PayOS, tài khoản MB Bank nhận tiền, hệ thống sẽ tự động kích hoạt đơn sang <code>paid</code> trong 3 giây.
                    </div>
                </li>
            </ol>

            <div class="alert alert-light border border-light-subtle mt-3 p-2 small mb-0">
                <i class="bi bi-info-circle text-primary me-1"></i>
                <em>Lưu ý khi chạy trên localhost: Nếu PayOS chưa gửi được Webhook về máy local của bạn, hệ thống đã tích hợp sẵn cơ chế tự động đối soát ngay khi khách chuyển khoản thành công.</em>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }

    function copyWebhookUrl(btn) {
        const copyText = document.getElementById("webhookUrlInput");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value).then(function() {
            const originalHtml = btn.innerHTML;
            btn.className = "btn btn-success";
            btn.innerHTML = '<i class="bi bi-check2 me-1"></i> Đã chép!';
            setTimeout(function() {
                btn.className = "btn btn-outline-primary";
                btn.innerHTML = originalHtml;
            }, 2000);
        });
    }
</script>
@endpush
@endsection
