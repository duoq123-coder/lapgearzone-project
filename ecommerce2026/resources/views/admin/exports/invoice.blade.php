<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa Đơn Xuất Kho - {{ $export->code }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=PT+Serif:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #faf9f6;
            font-family: 'Inter', sans-serif;
            color: #232220;
        }
        .serif-font {
            font-family: 'PT Serif', Georgia, serif;
        }
        .invoice-box {
            max-width: 800px;
            margin: 30px auto;
            padding: 40px;
            border: 1px solid #e5e0d8;
            background: #ffffff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            border-radius: 14px;
        }
        .invoice-header {
            border-bottom: 2px solid #f4f1ea;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .table thead th {
            background-color: #f8f6f2 !important;
            border-color: #e5e0d8;
            color: #232220;
            font-weight: 600;
        }
        .table td, .table th {
            border-color: #e5e0d8;
            padding: 12px;
        }
        @media print {
            body {
                background-color: #ffffff;
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
        <a href="{{ url()->previous() }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">&larr; Quay lại</a>
        <button onclick="window.print()" class="btn btn-dark btn-sm px-4 rounded-pill" style="background: #1c1a19;">
            In Hóa Đơn Ngay
        </button>
    </div>

    <!-- Header Hóa đơn -->
    <div class="invoice-header d-flex justify-content-between align-items-center">
        <div>
            <h3 class="serif-font fw-bold text-dark mb-1" style="letter-spacing: -0.5px;">Cửa Hàng Công Nghệ</h3>
            <p class="text-muted small mb-0">Cửa Hàng Công Nghệ &amp; Phân Phối Thiết Bị Công Nghệ</p>
            <p class="text-muted small mb-0">Hotline: 0346 884 415 | Email: duongtk2pc@gmail.com</p>
        </div>
        <div class="text-end">
            <h4 class="serif-font fw-bold text-uppercase text-dark mb-1">HÓA ĐƠN XUẤT KHO</h4>
            <p class="text-muted small mb-0">Mã phiếu: <strong class="font-monospace text-dark">{{ $export->code }}</strong></p>
            <p class="text-muted small mb-0">Ngày lập: {{ $export->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <!-- Thông tin khách hàng & Vận chuyển -->
    <div class="row mb-4">
        <div class="col-sm-6">
            <h6 class="fw-bold text-secondary text-uppercase small" style="letter-spacing: 0.5px;">Thông tin khách hàng:</h6>
            <h5 class="fw-bold text-dark mb-1">{{ $export->customer_name }}</h5>
            <p class="text-muted small mb-0">Hình thức nhận: Bàn giao trực tiếp / Giao vận</p>
        </div>
        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <h6 class="fw-bold text-secondary text-uppercase small" style="letter-spacing: 0.5px;">Nhân viên giao hàng:</h6>
            <p class="fw-bold text-dark mb-1">{{ $export->shipping }}</p>
            <p class="text-muted small mb-0">Trạng thái: <span class="badge" style="background-color: #4e7969;">{{ $export->status }}</span></p>
        </div>
    </div>

    <!-- Bảng chi tiết sản phẩm -->
    <div class="table-responsive mb-4">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">STT</th>
                    <th>Tên thiết bị / Sản phẩm</th>
                    <th class="text-center" style="width: 100px;">Số lượng</th>
                    <th class="text-end" style="width: 150px;">Đơn giá</th>
                    <th class="text-end" style="width: 160px;">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td>
                        <strong class="text-dark">{{ $export->product?->name ?? 'Sản phẩm đã bị xóa' }}</strong>
                        <br><small class="text-muted">Danh mục: {{ $export->product?->category?->name ?? 'N/A' }}</small>
                    </td>
                    <td class="text-center fw-bold">{{ $export->quantity }}</td>
                    <td class="text-end">{{ number_format($export->unit_price, 0, ',', '.') }} đ</td>
                    <td class="text-end fw-bold text-dark">{{ number_format($export->total, 0, ',', '.') }} đ</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-end fw-bold">Tổng thanh toán:</td>
                    <td class="text-end fw-bold fs-5 text-dark">{{ number_format($export->total, 0, ',', '.') }} đ</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Chữ ký / Xác nhận -->
    <div class="row mt-5 pt-3 border-top" style="border-color: #e5e0d8 !important;">
        <div class="col-4 text-center">
            <p class="fw-bold mb-5 small text-uppercase">Người lập phiếu</p>
            <p class="text-muted small">(Ký, họ tên)</p>
        </div>
        <div class="col-4 text-center">
            <p class="fw-bold mb-5 small text-uppercase">Nhân viên giao nhận</p>
            <p class="text-muted small">(Ký, họ tên)</p>
        </div>
        <div class="col-4 text-center">
            <p class="fw-bold mb-5 small text-uppercase">Khách hàng / Người nhận</p>
            <p class="text-muted small">(Ký, họ tên)</p>
        </div>
    </div>
</div>

</body>
</html>
