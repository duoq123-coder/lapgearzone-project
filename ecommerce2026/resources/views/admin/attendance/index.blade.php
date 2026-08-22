@extends('admin.layouts.app')
@section('title', 'Quản Lý Chấm Công & Nhân Sự')

@push('styles')
<style>
    .attendance-grid th, .attendance-grid td {
        text-align: center;
        vertical-align: middle;
        padding: 8px 4px;
        min-width: 40px;
    }
    .attendance-grid .staff-name-col {
        text-align: left;
        min-width: 250px;
        position: sticky;
        left: 0;
        background-color: #fff;
        z-index: 2;
        box-shadow: 2px 0 5px rgba(0,0,0,0.05);
    }
    .attendance-checkbox {
        width: 22px;
        height: 22px;
        cursor: pointer;
    }
    .attendance-grid .total-col {
        position: sticky;
        right: 0;
        background-color: #f8f9fa;
        z-index: 2;
        box-shadow: -2px 0 5px rgba(0,0,0,0.05);
        font-weight: bold;
        min-width: 70px;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0 text-dark fw-bold display-font"><i class="bi bi-calendar2-check text-primary me-2"></i>Quản Lý Nhân Sự & Chấm Công</h1>
        <p class="text-secondary small mb-0">Tháng {{ $month }}/{{ $year }}</p>
    </div>
    <div class="d-flex gap-2">
        <form action="{{ route('admin.attendance') }}" method="GET" class="d-flex gap-2">
            <select name="month" class="form-select form-select-sm rounded-pill px-3 shadow-sm" style="min-width: 110px;" onchange="this.form.submit()">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>Tháng {{ $m }}</option>
                @endfor
            </select>
            <select name="year" class="form-select form-select-sm rounded-pill px-3 shadow-sm" style="min-width: 110px;" onchange="this.form.submit()">
                @for($y = date('Y') - 1; $y <= date('Y') + 1; $y++)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>Năm {{ $y }}</option>
                @endfor
            </select>
        </form>
        <button class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalStaff" id="btnAddStaffBtn">
            <i class="bi bi-plus-lg me-1"></i> Thêm Nhân Viên
        </button>
    </div>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive" style="max-height: 70vh;">
            <table class="table table-bordered table-hover attendance-grid mb-0">
                <thead class="table-light position-sticky top-0 z-3" style="box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                    <tr>
                        <th class="staff-name-col border-bottom-0">Nhân viên</th>
                        @for($d = 1; $d <= $daysInMonth; $d++)
                            @php
                                $currentDate = \Carbon\Carbon::createFromDate($year, $month, $d);
                                $isWeekend = $currentDate->isWeekend();
                            @endphp
                            <th class="{{ $isWeekend ? 'bg-danger bg-opacity-10 text-danger' : '' }}" title="{{ $currentDate->format('d/m/Y') }}">
                                {{ $d }}
                            </th>
                        @endfor
                        <th class="border-bottom-0 bg-light total-col text-primary">Tổng (ngày)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($staffs as $staff)
                        <tr>
                            <td class="staff-name-col">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            @if(!empty($staff->avatar) && str_starts_with($staff->avatar, 'uploads/'))
                                                <img src="{{ asset($staff->avatar) }}" alt="Avatar" class="rounded-circle shadow-sm" style="width: 40px; height: 40px; object-fit: cover;">
                                            @elseif($staff->avatar)
                                                <img src="{{ asset('storage/' . $staff->avatar) }}" alt="Avatar" class="rounded-circle shadow-sm" style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm fw-bold" style="width: 40px; height: 40px; font-size: 1.1rem;">
                                                    {{ mb_substr($staff->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark">{{ $staff->name }}</h6>
                                            <small class="badge bg-secondary bg-opacity-10 text-dark px-2 rounded-pill border">{{ $staff->role }}</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-1 ms-2">
                                        <a href="{{ route('admin.staff.profile', $staff->id) }}" class="text-secondary btn btn-sm btn-link p-0" title="Xem hồ sơ">
                                            <i class="bi bi-person-vcard fs-5"></i>
                                        </a>
                                        <button class="btn btn-sm btn-link p-0 text-info btn-edit-staff" 
                                           data-bs-toggle="modal" data-bs-target="#modalStaff" 
                                           data-id="{{ $staff->id }}" data-name="{{ $staff->name }}" data-role="{{ $staff->role }}" data-phone="{{ $staff->phone ?? '' }}" data-cccd="{{ $staff->cccd ?? '' }}" data-address="{{ $staff->address ?? '' }}">
                                            <i class="bi bi-pencil fs-5"></i>
                                        </button>
                                        <form action="{{ route('admin.staff.destroy', $staff->id) }}" method="POST" class="m-0" onsubmit="return confirm('Bạn có chắc chắn muốn xóa nhân viên này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link p-0 text-danger" title="Xóa"><i class="bi bi-trash fs-5"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                            @for($d = 1; $d <= $daysInMonth; $d++)
                                @php
                                    $currentDateStr = sprintf('%04d-%02d-%02d', $year, $month, $d);
                                    $isPresent = $attendanceMatrix[$staff->id][$d];
                                @endphp
                                <td>
                                    <input type="checkbox" class="form-check-input attendance-checkbox shadow-none" 
                                           data-staff="{{ $staff->id }}" 
                                           data-date="{{ $currentDateStr }}"
                                           {{ $isPresent ? 'checked' : '' }}>
                                </td>
                            @endfor
                            <td class="total-col fs-5 text-success bg-light text-center" id="total-{{ $staff->id }}">
                                {{ count(array_filter($attendanceMatrix[$staff->id])) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $daysInMonth + 1 }}" class="text-center py-5 text-muted">
                                <div class="mb-3"><i class="bi bi-people fs-1 text-light"></i></div>
                                Chưa có nhân viên nào trong hệ thống.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-5">
    <h3 class="fw-bold mb-4"><i class="bi bi-file-earmark-bar-graph text-primary me-2"></i>Báo Cáo Giao Hàng (Từ Nhân Viên)</h3>
    <!-- BÁO CÁO HOÀN THÀNH TỪ NHÂN VIÊN GIAO HÀNG -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-white shadow-sm border-0 rounded-4 shadow-sm" style="border: 1px solid rgba(0, 0, 0, 0.08) !important;">
                <div class="card-header bg-transparent border-bottom border-secondary border-opacity-10 p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-person-check-fill text-success me-2"></i>Báo Cáo Hoàn Thành Từ Nhân Viên Giao Hàng</h5>
                        <span class="badge bg-success rounded-pill px-3 py-2 fw-bold">{{ isset($staffReports) ? $staffReports->where('delivery_status', 'completed')->count() : 0 }} báo cáo</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-light table-hover">
                            <thead>
                                <tr>
                                    <th class="ps-4">Mã Đơn</th>
                                    <th>Khách hàng</th>
                                    <th>Nhân viên giao</th>
                                    <th>Trạng thái</th>
                                    <th class="text-center pe-4">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($staffReports->where('delivery_status', 'completed') as $report)
                                    <tr>
                                        <td class="fw-bold text-info ps-4">#ORD-{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</td>
                                        <td class="fw-bold text-dark">{{ $report->name }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                    {{ mb_substr($report->deliveryStaff->name ?? '?', 0, 1) }}
                                                </div>
                                                <span class="fw-bold">{{ $report->deliveryStaff->name ?? 'Không rõ' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning text-dark rounded-pill px-3"><i class="bi bi-hourglass-split me-1"></i>Chờ xác nhận</span>
                                        </td>
                                        <td class="text-center pe-4">
                                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalViewReport_{{ $report->id }}">
                                                <i class="bi bi-eye-fill me-1"></i>Xem & Xác nhận
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-secondary">
                                            <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                                            Không có báo cáo hoàn thành nào chờ xử lý.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BÁO CÁO SỰ CỐ TỪ NHÂN VIÊN GIAO HÀNG -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-white shadow-sm border-0 rounded-4 shadow-sm" style="border: 1px solid rgba(0, 0, 0, 0.08) !important;">
                <div class="card-header bg-transparent border-bottom border-secondary border-opacity-10 p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Quản Lý Sự Cố Giao Hàng</h5>
                        <span class="badge bg-danger rounded-pill px-3 py-2 fw-bold">{{ isset($staffReports) ? $staffReports->where('delivery_status', 'issue')->count() : 0 }} sự cố</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-light table-hover">
                            <thead>
                                <tr>
                                    <th class="ps-4">Mã Đơn</th>
                                    <th>Khách hàng</th>
                                    <th>Nhân viên giao</th>
                                    <th>Lý do sự cố</th>
                                    <th class="text-center pe-4">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($staffReports->where('delivery_status', 'issue') as $report)
                                    <tr>
                                        <td class="fw-bold text-info ps-4">#ORD-{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</td>
                                        <td class="fw-bold text-dark">{{ $report->name }}</td>
                                        <td>{{ $report->deliveryStaff->name ?? 'Không rõ' }}</td>
                                        <td><span class="text-danger fw-bold text-truncate d-inline-block" style="max-width: 250px;">{{ $report->delivery_issue }}</span></td>
                                        <td class="text-center pe-4">
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalViewIssue_{{ $report->id }}">
                                                <i class="bi bi-eye-fill me-1"></i>Xử lý
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-secondary">
                                            <i class="bi bi-check-circle fs-1 d-block mb-3 opacity-50"></i>
                                            Không có sự cố giao hàng nào.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modals')
    <!-- Modals cho Staff Reports (Xem báo cáo và Xem sự cố) -->
    @foreach($staffReports ?? [] as $report)
    @if($report->delivery_status === 'completed')
    <div class="modal fade" id="modalViewReport_{{ $report->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-warning border-bottom-0">
                    <h5 class="modal-title fw-bold text-dark"><i class="bi bi-file-earmark-image-fill me-2"></i>Báo Cáo Giao Hàng & Lắp Đặt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-start">
                    <p><strong>Đơn hàng:</strong> #{{ $report->id }} - {{ $report->name }}</p>
                    <p><strong>Số tiền cần thu:</strong> <span class="text-danger fw-bold">{{ number_format($report->total_price, 0, ',', '.') }} đ</span></p>
                    @if($report->delivery_proof)
                        <h6 class="fw-bold mt-4 mb-3 text-primary">Hình ảnh minh chứng:</h6>
                        <div class="d-flex flex-wrap gap-3">
                            @foreach($report->delivery_proof as $img)
                                <a href="{{ asset($img) }}" target="_blank">
                                    <img src="{{ asset($img) }}" alt="Proof" class="img-thumbnail rounded-3 shadow-sm" style="width: 120px; height: 120px; object-fit: cover; border: 2px solid var(--accent-gold);">
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-warning">
                            Nhân viên báo cáo hoàn thành nhưng không tải lên hình ảnh minh chứng nào.
                        </div>
                    @endif
                </div>
                <div class="modal-footer border-top-0 justify-content-center flex-column pb-4">
                        <form action="{{ route('admin.orders.receivePayment', $report->id) }}" method="POST" class="w-100 text-center">
                            @csrf
                            @method('PATCH')
                            
                            @if($report->payment_method === 'cod_install')
                                <div class="form-check text-start d-inline-block bg-light p-3 rounded-3 mb-3 border border-success border-opacity-25 w-100">
                                    <input class="form-check-input border-success" type="checkbox" name="cash_remitted" id="cashRemitted_{{ $report->id }}" value="1" style="transform: scale(1.3); margin-top: 6px;">
                                    <label class="form-check-label fw-bold text-success ms-2" for="cashRemitted_{{ $report->id }}" style="cursor: pointer;">
                                        Xác nhận nhân viên đã nộp đủ tiền mặt <br><small class="text-muted fw-normal">Nếu bỏ trống, hệ thống sẽ ghi nhận nhân viên chưa nộp tiền.</small>
                                    </label>
                                </div>
                            @endif
                            
                            <div class="d-flex gap-2 justify-content-center">
                                <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Đóng lại</button>
                                <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm" onclick="return confirm('Xác nhận báo cáo và chuyển thành đơn hàng Hoàn thành?');">
                                    <i class="bi bi-check-circle me-1"></i> Xác nhận hoàn thành
                                </button>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
    @elseif($report->delivery_status === 'issue')
    <div class="modal fade" id="modalViewIssue_{{ $report->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-danger text-white border-bottom-0">
                    <h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Báo Cáo Sự Cố</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-start">
                    <p><strong>Đơn hàng:</strong> #{{ $report->id }} - {{ $report->name }}</p>
                    <p><strong>Nhân viên báo cáo:</strong> {{ $report->deliveryStaff->name ?? 'Không rõ' }}</p>
                    
                    <div class="alert alert-danger mt-4 border-0 shadow-sm rounded-4">
                        <h6 class="fw-bold"><i class="bi bi-chat-square-text-fill me-2"></i>Chi tiết sự cố:</h6>
                        <p class="mb-0 mt-2">{{ $report->delivery_issue }}</p>
                    </div>
                    
                    @if($report->delivery_proof)
                        <h6 class="fw-bold mt-4 mb-3 text-danger">Hình ảnh đính kèm (sự cố):</h6>
                        <div class="d-flex flex-wrap gap-3">
                            @foreach($report->delivery_proof as $img)
                                <a href="{{ asset($img) }}" target="_blank">
                                    <img src="{{ asset($img) }}" alt="Issue Proof" class="img-thumbnail rounded-3 shadow-sm" style="width: 120px; height: 120px; object-fit: cover; border: 2px solid #dc3545;">
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="modal-footer border-top-0 justify-content-center pb-4">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Đóng</button>
                    <form action="{{ route('admin.orders.resolveIssue', $report->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-primary rounded-pill px-3 fw-bold shadow-sm" onclick="return confirm('Xác nhận sự cố đã được giải quyết và yêu cầu nhân viên giao lại?');">
                            <i class="bi bi-arrow-repeat me-1"></i>Yêu cầu giao lại
                        </button>
                    </form>
                    <form action="{{ route('admin.orders.cancel', $report->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-outline-danger rounded-pill px-3 fw-bold shadow-sm" onclick="return confirm('Bạn chắc chắn muốn hủy đơn hàng này do sự cố không thể khắc phục?');">
                            <i class="bi bi-x-circle-fill me-1"></i>Hủy đơn
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endforeach
<!-- ================= MODAL QUẢN LÝ NHÂN VIÊN ================= -->
<div class="modal fade" id="modalStaff" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modalStaffLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-white shadow-sm border border-secondary border-opacity-25 text-dark rounded-4">
            <div class="modal-header border-bottom border-secondary border-opacity-20">
                <h5 class="modal-title fw-bold display-font" id="modalStaffTitle"><i class="bi bi-person-plus text-primary me-2"></i>Thêm Nhân Viên Mới</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formStaff" action="{{ route('admin.staff.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="methodStaffContainer"></div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold">Tên nhân viên</label>
                        <input type="text" name="name" id="staff_name" class="form-control bg-light text-dark border-secondary border-opacity-35 rounded-3" placeholder="Nhập tên nhân viên..." required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label text-secondary small fw-bold">Chức vụ</label>
                            <input type="text" name="role" id="staff_role" class="form-control bg-light text-dark border-secondary border-opacity-35 rounded-3" placeholder="Nhập chức vụ (VD: Bán hàng, Kỹ thuật...)" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-secondary small fw-bold">Số điện thoại</label>
                            <input type="text" name="phone" id="staff_phone" class="form-control bg-light text-dark border-secondary border-opacity-35 rounded-3" placeholder="Nhập SĐT..." required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold">Số CCCD</label>
                        <input type="text" name="cccd" id="staff_cccd" class="form-control bg-light text-dark border-secondary border-opacity-35 rounded-3" placeholder="Nhập CCCD..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold">Nơi ở hiện tại</label>
                        <input type="text" name="address" id="staff_address" class="form-control bg-light text-dark border-secondary border-opacity-35 rounded-3" placeholder="Nhập địa chỉ..." required>
                    </div>
                    <div class="mb-3 border p-3 rounded-3 bg-light">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" role="switch" id="create_account" name="create_account" value="1">
                            <label class="form-check-label fw-bold text-dark" for="create_account">Tạo tài khoản đăng nhập hệ thống</label>
                        </div>
                        <div id="account_role_container" style="display: none;">
                            <label class="form-label text-secondary small fw-bold">Quyền hạn hệ thống</label>
                            <select name="account_role" id="account_role" class="form-select bg-white text-dark border-secondary border-opacity-35 rounded-3">
                                <option value="delivery">Nhân viên Giao hàng & Lắp đặt</option>
                                <option value="admin">Quản trị viên (Admin)</option>
                            </select>
                            <small class="text-muted mt-1 d-block">Tài khoản mặc định: <b>Số CCCD</b>, Mật khẩu: <b>12345678</b></small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold">Ảnh đại diện (Tùy chọn)</label>
                        <input type="file" id="staff_avatar_input" class="form-control bg-light text-dark border-secondary border-opacity-35 rounded-3" accept="image/*">
                        <input type="hidden" name="avatar_base64" id="staff_avatar_base64">
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-20">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="bi bi-save me-1"></i> Lưu thông tin</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Handle Edit Staff Modal
        const modalStaffEl = document.getElementById('modalStaff');
        document.querySelectorAll('.btn-edit-staff').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const role = this.getAttribute('data-role');
                const phone = this.getAttribute('data-phone');
                const cccd = this.getAttribute('data-cccd');
                const address = this.getAttribute('data-address');

                document.getElementById('modalStaffTitle').innerHTML = '<i class="bi bi-pencil-square text-info me-2"></i>Cập Nhật Nhân Viên';
                const form = document.getElementById('formStaff');
                form.action = `/admin/staff/${id}`;
                document.getElementById('methodStaffContainer').innerHTML = '<input type="hidden" name="_method" value="PUT">';

                document.getElementById('staff_name').value = name;
                document.getElementById('staff_role').value = role;
                document.getElementById('staff_phone').value = phone;
                document.getElementById('staff_cccd').value = cccd;
                document.getElementById('staff_address').value = address;
            });
        });

        // Toggle Account Creation Fields
        const createAccountCheckbox = document.getElementById('create_account');
        const accountRoleContainer = document.getElementById('account_role_container');
        if (createAccountCheckbox && accountRoleContainer) {
            createAccountCheckbox.addEventListener('change', function() {
                accountRoleContainer.style.display = this.checked ? 'block' : 'none';
            });
        }

        modalStaffEl.addEventListener('hidden.bs.modal', function () {
            document.getElementById('modalStaffTitle').innerHTML = '<i class="bi bi-person-plus text-primary me-2"></i>Thêm Nhân Viên Mới';
            const form = document.getElementById('formStaff');
            form.action = "{{ route('admin.staff.store') }}";
            document.getElementById('methodStaffContainer').innerHTML = '';
            form.reset();
            if (accountRoleContainer) {
                accountRoleContainer.style.display = 'none';
            }
            document.getElementById('staff_avatar_base64').value = '';
            document.getElementById('staff_avatar_input').value = '';
        });

        // Initialize Cropper for Staff Avatar (1:1 aspect ratio)
        initImageCropper('staff_avatar_input', 'staff_avatar_base64', 1);

        // Handle AJAX Attendance Checkboxes
        document.querySelectorAll('.attendance-checkbox').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const staffId = this.getAttribute('data-staff');
                const date = this.getAttribute('data-date');
                const isPresent = this.checked ? 1 : 0;
                
                // Add loading state
                this.disabled = true;

                fetch("{{ route('admin.attendance.toggle') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        staff_id: staffId,
                        date: date,
                        is_present: isPresent
                    })
                })
                .then(response => response.json())
                .then(data => {
                    this.disabled = false;
                    if(data.success) {
                        // Create a subtle flash effect
                        this.parentElement.style.backgroundColor = 'rgba(25, 135, 84, 0.2)';
                        setTimeout(() => {
                            this.parentElement.style.backgroundColor = '';
                        }, 500);

                        // Update Total
                        const totalTd = document.getElementById('total-' + staffId);
                        if (totalTd) {
                            let currentTotal = parseInt(totalTd.innerText) || 0;
                            totalTd.innerText = isPresent ? currentTotal + 1 : currentTotal - 1;
                        }
                    } else {
                        alert('Có lỗi xảy ra khi lưu trạng thái.');
                        this.checked = !this.checked;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Lỗi mạng. Vui lòng thử lại.');
                    this.disabled = false;
                    this.checked = !this.checked;
                });
            });
        });
    });
</script>
@endpush
