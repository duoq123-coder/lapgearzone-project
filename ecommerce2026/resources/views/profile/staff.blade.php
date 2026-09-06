@extends('layouts.app')
@section('title', 'Hồ sơ nhân viên - Cửa Hàng Công Nghệ')

@push('styles')
<style>
    .profile-avatar {
        width: 130px;
        height: 130px;
        object-fit: cover;
        border: 3px solid var(--border-color);
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
    }    
    .nav-pills-custom .nav-link {
        color: var(--text-muted);
        background: transparent;
        border-radius: 50rem;
        padding: 10px 20px;
        margin-bottom: 6px;
        font-weight: 600;
        font-size: 0.88rem;
        transition: var(--transition-smooth);
        display: flex;
        align-items: center;
    }
    
    .nav-pills-custom .nav-link:hover {
        background: var(--surface-muted);
        color: var(--text-main);
    }
    
    .nav-pills-custom .nav-link.active {
        background: var(--bellroy-charcoal);
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    }
    
    .nav-pills-custom .nav-link i {
        width: 22px;
        margin-right: 8px;
    }

    .banner-edit-btn {
        transition: transform 0.2s ease, background-color 0.2s ease;
    }
    .banner-edit-btn:hover {
        transform: scale(1.04);
        background-color: rgba(0, 0, 0, 0.9) !important;
    }

    /* Thanh tiến độ Upload Đồ Họa Cao Cấp */
    .upload-progress-card {
        background: rgba(205, 76, 32, 0.05);
        border: 1px solid rgba(205, 76, 32, 0.25) !important;
        border-radius: 10px;
        padding: 14px 16px;
        transition: all 0.3s ease;
    }
    [data-bs-theme="dark"] .upload-progress-card {
        background: rgba(205, 76, 32, 0.12);
        border-color: rgba(205, 76, 32, 0.4) !important;
    }
    .upload-progress-track {
        height: 12px;
        border-radius: 6px;
        background-color: rgba(0, 0, 0, 0.08);
        overflow: hidden;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.15);
    }
    [data-bs-theme="dark"] .upload-progress-track {
        background-color: rgba(255, 255, 255, 0.12);
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.4);
    }
    .upload-progress-bar-fill {
        background: linear-gradient(90deg, #ff7b00, #cd4c20);
        box-shadow: 0 0 12px rgba(205, 76, 32, 0.5);
        transition: width 0.15s ease-out;
    }
</style>
@endpush

@section('content')
<div class="container py-4 animate-slide-up">
    <div class="row g-4 justify-content-center">
        <!-- Sidebar (Tabs) -->
        <div class="col-lg-4 col-md-5">
            <div class="card card-premium text-center mb-4 overflow-hidden p-0">
                <!-- Phần nền banner phía trên (Hỗ trợ Ảnh & Video MP4) -->
                <div class="position-relative overflow-hidden group-banner" style="height: 160px;">
                    @if($user->is_banner_video)
                        <video id="currentBannerDisplay" autoplay loop muted playsinline class="w-100 h-100 object-fit-cover position-absolute top-0 start-0" style="z-index: 1;">
                            <source src="{{ $user->banner_url }}" type="video/mp4">
                        </video>
                    @else
                        <img id="currentBannerDisplay" src="{{ $user->banner_url }}" alt="Profile Banner" class="w-100 h-100 object-fit-cover position-absolute top-0 start-0" style="z-index: 1;">
                    @endif
                    
                    <!-- Nút đổi ảnh bìa / video nền icon nhỏ góc dưới phải -->
                    <button type="button" class="btn btn-dark position-absolute rounded-circle d-flex align-items-center justify-content-center shadow-sm banner-edit-btn" 
                            style="width: 32px; height: 32px; padding: 0; z-index: 3; bottom: 12px; right: 12px; border: 2px solid rgba(255,255,255,0.7); background: rgba(24, 24, 27, 0.85); backdrop-filter: blur(4px); cursor: pointer;" 
                            data-bs-toggle="modal" data-bs-target="#bannerModal"
                            title="Đổi ảnh bìa / Video nền">
                        <i class="bi bi-camera" style="font-size: 15px; color: #ffffff;"></i>
                    </button>
                </div>

                <div class="px-4 pb-4" style="margin-top: -90px;">
                    <div class="position-relative d-inline-block mx-auto mb-3" style="width: 180px; height: 180px;">
                        <img src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=f4f1ea&color=232220&size=150' }}" 
                             alt="Avatar" class="rounded-circle profile-avatar bg-white w-100 h-100 m-0" style="position: relative; z-index: 2;">
                        
                        <button type="button" class="btn btn-dark position-absolute rounded-circle d-flex align-items-center justify-content-center shadow" 
                                style="width: 32px; height: 32px; padding: 0; border: 2px solid #fff; z-index: 4; bottom: 10px; right: 10px; flex-shrink: 0;" 
                                data-bs-toggle="modal" data-bs-target="#avatarModal"
                                title="Đổi ảnh đại diện">
                            <i class="bi bi-camera" style="font-size: 15px;"></i>
                        </button>
                    </div>
                    
                    <h4 class="serif-title mb-1 text-dark" style="position: relative; z-index: 5;">{{ $user->name }}</h4>
                    <p class="text-muted small mb-2">{{ $user->email }}</p>
                    
                    @if($user->role === 'admin')
                        <span class="badge badge-terracotta">Quản trị viên</span>
                    @elseif($user->role === 'delivery')
                        <span class="badge badge-sage">Nhân viên giao hàng</span>
                    @else
                        <span class="badge badge-premium">Nhân viên hệ thống</span>
                    @endif
                </div>
            </div>

            <div class="nav flex-column nav-pills nav-pills-custom" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <button class="nav-link active text-start" id="v-pills-info-tab" data-bs-toggle="pill" data-bs-target="#v-pills-info" type="button" role="tab" aria-controls="v-pills-info" aria-selected="true">
                    <i class="bi bi-person-vcard"></i> Thông tin chung
                </button>

                @if($user->role === 'delivery')
                <a href="{{ route('delivery.index') }}" class="nav-link text-start text-decoration-none">
                    <i class="bi bi-truck"></i> Khu vực giao hàng
                </a>
                @endif

                <button class="nav-link text-start" id="v-pills-security-tab" data-bs-toggle="pill" data-bs-target="#v-pills-security" type="button" role="tab" aria-controls="v-pills-security" aria-selected="false">
                    <i class="bi bi-shield-lock"></i> Đổi mật khẩu
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-8 col-md-7">
            <div class="tab-content" id="v-pills-tabContent">
                
                <!-- Tab: Thông tin chung -->
                <div class="tab-pane fade show active" id="v-pills-info" role="tabpanel" aria-labelledby="v-pills-info-tab">
                    <div class="card card-premium p-4">
                        <h4 class="serif-title mb-4 text-dark"><i class="bi bi-person-lines-fill me-2" style="color: var(--bellroy-orange);"></i>Hồ sơ nhân viên</h4>
                        
                        <form action="{{ route('profile.updateInfo') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-dark small fw-bold">Họ và tên</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-dark small fw-bold">Địa chỉ Email</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-dark small fw-bold">Số điện thoại</label>
                                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" placeholder="Nhập số điện thoại...">
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-dark small fw-bold">Ngày tham gia</label>
                                    <input type="text" class="form-control text-muted" value="{{ $user->created_at->format('d/m/Y') }}" readonly style="background-color: #faf9f6;">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-dark small fw-bold">Địa chỉ cư trú</label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3" placeholder="Nhập địa chỉ cư trú của bạn...">{{ old('address', $user->address) }}</textarea>
                                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-premium px-4"><i class="bi bi-check2 me-1"></i>Lưu thay đổi</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tab: Đổi mật khẩu -->
                <div class="tab-pane fade" id="v-pills-security" role="tabpanel" aria-labelledby="v-pills-security-tab">
                    <div class="card card-premium p-4">
                        <h4 class="serif-title mb-4 text-dark"><i class="bi bi-shield-lock me-2" style="color: var(--bellroy-orange);"></i>Đổi mật khẩu</h4>
                        
                        <form id="changePasswordForm" action="{{ route('profile.password.sendOtp') }}" method="POST">
                            @csrf
                            <div id="changePasswordAlert" class="alert d-none p-3 mb-3 small rounded-2"></div>

                            <div class="mb-3">
                                <label class="form-label text-dark small fw-bold">Mật khẩu hiện tại</label>
                                <input type="password" name="current_password" id="input_current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                                <div class="invalid-feedback">@error('current_password') {{ $message }} @enderror</div>
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-dark small fw-bold">Mật khẩu mới</label>
                                    <input type="password" name="new_password" id="input_new_password" class="form-control @error('new_password') is-invalid @enderror" required>
                                    <div class="invalid-feedback">@error('new_password') {{ $message }} @enderror</div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label text-dark small fw-bold">Xác nhận mật khẩu mới</label>
                                    <input type="password" name="new_password_confirmation" id="input_new_password_confirmation" class="form-control" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                                <div class="text-muted small">
                                    <i class="bi bi-shield-lock me-1 text-warning"></i>Yêu cầu xác thực mã OTP qua email trước khi lưu mật khẩu mới.
                                </div>
                                <button type="submit" class="btn btn-premium px-4" id="btnSubmitChangePass">
                                    <span class="spinner-border spinner-border-sm me-1 d-none" id="spinnerChangePass" role="status"></span>
                                    <i class="bi bi-key me-1" id="iconChangePass"></i>
                                    <span id="textChangePass">Đổi mật khẩu</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal Xác thực OTP Đổi Mật Khẩu -->
<div class="modal fade" id="modalPasswordOtp" tabindex="-1" aria-labelledby="modalPasswordOtpLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <div class="modal-content card-premium border-0 shadow-lg" style="border-radius: 4px;">
            <div class="modal-header border-bottom p-3 px-4" style="border-color: var(--border-color) !important;">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2" id="modalPasswordOtpLabel" style="color: var(--text-main, inherit); font-size: 1.05rem;">
                    <i class="bi bi-shield-lock-fill" style="color: var(--bellroy-orange);"></i>
                    <span>Xác thực OTP đổi mật khẩu</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2" style="width: 52px; height: 52px; background: rgba(205, 76, 32, 0.1); color: var(--bellroy-orange);">
                        <i class="bi bi-envelope-check-fill fs-3"></i>
                    </div>
                    <h6 class="fw-bold mb-1" style="color: var(--text-main, inherit);">Kiểm tra hòm thư email</h6>
                    <p class="text-muted small mb-0">
                        Mã OTP gồm 6 chữ số đã được gửi tới email <br>
                        <strong class="font-monospace text-dark" id="otpTargetEmail" style="color: var(--text-main, inherit) !important;">---</strong>
                    </p>
                </div>

                <div id="otpVerifyAlert" class="alert d-none p-2.5 mb-3 small rounded-2 text-start"></div>

                <form id="verifyPasswordOtpForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold mb-1" style="color: var(--text-main, inherit);">Nhập mã xác thực (6 số)</label>
                        <input type="text"
                               id="inputPasswordOtp"
                               name="otp"
                               maxlength="6"
                               pattern="[0-9]{6}"
                               inputmode="numeric"
                               autocomplete="one-time-code"
                               class="form-control form-control-lg text-center font-monospace fw-bold"
                               style="letter-spacing: 10px; font-size: 1.8rem; border-radius: 4px;"
                               placeholder="------"
                               required>
                        <div class="invalid-feedback text-center mt-1" id="otpInputError"></div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3 small">
                        <span class="text-muted">Chưa nhận được mã?</span>
                        <button type="button" class="btn btn-sm btn-link text-decoration-none fw-bold p-0" id="btnResendPasswordOtp" style="color: var(--bellroy-orange);">
                            Gửi lại mã (<span id="resendCountdown">60</span>s)
                        </button>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-premium py-2.5 fw-bold" id="btnSubmitVerifyOtp">
                            <span class="spinner-border spinner-border-sm me-1 d-none" id="spinnerVerifyOtp" role="status"></span>
                            <i class="bi bi-check2-circle me-1" id="iconVerifyOtp"></i>
                            <span id="textVerifyOtp">Xác nhận & Cập nhật</span>
                        </button>
                        <button type="button" class="btn btn-outline-premium py-2" data-bs-dismiss="modal">
                            Hủy bỏ (Giữ mật khẩu cũ)
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top py-2.5 px-4 justify-content-center" style="background: rgba(0,0,0,0.02); border-color: var(--border-color) !important;">
                <small class="text-muted text-center" style="font-size: 0.76rem;">
                    <i class="bi bi-shield-check me-1 text-success"></i>Nếu hủy bỏ hoặc không xác thực, mật khẩu cũ được bảo toàn 100%.
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Modal Đổi Avatar (Có Crop) -->
<div class="modal fade" id="avatarModal" tabindex="-1" aria-labelledby="avatarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content card-premium border-0 shadow-lg">
            <div class="modal-header border-bottom p-3 px-4" style="border-color: var(--border-color) !important;">
                <h5 class="modal-title fw-bold" id="avatarModalLabel" style="color: var(--text-main, inherit);">
                    <i class="bi bi-camera-fill me-2" style="color: var(--bellroy-orange);"></i>Đổi ảnh đại diện
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('profile.updateAvatar') }}" method="POST" enctype="multipart/form-data" id="avatarForm">
                @csrf
                <input type="hidden" name="avatar_base64" id="user_avatar_base64">
                <div class="modal-body p-4">
                    <!-- Ảnh xem trước -->
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <img id="avatarPreviewDisplay" 
                                 src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=f4f1ea&color=232220&size=150' }}" 
                                 alt="Avatar hiện tại" 
                                 class="rounded-circle shadow-sm border border-3" 
                                 style="width: 140px; height: 140px; object-fit: cover; border-color: var(--border-color) !important;">
                        </div>
                        <div class="mt-2 small text-muted" id="avatarPreviewNote">
                            <i class="bi bi-image me-1"></i>Ảnh đại diện hiện tại
                        </div>
                    </div>

                    <!-- Input chọn ảnh từ máy -->
                    <div class="mb-3">
                        <label for="user_avatar_file" class="form-label fw-bold small text-uppercase" style="color: var(--text-main, inherit); letter-spacing: 0.03em;">
                            Chọn ảnh từ thiết bị của bạn
                        </label>
                        <input class="form-control" type="file" id="user_avatar_file" name="avatar" accept="image/*" required>
                        <div class="form-text small mt-1" style="color: var(--text-muted, #71717a);">
                            <i class="bi bi-info-circle me-1"></i>Hỗ trợ JPG, PNG, WEBP, GIF (Tự động mở công cụ cắt ảnh)
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top p-3 px-4" style="border-color: var(--border-color) !important;">
                    <button type="button" class="btn btn-outline-premium" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-premium" id="btnSubmitAvatar">
                        <i class="bi bi-check2-circle me-1"></i> Cập nhật ảnh
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Đổi Banner (Hỗ trợ Ảnh & Video MP4) -->
<div class="modal fade" id="bannerModal" tabindex="-1" aria-labelledby="bannerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content card-premium border-0 shadow-lg">
            <div class="modal-header border-bottom p-3 px-4" style="border-color: var(--border-color) !important;">
                <h5 class="modal-title fw-bold" id="bannerModalLabel" style="color: var(--text-main, inherit);">
                    <i class="bi bi-camera-video me-2" style="color: var(--bellroy-orange);"></i>Đổi ảnh bìa / Video nền
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('profile.updateBanner') }}" method="POST" enctype="multipart/form-data" id="bannerForm">
                @csrf
                <input type="hidden" name="banner_base64" id="user_banner_base64">
                <div class="modal-body p-4">
                    <!-- Ảnh / Video xem trước -->
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block w-100 rounded-3 overflow-hidden border shadow-sm" style="height: 140px; border-color: var(--border-color) !important;" id="bannerPreviewContainer">
                            @if($user->is_banner_video)
                                <video id="bannerPreviewVideo" src="{{ $user->banner_url }}" autoplay loop muted playsinline class="w-100 h-100 object-fit-cover"></video>
                                <img id="bannerPreviewDisplay" src="" alt="Banner xem trước" class="w-100 h-100 object-fit-cover d-none">
                            @else
                                <img id="bannerPreviewDisplay" src="{{ $user->banner_url }}" alt="Banner hiện tại" class="w-100 h-100 object-fit-cover">
                                <video id="bannerPreviewVideo" src="" autoplay loop muted playsinline class="w-100 h-100 object-fit-cover d-none"></video>
                            @endif
                        </div>
                        <div class="mt-2 small" id="bannerPreviewNote" style="color: var(--text-muted, #71717a);">
                            <i class="bi bi-info-circle me-1"></i>Hỗ trợ cả hình ảnh (JPG, PNG, GIF) và Video MP4
                        </div>
                    </div>

                    <!-- Input chọn ảnh hoặc video từ máy -->
                    <div class="mb-3">
                        <label for="user_banner_file" class="form-label fw-bold small text-uppercase" style="color: var(--text-main, inherit); letter-spacing: 0.03em;">
                            Chọn tệp ảnh hoặc video MP4 từ thiết bị
                        </label>
                        <input class="form-control" type="file" id="user_banner_file" name="banner" accept="image/*,video/mp4,video/*" required>
                        <div class="form-text small mt-1" style="color: var(--text-muted, #71717a);">
                            <i class="bi bi-check-circle me-1"></i>Ảnh sẽ mở công cụ cắt tỷ lệ 2.35:1. Video MP4 sẽ tải lên trực tiếp.
                        </div>
                    </div>

                    <!-- THANH TIẾN ĐỘ TẢI LÊN (Upload Progress Bar) -->
                    <div id="bannerUploadProgressWrapper" class="mt-3 upload-progress-card d-none">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small fw-bold d-flex align-items-center gap-2" id="uploadStatusText" style="color: var(--text-main, inherit);">
                                <span class="spinner-border spinner-border-sm text-danger d-none" role="status" id="uploadSpinner"></span>
                                <i class="bi bi-cloud-arrow-up-fill fs-5" id="uploadStatusIcon" style="color: var(--bellroy-orange);"></i>
                                <span id="uploadStatusMessage">Sẵn sàng tải lên</span>
                            </span>
                            <span class="badge px-2 py-1 font-monospace fs-6 fw-bold" id="uploadPercentBadge" style="background: var(--bellroy-orange); color: #ffffff;">0%</span>
                        </div>
                        
                        <div class="progress upload-progress-track position-relative">
                            <div id="bannerProgressBar" class="progress-bar progress-bar-striped upload-progress-bar-fill" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-2 small font-monospace" style="font-size: 0.78rem; color: var(--text-muted, #71717a);">
                            <span id="uploadSizeText">0 MB / 0 MB</span>
                            <span id="uploadSpeedText" class="fw-semibold">Chờ bắt đầu</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top p-3 px-4" style="border-color: var(--border-color) !important;">
                    <button type="button" class="btn btn-outline-premium" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-premium" id="btnSubmitBanner">
                        <i class="bi bi-check2-circle me-1"></i> Cập nhật nền
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const avatarModalEl = document.getElementById('avatarModal');
        const bannerModalEl = document.getElementById('bannerModal');
        
        if (avatarModalEl) document.body.appendChild(avatarModalEl);
        if (bannerModalEl) document.body.appendChild(bannerModalEl);

        const avatarInput = document.getElementById('user_avatar_file');
        const avatarPreview = document.getElementById('avatarPreviewDisplay');
        const avatarNote = document.getElementById('avatarPreviewNote');
        const avatarForm = document.getElementById('avatarForm');
        const btnSubmitAvatar = document.getElementById('btnSubmitAvatar');

        const bannerInput = document.getElementById('user_banner_file');
        const bannerPreviewImg = document.getElementById('bannerPreviewDisplay');
        const bannerPreviewVideo = document.getElementById('bannerPreviewVideo');
        const bannerNote = document.getElementById('bannerPreviewNote');
        const bannerForm = document.getElementById('bannerForm');
        const btnSubmitBanner = document.getElementById('btnSubmitBanner');
        const hiddenBanner = document.getElementById('user_banner_base64');

        const originalAvatarSrc = avatarPreview ? avatarPreview.src : '';
        const originalBannerSrc = bannerPreviewImg ? bannerPreviewImg.src : '';
        const originalBannerVideoSrc = bannerPreviewVideo ? bannerPreviewVideo.src : '';
        const isOriginalBannerVideo = {{ $user->is_banner_video ? 'true' : 'false' }};

        const progressWrapper = document.getElementById('bannerUploadProgressWrapper');
        const progressBar = document.getElementById('bannerProgressBar');
        const percentBadge = document.getElementById('uploadPercentBadge');
        const statusMessage = document.getElementById('uploadStatusMessage');
        const sizeText = document.getElementById('uploadSizeText');
        const speedText = document.getElementById('uploadSpeedText');
        const uploadSpinner = document.getElementById('uploadSpinner');
        const uploadStatusIcon = document.getElementById('uploadStatusIcon');

        // Tích hợp công cụ Crop ảnh
        if (typeof initImageCropper === 'function') {
            initImageCropper('user_avatar_file', 'user_avatar_base64', 1, 'avatarPreviewDisplay');
            initImageCropper('user_banner_file', 'user_banner_base64', 2.35, 'bannerPreviewDisplay');

            if (avatarInput) {
                avatarInput.addEventListener('change', function(e) {
                    const file = e.target.files && e.target.files[0];
                    if (file && avatarNote) {
                        if (file.type === 'image/gif') {
                            avatarNote.innerHTML = '<span class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i>Đã chọn ảnh GIF (giữ nguyên hoạt ảnh)</span>';
                        } else {
                            avatarNote.innerHTML = '<span class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i>Đã chọn và căn chỉnh ảnh</span>';
                        }
                    }
                });
            }
        }

        // Xử lý chọn Banner (Ảnh hoặc Video MP4)
        if (bannerInput) {
            bannerInput.addEventListener('change', function(e) {
                const file = e.target.files && e.target.files[0];
                if (!file) return;

                const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);

                // Hiển thị ngay khối tiến độ ở trạng thái Sẵn sàng
                if (progressWrapper) progressWrapper.classList.remove('d-none');
                if (uploadSpinner) uploadSpinner.classList.add('d-none');
                if (uploadStatusIcon) {
                    uploadStatusIcon.classList.remove('d-none');
                    uploadStatusIcon.className = file.type.startsWith('video/') ? 'bi bi-camera-video-fill fs-5' : 'bi bi-image-fill fs-5';
                    uploadStatusIcon.style.color = 'var(--bellroy-orange)';
                }
                if (statusMessage) statusMessage.innerText = 'Sẵn sàng: ' + file.name;
                if (percentBadge) {
                    percentBadge.innerText = '0%';
                    percentBadge.style.background = 'var(--bellroy-orange)';
                }
                if (progressBar) {
                    progressBar.style.width = '0%';
                    progressBar.className = 'progress-bar progress-bar-striped upload-progress-bar-fill';
                }
                if (sizeText) sizeText.innerText = '0 MB / ' + fileSizeMB + ' MB';
                if (speedText) speedText.innerText = 'Nhấn "Cập nhật nền" để tải';

                if (file.type.startsWith('video/')) {
                    if (hiddenBanner) hiddenBanner.value = '';
                    if (bannerPreviewImg) bannerPreviewImg.classList.add('d-none');
                    if (bannerPreviewVideo) {
                        bannerPreviewVideo.src = URL.createObjectURL(file);
                        bannerPreviewVideo.classList.remove('d-none');
                        bannerPreviewVideo.play().catch(function(){});
                    }
                    if (bannerNote) {
                        bannerNote.innerHTML = '<span class="text-success fw-bold"><i class="bi bi-play-circle-fill me-1"></i>Đã chọn video MP4 (' + fileSizeMB + ' MB)</span>';
                    }
                } else {
                    if (bannerPreviewVideo) {
                        bannerPreviewVideo.classList.add('d-none');
                        bannerPreviewVideo.pause();
                    }
                    if (bannerPreviewImg) {
                        bannerPreviewImg.classList.remove('d-none');
                    }
                    if (file.type === 'image/gif') {
                        if (bannerNote) bannerNote.innerHTML = '<span class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i>Đã chọn ảnh GIF (giữ nguyên hoạt ảnh)</span>';
                    } else {
                        if (bannerNote) bannerNote.innerHTML = '<span class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i>Đã chọn ảnh bìa</span>';
                    }
                }
            });

            // Khi người dùng cắt ảnh xong
            bannerInput.addEventListener('cropApply', function(e) {
                if (bannerPreviewVideo) {
                    bannerPreviewVideo.classList.add('d-none');
                    bannerPreviewVideo.pause();
                }
                if (bannerPreviewImg) bannerPreviewImg.classList.remove('d-none');
                if (bannerNote) bannerNote.innerHTML = '<span class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i>Đã căn chỉnh ảnh bìa (2.35:1)</span>';
                
                if (progressWrapper) {
                    progressWrapper.classList.remove('d-none');
                    if (uploadStatusIcon) {
                        uploadStatusIcon.classList.remove('d-none');
                        uploadStatusIcon.className = 'bi bi-crop fs-5';
                        uploadStatusIcon.style.color = 'var(--bellroy-orange)';
                    }
                    if (uploadSpinner) uploadSpinner.classList.add('d-none');
                    if (statusMessage) statusMessage.innerText = 'Sẵn sàng: Ảnh bìa đã căn chỉnh';
                    if (percentBadge) {
                        percentBadge.innerText = '0%';
                        percentBadge.style.background = 'var(--bellroy-orange)';
                    }
                    if (progressBar) {
                        progressBar.style.width = '0%';
                        progressBar.className = 'progress-bar progress-bar-striped upload-progress-bar-fill';
                    }
                    if (sizeText) sizeText.innerText = 'Đã tối ưu tỷ lệ 2.35:1';
                    if (speedText) speedText.innerText = 'Nhấn "Cập nhật nền" để tải';
                }
            });
        }

        // Trạng thái nút submit avatar
        if (avatarForm && btnSubmitAvatar) {
            avatarForm.addEventListener('submit', function() {
                btnSubmitAvatar.disabled = true;
                btnSubmitAvatar.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang tải lên...';
            });
        }

        // Xử lý upload Banner với THANH TIẾN ĐỘ TRỰC QUAN & REAL-TIME (Progress Bar)
        if (bannerForm) {
            bannerForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const file = bannerInput && bannerInput.files && bannerInput.files[0];
                const base64Data = hiddenBanner ? hiddenBanner.value : '';

                if (!file && !base64Data) {
                    alert('Vui lòng chọn tệp ảnh hoặc video!');
                    return;
                }

                const formData = new FormData(bannerForm);

                if (progressWrapper) progressWrapper.classList.remove('d-none');
                if (progressBar) {
                    progressBar.style.width = '0%';
                    progressBar.className = 'progress-bar progress-bar-striped progress-bar-animated upload-progress-bar-fill';
                }
                if (percentBadge) {
                    percentBadge.innerText = '0%';
                    percentBadge.style.background = 'var(--bellroy-orange)';
                }
                if (statusMessage) statusMessage.innerText = 'Đang tải lên máy chủ...';
                if (uploadSpinner) uploadSpinner.classList.remove('d-none');
                if (uploadStatusIcon) uploadStatusIcon.classList.add('d-none');
                if (speedText) speedText.innerText = 'Đang kết nối...';

                if (btnSubmitBanner) {
                    btnSubmitBanner.disabled = true;
                    btnSubmitBanner.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang tải lên...';
                }
                const btnCancel = bannerModalEl ? bannerModalEl.querySelector('[data-bs-dismiss="modal"]') : null;
                if (btnCancel) btnCancel.disabled = true;

                const startTime = Date.now();
                let lastLoaded = 0;
                let lastTime = startTime;

                const xhr = new XMLHttpRequest();
                xhr.open('POST', bannerForm.action, true);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader('Accept', 'application/json');

                xhr.upload.onprogress = function(event) {
                    if (event.lengthComputable) {
                        const percentComplete = Math.min(100, Math.round((event.loaded / event.total) * 100));
                        if (progressBar) progressBar.style.width = percentComplete + '%';
                        if (percentBadge) percentBadge.innerText = percentComplete + '%';

                        const loadedMB = (event.loaded / (1024 * 1024)).toFixed(1);
                        const totalMB = (event.total / (1024 * 1024)).toFixed(1);
                        if (sizeText) sizeText.innerText = loadedMB + ' MB / ' + totalMB + ' MB';

                        const now = Date.now();
                        const timeDiff = (now - lastTime) / 1000;
                        if (timeDiff >= 0.25) {
                            const bytesDiff = event.loaded - lastLoaded;
                            const speedKB = (bytesDiff / 1024) / timeDiff;
                            if (speedText) {
                                let speedStr = '';
                                if (speedKB >= 1024) {
                                    speedStr = (speedKB / 1024).toFixed(1) + ' MB/s';
                                } else {
                                    speedStr = Math.round(speedKB) + ' KB/s';
                                }
                                const remainingBytes = event.total - event.loaded;
                                const remainingSec = speedKB > 0 ? Math.ceil((remainingBytes / 1024) / speedKB) : 0;
                                const timeStr = remainingSec > 0 ? ' (~' + remainingSec + 's)' : '';
                                speedText.innerText = speedStr + timeStr;
                            }
                            lastLoaded = event.loaded;
                            lastTime = now;
                        }

                        if (percentComplete >= 100) {
                            if (statusMessage) statusMessage.innerText = 'Đang xử lý và lưu tệp trên máy chủ, vui lòng đợi...';
                            if (speedText) speedText.innerText = 'Đang lưu tệp...';
                        }
                    }
                };

                xhr.onload = function() {
                    if (btnCancel) btnCancel.disabled = false;
                    if (xhr.status >= 200 && xhr.status < 300) {
                        try {
                            const res = JSON.parse(xhr.responseText);
                            if (res.success) {
                                if (uploadSpinner) uploadSpinner.classList.add('d-none');
                                if (uploadStatusIcon) {
                                    uploadStatusIcon.classList.remove('d-none');
                                    uploadStatusIcon.className = 'bi bi-check-circle-fill text-success fs-5';
                                }
                                if (statusMessage) statusMessage.innerHTML = '<span class="text-success fw-bold">Tải lên thành công! Đang làm mới...</span>';
                                if (progressBar) {
                                    progressBar.style.width = '100%';
                                    progressBar.className = 'progress-bar bg-success';
                                }
                                if (percentBadge) {
                                    percentBadge.innerText = '100%';
                                    percentBadge.style.background = '#198754';
                                }
                                if (speedText) speedText.innerText = 'Hoàn tất';
                                if (btnSubmitBanner) {
                                    btnSubmitBanner.className = 'btn btn-success';
                                    btnSubmitBanner.innerHTML = '<i class="bi bi-check2-circle me-1"></i> Hoàn tất!';
                                }
                                setTimeout(function() {
                                    window.location.reload();
                                }, 700);
                                return;
                            } else {
                                throw new Error(res.message || 'Có lỗi xảy ra');
                            }
                        } catch (err) {
                            handleUploadError(err.message || 'Lỗi xử lý phản hồi');
                        }
                    } else {
                        let errMsg = 'Tải lên thất bại (Mã ' + xhr.status + ')';
                        try {
                            const res = JSON.parse(xhr.responseText);
                            if (res.message) errMsg = res.message;
                        } catch(e) {}
                        handleUploadError(errMsg);
                    }
                };

                xhr.onerror = function() {
                    if (btnCancel) btnCancel.disabled = false;
                    handleUploadError('Lỗi kết nối mạng khi tải tệp.');
                };

                function handleUploadError(msg) {
                    if (uploadSpinner) uploadSpinner.classList.add('d-none');
                    if (uploadStatusIcon) {
                        uploadStatusIcon.classList.remove('d-none');
                        uploadStatusIcon.className = 'bi bi-exclamation-triangle-fill text-danger fs-5';
                    }
                    if (statusMessage) statusMessage.innerHTML = '<span class="text-danger fw-bold">' + msg + '</span>';
                    if (progressBar) {
                        progressBar.className = 'progress-bar bg-danger';
                        progressBar.style.background = '#dc3545';
                    }
                    if (percentBadge) percentBadge.style.background = '#dc3545';
                    if (speedText) speedText.innerText = 'Đã dừng';
                    if (btnSubmitBanner) {
                        btnSubmitBanner.disabled = false;
                        btnSubmitBanner.className = 'btn btn-premium';
                        btnSubmitBanner.innerHTML = '<i class="bi bi-arrow-repeat me-1"></i> Thử lại';
                    }
                    if (btnCancel) btnCancel.disabled = false;
                }

                xhr.send(formData);
            });
        }

        // Reset khi đóng modal
        if (avatarModalEl) {
            avatarModalEl.addEventListener('hidden.bs.modal', function () {
                if (avatarInput) {
                    avatarInput.value = '';
                    avatarInput.setAttribute('required', 'required');
                }
                const hiddenAvatar = document.getElementById('user_avatar_base64');
                if (hiddenAvatar) hiddenAvatar.value = '';
                if (avatarPreview) avatarPreview.src = originalAvatarSrc;
                if (avatarNote) avatarNote.innerHTML = '<i class="bi bi-image me-1"></i>Ảnh đại diện hiện tại';
                if (btnSubmitAvatar) {
                    btnSubmitAvatar.disabled = false;
                    btnSubmitAvatar.innerHTML = '<i class="bi bi-check2-circle me-1"></i> Cập nhật ảnh';
                }
            });
        }

        if (bannerModalEl) {
            bannerModalEl.addEventListener('hidden.bs.modal', function () {
                if (bannerInput) {
                    bannerInput.value = '';
                    bannerInput.setAttribute('required', 'required');
                }
                if (hiddenBanner) hiddenBanner.value = '';
                if (isOriginalBannerVideo) {
                    if (bannerPreviewVideo) {
                        bannerPreviewVideo.src = originalBannerVideoSrc;
                        bannerPreviewVideo.classList.remove('d-none');
                    }
                    if (bannerPreviewImg) bannerPreviewImg.classList.add('d-none');
                } else {
                    if (bannerPreviewImg) {
                        bannerPreviewImg.src = originalBannerSrc;
                        bannerPreviewImg.classList.remove('d-none');
                    }
                    if (bannerPreviewVideo) bannerPreviewVideo.classList.add('d-none');
                }
                if (bannerNote) bannerNote.innerHTML = '<i class="bi bi-info-circle me-1"></i>Hỗ trợ cả hình ảnh (JPG, PNG, GIF) và Video MP4';
                
                if (progressWrapper) progressWrapper.classList.add('d-none');

                if (btnSubmitBanner) {
                    btnSubmitBanner.disabled = false;
                    btnSubmitBanner.className = 'btn btn-premium';
                    btnSubmitBanner.innerHTML = '<i class="bi bi-check2-circle me-1"></i> Cập nhật nền';
                }
            });
        }
        // ==========================================
        // QUY TRÌNH ĐỔI MẬT KHẨU CÓ XÁC THỰC MÃ OTP
        // ==========================================
        const modalPasswordOtpEl = document.getElementById('modalPasswordOtp');
        let modalPasswordOtp = null;
        if (modalPasswordOtpEl) {
            document.body.appendChild(modalPasswordOtpEl);
            modalPasswordOtp = new bootstrap.Modal(modalPasswordOtpEl);
        }

        const formChangePass = document.getElementById('changePasswordForm');
        const btnSubmitChangePass = document.getElementById('btnSubmitChangePass');
        const spinnerChangePass = document.getElementById('spinnerChangePass');
        const iconChangePass = document.getElementById('iconChangePass');
        const textChangePass = document.getElementById('textChangePass');
        const alertChangePass = document.getElementById('changePasswordAlert');

        const formVerifyOtp = document.getElementById('verifyPasswordOtpForm');
        const inputOtp = document.getElementById('inputPasswordOtp');
        const btnVerifyOtp = document.getElementById('btnSubmitVerifyOtp');
        const spinnerVerifyOtp = document.getElementById('spinnerVerifyOtp');
        const iconVerifyOtp = document.getElementById('iconVerifyOtp');
        const textVerifyOtp = document.getElementById('textVerifyOtp');
        const alertVerifyOtp = document.getElementById('otpVerifyAlert');
        const btnResendOtp = document.getElementById('btnResendPasswordOtp');
        const targetEmailEl = document.getElementById('otpTargetEmail');

        let resendTimer = null;
        let resendSeconds = 60;

        function startResendCountdown() {
            clearInterval(resendTimer);
            resendSeconds = 60;
            if (!btnResendOtp) return;
            btnResendOtp.disabled = true;
            btnResendOtp.classList.add('disabled');
            btnResendOtp.innerHTML = `Gửi lại mã (<span id="resendCountdown">${resendSeconds}</span>s)`;

            resendTimer = setInterval(() => {
                resendSeconds--;
                const spanCd = document.getElementById('resendCountdown');
                if (spanCd) spanCd.textContent = resendSeconds;
                if (resendSeconds <= 0) {
                    clearInterval(resendTimer);
                    btnResendOtp.disabled = false;
                    btnResendOtp.classList.remove('disabled');
                    btnResendOtp.innerHTML = '<i class="bi bi-arrow-repeat me-1"></i>Gửi lại mã OTP';
                }
            }, 1000);
        }

        if (inputOtp) {
            inputOtp.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                if (this.value.length === 6) {
                    this.classList.remove('is-invalid');
                }
            });
        }

        // 1. Nhập mật khẩu cũ & mới -> Bấm Đổi mật khẩu -> Gửi OTP
        if (formChangePass) {
            formChangePass.addEventListener('submit', async function(e) {
                e.preventDefault();

                formChangePass.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                formChangePass.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
                if (alertChangePass) {
                    alertChangePass.classList.add('d-none');
                    alertChangePass.className = 'alert d-none p-3 mb-3 small rounded-2';
                    alertChangePass.textContent = '';
                }

                if (btnSubmitChangePass) btnSubmitChangePass.disabled = true;
                if (spinnerChangePass) spinnerChangePass.classList.remove('d-none');
                if (iconChangePass) iconChangePass.classList.add('d-none');
                if (textChangePass) textChangePass.textContent = 'Đang gửi mã OTP...';

                const formData = new FormData(formChangePass);

                try {
                    const response = await fetch("{{ route('profile.password.sendOtp') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        if (response.status === 422 && data.errors) {
                            for (const [key, messages] of Object.entries(data.errors)) {
                                const field = formChangePass.querySelector(`[name="${key}"]`);
                                if (field) {
                                    field.classList.add('is-invalid');
                                    let feedback = field.parentNode.querySelector('.invalid-feedback');
                                    if (!feedback) {
                                        feedback = document.createElement('div');
                                        feedback.className = 'invalid-feedback';
                                        field.parentNode.appendChild(feedback);
                                    }
                                    feedback.textContent = messages[0];
                                }
                            }
                        } else {
                            if (alertChangePass) {
                                alertChangePass.classList.remove('d-none');
                                alertChangePass.className = 'alert alert-danger p-3 mb-3 small rounded-2 fw-semibold';
                                alertChangePass.innerHTML = `<i class="bi bi-exclamation-circle me-1"></i>${data.message || 'Có lỗi xảy ra, vui lòng thử lại.'}`;
                            }
                        }
                        return;
                    }

                    // Gửi OTP thành công -> Chuẩn bị và mở Modal
                    if (targetEmailEl) targetEmailEl.textContent = data.masked_email || '';
                    if (inputOtp) {
                        inputOtp.value = '';
                        inputOtp.classList.remove('is-invalid');
                    }
                    if (alertVerifyOtp) {
                        alertVerifyOtp.classList.add('d-none');
                        alertVerifyOtp.textContent = '';
                    }

                    startResendCountdown();

                    if (modalPasswordOtp) {
                        modalPasswordOtp.show();
                        setTimeout(() => { if (inputOtp) inputOtp.focus(); }, 400);
                    }
                } catch (err) {
                    console.error('Send OTP error:', err);
                    if (alertChangePass) {
                        alertChangePass.classList.remove('d-none');
                        alertChangePass.className = 'alert alert-danger p-3 mb-3 small rounded-2 fw-semibold';
                        alertChangePass.innerHTML = '<i class="bi bi-wifi-off me-1"></i>Lỗi kết nối máy chủ. Vui lòng kiểm tra lại đường truyền mạng.';
                    }
                } finally {
                    if (btnSubmitChangePass) btnSubmitChangePass.disabled = false;
                    if (spinnerChangePass) spinnerChangePass.classList.add('d-none');
                    if (iconChangePass) iconChangePass.classList.remove('d-none');
                    if (textChangePass) textChangePass.textContent = 'Đổi mật khẩu';
                }
            });
        }

        // 2. Gửi lại mã OTP
        if (btnResendOtp) {
            btnResendOtp.addEventListener('click', async function() {
                if (btnResendOtp.disabled) return;
                btnResendOtp.disabled = true;
                btnResendOtp.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Đang gửi lại...';

                try {
                    const response = await fetch("{{ route('profile.password.resendOtp') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();

                    if (alertVerifyOtp) {
                        alertVerifyOtp.classList.remove('d-none');
                        alertVerifyOtp.className = 'alert p-2.5 mb-3 small rounded-2 ' + (data.success ? 'alert-success fw-semibold' : 'alert-danger fw-semibold');
                        alertVerifyOtp.innerHTML = (data.success ? '<i class="bi bi-check-circle me-1"></i>' : '<i class="bi bi-exclamation-circle me-1"></i>') + data.message;
                    }

                    if (data.success) {
                        startResendCountdown();
                    } else {
                        btnResendOtp.disabled = false;
                        btnResendOtp.innerHTML = '<i class="bi bi-arrow-repeat me-1"></i>Gửi lại mã OTP';
                    }
                } catch (err) {
                    btnResendOtp.disabled = false;
                    btnResendOtp.innerHTML = '<i class="bi bi-arrow-repeat me-1"></i>Gửi lại mã OTP';
                }
            });
        }

        // 3. Xác thực OTP & Cập nhật mật khẩu
        if (formVerifyOtp) {
            formVerifyOtp.addEventListener('submit', async function(e) {
                e.preventDefault();

                const otpVal = inputOtp ? inputOtp.value.trim() : '';

                if (!otpVal || otpVal.length !== 6) {
                    if (inputOtp) inputOtp.classList.add('is-invalid');
                    const errFeedback = document.getElementById('otpInputError');
                    if (errFeedback) errFeedback.textContent = 'Vui lòng nhập đủ 6 chữ số mã OTP.';
                    return;
                }

                if (inputOtp) inputOtp.classList.remove('is-invalid');
                if (btnVerifyOtp) btnVerifyOtp.disabled = true;
                if (spinnerVerifyOtp) spinnerVerifyOtp.classList.remove('d-none');
                if (iconVerifyOtp) iconVerifyOtp.classList.add('d-none');
                if (textVerifyOtp) textVerifyOtp.textContent = 'Đang xác thực...';

                try {
                    const response = await fetch("{{ route('profile.password.verifyOtp') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ otp: otpVal })
                    });

                    const data = await response.json();

                    if (!data.success) {
                        if (alertVerifyOtp) {
                            alertVerifyOtp.classList.remove('d-none');
                            alertVerifyOtp.className = 'alert alert-danger p-2.5 mb-3 small rounded-2 fw-semibold';
                            alertVerifyOtp.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-1"></i>${data.message}`;
                        }
                        if (inputOtp) {
                            inputOtp.classList.add('is-invalid');
                            inputOtp.select();
                        }
                        return;
                    }

                    // THÀNH CÔNG: Đóng modal, reset form, hiển thị thông báo
                    if (modalPasswordOtp) modalPasswordOtp.hide();
                    if (formChangePass) formChangePass.reset();

                    if (alertChangePass) {
                        alertChangePass.classList.remove('d-none');
                        alertChangePass.className = 'alert alert-success p-3 mb-3 small rounded-2 fw-bold text-success';
                        alertChangePass.innerHTML = `<i class="bi bi-check-circle-fill me-2 fs-6"></i>${data.message}`;
                        alertChangePass.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
                } catch (err) {
                    console.error('Verify OTP error:', err);
                    if (alertVerifyOtp) {
                        alertVerifyOtp.classList.remove('d-none');
                        alertVerifyOtp.className = 'alert alert-danger p-2.5 mb-3 small rounded-2 fw-semibold';
                        alertVerifyOtp.innerHTML = '<i class="bi bi-wifi-off me-1"></i>Lỗi kết nối khi xác thực OTP. Vui lòng thử lại.';
                    }
                } finally {
                    if (btnVerifyOtp) btnVerifyOtp.disabled = false;
                    if (spinnerVerifyOtp) spinnerVerifyOtp.classList.add('d-none');
                    if (iconVerifyOtp) iconVerifyOtp.classList.remove('d-none');
                    if (textVerifyOtp) textVerifyOtp.textContent = 'Xác nhận & Cập nhật';
                }
            });
        }
    });
</script>
@endpush
@endsection