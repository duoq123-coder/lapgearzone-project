@extends('admin.layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid py-2">
    <style>
        .action-icon {
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .action-icon:hover {
            opacity: 0.7;
        }
        .card-hero-sync {
            border-radius: 2px !important;
            overflow: hidden;
        }
        [data-bs-theme="dark"] .card-hero-sync {
            background-color: #111113 !important;
            border-color: #26262a !important;
        }
    </style>
    <!-- Header Block -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-dark display-font" style="letter-spacing: -0.5px; text-transform: uppercase;"><i class="bi bi-speedometer2 text-dark me-2"></i>Dashboard Admin</h2>
            <p class="text-secondary small mb-0">Quản lý nhập xuất hàng hóa, doanh thu và nguồn nhân lực tại LapGearZone.</p>
        </div>
    </div>

    <!-- 3 Stats Cards (Tactical Telemetry) -->
    @php
        $collection = collect($staffList ?? []);
        $workingCount = $collection->whereNotIn('status', ['Nghỉ', 'Không đi làm'])->count();
        $totalStaff = $collection->count();
        $percent = $totalStaff > 0 ? round(($workingCount / $totalStaff) * 100) : 0;
        
        // Lấy danh sách danh mục duy nhất từ sản phẩm cho bộ lọc
        $uniqueCategories = collect($products ?? [])->pluck('category')->unique('id')->filter();
    @endphp

    <div class="row g-4 mb-4">
        <!-- Daily Revenue -->
        <div class="col-xl-4 col-md-12">
            <div class="card stat-card-tactical h-100 overflow-hidden">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div style="max-width: calc(100% - 70px);">
                        <small class="text-secondary fw-bold d-block mb-1 text-uppercase font-monospace" style="font-size: 0.72rem; letter-spacing: 0.08em;">// Doanh thu trong ngày</small>
                        <h3 class="fw-bold text-dark font-monospace mb-0" style="word-break: break-word; font-size: 1.65rem;">{{ number_format($dailyRevenue ?? 0, 0, ',', '.') }} <span style="font-size: 1rem; font-weight: 500;">đ</span></h3>
                    </div>
                    <div class="stat-icon-box flex-shrink-0">
                        <i class="bi bi-currency-dollar text-dark fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Weekly Revenue -->
        <div class="col-xl-4 col-md-12">
            <div class="card stat-card-tactical h-100 overflow-hidden">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div style="max-width: calc(100% - 70px);">
                        <small class="text-secondary fw-bold d-block mb-1 text-uppercase font-monospace" style="font-size: 0.72rem; letter-spacing: 0.08em;">// Doanh thu trong tuần</small>
                        <h3 class="fw-bold text-dark font-monospace mb-0" style="word-break: break-word; font-size: 1.65rem;">{{ number_format($weeklyRevenue ?? 0, 0, ',', '.') }} <span style="font-size: 1rem; font-weight: 500;">đ</span></h3>
                    </div>
                    <div class="stat-icon-box flex-shrink-0">
                        <i class="bi bi-wallet2 text-dark fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Stock -->
        <div class="col-xl-4 col-md-12">
            <div class="card stat-card-tactical h-100 overflow-hidden">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div style="max-width: calc(100% - 70px);">
                        <small class="text-secondary fw-bold d-block mb-1 text-uppercase font-monospace" style="font-size: 0.72rem; letter-spacing: 0.08em;">// Sản phẩm hệ thống</small>
                        <h3 class="fw-bold text-dark font-monospace mb-2" style="font-size: 1.65rem;">{{ $totalProducts ?? 0 }} <span style="font-size: 0.95rem; font-family: 'Space Grotesk', sans-serif;">DÒNG</span></h3>
                        <span class="badge badge-terracotta">
                            <i class="bi bi-boxes me-1"></i> {{ $totalCategories ?? 0 }} Danh mục
                        </span>
                    </div>
                    <div class="stat-icon-box flex-shrink-0">
                        <i class="bi bi-box text-dark fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="row g-4 mb-4">
        <!-- Revenue Chart -->
        <div class="col-lg-12">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-activity text-dark me-2"></i>Biểu Đồ Doanh Thu Tổng Hợp</h5>
                    <span class="text-muted small">7 ngày gần nhất</span>
                </div>
                <div class="card-body">
                    <div style="height: 320px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Selling Products -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-white shadow-sm border-0 card-hero-sync" style="border: 1px solid rgba(0, 0, 0, 0.08) !important;">
                <div class="card-header bg-transparent border-bottom border-secondary border-opacity-10 p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-star-fill text-dark me-2"></i>Thống Kê Sản Phẩm Bán Chạy Nhất Tháng</h5>
                        <span class="badge bg-dark text-white rounded-0 px-3 py-2 fw-bold">Top {{ count($topSellingProducts) }} sản phẩm</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-light table-hover">
                            <thead>
                                <tr>
                                    <th class="ps-4" style="width: 50px;">#</th>
                                    <th style="width: 80px;">Hình ảnh</th>
                                    <th>Tên Thiết Bị / Sản Phẩm</th>
                                    <th>Phân Loại</th>
                                    <th class="text-end pe-4">Số Lượng Đã Bán</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topSellingProducts ?? [] as $index => $product)
                                    <tr>
                                        <td class="fw-bold ps-4 text-muted">{{ $index + 1 }}</td>
                                        <td>
                                            <div style="width: 48px; height: 48px; background-color: #f8f9fa; border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 1px solid rgba(0,0,0,0.05);">
                                                @if($product->image)
                                                    <img src="{{ asset('storage/' . $product->image) }}" alt="Img" style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    <i class="bi bi-box text-secondary" style="font-size: 1.5rem;"></i>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="fw-bold text-dark">{{ $product->name }}</td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ $product->category->name ?? 'Không xác định' }}</span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <span class="badge bg-dark rounded-0 px-3 py-2 fw-bold fs-6">
                                                {{ $product->total_sold }} <small class="fw-normal">đã bán</small>
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-secondary">
                                            <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                                            Chưa có sản phẩm nào được bán ra trong tháng này.
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

    <!-- Order Management Panel -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-cart-check text-dark me-2"></i>Quản Lý Đơn Hàng Chờ Xử Lý</h5>
                    <span class="badge bg-dark text-white px-3 py-1.5 font-monospace">{{ isset($pendingOrders) ? $pendingOrders->count() : 0 }} ĐƠN MỚI</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover">
                            <thead>
                                <tr>
                                    <th class="ps-4">Mã Đơn</th>
                                    <th>Khách hàng</th>
                                    <th>Số điện thoại</th>
                                    <th>Địa chỉ giao hàng</th>
                                    <th class="text-end">Tổng tiền</th>
                                    <th>Thanh toán</th>
                                    <th>Ngày tạo</th>
                                    <th class="text-center">Trạng thái</th>
                                    <th class="text-center pe-4 text-nowrap" style="width: 1%; min-width: 220px;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingOrders ?? [] as $order)
                                    <tr>
                                        <td class="fw-bold text-dark ps-4 font-monospace">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                                        <td class="fw-bold text-dark">{{ $order->name }}</td>
                                        <td><a href="tel:{{ $order->phone }}" class="text-decoration-none text-dark font-monospace">{{ $order->phone }}</a></td>
                                        <td><small class="text-secondary">{{ $order->address }}</small></td>
                                        <td class="text-end text-dark fw-bold font-monospace">{{ number_format($order->total_price, 0, ',', '.') }} đ</td>
                                        <td>
                                            @if($order->payment_method == 'payos')
                                                <span class="badge bg-dark text-white">PayOS</span>
                                            @elseif($order->payment_method == 'cod_install')
                                                <span class="badge bg-secondary text-white">COD</span>
                                            @elseif($order->payment_method == 'vnpay')
                                                <span class="badge border text-dark">VNPay</span>
                                            @else
                                                <span class="badge bg-light text-dark border">{{ $order->payment_method }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-secondary font-monospace">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                                            @if($order->delivery_status === 'assigned')
                                                <span class="badge border text-dark d-block mt-1">Đã giao NV</span>
                                            @elseif($order->delivery_status === 'customer_confirmed')
                                                <span class="badge bg-info text-dark d-block mt-1">Khách Đã Nhận</span>
                                            @elseif($order->delivery_status === 'completed')
                                                <span class="badge bg-dark text-white d-block mt-1">NV Đã xong</span>
                                            @endif
                                        </td>
                                        
                                        <td class="text-center">
                                            @if($order->status === 'paid')
                                                <span class="badge bg-success text-white">
                                                    <i class="bi bi-check-circle-fill me-1"></i>Đã thanh toán
                                                </span>
                                            @elseif($order->status === 'processing')
                                                <span class="badge bg-primary text-white">
                                                    <i class="bi bi-gear-wide-connected me-1"></i>Đang xử lý
                                                </span>
                                            @elseif($order->status === 'cancelled')
                                                <span class="badge bg-danger text-white">
                                                    <i class="bi bi-x-circle-fill me-1"></i>Đã hủy
                                                </span>
                                            @elseif($order->status === 'completed')
                                                <span class="badge bg-dark text-white">
                                                    <i class="bi bi-check2-all me-1"></i>Hoàn thành
                                                </span>
                                            @else
                                                <span class="badge border text-dark">
                                                    <i class="bi bi-clock me-1"></i>Chờ thanh toán
                                                </span>
                                            @endif
                                            @if($order->payment_method === 'cod_install')
                                                @if($order->cash_remitted)
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle d-block mt-1" style="font-size: 0.7rem;">
                                                        <i class="bi bi-cash-coin me-1"></i>Đã nộp tiền
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle d-block mt-1" style="font-size: 0.7rem;">
                                                        <i class="bi bi-hourglass-split me-1"></i>Chưa nộp tiền
                                                    </span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-center pe-4 text-nowrap align-middle" style="width: 1%;">
                                            <div class="d-inline-flex align-items-center justify-content-center gap-1.5 flex-nowrap">
                                                @if(!in_array($order->status, ['paid', 'completed']))
                                                <button type="button" class="btn btn-sm btn-outline-dark rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#modalEditOrder_{{ $order->id }}">
                                                    <i class="bi bi-pencil-fill me-1"></i>Sửa
                                                </button>
                                                @endif
                                                <button type="button" class="btn btn-sm btn-dark rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#modalAssignDelivery_{{ $order->id }}">
                                                    <i class="bi bi-truck me-1"></i>Giao NV
                                                </button>
                                                <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST" class="m-0 d-inline-flex align-items-center" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không? Khách hàng có thể sẽ không hài lòng.');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-bold">
                                                        <i class="bi bi-x-circle-fill me-1"></i>Hủy
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5 text-secondary">
                                            <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                                            Hiện không có đơn hàng nào chờ xử lý.
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

    <!-- Import / Export Warehousing Panel -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent border-bottom p-4">
                    <div class="d-md-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-3 mb-md-0 text-dark"><i class="bi bi-arrow-left-right text-dark me-2"></i>Quản Lý Luồng Kho Hàng</h5>
                        
                        <div class="d-flex align-items-center">
                            <!-- Tabs Navigation -->
                            <ul class="nav nav-pills me-3" id="warehouseTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active fw-bold" id="import-tab" data-bs-toggle="tab" data-bs-target="#import-pane" type="button" role="tab" aria-controls="import-pane" aria-selected="true">
                                        <i class="bi bi-box-arrow-in-down me-1"></i> Nhập sản phẩm vào
                                    </button>
                                </li>
                                <li class="nav-item ms-2" role="presentation">
                                    <button class="nav-link fw-bold" id="export-tab" data-bs-toggle="tab" data-bs-target="#export-pane" type="button" role="tab" aria-controls="export-pane" aria-selected="false">
                                        <i class="bi bi-box-arrow-up me-1"></i> Xuất sản phẩm đi
                                    </button>
                                </li>
                            </ul>

                            <!-- Add Record Button -->
                            <button type="button" class="btn btn-dark btn-sm px-3" id="btnAddRecord" data-bs-toggle="modal" data-bs-target="#modalImport">
                                <i class="bi bi-plus-circle-fill me-1"></i> Tạo phiếu nhập
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="tab-content" id="warehouseTabContent">
                        <!-- Pane 1: Nhập hàng -->
                        <div class="tab-pane fade show active p-3" id="import-pane" role="tabpanel" aria-labelledby="import-tab" tabindex="0">
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Mã phiếu nhập</th>
                                            <th>Tên thiết bị</th>
                                            <th>Nhà cung cấp</th>
                                            <th>Số lượng</th>
                                            <th class="text-end">Tổng tiền nhập</th>
                                            <th>Ngày lập</th>
                                            <th class="text-center">Trạng thái</th>
                                            <th class="text-center text-nowrap" style="width: 1%; min-width: 290px;">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $groupedImports = collect($importsHistory ?? [])->groupBy('code');
                                        @endphp
                                        @forelse($groupedImports as $code => $group)
                                            @php
                                                $firstImport = $group->first();
                                                $totalQuantity = $group->sum('quantity');
                                                $totalPrice = $group->sum('total');
                                                $status = $firstImport->status;
                                                $supplier = $firstImport->supplier;
                                                $createdAt = $firstImport->created_at->format('d/m/Y H:i');
                                            @endphp
                                            <tr>
                                                <td class="fw-bold text-dark">{{ $code }}</td>
                                                <td class="fw-bold text-dark">{{ $group->count() }} mặt hàng</td>
                                                <td>{{ $supplier }}</td>
                                                <td class="text-center fw-bold">{{ $totalQuantity }}</td>
                                                <td class="text-end text-success fw-bold">{{ number_format($totalPrice, 0, ',', '.') }} đ</td>
                                                <td class="text-secondary small">{{ $createdAt }}</td>
                                                <td class="text-center">
                                                    <span class="badge {{ $status == 'Đã hoàn thành' ? 'bg-success' : 'bg-light text-dark border text-dark' }} rounded-pill px-3 py-1.5" style="font-size: 0.75rem;">
                                                        {{ $status }}
                                                    </span>
                                                </td>
                                                <td class="text-center text-nowrap align-middle" style="width: 1%;">
                                                    <div class="d-inline-flex align-items-center justify-content-center gap-1.5 flex-nowrap">
                                                        <button type="button" class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#modalImportDetail_{{ $code }}" title="Xem chi tiết">
                                                            <i class="bi bi-eye-fill me-1"></i> Chi tiết
                                                        </button>
                                                        @if($status !== 'Đã hoàn thành')
                                                            <button type="button" class="btn btn-dark btn-sm rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#modalConfirm_{{ $code }}">
                                                                <i class="bi bi-check-circle-fill me-1"></i> Xác nhận
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn-light btn-sm border rounded-pill px-3 fw-bold text-muted" disabled title="Phiếu nhập kho này đã được xác nhận">
                                                                <i class="bi bi-check-circle-fill text-success me-1"></i> Đã xác nhận
                                                            </button>
                                                        @endif
                                                        <form action="{{ route('admin.imports.destroy', $firstImport->id) }}" method="POST" class="m-0 d-inline-flex align-items-center" onsubmit="return confirm('Bạn có chắc chắn muốn xóa phiếu nhập kho này? Lưu ý: Hành động này sẽ xóa phiếu nhập (Demo).');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold">
                                                                <i class="bi bi-trash-fill me-1"></i> Xóa
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center text-secondary py-4">Chưa có lịch sử nhập kho.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
</div>

                        <!-- Pane 2: Xuất hàng -->
                        <div class="tab-pane fade p-3" id="export-pane" role="tabpanel" aria-labelledby="export-tab" tabindex="0">

                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Mã xuất kho</th>
                                            <th>Tên thiết bị</th>
                                            <th>Khách hàng</th>
                                            <th>Nhân viên giao hàng</th>
                                            <th class="text-center">Số lượng</th>
                                            <th class="text-end">Đơn giá bán</th>
                                            <th>Ngày xuất</th>
                                            <th class="text-center">Trạng thái</th>
                                            <th class="text-center text-nowrap" style="width: 1%; min-width: 320px;">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($exportsHistory ?? [] as $export)
                                            <tr>
                                                <td class="fw-bold text-dark">{{ $export->code }}</td>
                                                <td class="fw-bold text-dark">{{ $export->product?->name ?? 'Sản phẩm đã bị xóa' }}</td>
                                                <td>{{ $export->customer_name }}</td>
                                                <td>
                                                    @php
                                                        $shippingVal = trim($export->shipping ?? '');
                                                        $staffName = $shippingVal;
                                                        $staffPhone = null;
                                                        if (preg_match('/^(.*?)\s*\((.*?)\)$/', $shippingVal, $matches)) {
                                                            $staffName = trim($matches[1]);
                                                            $staffPhone = trim($matches[2]);
                                                        }
                                                    @endphp
                                                    @if($shippingVal === 'Chưa phân bổ' || empty($shippingVal))
                                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary border-opacity-25 px-2.5 py-1.5 fw-medium">
                                                            <i class="bi bi-person-dash me-1"></i>Chưa phân bổ
                                                        </span>
                                                    @else
                                                        <div class="d-flex flex-column">
                                                            <span class="fw-bold text-dark">
                                                                <i class="bi bi-person-badge text-primary me-1"></i>{{ $staffName }}
                                                            </span>
                                                            @if($staffPhone)
                                                                <small class="text-secondary" style="font-size: 0.78rem;">
                                                                    <i class="bi bi-telephone-fill text-muted me-1"></i>{{ $staffPhone }}
                                                                </small>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="text-center fw-bold">{{ $export->quantity }}</td>
                                                <td class="text-end text-success fw-bold">{{ number_format($export->total, 0, ',', '.') }} đ</td>
                                                <td class="text-secondary small">{{ $export->created_at->format('d/m/Y H:i') }}</td>
                                                <td class="text-center">
                                                    @php
                                                        $badgeColor = 'bg-dark';
                                                        if($export->status == 'Đã giao hàng') $badgeColor = 'bg-success';
                                                        if($export->status == 'Chờ xác nhận') $badgeColor = 'bg-danger';
                                                    @endphp
                                                    <span class="badge {{ $badgeColor }} rounded-pill px-3 py-1.5" style="font-size: 0.75rem;">
                                                        {{ $export->status }}
                                                    </span>
                                                </td>
                                                <td class="text-center text-nowrap align-middle" style="width: 1%;">
                                                    <div class="d-inline-flex align-items-center justify-content-center gap-1.5 flex-nowrap">
                                                        <!-- Nút Chi tiết -->
                                                        <button type="button" class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#modalExportDetail_{{ $export->id }}" title="Xem chi tiết">
                                                            <i class="bi bi-eye-fill me-1"></i> Chi tiết
                                                        </button>

                                                        <!-- Nút In hóa đơn -->
                                                        <a href="{{ route('admin.exports.invoice', $export->id) }}" target="_blank" class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-bold" title="In hóa đơn">
                                                            <i class="bi bi-printer-fill me-1"></i> In
                                                        </a>

                                                        @if($export->status !== 'Hoàn thành' && $export->status !== 'Đã hủy')
                                                        <!-- Nút Hoàn thành -->
                                                        <form action="{{ route('admin.exports.complete', $export->id) }}" method="POST" class="m-0 d-inline-flex align-items-center" onsubmit="return confirm('Bạn có chắc chắn muốn hoàn thành phiếu xuất kho này? Số lượng tồn kho sẽ bị trừ nếu chưa bị trừ.');">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-bold">
                                                                <i class="bi bi-check-circle-fill me-1"></i> Hoàn thành
                                                            </button>
                                                        </form>
                                                        @endif
                                                        
                                                        <!-- Nút Xóa -->
                                                        <form action="{{ route('admin.exports.destroy', $export->id) }}" method="POST" class="m-0 d-inline-flex align-items-center" onsubmit="return confirm('Bạn có chắc chắn muốn xóa phiếu xuất kho này? Hành động này không thể hoàn tác.');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold">
                                                                <i class="bi bi-trash-fill me-1"></i> Xóa
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center text-secondary py-4">Chưa có lịch sử xuất kho.</td>
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
    </div>
</div>
@endsection

@section('modals')
<!-- Modals for Edit Order (Moved outside table) -->
                    @foreach($pendingOrders ?? [] as $order)
                    @if(!in_array($order->status, ['paid', 'completed']))
                    <div class="modal fade" id="modalEditOrder_{{ $order->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                        <div class="modal-dialog modal-dialog-centered text-start">
                            <div class="modal-content border-0 shadow-lg rounded-4">
                                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header bg-light border-bottom-0">
                                        <h5 class="modal-title fw-bold text-dark"><i class="bi bi-pencil-square text-dark me-2"></i>Sửa Thông Tin Giao Hàng</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-4 text-start">
                                        <h6 class="fw-bold text-dark mb-3">Thông Tin Khách Hàng</h6>
                                        <div class="mb-3">
                                            <label class="form-label text-secondary small fw-bold">Tên khách hàng</label>
                                            <input type="text" name="name" class="form-control bg-light" value="{{ $order->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-secondary small fw-bold">Số điện thoại</label>
                                            <input type="text" name="phone" class="form-control bg-light" value="{{ $order->phone }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-secondary small fw-bold">Địa chỉ giao hàng</label>
                                            <textarea name="address" class="form-control bg-light" rows="2" required>{{ $order->address }}</textarea>
                                        </div>

                                        <h6 class="fw-bold text-dark mb-3 mt-4">Sản Phẩm Đơn Hàng</h6>
                                        <div class="table-responsive border rounded-3 bg-light">
                                            <table class="table table-sm table-borderless align-middle mb-0">
                                                <thead class="table-light border-bottom">
                                                    <tr>
                                                        <th>Sản phẩm</th>
                                                        <th class="text-center" width="100">Số lượng</th>
                                                        <th class="text-end">Đơn giá</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($order->items as $item)
                                                    <tr>
                                                        <td>
                                                            <small class="fw-bold d-block text-truncate" style="max-width: 200px;" title="{{ $item->product ? $item->product->name : 'Sản phẩm đã xóa' }}">
                                                                {{ $item->product ? $item->product->name : 'Sản phẩm đã bị xóa' }}
                                                            </small>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="items[{{ $item->id }}][quantity]" class="form-control form-control-sm text-center" value="{{ $item->quantity }}" min="0" {{ $item->product ? '' : 'readonly' }}>
                                                            <small class="text-muted d-block text-center mt-1">(Nhập 0 để xóa)</small>
                                                        </td>
                                                        <td class="text-end">
                                                            <small class="fw-bold text-danger">{{ number_format($item->price, 0, ',', '.') }}đ</small>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @if($order->discount_amount > 0)
                                            <div class="text-end mt-2">
                                                <small class="text-muted">Giảm giá mã <span class="fw-bold">{{ $order->coupon_code }}</span>: -{{ number_format($order->discount_amount, 0, ',', '.') }}đ</small>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer border-top-0 justify-content-center pb-4">
                                        <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Hủy bỏ</button>
                                        <button type="submit" class="btn btn-dark text-white rounded-pill px-5 fw-bold shadow-sm">Cập nhật đơn hàng</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Modal Giao nhiệm vụ cho nhân viên -->
                    <div class="modal fade" id="modalAssignDelivery_{{ $order->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                        <div class="modal-dialog modal-dialog-centered text-start">
                            <div class="modal-content border-0 shadow-lg rounded-4">
                                <form action="{{ route('admin.orders.assignDelivery', $order->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-header bg-light border-bottom-0">
                                        <h5 class="modal-title fw-bold text-dark"><i class="bi bi-truck text-dark me-2"></i>Giao nhiệm vụ giao hàng</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-4 text-start">
                                        <p class="mb-3">Chọn nhân viên để giao đơn hàng <strong>#{{ $order->id }}</strong> (Khách: {{ $order->name }})</p>
                                        <div class="mb-3">
                                            <label class="form-label text-secondary fw-bold">Nhân viên giao hàng</label>
                                            <select name="delivery_staff_id" class="form-select bg-light" required>
                                                <option value="">-- Chọn nhân viên --</option>
                                                @foreach($deliveryStaffUsers as $staffUser)
                                                    <option value="{{ $staffUser->id }}" {{ $order->delivery_staff_id == $staffUser->id ? 'selected' : '' }}>
                                                        {{ $staffUser->name }} ({{ $staffUser->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top-0 justify-content-center pb-4">
                                        <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Hủy bỏ</button>
                                        <button type="submit" class="btn btn-dark rounded-pill px-5 fw-bold shadow-sm">Giao nhiệm vụ</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    
                    @endforeach



<!-- Modals for Confirm Import (Moved outside table) -->
                            @forelse($groupedImports ?? [] as $code => $group)
                                @php
                                    $firstImport = $group->first();
                                    $supplier = $firstImport->supplier;
                                @endphp
                                <div class="modal fade" id="modalConfirm_{{ $code }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                                    <div class="modal-dialog modal-dialog-centered text-start">
                                        <div class="modal-content border-0 shadow-lg rounded-4">
                                            <form action="{{ route('admin.imports.confirmBatch', $code) }}" method="POST" onsubmit="if(!document.getElementById('check_{{ $code }}').checked) { alert('Vui lòng đánh dấu Xác nhận đơn hàng đó đã đúng trước khi Lưu lại!'); return false; }">
                                                @csrf
                                                @method('PATCH')
                                                <div class="modal-header bg-light border-bottom-0">
                                                    <h5 class="modal-title fw-bold text-dark"><i class="bi bi-clipboard-check text-dark me-2"></i>Xác nhận phiếu nhập</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="alert alert-info rounded-3 mb-4">
                                                        <h6 class="fw-bold mb-1">Mã phiếu: {{ $code }}</h6>
                                                        <small>Nhà cung cấp: {{ $supplier }}</small>
                                                    </div>
                                                    
                                                    <h6 class="fw-bold text-secondary mb-3">Danh sách thiết bị đã nhập:</h6>
                                                    <ul class="list-group list-group-flush mb-4 rounded-3 border">
                                                        @foreach($group as $item)
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <span class="fw-bold text-dark d-block">{{ $item->product?->name ?? 'SP không rõ' }}</span>
                                                                    <small class="text-muted">Đơn giá: {{ number_format($item->unit_price, 0, ',', '.') }}đ</small>
                                                                </div>
                                                                <span class="badge bg-dark rounded-pill px-3">{{ $item->quantity }} chiếc</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                    
                                                    <div class="form-check d-flex justify-content-center bg-light p-3 rounded-3">
                                                        <input class="form-check-input me-2 border-primary" type="checkbox" id="check_{{ $code }}" style="transform: scale(1.2);">
                                                        <label class="form-check-label fw-bold text-danger" for="check_{{ $code }}" style="cursor: pointer;">
                                                            Xác nhận đơn hàng đó đã đúng
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-top-0 justify-content-center pb-4">
                                                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Hủy bỏ</button>
                                                    <button type="submit" class="btn btn-success rounded-pill px-5 fw-bold shadow-sm">Lưu lại</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                            @endforelse

<!-- ================= MODAL NHẬP HÀNG (ADD / EDIT) ================= -->
<div class="modal fade" id="modalImport" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modalImportLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-white shadow-sm border border-secondary border-opacity-25 text-dark rounded-4">
            <div class="modal-header border-bottom border-secondary border-opacity-20">
                <h5 class="modal-title fw-bold display-font" id="modalImportTitle"><i class="bi bi-box-arrow-in-down text-dark me-2"></i>Tạo Phiếu Nhập Kho</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formImport" action="{{ route('admin.imports.store') }}" method="POST">
                @csrf
                <div id="methodImportContainer"></div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <!-- Cột Trái: Thông tin chung & Bộ lọc -->
                        <div class="col-lg-4 border-end border-secondary border-opacity-25 pe-lg-4">
                            <div class="sticky-top" style="top: 1rem; z-index: 10;">
                                <!-- Nhà cung cấp -->
                                <div class="mb-3">
                                    <label class="form-label text-secondary small fw-bold">Nhà cung cấp</label>
                                    <input type="text" name="supplier" id="import_supplier" class="form-control bg-light text-dark border-secondary border-opacity-35 rounded-3" placeholder="Nhập tên nhà cung cấp..." required>
                                </div>

                                <!-- Thanh lọc và Tìm kiếm SP -->
                                <div class="mb-4 bg-light p-3 rounded-3 border border-secondary border-opacity-25">
                                    <div class="mb-2"><small class="text-dark fw-bold"><i class="bi bi-funnel"></i> Bộ lọc nhanh sản phẩm</small></div>
                                    <div class="mb-2">
                                        <select id="import_category_filter" class="form-select form-select-sm bg-white text-dark shadow-sm border-secondary border-opacity-25" onchange="filterProducts('import')">
                                            <option value="">Tất cả danh mục</option>
                                            @foreach($uniqueCategories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <input type="text" id="import_search_filter" class="form-control form-control-sm bg-white text-dark shadow-sm border-secondary border-opacity-25" placeholder="Nhập tên SP cần tìm..." oninput="filterProducts('import')">
                                    </div>
                                </div>
                                
                                <!-- Trạng thái -->
                                <div class="mb-3">
                                    <label class="form-label text-secondary small fw-bold">Trạng thái phiếu</label>
                                    <select name="status" id="import_status" class="form-select bg-light text-dark border-secondary border-opacity-35 rounded-3" required>
                                        <option value="Đang kiểm kho" selected>Đang kiểm kho (Chờ xác nhận)</option>
                                        <option value="Đã hoàn thành">Đã hoàn thành</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Cột Phải: Danh sách sản phẩm nhập -->
                        <div class="col-lg-8">
                            <h6 class="fw-bold mb-3 text-secondary border-bottom pb-2">Chi tiết sản phẩm nhập</h6>
                            
                            <div id="import_products_container">
                                <div class="import-product-row border rounded-3 p-3 mb-3 position-relative bg-white shadow-sm">
                                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2 btn-remove-product" aria-label="Close" style="display: none;"></button>
                                    
                                    <!-- Chọn sản phẩm -->
                                    <div class="mb-3">
                                        <label class="form-label text-secondary small fw-bold">Sản phẩm nhập</label>
                                        <select name="product_id[]" class="form-select import_product_select bg-light text-dark border-secondary border-opacity-35 rounded-3" required>
                                            <option value="" data-price="">-- Chọn sản phẩm bên dưới --</option>
                                            @foreach($products ?? [] as $prod)
                                                <option value="{{ $prod->id }}" data-category="{{ $prod->category_id }}" data-name="{{ $prod->name }}" data-price="{{ $prod->price }}">
                                                    [{{ $prod->category?->name }}] {{ $prod->name }} (Hiện tại: {{ $prod->quantity }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="mt-1"><small class="text-dark fw-bold current-selling-price" style="display: none;">Giá bán hiện tại: <span class="price-val">0</span> đ</small></div>
                                    </div>
                                    
                                    <!-- Số lượng & Đơn giá Nhập -->
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label text-secondary small fw-bold">Số lượng nhập</label>
                                            <input type="number" name="quantity[]" min="1" class="form-control import_quantity_input bg-light text-dark border-secondary border-opacity-35 rounded-3" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-secondary small fw-bold">Đơn giá nhập từ NCC (đ)</label>
                                            <input type="number" name="unit_price[]" min="0" class="form-control import_price_input bg-light text-dark border-secondary border-opacity-35 rounded-3" placeholder="Nhập giá mua vào..." required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-end mt-2" id="add_product_row_container">
                                <button type="button" class="btn btn-outline-dark rounded-pill fw-bold" id="btn_add_import_product">
                                    <i class="bi bi-plus-circle me-1"></i> Thêm sản phẩm khác
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-20">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="submit" class="btn btn-dark rounded-pill px-4"><i class="bi bi-save me-1"></i> Lưu phiếu nhập</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ================= MODAL XUẤT HÀNG (ADD / EDIT) ================= -->
<div class="modal fade" id="modalExport" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modalExportLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-white shadow-sm border border-secondary border-opacity-25 text-dark rounded-4">
            <div class="modal-header border-bottom border-secondary border-opacity-20">
                <h5 class="modal-title fw-bold display-font" id="modalExportTitle"><i class="bi bi-box-arrow-up text-dark me-2"></i>Tạo Phiếu Xuất Kho</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formExport" action="{{ route('admin.exports.store') }}" method="POST">
                @csrf
                <div id="methodExportContainer"></div>
                <div class="modal-body">
                    <!-- Thanh lọc và Tìm kiếm SP -->
                    <div class="row g-2 mb-3 bg-light p-2 rounded-3 border border-secondary border-opacity-25">
                        <div class="col-12 mb-1"><small class="text-dark fw-bold"><i class="bi bi-funnel"></i> Bộ lọc nhanh sản phẩm</small></div>
                        <div class="col-md-6">
                            <select id="export_category_filter" class="form-select form-select-sm bg-white text-dark shadow-sm border-secondary border-opacity-25" onchange="filterProducts('export')">
                                <option value="">Tất cả danh mục</option>
                                @foreach($uniqueCategories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" id="export_search_filter" class="form-control form-control-sm bg-white text-dark shadow-sm border-secondary border-opacity-25" placeholder="Nhập tên SP cần tìm..." oninput="filterProducts('export')">
                        </div>
                    </div>

                    <!-- Chọn sản phẩm -->
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold">Sản phẩm xuất</label>
                        <select name="product_id" id="export_product" class="form-select bg-light text-dark border-secondary border-opacity-35 rounded-3" required>
                            <option value="" data-price="">-- Chọn sản phẩm bên dưới --</option>
                            @foreach($products ?? [] as $prod)
                                <option value="{{ $prod->id }}" 
                                        data-category="{{ $prod->category_id }}" 
                                        data-name="{{ $prod->name }}"
                                        data-price="{{ $prod->price }}">
                                    [{{ $prod->category?->name }}] {{ $prod->name }} (Giá bán: {{ number_format($prod->price, 0, ',', '.') }} đ | Tồn: {{ $prod->quantity }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Tên khách hàng -->
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold">Họ và tên khách hàng / Đối tác</label>
                        <input type="text" name="customer_name" id="export_customer" class="form-control bg-light text-dark border-secondary border-opacity-35 rounded-3" placeholder="Nhập tên người nhận hàng..." required>
                    </div>
                    <!-- Nhân viên giao hàng -->
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold">Nhân viên giao hàng phụ trách</label>
                        <select name="shipping" id="export_shipping" class="form-select bg-light text-dark border-secondary border-opacity-35 rounded-3">
                            <option value="Chưa phân bổ">-- Chọn nhân viên giao hàng (hoặc để trống) --</option>
                            @foreach($allDeliveryStaff ?? [] as $staff)
                                <option value="{{ $staff->name }}{{ $staff->phone ? ' (' . $staff->phone . ')' : '' }}">{{ $staff->name }} - SĐT: {{ $staff->phone ?? 'Chưa có SĐT' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Số lượng & Đơn giá xuất -->
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label text-secondary small fw-bold">Số lượng xuất</label>
                            <input type="number" name="quantity" id="export_quantity" min="1" class="form-control bg-light text-dark border-secondary border-opacity-35 rounded-3" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-secondary small fw-bold">Đơn giá bán ra (đ)</label>
                            <input type="number" name="unit_price" id="export_price" min="0" class="form-control bg-light text-dark border-secondary border-opacity-35 rounded-3" placeholder="Tự động theo giá niêm yết" required>
                        </div>
                    </div>
                    <!-- Trạng thái -->
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold">Trạng thái xuất</label>
                        <select name="status" id="export_status" class="form-select bg-light text-dark border-secondary border-opacity-35 rounded-3" required>
                            <option value="Chờ xác nhận">Chờ xác nhận (Từ Đơn hàng duyệt sang)</option>
                            <option value="Đang xử lý">Đang xử lý (Đang lấy hàng)</option>
                            <option value="Đã giao hàng">Đã giao hàng (Hoàn tất)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-20">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="submit" class="btn btn-dark rounded-pill px-4"><i class="bi bi-save me-1"></i> Lưu phiếu xuất</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ================= MODAL QUẢN LÝ NHÂN VIÊN (ADD / EDIT) ================= -->
<div class="modal fade" id="modalStaff" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modalStaffLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-white shadow-sm border border-secondary border-opacity-25 text-dark rounded-4">
            <div class="modal-header border-bottom border-secondary border-opacity-20">
                <h5 class="modal-title fw-bold display-font" id="modalStaffTitle"><i class="bi bi-person-plus text-dark me-2"></i>Thêm Nhân Viên Mới</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formStaff" action="{{ route('admin.staff.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="methodStaffContainer"></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold">Ảnh đại diện</label>
                        <input type="file" name="avatar" id="staff_avatar" class="form-control bg-light text-dark border-secondary border-opacity-35 rounded-3" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold">Tên nhân viên</label>
                        <input type="text" name="name" id="staff_name" class="form-control bg-light text-dark border-secondary border-opacity-35 rounded-3" placeholder="Nhập họ tên nhân viên..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold">Vị trí / Chức vụ</label>
                        <input type="text" name="role" id="staff_role" class="form-control bg-light text-dark border-secondary border-opacity-35 rounded-3" placeholder="Ví dụ: Kỹ thuật viên, NV Bán hàng..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold">Số điện thoại</label>
                        <input type="text" name="phone" id="staff_phone" class="form-control bg-light text-dark border-secondary border-opacity-35 rounded-3" placeholder="Nhập số điện thoại...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold">CCCD / CMND</label>
                        <input type="text" name="cccd" id="staff_cccd" class="form-control bg-light text-dark border-secondary border-opacity-35 rounded-3" placeholder="Nhập CCCD hoặc CMND...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold">Nơi ở hiện tại</label>
                        <input type="text" name="address" id="staff_address" class="form-control bg-light text-dark border-secondary border-opacity-35 rounded-3" placeholder="Nhập địa chỉ, nơi ở...">
                    </div>

                </div>
                <div class="modal-footer border-top border-secondary border-opacity-20">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-dark rounded-pill px-4"><i class="bi bi-save me-1"></i> Lưu thông tin</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modals for Import Details -->
@foreach($groupedImports ?? [] as $code => $group)
<div class="modal fade" id="modalImportDetail_{{ $code }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-file-earmark-text text-dark me-2"></i>Chi tiết phiếu nhập: {{ $code }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1"><span class="text-muted">Nhà cung cấp:</span> <span class="fw-bold">{{ $group->first()->supplier }}</span></p>
                        <p class="mb-1"><span class="text-muted">Ngày lập:</span> <span class="fw-bold">{{ $group->first()->created_at->format('d/m/Y H:i') }}</span></p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="mb-1"><span class="text-muted">Trạng thái:</span> <span class="badge {{ $group->first()->status == 'Đã hoàn thành' ? 'bg-success' : 'bg-light text-dark border text-dark' }}">{{ $group->first()->status }}</span></p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Sản phẩm</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-end">Đơn giá</th>
                                <th class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($group as $item)
                            <tr>
                                <td>{{ $item->product->name ?? 'Sản phẩm đã bị xóa' }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">{{ number_format($item->unit_price, 0, ',', '.') }} đ</td>
                                <td class="text-end fw-bold text-success">{{ number_format($item->total, 0, ',', '.') }} đ</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="3" class="text-end">Tổng cộng:</th>
                                <th class="text-end text-danger fs-5">{{ number_format($group->sum('total'), 0, ',', '.') }} đ</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modals for Export Details -->
@foreach($exportsHistory ?? [] as $export)
<div class="modal fade" id="modalExportDetail_{{ $export->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-file-earmark-arrow-up text-dark me-2"></i>Chi tiết phiếu xuất: {{ $export->code }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1"><span class="text-muted">Khách hàng:</span> <span class="fw-bold">{{ $export->customer_name }}</span></p>
                        <p class="mb-1"><span class="text-muted">Nhân viên giao hàng:</span> <span class="fw-bold">{{ $export->shipping }}</span></p>
                        <p class="mb-1"><span class="text-muted">Ngày xuất:</span> <span class="fw-bold">{{ $export->created_at->format('d/m/Y H:i') }}</span></p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="mb-1"><span class="text-muted">Trạng thái:</span> <span class="badge {{ $export->status == 'Đã giao hàng' ? 'bg-success' : ($export->status == 'Chờ xác nhận' ? 'bg-danger' : 'bg-dark') }}">{{ $export->status }}</span></p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Sản phẩm</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-end">Đơn giá (tạm tính)</th>
                                <th class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $export->product->name ?? 'Sản phẩm đã bị xóa' }}</td>
                                <td class="text-center">{{ $export->quantity }}</td>
                                <td class="text-end">{{ $export->quantity > 0 ? number_format($export->total / $export->quantity, 0, ',', '.') : 0 }} đ</td>
                                <td class="text-end fw-bold text-success">{{ number_format($export->total, 0, ',', '.') }} đ</td>
                            </tr>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="3" class="text-end">Tổng cộng:</th>
                                <th class="text-end text-danger fs-5">{{ number_format($export->total, 0, ',', '.') }} đ</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @if($export->note)
                <div class="mt-3 p-3 bg-light rounded">
                    <strong>Ghi chú:</strong> {{ $export->note }}
                </div>
                @endif
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@push('scripts')
<!-- Tải thư viện Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // --- 1. BIỂU ĐỒ REVENUE ---
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(0, 122, 255, 0.35)');
        gradient.addColorStop(1, 'rgba(0, 122, 255, 0.0)');
        
        const chartData = {
            labels: {!! json_encode($chartLabels ?? []) !!},
            datasets: [{
                label: 'Doanh thu (đ)',
                data: {!! json_encode($chartValues ?? []) !!},
                fill: true,
                backgroundColor: gradient,
                borderColor: '#007aff',
                borderWidth: 3,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#007aff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                tension: 0.4
            }]
        };

        // Tính max động: lấy giá trị lớn nhất trong data, tối thiểu 1 triệu để tránh lỗi số khoa học
        const rawValues = {!! json_encode($chartValues ?? []) !!};
        const maxVal = Math.max(...rawValues, 1000000);
        // Làm tròn lên bội số đẹp (10tr, 50tr, 100tr...)
        const magnitude = Math.pow(10, Math.floor(Math.log10(maxVal)));
        const niceMax = Math.ceil(maxVal / magnitude) * magnitude;

        new Chart(ctx, {
            type: 'line',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1c1c1e',
                        titleColor: '#ffffff',
                        bodyColor: '#e5e5e5',
                        borderColor: 'rgba(255,255,255,0.08)',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Doanh thu: ' + Math.round(context.parsed.y).toLocaleString('vi-VN') + ' đ';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(150,150,150,0.08)' },
                        ticks: { color: '#8e8e93', font: { family: 'Outfit' } }
                    },
                    y: {
                        beginAtZero: true,
                        max: niceMax,
                        grid: { color: 'rgba(150,150,150,0.08)' },
                        ticks: {
                            color: '#8e8e93',
                            font: { family: 'Outfit' },
                            maxTicksLimit: 6,
                            callback: function(value) {
                                if (value === 0) return '0';
                                if (value >= 1000000000) return (value / 1000000000).toFixed(1).replace('.0','') + ' Tỷ';
                                if (value >= 1000000) return (value / 1000000).toFixed(1).replace('.0','') + ' Tr';
                                if (value >= 1000) return (value / 1000).toFixed(0) + ' K';
                                return value.toLocaleString('vi-VN');
                            }
                        }
                    }
                }
            }
        });

        // --- 2. XỬ LÝ ĐỔI TAB & NÚT TẠO MỚI (IMPORT/EXPORT) ---
        const btnAddRecord = document.getElementById('btnAddRecord');
        const importTab = document.getElementById('import-tab');
        const exportTab = document.getElementById('export-tab');

        importTab.addEventListener('click', function() {
            btnAddRecord.innerHTML = '<i class="bi bi-plus-circle-fill me-1"></i> Tạo phiếu nhập';
            btnAddRecord.setAttribute('data-bs-target', '#modalImport');
        });

        exportTab.addEventListener('click', function() {
            btnAddRecord.innerHTML = '<i class="bi bi-plus-circle-fill me-1"></i> Tạo phiếu xuất';
            btnAddRecord.setAttribute('data-bs-target', '#modalExport');
        });

        // --- 3. ĐỔ DỮ LIỆU EDIT NHẬP KHO ---
        const modalImportEl = document.getElementById('modalImport');
        const productsContainer = document.getElementById('import_products_container');
        const addProductBtn = document.getElementById('btn_add_import_product');
        const addProductContainer = document.getElementById('add_product_row_container');
        
        // Cập nhật script clone dòng sản phẩm
        if(addProductBtn) {
            addProductBtn.addEventListener('click', function() {
                const firstRow = productsContainer.querySelector('.import-product-row');
                const newRow = firstRow.cloneNode(true);
                
                // Reset giá trị
                newRow.querySelector('.import_product_select').value = '';
                newRow.querySelector('.import_quantity_input').value = '';
                newRow.querySelector('.import_price_input').value = '';
                newRow.querySelector('.current-selling-price').style.display = 'none';
                
                // Hiện nút xóa
                newRow.querySelector('.btn-remove-product').style.display = 'block';
                
                productsContainer.appendChild(newRow);
            });
        }
        
        // Bắt sự kiện xóa dòng sản phẩm
        productsContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-remove-product')) {
                if (productsContainer.querySelectorAll('.import-product-row').length > 1) {
                    e.target.closest('.import-product-row').remove();
                }
            }
        });

        // Bắt sự kiện thay đổi sản phẩm để hiển thị giá bán hiện tại
        productsContainer.addEventListener('change', function(e) {
            if (e.target.classList.contains('import_product_select')) {
                const selectedOption = e.target.options[e.target.selectedIndex];
                const price = selectedOption.getAttribute('data-price');
                const row = e.target.closest('.import-product-row');
                const priceLabel = row.querySelector('.current-selling-price');
                const priceVal = row.querySelector('.price-val');
                
                if (price) {
                    priceVal.innerText = new Intl.NumberFormat('vi-VN').format(price);
                    priceLabel.style.display = 'block';
                } else {
                    priceLabel.style.display = 'none';
                }
            }
        });

        document.querySelectorAll('.btn-edit-import').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const product = this.getAttribute('data-product');
                const supplier = this.getAttribute('data-supplier');
                const qty = this.getAttribute('data-quantity');
                const price = parseFloat(this.getAttribute('data-price')) || 0;
                const status = this.getAttribute('data-status');

                document.getElementById('modalImportTitle').innerHTML = '<i class="bi bi-pencil-square text-dark me-2"></i>Hiệu Chỉnh Phiếu Nhập';
                const form = document.getElementById('formImport');
                form.action = `/admin/imports/${id}`;
                document.getElementById('methodImportContainer').innerHTML = '<input type="hidden" name="_method" value="PUT">';

                // Reset về 1 dòng khi edit
                const rows = productsContainer.querySelectorAll('.import-product-row');
                for (let i = 1; i < rows.length; i++) {
                    rows[i].remove();
                }
                
                // Đổ dữ liệu vào dòng đầu tiên
                const firstRow = productsContainer.querySelector('.import-product-row');
                firstRow.querySelector('.import_product_select').value = product;
                firstRow.querySelector('.import_quantity_input').value = qty;
                firstRow.querySelector('.import_price_input').value = price;
                firstRow.querySelector('.btn-remove-product').style.display = 'none'; // Ẩn nút xóa

                document.getElementById('import_supplier').value = supplier;
                document.getElementById('import_status').value = status;
                
                // Ẩn nút "Thêm sản phẩm" vì khi sửa chỉ cho sửa dòng hiện tại
                addProductContainer.style.display = 'none';
            });
        });

        modalImportEl.addEventListener('hidden.bs.modal', function () {
            document.getElementById('modalImportTitle').innerHTML = '<i class="bi bi-box-arrow-in-down text-dark me-2"></i>Tạo Phiếu Nhập Kho';
            const form = document.getElementById('formImport');
            form.action = "{{ route('admin.imports.store') }}";
            document.getElementById('methodImportContainer').innerHTML = '';
            
            // Reset về 1 dòng
            const rows = productsContainer.querySelectorAll('.import-product-row');
            for (let i = 1; i < rows.length; i++) {
                rows[i].remove();
            }
            const firstRow = productsContainer.querySelector('.import-product-row');
            firstRow.querySelector('.import_product_select').value = '';
            firstRow.querySelector('.import_quantity_input').value = '';
            firstRow.querySelector('.import_price_input').value = '';
            firstRow.querySelector('.btn-remove-product').style.display = 'none';
            
            // Hiện lại nút thêm sản phẩm
            addProductContainer.style.display = 'block';
            document.getElementById('methodImportContainer').innerHTML = '';
            form.reset();
            document.getElementById('import_category_filter').value = "";
            document.getElementById('import_search_filter').value = "";
            filterProducts('import');
        });

        // --- 4. TỰ ĐỘNG LẤY GIÁ BÁN NIÊM YẾT KHI CHỌN SP XUẤT KHO ---
        const exportProductSelect = document.getElementById('export_product');
        if (exportProductSelect) {
            exportProductSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const sellingPrice = selectedOption.getAttribute('data-price');
                const form = document.getElementById('formExport');
                if (sellingPrice && !form.action.includes('/admin/exports/')) {
                    document.getElementById('export_price').value = sellingPrice;
                }
            });
        }

        // --- 5. ĐỔ DỮ LIỆU EDIT XUẤT KHO ---
        const modalExportEl = document.getElementById('modalExport');

        document.querySelectorAll('.btn-edit-export').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const product = this.getAttribute('data-product');
                const customer = this.getAttribute('data-customer');
                const shipping = this.getAttribute('data-shipping');
                const qty = this.getAttribute('data-quantity');
                const price = parseFloat(this.getAttribute('data-price')) || 0;
                const status = this.getAttribute('data-status');

                document.getElementById('modalExportTitle').innerHTML = '<i class="bi bi-pencil-square text-dark me-2"></i>Hiệu Chỉnh Phiếu Xuất';
                const form = document.getElementById('formExport');
                form.action = `/admin/exports/${id}`;
                document.getElementById('methodExportContainer').innerHTML = '<input type="hidden" name="_method" value="PUT">';

                document.getElementById('export_product').value = product;
                document.getElementById('export_customer').value = customer;
                document.getElementById('export_shipping').value = shipping;
                document.getElementById('export_quantity').value = qty;
                document.getElementById('export_price').value = price;
                document.getElementById('export_status').value = status;
            });
        });

        modalExportEl.addEventListener('hidden.bs.modal', function () {
            document.getElementById('modalExportTitle').innerHTML = '<i class="bi bi-box-arrow-up text-dark me-2"></i>Tạo Phiếu Xuất Kho';
            const form = document.getElementById('formExport');
            form.action = "{{ route('admin.exports.store') }}";
            document.getElementById('methodExportContainer').innerHTML = '';
            form.reset();
            document.getElementById('export_category_filter').value = "";
            document.getElementById('export_search_filter').value = "";
            filterProducts('export');
        });

        // --- 6. ĐỔ DỮ LIỆU EDIT NHÂN VIÊN ---
        const modalStaffEl = document.getElementById('modalStaff');
        document.querySelectorAll('.btn-edit-staff').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const role = this.getAttribute('data-role');
                const phone = this.getAttribute('data-phone');
                const cccd = this.getAttribute('data-cccd');
                const address = this.getAttribute('data-address');
                const avatar = this.getAttribute('data-avatar');

                document.getElementById('modalStaffTitle').innerHTML = '<i class="bi bi-pencil-square text-dark me-2"></i>Cập Nhật Nhân Viên';
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

        modalStaffEl.addEventListener('hidden.bs.modal', function () {
            document.getElementById('modalStaffTitle').innerHTML = '<i class="bi bi-person-plus text-dark me-2"></i>Thêm Nhân Viên Mới';
            const form = document.getElementById('formStaff');
            form.action = "{{ route('admin.staff.store') }}";
            document.getElementById('methodStaffContainer').innerHTML = '';
            form.reset();
        });
    });

    // --- HÀM TÌM KIẾM & LỌC SP ---
    function filterProducts(type) {
        let catId = document.getElementById(type + '_category_filter').value;
        let search = document.getElementById(type + '_search_filter').value.toLowerCase().trim();
        
        let selects;
        if(type === 'import') {
            selects = document.querySelectorAll('.import_product_select');
        } else {
            selects = [document.getElementById(type + '_product')];
        }

        selects.forEach(select => {
            let options = select.querySelectorAll('option');
            options.forEach(opt => {
                if (opt.value === "") return;
                
                let cat = opt.getAttribute('data-category');
                let name = opt.getAttribute('data-name');
                if (name) name = name.toLowerCase();
                
                let matchCat = (catId === "" || cat === catId);
                let matchSearch = (search === "" || (name && name.includes(search)));
                
                // Không ẩn option nếu nó đang được chọn (để giữ lại dữ liệu cũ)
                opt.hidden = !(matchCat && matchSearch) && !opt.selected;
            });
        });
    }
</script>
@endpush

