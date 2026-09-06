@extends('admin.layouts.app')
@section('title', 'Quản Lý Chấm Công & Nhân Sự - Admin')

@push('styles')
<style>
    .attendance-grid th, .attendance-grid td {
        text-align: center;
        vertical-align: middle;
        padding: 8px 4px;
        min-width: 38px;
    }
    .attendance-grid th {
        font-family: 'Space Grotesk', sans-serif;
    }
    .attendance-grid .staff-name-col {
        text-align: left;
        min-width: 240px;
        position: sticky;
        left: 0;
        background-color: #ffffff !important;
        color: var(--text-main) !important;
        z-index: 2;
        border-right: 2px solid var(--border-color) !important;
        box-shadow: 4px 0 6px -2px rgba(0, 0, 0, 0.08);
    }
    .attendance-grid thead th.staff-name-col {
        background-color: var(--surface-muted) !important;
        z-index: 4 !important;
    }
    .attendance-grid tbody td.staff-name-col {
        background-color: #ffffff !important;
    }
    .attendance-grid tbody tr:hover td.staff-name-col {
        background-color: #fdfaf7 !important;
    }

    .attendance-checkbox {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: var(--bellroy-orange);
    }

    .attendance-grid .total-col {
        position: sticky;
        right: 0;
        background-color: var(--surface-muted) !important;
        color: var(--text-main) !important;
        z-index: 2;
        border-left: 2px solid var(--border-color) !important;
        box-shadow: -4px 0 6px -2px rgba(0, 0, 0, 0.08);
        font-weight: bold;
        min-width: 75px;
        font-family: 'Space Mono', monospace;
    }
    .attendance-grid thead th.total-col {
        background-color: var(--surface-muted) !important;
        z-index: 4 !important;
    }
    .attendance-grid tbody td.total-col {
        background-color: var(--surface-muted) !important;
    }
    .attendance-grid tbody tr:hover td.total-col {
        background-color: #e8e4dc !important;
    }

    /* Dark Mode Solid Backgrounds */
    [data-bs-theme="dark"] .attendance-grid thead th.staff-name-col {
        background-color: #141418 !important;
        color: #f0f0f3 !important;
    }
    [data-bs-theme="dark"] .attendance-grid thead th.total-col {
        background-color: #141418 !important;
        color: #f0f0f3 !important;
    }
    [data-bs-theme="dark"] .attendance-grid tbody td.staff-name-col {
        background-color: #1a1a1e !important;
        color: #f0f0f3 !important;
    }
    [data-bs-theme="dark"] .attendance-grid tbody tr:hover td.staff-name-col {
        background-color: #24242a !important;
    }
    [data-bs-theme="dark"] .attendance-grid tbody td.total-col {
        background-color: #161619 !important;
        color: #f0f0f3 !important;
    }
    [data-bs-theme="dark"] .attendance-grid tbody tr:hover td.total-col {
        background-color: #202026 !important;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0 text-dark fw-bold display-font"><i class="bi bi-calendar2-check me-2" style="color: var(--bellroy-orange);"></i>Quản Lý Nhân Sự &amp; Chấm Công</h1>
        <p class="text-secondary small mb-0">Bảng theo dõi chấm công tháng {{ $month }}/{{ $year }}</p>
    </div>
    <div class="d-flex gap-2">
    <form action="{{ route('admin.attendance') }}" method="GET" class="d-flex gap-2">
        <!-- Tăng min-width lên 120px -->
        <select name="month" class="form-select form-select-sm" style="min-width: 120px;" onchange="this.form.submit()">
            @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>Tháng {{ $m }}</option>
            @endfor
        </select>
        
        <!-- Tăng min-width lên 120px -->
        <select name="year" class="form-select form-select-sm" style="min-width: 120px;" onchange="this.form.submit()">
            @for($y = date('Y') - 1; $y <= date('Y') + 1; $y++)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>Năm {{ $y }}</option>
            @endfor
        </select>
    </form>
    
    <button class="btn btn-premium px-3 py-1 text-nowrap" data-bs-toggle="modal" data-bs-target="#modalStaff" id="btnAddStaffBtn">
        <i class="bi bi-plus-lg me-1"></i> Thêm Nhân Viên
    </button>
</div>
</div>

<div class="card overflow-hidden mb-5">
    <div class="card-body p-0">
        <div class="table-responsive" style="max-height: 65vh;">
            <table class="table table-bordered table-hover attendance-grid mb-0">
                <thead class="position-sticky top-0 z-3" style="background: var(--surface-muted);">
                    <tr>
                        <th class="staff-name-col border-bottom-0">Nhân viên</th>
                        @for($d = 1; $d <= $daysInMonth; $d++)
                            @php
                                $currentDate = \Carbon\Carbon::createFromDate($year, $month, $d);
                                $isWeekend = $currentDate->isWeekend();
                            @endphp
                            <th class="{{ $isWeekend ? 'text-danger fw-bold' : '' }}" style="{{ $isWeekend ? 'background-color: var(--bellroy-orange-subtle);' : '' }}" title="{{ $currentDate->format('d/m/Y') }}">
                                {{ $d }}
                            </th>
                        @endfor
                        <th class="border-bottom-0 total-col text-dark">Tổng (ngày)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($staffs as $staff)
                        <tr>
                            <td class="staff-name-col">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="me-2.5">
                                            @if($staff->avatar_url)
                                                <img src="{{ $staff->avatar_url }}" alt="Avatar" class="rounded-circle border" style="width: 36px; height: 36px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px; font-size: 0.9rem; background: var(--bellroy-charcoal); color: #fff;">
                                                    {{ mb_substr($staff->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.88rem;">{{ $staff->name }}</h6>
                                            <span class="badge badge-terracotta" style="font-size: 0.68rem;">{{ $staff->role }}</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-1 ms-2">
                                        <a href="{{ route('admin.staff.profile', $staff->id) }}" class="text-secondary btn btn-sm btn-link p-0" title="Xem hồ sơ">
                                            <i class="bi bi-person-vcard fs-6"></i>
                                        </a>
                                        <button class="btn btn-sm btn-link p-0 text-dark btn-edit-staff" 
                                           data-bs-toggle="modal" data-bs-target="#modalStaff" 
                                           data-id="{{ $staff->id }}" data-name="{{ $staff->name }}" data-role="{{ $staff->role }}" data-phone="{{ $staff->phone ?? '' }}" data-cccd="{{ $staff->cccd ?? '' }}" data-address="{{ $staff->address ?? '' }}"
                                           title="Sửa nhân viên">
                                            <i class="bi bi-pencil fs-6"></i>
                                        </button>
                                        <form action="{{ route('admin.staff.destroy', $staff->id) }}" method="POST" class="m-0" onsubmit="return confirm('Bạn có chắc chắn muốn xóa nhân viên này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link p-0 text-danger" title="Xóa"><i class="bi bi-trash fs-6"></i></button>
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
                            <td class="total-col fs-6 fw-bold text-center" style="color: var(--bellroy-sage);" id="total-{{ $staff->id }}">
                                {{ count(array_filter($attendanceMatrix[$staff->id])) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $daysInMonth + 1 }}" class="text-center py-5 text-muted">
                                <div class="mb-3"><i class="bi bi-people fs-1 opacity-50"></i></div>
                                Chưa có nhân viên nào trong hệ thống.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    <div class="d-flex align-items-center mb-3">
        <h4 class="serif-title mb-0 text-dark" style="font-size: 1.35rem;"><i class="bi bi-file-earmark-bar-graph me-2" style="color: var(--bellroy-orange);"></i>Báo Cáo Hoàn Thành &amp; Sự Cố Giao Hàng</h4>
    </div>

    <!-- BÁO CÁO HOÀN THÀNH TỪ NHÂN VIÊN GIAO HÀNG -->
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card h-100 overflow-hidden">
                <div class="card-header bg-white border-bottom p-3 px-4 d-flex justify-content-between align-items-center" style="border-color: var(--border-color) !important;">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-check2-circle me-2" style="color: var(--bellroy-sage);"></i>Báo cáo giao hàng hoàn tất</h6>
                    <span class="badge badge-sage">{{ isset($staffReports) ? $staffReports->where('delivery_status', 'completed')->count() : 0 }} đơn</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Mã Đơn</th>
                                    <th>Khách hàng</th>
                                    <th>Nhân viên</th>
                                    <th class="text-end pe-4">Xử lý</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($staffReports->where('delivery_status', 'completed') as $report)
                                    <tr>
                                        <td class="fw-bold font-monospace text-dark ps-4">#{{ $report->id }}</td>
                                        <td class="text-dark small">{{ $report->name }}</td>
                                        <td class="text-secondary small">{{ $report->deliveryStaff->name ?? 'N/A' }}</td>
                                        <td class="text-end pe-4">
                                            <button type="button" class="btn btn-sm btn-outline-premium py-1 px-3" data-bs-toggle="modal" data-bs-target="#modalViewReport_{{ $report->id }}">
                                                Xem &amp; Xác nhận
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted small">
                                            Không có báo cáo hoàn thành nào chờ duyệt.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- BÁO CÁO SỰ CỐ TỪ NHÂN VIÊN GIAO HÀNG -->
        <div class="col-lg-6">
            <div class="card h-100 overflow-hidden">
                <div class="card-header bg-white border-bottom p-3 px-4 d-flex justify-content-between align-items-center" style="border-color: var(--border-color) !important;">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-exclamation-triangle me-2" style="color: var(--bellroy-orange);"></i>Báo cáo sự cố giao hàng</h6>
                    <span class="badge badge-terracotta">{{ isset($staffReports) ? $staffReports->where('delivery_status', 'issue')->count() : 0 }} đơn</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Mã Đơn</th>
                                    <th>Khách hàng</th>
                                    <th>Chi tiết sự cố</th>
                                    <th class="text-end pe-4">Xử lý</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($staffReports->where('delivery_status', 'issue') as $report)
                                    <tr>
                                        <td class="fw-bold font-monospace text-dark ps-4">#{{ $report->id }}</td>
                                        <td class="text-dark small">{{ $report->name }}</td>
                                        <td><span class="text-danger fw-bold text-truncate d-inline-block small" style="max-width: 160px;">{{ $report->delivery_issue }}</span></td>
                                        <td class="text-end pe-4">
                                            <button type="button" class="btn btn-sm btn-outline-dark text-danger border-danger py-1 px-3" data-bs-toggle="modal" data-bs-target="#modalViewIssue_{{ $report->id }}">
                                                Xử lý
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted small">
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
    <!-- Modals cho Staff Reports -->
    @foreach($staffReports ?? [] as $report)
    @if($report->delivery_status === 'completed')
    <div class="modal fade" id="modalViewReport_{{ $report->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content card-premium border-0 shadow-lg">
                <div class="modal-header border-bottom p-3 px-4" style="border-color: var(--border-color) !important;">
                    <h5 class="modal-title fw-bold text-dark fs-6"><i class="bi bi-file-earmark-image me-2" style="color: var(--bellroy-sage);"></i>Minh Chứng Giao Hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-start">
                    <p class="mb-1"><strong>Đơn hàng:</strong> #{{ $report->id }} - {{ $report->name }}</p>
                    <p class="mb-1 small text-muted">
                        <i class="bi bi-clock me-1"></i>
                        <strong>Đặt lúc:</strong> {{ $report->created_at->format('H:i - d/m/Y') }}
                    </p>
                    <p class="mb-3 small text-muted">
                        <i class="bi bi-truck me-1" style="color: var(--bellroy-sage);"></i>
                        <strong>Giao lúc:</strong>
                        <span class="fw-bold" style="color: var(--bellroy-sage);">{{ $report->updated_at->format('H:i - d/m/Y') }}</span>
                        <span class="ms-2 badge" style="background: var(--bellroy-sage-subtle); color: var(--bellroy-sage); font-size: 0.72rem;">
                            {{ $report->created_at->diffForHumans($report->updated_at, true) }} sau khi đặt
                        </span>
                    </p>
                    @if(in_array($report->payment_method, ['payos', 'vnpay', 'banking', 'online']) || $report->status === 'paid' || $report->payment_method !== 'cod_install')
                        <p class="mb-3"><strong>Hình thức:</strong> <span class="badge bg-success text-white">Đã thanh toán Online ({{ strtoupper($report->payment_method) }})</span> <span class="text-success small ms-2 fw-bold"><i class="bi bi-check-circle-fill me-1"></i>Không thu tiền mặt</span></p>
                    @else
                        <p class="mb-3"><strong>Số tiền thu COD:</strong> <span class="fw-bold display-font text-dark">{{ number_format($report->total_price, 0, ',', '.') }} đ</span></p>
                    @endif
                    @if($report->delivery_proof)
                        <h6 class="fw-bold mb-2 text-dark small text-uppercase" style="letter-spacing: 0.5px;">Hình ảnh minh chứng:</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($report->delivery_proof as $img)
                                <a href="{{ asset($img) }}" target="_blank">
                                    <img src="{{ asset($img) }}" alt="Proof" class="rounded border p-1" style="width: 100px; height: 100px; object-fit: cover; background: #fff;">
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="alert small rounded-3" style="background-color: #fef8ee; color: var(--bellroy-amber);">
                            Nhân viên báo cáo hoàn thành nhưng chưa tải lên ảnh minh chứng.
                        </div>
                    @endif
                </div>
                <div class="modal-footer border-top p-3 px-4 justify-content-center flex-column" style="border-color: var(--border-color) !important;">
                    <form action="{{ route('admin.orders.receivePayment', $report->id) }}" method="POST" class="w-100 text-center">
                        @csrf
                        @method('PATCH')
                        
                        @if($report->payment_method === 'cod_install')
                            <div class="form-check text-start d-inline-block p-3 rounded-3 mb-3 border w-100" style="background-color: var(--bellroy-sage-subtle); border-color: var(--border-color) !important;">
                                <input class="form-check-input" type="checkbox" name="cash_remitted" id="cashRemitted_{{ $report->id }}" value="1" style="transform: scale(1.2); margin-top: 5px;" {{ $report->cash_remitted ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold ms-2" for="cashRemitted_{{ $report->id }}" style="color: var(--bellroy-sage); cursor: pointer;">
                                    Xác nhận nhân viên đã nộp đủ tiền mặt <br><small class="text-muted fw-normal">Khi tích chọn, hệ thống sẽ cộng đơn hàng này vào Doanh thu thực tế.</small>
                                </label>
                            </div>
                        @endif
                        
                        <div class="d-flex gap-2 justify-content-center">
                            <button type="button" class="btn btn-outline-premium" data-bs-dismiss="modal">Đóng lại</button>
                            <button type="submit" class="btn btn-premium px-4" onclick="return confirm('Xác nhận báo cáo và chuyển đơn hàng sang trạng thái Hoàn thành?');">
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
            <div class="modal-content card-premium border-0 shadow-lg">
                <div class="modal-header text-white border-0 px-4 py-3" style="background: var(--bellroy-charcoal);">
                    <h5 class="modal-title fw-bold text-white fs-6"><i class="bi bi-exclamation-triangle me-2" style="color: var(--bellroy-orange);"></i>Chi Tiết Sự Cố Đơn #{{ $report->id }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-start">
                    <p class="mb-1"><strong>Khách hàng:</strong> {{ $report->name }}</p>
                    <p class="mb-3"><strong>Nhân viên phụ trách:</strong> {{ $report->deliveryStaff->name ?? 'Không rõ' }}</p>
                    
                    <div class="alert rounded-3 border-0 p-3 mb-3" style="background-color: var(--bellroy-orange-subtle); color: var(--bellroy-orange);">
                        <h6 class="fw-bold mb-1 small"><i class="bi bi-chat-square-text me-1"></i>Mô tả sự cố:</h6>
                        <p class="mb-0 small">{{ $report->delivery_issue }}</p>
                    </div>
                    
                    @if($report->delivery_proof)
                        <h6 class="fw-bold mb-2 text-dark small">Hình ảnh đính kèm:</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($report->delivery_proof as $img)
                                <a href="{{ asset($img) }}" target="_blank">
                                    <img src="{{ asset($img) }}" alt="Issue Proof" class="rounded border p-1" style="width: 90px; height: 90px; object-fit: cover; background: #fff;">
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="modal-footer border-top p-3 px-4 justify-content-center" style="border-color: var(--border-color) !important;">
                    <button type="button" class="btn btn-outline-premium" data-bs-dismiss="modal">Đóng</button>
                    <form action="{{ route('admin.orders.resolveIssue', $report->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-premium px-3" onclick="return confirm('Yêu cầu nhân viên tiếp tục giao lại đơn này?');">
                            <i class="bi bi-arrow-repeat me-1"></i>Yêu cầu giao lại
                        </button>
                    </form>
                    <form action="{{ route('admin.orders.cancel', $report->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-outline-dark text-danger border-danger px-3" onclick="return confirm('Bạn chắc chắn muốn hủy đơn hàng này?');">
                            <i class="bi bi-x-circle me-1"></i>Hủy đơn
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endforeach

    <!-- Modal Thêm/Sửa Nhân Viên -->
    <div class="modal fade" id="modalStaff" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modalStaffLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content card-premium border-0 shadow-lg">
                <div class="modal-header border-bottom p-3 px-4" style="border-color: var(--border-color) !important;">
                    <h5 class="modal-title fw-bold text-dark fs-6" id="modalStaffTitle"><i class="bi bi-person-plus me-2" style="color: var(--bellroy-orange);"></i>Thêm Nhân Viên Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formStaff" action="{{ route('admin.staff.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div id="methodStaffContainer"></div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label text-dark small fw-bold">Tên nhân viên <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="staff_name" class="form-control" placeholder="Nhập họ và tên..." required>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label text-dark small fw-bold">Chức vụ <span class="text-danger">*</span></label>
                                <input type="text" name="role" id="staff_role" class="form-control" placeholder="VD: Giao hàng, Kỹ thuật..." required>
                            </div>
                            <div class="col-6">
                                <label class="form-label text-dark small fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="text" name="phone" id="staff_phone" class="form-control" placeholder="Nhập SĐT..." required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-dark small fw-bold">Số CCCD <span class="text-danger">*</span></label>
                            <input type="text" name="cccd" id="staff_cccd" class="form-control" placeholder="Nhập số CCCD..." required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-dark small fw-bold">Nơi ở hiện tại <span class="text-danger">*</span></label>
                            <input type="text" name="address" id="staff_address" class="form-control" placeholder="Nhập địa chỉ cư trú..." required>
                        </div>
                        <div class="mb-3 border p-3 rounded-3" style="background-color: #faf9f6; border-color: var(--border-color) !important;">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="create_account" name="create_account" value="1">
                                <label class="form-check-label fw-bold text-dark small" for="create_account">Cấp tài khoản đăng nhập hệ thống</label>
                            </div>
                            <div id="account_role_container" style="display: none;">
                                <label class="form-label text-dark small fw-bold">Quyền hạn</label>
                                <select name="account_role" id="account_role" class="form-select bg-white">
                                    <option value="delivery">Nhân viên Giao hàng</option>
                                    <option value="admin">Quản trị viên (Admin)</option>
                                </select>
                                <small class="text-muted mt-1 d-block" style="font-size: 0.78rem;">Tài khoản mặc định: <b>Số CCCD</b>, Mật khẩu: <b>12345678</b></small>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label text-dark small fw-bold">Ảnh đại diện (Tùy chọn)</label>
                            <input type="file" id="staff_avatar_input" class="form-control" accept="image/*">
                            <input type="hidden" name="avatar_base64" id="staff_avatar_base64">
                        </div>
                    </div>
                    <div class="modal-footer border-top p-3 px-4" style="border-color: var(--border-color) !important;">
                        <button type="button" class="btn btn-outline-premium" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-premium px-4"><i class="bi bi-check2 me-1"></i> Lưu nhân viên</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const modalStaffEl = document.getElementById('modalStaff');
        document.querySelectorAll('.btn-edit-staff').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const role = this.getAttribute('data-role');
                const phone = this.getAttribute('data-phone');
                const cccd = this.getAttribute('data-cccd');
                const address = this.getAttribute('data-address');

                document.getElementById('modalStaffTitle').innerHTML = '<i class="bi bi-pencil-square me-2" style="color: var(--bellroy-orange);"></i>Cập Nhật Nhân Viên';
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

        const createAccountCheckbox = document.getElementById('create_account');
        const accountRoleContainer = document.getElementById('account_role_container');
        if (createAccountCheckbox && accountRoleContainer) {
            createAccountCheckbox.addEventListener('change', function() {
                accountRoleContainer.style.display = this.checked ? 'block' : 'none';
            });
        }

        modalStaffEl.addEventListener('hidden.bs.modal', function () {
            document.getElementById('modalStaffTitle').innerHTML = '<i class="bi bi-person-plus me-2" style="color: var(--bellroy-orange);"></i>Thêm Nhân Viên Mới';
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

        if(typeof initImageCropper === 'function') {
            initImageCropper('staff_avatar_input', 'staff_avatar_base64', 1);
        }

        document.querySelectorAll('.attendance-checkbox').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const staffId = this.getAttribute('data-staff');
                const date = this.getAttribute('data-date');
                const isPresent = this.checked ? 1 : 0;
                
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
                        this.parentElement.style.backgroundColor = 'var(--bellroy-sage-subtle)';
                        setTimeout(() => {
                            this.parentElement.style.backgroundColor = '';
                        }, 400);

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
                    this.disabled = false;
                    this.checked = !this.checked;
                });
            });
        });
    });
</script>
@endpush
