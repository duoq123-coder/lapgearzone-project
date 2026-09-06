@extends('layouts.app')
@section('title', 'Chính sách bảo hành - Cửa Hàng Công Nghệ')

@section('content')
<div class="container py-5 animate-slide-up">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card card-premium p-4 p-md-5">
                <span class="badge badge-sage mb-2 d-inline-block" style="width: fit-content;">CAM KẾT CHẤT LƯỢNG</span>
                <h2 class="serif-title mb-4 text-dark" style="font-size: 2rem;">Chính Sách Bảo Hành Thiết Bị</h2>
                
                <div class="content-body" style="line-height: 1.85; font-size: 0.95rem; color: var(--text-muted);">
                    <p class="lead" style="font-size: 1.05rem; color: var(--text-main);">LapGearZone cam kết mang đến những thiết bị công nghệ chuẩn mực nhất. Chúng tôi áp dụng quy trình bảo hành minh bạch, nhanh chóng và tận tâm cho mọi sản phẩm cung cấp.</p>

                    <h5 class="fw-bold mt-4 mb-2 text-dark">1. Phạm vi áp dụng</h5>
                    <p>Áp dụng cho tất cả sản phẩm phần cứng (Laptop cao cấp, PC Workstation, Linh kiện và Phụ kiện công nghệ) mua tại LapGearZone còn thời hạn bảo hành.</p>

                    <h5 class="fw-bold mt-4 mb-2 text-dark">2. Thời hạn bảo hành tiêu chuẩn</h5>
                    <ul class="ps-3">
                        <li class="mb-1"><strong>Laptop &amp; PC:</strong> Bảo hành chính hãng 12 - 24 tháng theo tiêu chuẩn của hãng sản xuất.</li>
                        <li class="mb-1"><strong>Linh phụ kiện:</strong> Bảo hành từ 1 - 12 tháng tùy dòng sản phẩm (được thể hiện chi tiết trên hóa đơn điện tử).</li>
                    </ul>

                    <h5 class="fw-bold mt-4 mb-2 text-dark">3. Điều kiện tiếp nhận bảo hành</h5>
                    <ul class="ps-3">
                        <li class="mb-1">Sản phẩm còn nguyên tem niêm phong bảo hành của LapGearZone hoặc của nhà phân phối.</li>
                        <li class="mb-1">Lỗi kỹ thuật xuất phát từ phần cứng của nhà sản xuất.</li>
                        <li class="mb-1">Số Serial / IMEI / Service Tag trùng khớp với thông tin lưu trữ trên hệ thống đơn hàng.</li>
                    </ul>

                    <h5 class="fw-bold mt-4 mb-2 text-dark">4. Địa chỉ tiếp nhận &amp; Hotline hỗ trợ</h5>
                    <div class="p-4 rounded-3 border mt-3" style="background-color: var(--surface-muted); border-color: var(--border-color) !important;">
                        <h6 class="fw-bold text-dark mb-2">Trung tâm bảo hành &amp; kỹ thuật LapGearZone</h6>
                        <ul class="list-unstyled mb-0 small" style="color: var(--text-muted);">
                            <li class="mb-1.5"><i class="bi bi-geo-alt me-2" style="color: var(--bellroy-orange);"></i><strong>Địa chỉ:</strong> Số 25A Ngõ 261, đường Phú Diễn, phường Phú Diễn, Bắc Từ Liêm, Hà Nội</li>
                            <li class="mb-1.5"><i class="bi bi-telephone me-2" style="color: var(--bellroy-sage);"></i><strong>Hotline Kỹ thuật:</strong> 0346 884 415</li>
                            <li><i class="bi bi-clock me-2" style="color: var(--bellroy-amber);"></i><strong>Giờ làm việc:</strong> 08:00 - 21:00 (Tất cả các ngày trong tuần)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
