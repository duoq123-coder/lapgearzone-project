@extends('admin.layouts.app')
@section('title', 'Hồ sơ cá nhân - Admin')

@push('styles')
<style>
    .profile-card {
        border: 1px solid rgba(205, 76, 32, 0.25) !important;
        border-radius: 12px;
        background: var(--card-bg, #ffffff);
    }

    .profile-avatar {
        width: 180px;
        height: 180px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #ffffff;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    }

    /* NAV PILLS */
    .nav-pills-custom .nav-link {
        color: var(--text-muted, #71717a);
        background: transparent;
        border-radius: 50rem;
        padding: 10px 20px;
        margin-bottom: 6px;
        font-weight: 600;
        font-size: 0.88rem;
        transition: var(--transition-smooth, all 0.2s ease);
        display: flex;
        align-items: center;
        border: 1px solid transparent;
    }
    
    .nav-pills-custom .nav-link:hover {
        background: var(--surface-muted, #f4f4f5);
        color: var(--text-main, #18181b);
    }
    
    .nav-pills-custom .nav-link.active {
        background: #18181b !important;
        color: #ffffff !important;
        border-color: #18181b !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    }
    
    .nav-pills-custom .nav-link i {
        width: 22px;
        margin-right: 8px;
        font-size: 1.1rem;
    }

    .banner-edit-btn {
        transition: transform 0.2s ease, background-color 0.2s ease;
    }
    .banner-edit-btn:hover {
        transform: scale(1.05);
        background-color: rgba(0, 0, 0, 0.95) !important;
    }

    .btn-save-custom {
        background-color: #cd4c20 !important;
        border-color: #cd4c20 !important;
        color: #ffffff !important;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        font-size: 0.85rem;
        border-radius: 8px;
        padding: 11px 26px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    .btn-save-custom:hover {
        background-color: #b85021 !important;
        border-color: #b85021 !important;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(205, 76, 32, 0.25);
    }

    .profile-card .form-control {
        border-radius: 6px !important;
        padding: 10px 14px;
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
<div class="row g-4 justify-content-center">
    <!-- Sidebar (Tabs) -->
    <div class="col-lg-4 col-md-5">
        <div class="card profile-card text-center mb-4 overflow-hidden p-0">
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
                        title="Đổi nền / video">
                    <i class="bi bi-camera" style="font-size: 15px; color: #ffffff;"></i>
                </button>
            </div>

            <div class="px-4 pb-4" style="margin-top: -90px;">
                <div class="position-relative d-inline-block mx-auto mb-3" style="width: 180px; height: 180px;">
                    <!-- Khung Avatar Động (Nhận diện .png, .gif, .svg) nếu có -->
                    @php
                        $pngFramePath = public_path('images/avatar-frame.png');
                        $gifFramePath = public_path('images/avatar-frame.gif');
                        $svgFramePath = public_path('images/avatar-frame.svg');
                        
                        $frameUrl = null;
                        if (file_exists($pngFramePath)) {
                            $frameUrl = asset('images/avatar-frame.png?v=' . filemtime($pngFramePath));
                        } elseif (file_exists($gifFramePath)) {
                            $frameUrl = asset('images/avatar-frame.gif?v=' . filemtime($gifFramePath));
                        } elseif (file_exists($svgFramePath)) {
                            $frameUrl = asset('images/avatar-frame.svg?v=' . filemtime($svgFramePath));
                        }
                    @endphp
                    @if($frameUrl)
                    <img src="{{ $frameUrl }}" 
                         alt="Avatar Frame" 
                         class="position-absolute"
                         style="width: 216px; height: 216px; top: -18px; left: -18px; z-index: 3; pointer-events: none;">
                    @endif

                    <img src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=f4f1ea&color=232220&size=150' }}" 
                         alt="Avatar" class="rounded-circle profile-avatar bg-white w-100 h-100 m-0" style="position: relative; z-index: 2;">
                    
                    <button type="button" class="btn btn-dark position-absolute rounded-circle d-flex align-items-center justify-content-center shadow" 
                            style="width: 32px; height: 32px; padding: 0; border: 2px solid #fff; z-index: 4; bottom: 10px; right: 10px; flex-shrink: 0;" 
                            data-bs-toggle="modal" data-bs-target="#avatarModal"
                            title="Đổi ảnh đại diện">
                        <i class="bi bi-camera" style="font-size: 15px;"></i>
                    </button>
                </div>
                
                <h4 class="mb-1 text-dark fw-bold" style="position: relative; z-index: 5; font-size: 1.4rem;">{{ $user->name }}</h4>
                <p class="text-muted small mb-2">{{ $user->email }}</p>
                
                @if($user->role === 'admin')
                    <span class="badge" style="background: #edf5f1; color: #4e7969; font-weight: 700; font-size: 0.72rem; letter-spacing: 0.12em; padding: 6px 14px; border-radius: 4px; text-transform: uppercase;">QUẢN TRỊ VIÊN</span>
                @elseif($user->role === 'delivery')
                    <span class="badge" style="background: #edf5f1; color: #4e7969; font-weight: 700; font-size: 0.72rem; letter-spacing: 0.12em; padding: 6px 14px; border-radius: 4px; text-transform: uppercase;">NHÂN VIÊN GIAO HÀNG</span>
                @else
                    <span class="badge" style="background: #edf5f1; color: #4e7969; font-weight: 700; font-size: 0.72rem; letter-spacing: 0.12em; padding: 6px 14px; border-radius: 4px; text-transform: uppercase;">NHÂN VIÊN HỆ THỐNG</span>
                @endif
            </div>
        </div>

        <div class="nav flex-column nav-pills nav-pills-custom" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <button class="nav-link active text-start" id="v-pills-info-tab" data-bs-toggle="pill" data-bs-target="#v-pills-info" type="button" role="tab" aria-controls="v-pills-info" aria-selected="true">
                <i class="bi bi-person-vcard"></i> Thông tin chung
            </button>

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
                <div class="card profile-card p-4">
                    <h4 class="fw-bold mb-4 text-dark d-flex align-items-center" style="font-size: 1.35rem;">
                        <i class="bi bi-person-lines-fill me-2" style="color: #cd4c20; font-size: 1.4rem;"></i>
                        Hồ sơ nhân viên
                    </h4>
                    
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
                            <button type="submit" class="btn btn-save-custom">
                                <i class="bi bi-check2 me-1" style="font-size: 1.1rem; stroke-width: 1.5;"></i> LƯU THAY ĐỔI
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tab: Đổi mật khẩu -->
            <div class="tab-pane fade" id="v-pills-security" role="tabpanel" aria-labelledby="v-pills-security-tab">
                <div class="card profile-card p-4">
                    <h4 class="fw-bold mb-4 text-dark d-flex align-items-center" style="font-size: 1.35rem;">
                        <i class="bi bi-shield-lock me-2" style="color: #cd4c20; font-size: 1.4rem;"></i>
                        Đổi mật khẩu
                    </h4>
                    
                    <form action="{{ route('profile.updatePassword') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label text-dark small fw-bold">Mật khẩu hiện tại</label>
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                            @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-dark small fw-bold">Mật khẩu mới</label>
                                <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" required>
                                @error('new_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label text-dark small fw-bold">Xác nhận mật khẩu mới</label>
                                <input type="password" name="new_password_confirmation" class="form-control" required>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-save-custom">
                                <i class="bi bi-key me-1" style="font-size: 1.1rem;"></i> ĐỔI MẬT KHẨU
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal Đổi Avatar (Có Crop) -->
<div class="modal fade" id="avatarModal" tabindex="-1" aria-labelledby="avatarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content profile-card border-0 shadow-lg">
            <div class="modal-header border-bottom p-3 px-4" style="border-color: var(--border-color, rgba(205, 76, 32, 0.2)) !important;">
                <h5 class="modal-title fw-bold" id="avatarModalLabel" style="color: var(--text-main, inherit);">
                    <i class="bi bi-camera-fill me-2" style="color: #cd4c20;"></i>Đổi ảnh đại diện
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
                                 style="width: 140px; height: 140px; object-fit: cover;">
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
                <div class="modal-footer border-top p-3 px-4" style="border-color: var(--border-color, rgba(205, 76, 32, 0.2)) !important;">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-save-custom" id="btnSubmitAvatar">
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
        <div class="modal-content profile-card border-0 shadow-lg">
            <div class="modal-header border-bottom p-3 px-4" style="border-color: var(--border-color, rgba(205, 76, 32, 0.2)) !important;">
                <h5 class="modal-title fw-bold" id="bannerModalLabel" style="color: var(--text-main, inherit);">
                    <i class="bi bi-camera-video me-2" style="color: #cd4c20;"></i>Đổi ảnh bìa / Video nền
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('profile.updateBanner') }}" method="POST" enctype="multipart/form-data" id="bannerForm">
                @csrf
                <input type="hidden" name="banner_base64" id="user_banner_base64">
                <div class="modal-body p-4">
                    <!-- Ảnh / Video xem trước -->
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block w-100 rounded-3 overflow-hidden border shadow-sm" style="height: 140px; border-color: var(--border-color, rgba(205, 76, 32, 0.2)) !important;" id="bannerPreviewContainer">
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
                                <i class="bi bi-cloud-arrow-up-fill fs-5" id="uploadStatusIcon" style="color: #cd4c20;"></i>
                                <span id="uploadStatusMessage">Sẵn sàng tải lên</span>
                            </span>
                            <span class="badge px-2 py-1 font-monospace fs-6 fw-bold" id="uploadPercentBadge" style="background: #cd4c20; color: #ffffff;">0%</span>
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
                <div class="modal-footer border-top p-3 px-4" style="border-color: var(--border-color, rgba(205, 76, 32, 0.2)) !important;">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-save-custom" id="btnSubmitBanner">
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
                    uploadStatusIcon.style.color = '#cd4c20';
                }
                if (statusMessage) statusMessage.innerText = 'Sẵn sàng: ' + file.name;
                if (percentBadge) {
                    percentBadge.innerText = '0%';
                    percentBadge.style.background = '#cd4c20';
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
                        uploadStatusIcon.style.color = '#cd4c20';
                    }
                    if (uploadSpinner) uploadSpinner.classList.add('d-none');
                    if (statusMessage) statusMessage.innerText = 'Sẵn sàng: Ảnh bìa đã căn chỉnh';
                    if (percentBadge) {
                        percentBadge.innerText = '0%';
                        percentBadge.style.background = '#cd4c20';
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
                    percentBadge.style.background = '#cd4c20';
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
                        btnSubmitBanner.className = 'btn btn-save-custom';
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
                    btnSubmitBanner.className = 'btn btn-save-custom';
                    btnSubmitBanner.innerHTML = '<i class="bi bi-check2-circle me-1"></i> Cập nhật nền';
                }
            });
        }
    });
</script>
@endpush
@endsection
