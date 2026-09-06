@extends('admin.layouts.app')
@section('title', 'Quản lý Voucher - Admin')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    [data-bs-theme="light"] .coupon-status-active { background-color: #d9f0e3 !important; color: #216a48 !important; border: 1px solid #a9d9bd; }
    [data-bs-theme="light"] .coupon-status-inactive { background-color: #ececf0 !important; color: #51515c !important; border: 1px solid #cbcbd3; }
    [data-bs-theme="dark"] .coupon-status-active { background-color: #193d2b !important; color: #b8f0cc !important; border: 1px solid #32684b; }
    [data-bs-theme="dark"] .coupon-status-inactive { background-color: #2a2a32 !important; color: #d0d0d8 !important; border: 1px solid #4b4b57; }
    .flatpickr-time input { font-weight: bold; background: transparent; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0 fw-bold text-dark display-font"><i class="bi bi-ticket-perforated me-2" style="color: var(--bellroy-orange);"></i>Quản lý Voucher Khuyến Mãi</h2>
        <p class="text-secondary small mb-0">Cấu hình mã giảm giá, chiết khấu và ưu đãi dành cho khách hàng</p>
    </div>
    <button type="button" class="btn btn-premium px-4" data-bs-toggle="modal" data-bs-target="#createCouponModal">
        <i class="bi bi-plus-circle-fill me-1"></i> Thêm Voucher mới
    </button>
</div>

<div class="card overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th width="15%" class="ps-4">Mã Voucher</th>
                    <th width="15%">Mức giảm giá</th>
                    <th width="15%">Đơn tối thiểu</th>
                    <th width="15%">Hạng thành viên</th>
                    <th width="12%">Lượt sử dụng</th>
                    <th width="10%">Tự động áp dụng</th>
                    <th width="10%">Trạng thái</th>
                    <th width="8%" class="text-center pe-4">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($coupons as $coupon)
                    <tr>
                        <td class="ps-4"><span class="badge badge-terracotta fs-6 font-monospace" style="letter-spacing: 1px;">{{ $coupon->code }}</span></td>
                        <td>
                            @if($coupon->type == 'fixed')
                                <span class="fw-bold display-font text-dark">-{{ number_format($coupon->value, 0, ',', '.') }} đ</span>
                            @else
                                <span class="fw-bold display-font text-dark">-{{ $coupon->value }}%</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ number_format($coupon->min_order_value, 0, ',', '.') }} đ</td>
                        <td>
                            @if($coupon->required_tier)
                                <span class="badge badge-premium">{{ $coupon->required_tier }}</span>
                            @else
                                <span class="badge bg-light text-dark border">Tất cả khách hàng</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $coupon->used }} / {{ $coupon->usage_limit ?? '∞' }}</td>
                        <td>
                            @if($coupon->is_auto_apply) <span class="badge badge-sage"><i class="bi bi-check-circle me-1"></i>Có</span>
                            @else <span class="badge bg-light text-muted border">Không</span> @endif
                        </td>
                        <td>
                            @if(!$coupon->is_active)
                                <span class="badge coupon-status-inactive">Tạm khóa</span>
                            @elseif($coupon->isExpired())
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Đã hết hạn</span>
                            @elseif(!$coupon->hasStarted())
                                <span class="badge bg-info-subtle text-info border border-info-subtle">Chưa bắt đầu</span>
                            @elseif($coupon->usage_limit !== null && $coupon->used >= $coupon->usage_limit)
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Hết lượt</span>
                            @else
                                <span class="badge coupon-status-active">Đang kích hoạt</span>
                            @endif
                        </td>
                        <td class="text-center pe-4">
                            <button type="button" class="btn btn-sm btn-link text-primary p-0 text-decoration-none fw-bold me-2 btn-view" 
                                data-bs-toggle="modal" data-bs-target="#viewCouponModal"
                                data-code="{{ $coupon->code }}"
                                data-type="{{ $coupon->type == 'fixed' ? 'Giảm số tiền cố định (VNĐ)' : 'Giảm theo phần trăm (%)' }}"
                                data-value="{{ $coupon->type == 'fixed' ? number_format($coupon->value, 0, ',', '.') . ' đ' : $coupon->value . '%' }}"
                                data-max_discount="{{ $coupon->max_discount_amount ? number_format($coupon->max_discount_amount, 0, ',', '.') . ' đ' : 'Không giới hạn' }}"
                                data-min_order="{{ number_format($coupon->min_order_value, 0, ',', '.') }} đ"
                                data-usage="{{ $coupon->used }} / {{ $coupon->usage_limit ?? 'Không giới hạn' }}"
                                data-starts_at="{{ !empty($coupon->starts_at) ? $coupon->starts_at->format('H:i d/m/Y') : 'Ngay lập tức (Khi tạo)' }}"
                                data-ends_at="{{ !empty($coupon->ends_at) ? $coupon->ends_at->format('H:i d/m/Y') : 'Không thời hạn' }}"
                                data-tier="{{ $coupon->required_tier ?? 'Tất cả khách hàng' }}"
                                data-auto="{{ $coupon->is_auto_apply ? 'Có' : 'Không' }}"
                                data-status="{{ !$coupon->is_active ? 'Tạm khóa' : ($coupon->isExpired() ? 'Đã hết hạn' : (!$coupon->hasStarted() ? 'Chưa bắt đầu' : ($coupon->usage_limit !== null && $coupon->used >= $coupon->usage_limit ? 'Hết lượt dùng' : 'Đang kích hoạt'))) }}"
                                title="Xem chi tiết"><i class="bi bi-eye fs-6"></i></button>

                            <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-link text-danger p-0 text-decoration-none fw-bold" onclick="return confirm('Xóa voucher này?')" title="Xóa">
                                    <i class="bi bi-trash fs-6"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-5"><i class="bi bi-ticket-x fs-1 d-block mb-3 opacity-50"></i>Chưa có voucher nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('modals')
<!-- Modal Thêm Voucher -->
<div class="modal fade" id="createCouponModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content card-premium border-0 shadow-lg">
            <div class="modal-header border-bottom p-3 px-4">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-ticket-perforated me-2" style="color: var(--bellroy-orange);"></i>Tạo Voucher Khuyến Mãi Mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('admin.coupons.store') }}" method="POST" id="couponForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-bold text-dark small">Mã Voucher (Code) *</label>
                            <input type="text" name="code" class="form-control" placeholder="VD: SUMMER50" required style="text-transform: uppercase;">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-bold text-dark small">Hình thức giảm giá *</label>
                            <select name="type" id="discountType" class="form-select" required>
                                <option value="fixed">Giảm số tiền cố định (VNĐ)</option>
                                <option value="percent">Giảm theo phần trăm (%)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-bold text-dark small" id="discountValueLabel">Giá trị giảm (VNĐ) *</label>
                            <input type="number" name="value" id="discountValue" class="form-control" placeholder="VD: 50000" required min="1">
                        </div>
                        <div class="col-md-6 mb-2" id="maxDiscountWrapper" style="display: none;">
                            <label class="form-label fw-bold text-dark small">Mức giảm tối đa (VNĐ)</label>
                            <input type="number" name="max_discount_amount" class="form-control" placeholder="Không giới hạn" min="0">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-bold text-dark small">Giá trị đơn tối thiểu (VNĐ)</label>
                            <input type="number" name="min_order_value" id="minOrderValue" class="form-control" required min="0" value="0">
                            <div class="invalid-feedback">Đơn tối thiểu phải lớn hơn hoặc bằng giá trị giảm.</div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-bold text-dark small">Giới hạn số lượt dùng</label>
                            <input type="number" name="usage_limit" class="form-control" min="1" placeholder="Không giới hạn">
                        </div>

                        <div class="col-12 mt-3 mb-1"><h6 class="fw-bold text-dark mb-0 border-bottom pb-2"><i class="bi bi-clock-history me-2 text-primary"></i>Thời gian áp dụng</h6></div>
                        
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-bold text-dark small">Ngày & Giờ bắt đầu</label>
                            <input type="text" name="starts_at" class="form-control datetime-picker" placeholder="Bỏ trống = Ngay lập tức">
                            <small class="text-muted" style="font-size: 0.75rem;">Để trống nếu muốn chạy ngay.</small>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-bold text-dark small">Ngày & Giờ kết thúc</label>
                            <input type="text" name="ends_at" class="form-control datetime-picker" placeholder="Không thời hạn">
                            <small class="text-muted" style="font-size: 0.75rem;">Để trống nếu không có hạn sử dụng.</small>
                        </div>

                        <div class="col-12 mt-3 mb-1"><h6 class="fw-bold text-dark mb-0 border-bottom pb-2"><i class="bi bi-gear me-2 text-primary"></i>Cấu hình nâng cao</h6></div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label fw-bold text-dark small">Hạng thành viên</label>
                            <select name="required_tier" class="form-select">
                                <option value="">Tất cả khách hàng</option>
                                <option value="customer_bronze">Hạng Đồng+</option>
                                <option value="customer_silver">Hạng Bạc+</option>
                                <option value="customer_gold">Hạng Vàng+</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label fw-bold text-dark small">Tự động áp dụng?</label>
                            <select name="is_auto_apply" class="form-select" required>
                                <option value="0">Không (Khách tự nhập)</option>
                                <option value="1">Có (Tự kích hoạt)</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label fw-bold text-dark small">Trạng thái</label>
                            <select name="is_active" class="form-select" required>
                                <option value="1">Phát hành ngay</option>
                                <option value="0">Tạm khóa (Nháp)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-top p-0 pt-3 mt-4">
                        <button type="button" class="btn btn-outline-premium" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-premium px-4">Tạo mã Voucher</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xem Chi Tiết -->
<div class="modal fade" id="viewCouponModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content card-premium border-0 shadow-lg">
            <div class="modal-header border-bottom p-3 px-4">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-info-circle me-2 text-primary"></i>Mã: <span id="viewCode" class="text-uppercase font-monospace text-danger"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <ul class="list-group list-group-flush small">
                    <li class="list-group-item d-flex justify-content-between px-0 py-2"><span class="text-muted fw-bold">Hình thức:</span><span id="viewType" class="fw-semibold text-dark"></span></li>
                    <li class="list-group-item d-flex justify-content-between px-0 py-2"><span class="text-muted fw-bold">Giá trị giảm:</span><span id="viewValue" class="fw-semibold text-success"></span></li>
                    <li class="list-group-item d-flex justify-content-between px-0 py-2"><span class="text-muted fw-bold">Giảm tối đa:</span><span id="viewMaxDiscount" class="fw-semibold text-dark"></span></li>
                    <li class="list-group-item d-flex justify-content-between px-0 py-2"><span class="text-muted fw-bold">Đơn tối thiểu:</span><span id="viewMinOrder" class="fw-semibold text-dark"></span></li>
                    <li class="list-group-item d-flex justify-content-between px-0 py-2"><span class="text-muted fw-bold">Đã dùng / Giới hạn:</span><span id="viewUsage" class="fw-semibold text-dark"></span></li>
                    <li class="list-group-item d-flex justify-content-between px-0 py-2"><span class="text-muted fw-bold">Bắt đầu:</span><span id="viewStartsAt" class="fw-semibold text-dark"></span></li>
                    <li class="list-group-item d-flex justify-content-between px-0 py-2"><span class="text-muted fw-bold">Kết thúc:</span><span id="viewEndsAt" class="fw-semibold text-dark"></span></li>
                    <li class="list-group-item d-flex justify-content-between px-0 py-2"><span class="text-muted fw-bold">Thành viên:</span><span id="viewTier" class="fw-semibold text-dark"></span></li>
                    <li class="list-group-item d-flex justify-content-between px-0 py-2"><span class="text-muted fw-bold">Tự động áp dụng:</span><span id="viewAuto" class="fw-semibold text-dark"></span></li>
                    <li class="list-group-item d-flex justify-content-between px-0 py-2 border-bottom-0"><span class="text-muted fw-bold">Trạng thái:</span><span id="viewStatus" class="fw-semibold"></span></li>
                </ul>
            </div>
            <div class="modal-footer border-top p-3"><button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">Đóng</button></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/vn.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo Flatpickr tiếng Việt
    flatpickr(".datetime-picker", { 
        enableTime: true, 
        dateFormat: "Y-m-d H:i", 
        time_24hr: true, 
        allowInput: true,
        locale: "vn" 
    });

    // Xem chi tiết voucher - Đã sửa lấy đúng giá trị bắt đầu và kết thúc từ data attributes
    document.querySelectorAll('.btn-view').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('viewCode').textContent = this.dataset.code;
            document.getElementById('viewType').textContent = this.dataset.type;
            document.getElementById('viewValue').textContent = this.dataset.value;
            document.getElementById('viewMaxDiscount').textContent = this.dataset.max_discount;
            document.getElementById('viewMinOrder').textContent = this.dataset.min_order;
            document.getElementById('viewUsage').textContent = this.dataset.usage;
            document.getElementById('viewStartsAt').textContent = this.dataset.starts_at;
            document.getElementById('viewEndsAt').textContent = this.dataset.ends_at;
            document.getElementById('viewTier').textContent = this.dataset.tier;
            document.getElementById('viewAuto').textContent = this.dataset.auto;
            
            const status = document.getElementById('viewStatus');
            status.textContent = this.dataset.status;
            if (status.textContent === 'Đang kích hoạt') {
                status.className = 'fw-semibold text-success';
            } else if (status.textContent === 'Đã hết hạn' || status.textContent === 'Hết lượt dùng') {
                status.className = 'fw-semibold text-danger';
            } else if (status.textContent === 'Chưa bắt đầu') {
                status.className = 'fw-semibold text-info';
            } else {
                status.className = 'fw-semibold text-secondary';
            }
        });
    });

    // Đổi loại giảm giá
    const typeSelect = document.getElementById('discountType'), valueInput = document.getElementById('discountValue'), valueLabel = document.getElementById('discountValueLabel'), maxDiscount = document.getElementById('maxDiscountWrapper');
    typeSelect.addEventListener('change', function() {
        if (this.value === 'percent') {
            valueLabel.innerHTML = 'Giá trị giảm (%) *'; valueInput.placeholder = "VD: 10, 20"; valueInput.setAttribute('max', '100'); maxDiscount.style.display = 'block';
        } else {
            valueLabel.innerHTML = 'Giá trị giảm (VNĐ) *'; valueInput.placeholder = "VD: 50000"; valueInput.removeAttribute('max'); maxDiscount.style.display = 'none';
        }
    });

    // Validate đơn tối thiểu
    document.getElementById('couponForm').addEventListener('submit', function(e) {
        if (typeSelect.value === 'fixed' && Number(document.getElementById('minOrderValue').value) > 0 && Number(document.getElementById('minOrderValue').value) < Number(valueInput.value)) {
            e.preventDefault(); document.getElementById('minOrderValue').classList.add('is-invalid');
        }
    });
    document.getElementById('minOrderValue').addEventListener('input', function() { this.classList.remove('is-invalid'); });
});
</script>
@endpush