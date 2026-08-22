@extends('layouts.app')
@section('title', 'Hồ sơ cá nhân')

@push('styles')
<style>
    .profile-avatar {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border: 4px solid var(--card-bg);
        box-shadow: 0 10px 25px rgba(0,0,0,0.5);
    }
    
    .nav-pills-custom .nav-link {
        color: var(--text-main);
        background: transparent;
        border-radius: 12px;
        padding: 12px 20px;
        margin-bottom: 8px;
        font-weight: 500;
        transition: var(--transition-smooth);
    }
    
    .nav-pills-custom .nav-link:hover {
        background: rgba(0,0,0,0.05);
    }
    
    .nav-pills-custom .nav-link.active {
        background: linear-gradient(135deg, rgba(79, 70, 229, 0.2) 0%, rgba(124, 58, 237, 0.2) 100%);
        color: #fff;
        border: 1px solid rgba(124, 58, 237, 0.3);
    }
    
    .nav-pills-custom .nav-link i {
        width: 24px;
        text-align: center;
        margin-right: 8px;
    }

    . {
        background-color: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.08);
        color: #222222;
    }
    
    .:focus {
        background-color: #16161a;
        border-color: rgba(124, 58, 237, 0.5);
        color: #fff;
        box-shadow: 0 0 0 0.25rem rgba(124, 58, 237, 0.25);
    }
</style>
@endpush

@section('content')
<div class="row">
    <!-- Sidebar (Tabs) -->
    <div class="col-md-3 mb-4">
        <div class="card card-premium p-4 text-center mb-4">
            <div class="position-relative d-inline-block mx-auto mb-3">
                <img src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random&color=fff&size=150' }}" 
                     alt="Avatar" class="rounded-circle profile-avatar">
                
                <button type="button" class="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle" 
                        style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;"
                        data-bs-toggle="modal" data-bs-target="#avatarModal">
                    <i class="bi bi-camera-fill"></i>
                </button>
            </div>
            <h4 class="fw-bold text-dark mb-1">{{ $user->name }}</h4>
            <p class="text-muted small mb-0">{{ $user->email }}</p>
            @if($user->role === 'admin')
                <span class="badge bg-warning text-dark mt-2">Quản trị viên</span>
            @endif
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
    <div class="col-md-9">
        <div class="tab-content" id="v-pills-tabContent">
            
            <!-- Tab: Thông tin chung -->
            <div class="tab-pane fade show active" id="v-pills-info" role="tabpanel" aria-labelledby="v-pills-info-tab">
                <div class="card card-premium p-4">
                    <h4 class="fw-bold mb-4 text-dark"><i class="bi bi-person-lines-fill me-2 text-primary"></i>Hồ sơ của tôi</h4>
                    
                    <form action="{{ route('profile.updateInfo') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-secondary small fw-bold">Họ và tên</label>
                                <input type="text" name="name" class="form-control  @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-secondary small fw-bold">Địa chỉ Email</label>
                                <input type="email" name="email" class="form-control  @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-secondary small fw-bold">Số điện thoại</label>
                                <input type="text" name="phone" class="form-control  @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" placeholder="Nhập số điện thoại...">
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-secondary small fw-bold">Ngày tham gia</label>
                                <input type="text" class="form-control  text-dark" value="{{ $user->created_at->format('d/m/Y') }}" readonly style="opacity: 1;">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-secondary small fw-bold">Địa chỉ giao hàng mặc định</label>
                            <textarea name="address" class="form-control  @error('address') is-invalid @enderror" rows="3" placeholder="Nhập địa chỉ của bạn...">{{ old('address', $user->address) }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-premium px-4"><i class="bi bi-save me-2"></i>Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>



            <!-- Tab: Bảo mật (Đổi mật khẩu) -->
            <div class="tab-pane fade" id="v-pills-security" role="tabpanel" aria-labelledby="v-pills-security-tab">
                <div class="card card-premium p-4">
                    <h4 class="fw-bold mb-4 text-dark"><i class="bi bi-shield-lock-fill me-2 text-primary"></i>Đổi mật khẩu</h4>
                    
                    <form action="{{ route('profile.updatePassword') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-bold">Mật khẩu hiện tại</label>
                            <input type="password" name="current_password" class="form-control  @error('current_password') is-invalid @enderror" required>
                            @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-secondary small fw-bold">Mật khẩu mới</label>
                                <input type="password" name="new_password" class="form-control  @error('new_password') is-invalid @enderror" required>
                                @error('new_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label text-secondary small fw-bold">Xác nhận mật khẩu mới</label>
                                <input type="password" name="new_password_confirmation" class="form-control " required>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-premium px-4"><i class="bi bi-key me-2"></i>Đổi mật khẩu</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal Đổi Avatar -->
<div class="modal fade" id="avatarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content card-premium border-0">
            <div class="modal-header border-bottom border-secondary">
                <h5 class="modal-title text-dark">Đổi ảnh đại diện</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('profile.updateAvatar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="user_avatar_input" class="form-label text-secondary small fw-bold">Chọn ảnh mới (Sẽ được căn chỉnh)</label>
                        <input class="form-control" type="file" id="user_avatar_input" accept="image/*" required>
                        <input type="hidden" name="avatar_base64" id="user_avatar_base64">
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Tải lên</button>
                </div>
            </form>
        </div>
    </div>
</div>



@push('scripts')
<script>
    // Di chuyển các modal ra ngoài thẻ main (chứa css transform) để tránh lỗi z-index backdrop làm đơ màn hình
    document.addEventListener("DOMContentLoaded", function() {
        document.body.appendChild(document.getElementById('avatarModal'));


        // Initialize Cropper for User Avatar
        initImageCropper('user_avatar_input', 'user_avatar_base64', 1);
    });
</script>
@endpush

@endsection
