<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa Đơn Xuất Kho - {{ $export->code }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }
        .invoice-box {
            max-width: 800px;
            margin: 30px auto;
            padding: 40px;
            border: 1px solid #eee;
            background: #fff;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            border-radius: 12px;
        }
        .invoice-header {
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        @media print {
            body {
                background-color: #fff;
            }
            .invoice-box {
                box-shadow: none;
                border: none;
                margin: 0;
                padding: 10px;
                max-width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<div class="invoice-box">
    <!-- Nút điều khiển khi xem trên web -->
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">&larr; Quay lại</a>
        <button onclick="window.print()" class="btn btn-primary btn-sm px-4">
            <i class="bi bi-printer"></i> In Hóa Đơn Ngay
        </button>
    </div>

    <!-- Header Hóa đơn -->
    <div class="invoice-header d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold text-primary mb-1">LAPGEARZONE</h3>
            <p class="text-muted small mb-0">Hệ Thống Quản Trị & Phân Phối Thiết Bị Công Nghệ</p>
            <p class="text-muted small mb-0">Hotline: 0346884415 | Email: duongtk2pc@gmail.com</p>
        </div>
        <div class="text-end">
            <h4 class="fw-bold text-uppercase text-dark mb-1">HÓA ĐƠN XUẤT KHO</h4>
            <p class="text-muted small mb-0">Mã phiếu: <strong>{{ $export->code }}</strong></p>
            <p class="text-muted small mb-0">Ngày lập: {{ $export->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <!-- Thông tin khách hàng & Vận chuyển -->
    <div class="row mb-4">
        <div class="col-sm-6">
            <h6 class="fw-bold text-secondary text-uppercase small">Thông tin khách hàng:</h6>
            <h5 class="fw-bold text-dark mb-1">{{ $export->customer_name }}</h5>
            <p class="text-muted small mb-0">Hình thức nhận hàng: Trực tiếp / Giao hàng</p>
        </div>
        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <h6 class="fw-bold text-secondary text-uppercase small">Đơn vị vận chuyển:</h6>
            <p class="fw-bold text-dark mb-1"><i class="bi bi-truck me-1"></i> {{ $export->shipping }}</p>
            <p class="text-muted small mb-0">Trạng thái đơn: <span class="badge bg-success">{{ $export->status }}</span></p>
        </div>
    </div>

    <!-- Bảng chi tiết sản phẩm -->
    <div class="table-responsive mb-4">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th class="text-center" style="width: 50px;">STT</th>
                    <th>Tên sản phẩm thiết bị</th>
                    <th class="text-center" style="width: 100px;">Số lượng</th>
                    <th class="text-end" style="width: 150px;">Đơn giá bán</th>
                    <th class="text-end" style="width: 150px;">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td>
                        <strong>{{ $export->product?->name ?? 'Sản phẩm đã bị xóa' }}</strong>
                        <br><small class="text-muted">Danh mục: {{ $export->product?->category?->name ?? 'N/A' }}</small>
                    </td>
                    <td class="text-center fw-bold">{{ $export->quantity }}</td>
                    <td class="text-end">{{ number_format($export->unit_price, 0, ',', '.') }} đ</td>
                    <td class="text-end fw-bold text-success">{{ number_format($export->total, 0, ',', '.') }} đ</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-end fw-bold">Tổng giá trị thanh toán:</td>
                    <td class="text-end fw-bold text-danger fs-5">{{ number_format($export->total, 0, ',', '.') }} đ</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Chữ ký / Xác nhận -->
    <div class="row mt-5 pt-3 border-top">
        <div class="col-4 text-center">
            <p class="fw-bold mb-5">Người lập phiếu</p>
            <p class="text-muted small">(Ký, ghi rõ họ tên)</p>
        </div>
        <div class="col-4 text-center">
            <p class="fw-bold mb-5">Đơn vị vận chuyển</p>
            <p class="text-muted small">(Ký, ghi rõ họ tên)</p>
        </div>
        <div class="col-4 text-center">
            <p class="fw-bold mb-5">Khách hàng / Người nhận</p>
            <p class="text-muted small">(Ký, ghi rõ họ tên)</p>
        </div>
    </div>
</div>

</body>
</html> 