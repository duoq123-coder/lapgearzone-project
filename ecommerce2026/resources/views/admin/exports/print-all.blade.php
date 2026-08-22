<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>In Danh Sách Xuất Kho</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Times New Roman', serif; color: #000; background: #fff; }
        .invoice-box { max-width: 1000px; margin: auto; padding: 30px; }
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
            .invoice-box { border: none; padding: 0; }
        }
        .table th { background-color: #f8f9fa !important; border-bottom: 2px solid #000; }
        .table td, .table th { padding: 12px; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold mb-1">BÁO CÁO XUẤT KHO</h1>
                <p class="mb-0">Hệ thống LapGearZone</p>
                <p class="text-muted small">Ngày in: {{ now()->format('d/m/Y H:i') }}</p>
            </div>
            <button class="btn btn-primary no-print px-4" onclick="window.print()">
                In báo cáo
            </button>
        </div>

        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th class="text-center">STT</th>
                    <th>Mã xuất kho</th>
                    <th>Ngày xuất</th>
                    <th>Tên thiết bị</th>
                    <th>Khách hàng</th>
                    <th class="text-center">Số lượng</th>
                    <th class="text-end">Thành tiền</th>
                    <th class="text-center">Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @php $totalAmount = 0; $totalQuantity = 0; @endphp
                @forelse($exports as $index => $export)
                    @php 
                        $totalAmount += $export->total; 
                        $totalQuantity += $export->quantity;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="fw-bold">{{ $export->code }}</td>
                        <td>{{ $export->created_at->format('d/m/Y') }}</td>
                        <td>{{ $export->product?->name ?? 'Sản phẩm đã bị xóa' }}</td>
                        <td>{{ $export->customer_name }}</td>
                        <td class="text-center">{{ $export->quantity }}</td>
                        <td class="text-end">{{ number_format($export->total, 0, ',', '.') }} đ</td>
                        <td class="text-center">{{ $export->status }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">Chưa có dữ liệu xuất kho.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="fw-bold">
                    <td colspan="5" class="text-end">TỔNG CỘNG:</td>
                    <td class="text-center">{{ $totalQuantity }}</td>
                    <td class="text-end text-success">{{ number_format($totalAmount, 0, ',', '.') }} đ</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <div class="row mt-5 text-center">
            <div class="col-6">
                <p class="fw-bold mb-5">Người lập bảng</p>
                <p>(Ký, họ tên)</p>
            </div>
            <div class="col-6">
                <p class="fw-bold mb-5">Giám đốc / Quản lý</p>
                <p>(Ký, họ tên)</p>
            </div>
        </div>
    </div>

    <!-- Tự động mở hộp thoại in -->
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>
