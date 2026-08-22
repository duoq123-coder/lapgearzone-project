@extends('admin.layouts.app')
@section('title', 'Quản lý Voucher')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0 fw-bold text-dark display-font"><i class="bi bi-ticket-perforated-fill me-2 text-warning"></i>Quản lý Voucher (Khuyến mãi)</h2>
    <button type="button" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#createCouponModal">
        <i class="bi bi-plus-circle-fill me-1"></i> Thêm Voucher mới
    </button>
</div>

<div class="card bg-white shadow-sm border-0 rounded-4 shadow-sm" style="border: 1px solid rgba(0,0,0,0.1) !important;">
    <div class="card-body p-0 overflow-hidden rounded-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-light">
                <thead class="table-light border-bottom border-secondary border-opacity-20">
                    <tr>
                        <th width="15%" class="ps-4">Mã Voucher</th>
                        <th width="15%">Loại / Giá trị</th>
                        <th width="15%">Đơn tối thiểu</th>
                        <th width="15%">Yêu cầu hạng</th>
                        <th width="10%">Lượt dùng</th>
                        <th width="10%">Tự động áp dụng</th>
                        <th width="10%">Trạng thái</th>
                        <th width="10%" class="text-center pe-4">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($coupons as $coupon)
                        <tr>
                            <td class="ps-4 fw-bold text-info">{{ $coupon->code }}</td>
                            <td>
                                @if($coupon->type == 'fixed')
                                    <span class="text-success fw-bold">-{{ number_format($coupon->value, 0, ',', '.') }}đ</span>
                                @else
                                    <span class="text-primary fw-bold">-{{ $coupon->value }}%</span>
                                @endif
                            </td>
                            <td>{{ number_format($coupon->min_order_value, 0, ',', '.') }}đ</td>
                            <td>
                                @if($coupon->required_tier)
                                    <span class="badge bg-secondary">{{ $coupon->required_tier }}</span>
                                @else
                                    <span class="badge bg-light text-dark border">Không yêu cầu</span>
                                @endif
                            </td>
                            <td>{{ $coupon->used }} / {{ $coupon->usage_limit ?? '∞' }}</td>
                            <td>
                                @if($coupon->is_auto_apply)
                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Có</span>
                                @else
                                    <span class="badge bg-secondary">Không</span>
                                @endif
                            </td>
                            <td>
                                @if($coupon->is_active)
                                    <span class="badge bg-success">Đang kích hoạt</span>
                                @else
                                    <span class="badge bg-danger">Đã khóa</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-2">
                                    <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold" onclick="return confirm('Bạn có chắc muốn xóa voucher này?')">
                                            <i class="bi bi-trash me-1"></i> Xóa
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-ticket-x fs-1 d-block mb-3 opacity-50"></i>
                                Chưa có voucher nào trong hệ thống.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('modals')
<!-- Modal Thêm Voucher -->
<div class="modal fade" id="createCouponModal" tabindex="-1" aria-labelledby="createCouponModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold display-font" id="createCouponModalLabel">Thêm Voucher mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.coupons.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Mã Voucher</label>
                            <input type="text" name="code" class="form-control" required style="text-transform: uppercase;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Loại giảm giá</label>
                            <select name="type" class="form-select" required>
                                <option value="fixed">Giảm số tiền cố định</option>
                                <option value="percent">Giảm theo %</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Giá trị giảm</label>
                            <input type="number" name="value" class="form-control" required min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Giá trị đơn hàng tối thiểu</label>
                            <input type="number" name="min_order_value" class="form-control" required min="0" value="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Giới hạn số lần dùng</label>
                            <input type="number" name="usage_limit" class="form-control" min="1" placeholder="Bỏ trống nếu không giới hạn">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Yêu cầu hạng (Tùy chọn)</label>
                            <select name="required_tier" class="form-select">
                                <option value="">Không yêu cầu (Áp dụng cho mọi khách)</option>
                                <option value="customer_bronze">Hạng Đồng (Trở lên)</option>
                                <option value="customer_silver">Hạng Bạc (Trở lên)</option>
                                <option value="customer_gold">Hạng Vàng (Trở lên)</option>
                                <option value="customer_diamond">Hạng Kim Cương (Trở lên)</option>
                                <option value="customer_emerald">Hạng Lục Bảo</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tự động áp dụng?</label>
                            <select name="is_auto_apply" class="form-select" required>
                                <option value="0">Không (Khách phải nhập mã)</option>
                                <option value="1">Có (Hệ thống tự tìm voucher tốt nhất để áp dụng)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <select name="is_active" class="form-select" required>
                                <option value="1">Đang kích hoạt</option>
                                <option value="0">Khóa</option>
                            </select>
                        </div>
                    </div>
                    <div class="text-end mt-3">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm">Lưu Voucher</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
