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
            <button class="nav-link text-start" id="v-pills-orders-tab" data-bs-toggle="pill" data-bs-target="#v-pills-orders" type="button" role="tab" aria-controls="v-pills-orders" aria-selected="false">
                <i class="bi bi-bag-check"></i> Lịch sử mua hàng
            </button>
            <button class="nav-link text-start" id="v-pills-wishlist-tab" data-bs-toggle="pill" data-bs-target="#v-pills-wishlist" type="button" role="tab" aria-controls="v-pills-wishlist" aria-selected="false">
                <i class="bi bi-heart-fill text-danger"></i> Sản phẩm yêu thích
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

            <!-- Tab: Lịch sử đơn hàng -->
            <div class="tab-pane fade" id="v-pills-orders" role="tabpanel" aria-labelledby="v-pills-orders-tab">
                <div class="card card-premium p-4">
                    <h4 class="fw-bold mb-4 text-dark"><i class="bi bi-bag-heart-fill me-2 text-primary"></i>Lịch sử mua hàng</h4>
                    
                    @if($orders->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-bag-x display-1 text-muted mb-3 opacity-50"></i>
                            <h5 class="text-secondary">Bạn chưa có đơn hàng nào</h5>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-primary mt-3">Mua sắm ngay</a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="background-color: transparent;">
                                <thead>
                                    <tr>
                                        <th class="text-secondary border-bottom">Mã đơn</th>
                                        <th class="text-secondary border-bottom">Ngày đặt</th>
                                        <th class="text-secondary border-bottom">Tổng tiền</th>
                                        <th class="text-secondary border-bottom">Trạng thái</th>
                                        <th class="text-secondary border-bottom text-end">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                    <tr>
                                        <td class="fw-bold text-dark">#ORD-{{ $order->id }}</td>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-warning fw-bold">{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                                        <td>
                                            @if($order->status == 'pending')
                                                <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Chờ xác nhận</span>
                                            @elseif($order->status == 'paid')
                                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Đã thanh toán</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $order->status }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#orderModal-{{ $order->id }}">
                                                Chi tiết
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tab: Wishlist -->
            <div class="tab-pane fade" id="v-pills-wishlist" role="tabpanel" aria-labelledby="v-pills-wishlist-tab">
                <div class="card card-premium p-4">
                    <h4 class="fw-bold mb-4 text-dark"><i class="bi bi-heart-fill me-2 text-primary"></i>Sản phẩm yêu thích</h4>
                    
                    @if($user->wishlists->isEmpty())
                        <div class="text-center p-5">
                            <i class="bi bi-heart text-muted" style="font-size: 3rem;"></i>
                            <p class="mt-3 text-secondary">Bạn chưa có sản phẩm yêu thích nào.</p>
                            <a href="{{ route('welcome') }}" class="btn btn-primary rounded-pill px-4 mt-2">Khám phá ngay</a>
                        </div>
                    @else
                        <div class="row row-cols-1 row-cols-md-2 g-3">
                            @foreach($user->wishlists as $wishlist)
                            <div class="col">
                                <div class="card bg-white shadow-sm border-0 rounded-3 h-100 d-flex flex-row align-items-center" style="border: 1px solid rgba(0,0,0,0.05) !important;">
                                    @php
                                        $image = $wishlist->product->image ?: ($wishlist->product->images()->first()->image_path ?? null);
                                    @endphp
                                    @if($image)
                                        <img src="{{ asset('storage/'.$image) }}" class="rounded-start" style="width: 100px; height: 100px; object-fit: cover;" alt="...">
                                    @else
                                        <div class="rounded-start bg-secondary d-flex justify-content-center align-items-center" style="width: 100px; height: 100px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="card-body p-3">
                                        <h6 class="card-title text-dark text-truncate mb-1" style="max-width: 150px;">{{ $wishlist->product->name }}</h6>
                                        <p class="card-text text-warning fw-bold mb-2 small">{{ number_format($wishlist->product->price, 0, ',', '.') }}đ</p>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('products.show', $wishlist->product->id) }}" class="btn btn-sm btn-outline-dark rounded-pill px-3">Xem</a>
                                            <button class="btn btn-sm btn-danger rounded-pill px-3" onclick="toggleWishlist(event, this, {{ $wishlist->product->id }}); this.closest('.col').remove();"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
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

<!-- Modals Chi tiết đơn hàng -->
@foreach($orders as $order)
<div class="modal fade" id="orderModal-{{ $order->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content card-premium border-0">
            <div class="modal-header border-bottom border-secondary">
                <h5 class="modal-title text-dark">Chi tiết đơn hàng #ORD-{{ $order->id }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-striped align-middle m-0" style="background-color: transparent;">
                        <thead>
                            <tr>
                                <th class="text-muted border-bottom border-secondary">Sản phẩm</th>
                                <th class="text-muted border-bottom border-secondary">Đơn giá</th>
                                <th class="text-muted border-bottom border-secondary text-center">Số lượng</th>
                                <th class="text-muted border-bottom border-secondary text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->product && $item->product->images->count() > 0)
                                            <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" alt="{{ $item->product->name }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="bi bi-image text-dark"></i>
                                            </div>
                                        @endif
                                        <span class="text-dark">{{ $item->product ? $item->product->name : 'Sản phẩm đã xóa' }}</span>
                                    </div>
                                </td>
                                <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end fw-bold text-warning">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="border-top border-secondary">
                            <tr>
                                <td colspan="3" class="text-end text-muted fw-bold">Tổng cộng:</td>
                                <td class="text-end text-warning fs-5 fw-bold">{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="p-3 bg-white shadow-sm bg-opacity-50">
                    <h6 class="text-muted mb-2">Thông tin giao hàng:</h6>
                    <p class="text-dark mb-1 small"><i class="bi bi-person me-2"></i>{{ $order->name }}</p>
                    <p class="text-dark mb-1 small"><i class="bi bi-telephone me-2"></i>{{ $order->phone }}</p>
                    <p class="text-dark mb-0 small"><i class="bi bi-geo-alt me-2"></i>{{ $order->address }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

@push('scripts')
<script>
    // Di chuyển các modal ra ngoài thẻ main (chứa css transform) để tránh lỗi z-index backdrop làm đơ màn hình
    document.addEventListener("DOMContentLoaded", function() {
        document.body.appendChild(document.getElementById('avatarModal'));
        @foreach($orders as $order)
            document.body.appendChild(document.getElementById('orderModal-{{ $order->id }}'));
        @endforeach

        // Initialize Cropper for User Avatar
        initImageCropper('user_avatar_input', 'user_avatar_base64', 1);
    });
</script>
@endpush

@endsection
