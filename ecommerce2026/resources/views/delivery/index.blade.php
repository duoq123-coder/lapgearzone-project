@extends('layouts.app')

@section('title', 'Khu vực Nhân viên Giao hàng - Cửa Hàng Công Nghệ')

@push('styles')
<style>
    .delivery-accordion-body {
        background-color: var(--surface-muted) !important;
        color: var(--text-main);
    }

    [data-bs-theme="dark"] .delivery-accordion-body {
        background-color: #1a1a1e !important;
        color: #f0f0f3 !important;
    }

    [data-bs-theme="dark"] .delivery-accordion-body .text-dark {
        color: var(--text-main) !important;
    }

    [data-bs-theme="dark"] .delivery-proof-image {
        background-color: var(--card-bg) !important;
        border-color: var(--border-color) !important;
    }
</style>
@endpush

@section('content')
<div class="container py-4 animate-slide-up">
    <div class="row g-4">
        <!-- Cột Profile -->
        <div class="col-lg-4">
            <!-- Bỏ p-4, thêm overflow-hidden để bo góc banner -->
            <div class="card card-premium text-center overflow-hidden">
                
                <!-- Phần Banner -->
                <div class="position-relative overflow-hidden" style="height: 140px;">
                    <img src="{{ asset('images/banner1.jpg') }}" alt="Profile Banner" class="w-100 h-100 object-fit-cover position-absolute top-0 start-0" style="z-index: 1;">
                </div>

                <!-- Bọc nội dung lại, trả lại padding -->
                <div class="p-4 pt-0">
                    <!-- Thêm margin-top: -55px (vì ảnh 110px) và z-index để avatar nổi lên -->
                    <div class="position-relative d-inline-block mx-auto mb-3" style="margin-top: -55px; z-index: 3;">
                        @if($user->avatar)
                            <!-- Đã thêm background-color: #ffffff đề phòng ảnh trong suốt, thêm class avatar-fire và z-index -->
                            <img src="{{ $user->avatar_url }}" alt="Avatar" class="rounded-circle avatar-fire" style="width: 110px; height: 110px; object-fit: cover; border: 3px solid var(--border-color); background-color: #ffffff; position: relative; z-index: 2;">
                        @else
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center border avatar-fire" style="width: 110px; height: 110px; background: #ffffff; border-color: var(--border-color) !important; position: relative; z-index: 2;">
                                <i class="bi bi-person text-secondary" style="font-size: 3rem;"></i>
                            </div>
                        @endif

                        <!-- Các vệt lửa tản ra xung quanh -->
                        <div class="fire-sparks">
                            <span class="spark-left"></span>
                            <span class="spark-right"></span>
                            <span class="spark-left"></span>
                            <span class="spark-right"></span>
                            <span class="spark-left"></span>
                            <span class="spark-right"></span>
                            <span class="spark-left"></span>
                            <span class="spark-right"></span>
                        </div>
                    </div>
                    <h4 class="serif-title mb-1 text-dark">{{ $user->name }}</h4>
                    <span class="badge badge-terracotta mb-3 d-inline-block">
                        @if($user->role === 'admin')
                            Quản trị viên (Admin)
                        @else
                            Nhân viên Giao hàng
                        @endif
                    </span>
                    
                    <ul class="list-unstyled text-start mt-3 pt-3 border-top" style="border-color: var(--border-color) !important; font-size: 0.88rem;">
                        <li class="mb-2 d-flex align-items-center"><i class="bi bi-envelope me-2" style="color: var(--bellroy-orange);"></i> <span class="text-secondary">{{ $user->email }}</span></li>
                        <li class="mb-2 d-flex align-items-center"><i class="bi bi-telephone me-2" style="color: var(--bellroy-sage);"></i> <span class="text-secondary">{{ $user->phone ?? 'Chưa cập nhật' }}</span></li>
                        <li class="d-flex align-items-start"><i class="bi bi-geo-alt me-2 mt-1" style="color: var(--bellroy-amber);"></i> <span class="text-secondary">{{ $user->address ?? 'Chưa cập nhật' }}</span></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Cột Nhiệm vụ -->
        <div class="col-lg-8">
            <div class="card card-premium p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <span class="small fw-bold text-secondary text-uppercase" style="letter-spacing: 0.8px; font-size: 0.75rem;">DANH SÁCH ĐƠN HÀNG</span>
                        <h4 class="serif-title mb-0 text-dark" style="font-size: 1.4rem;">Nhiệm vụ Giao hàng</h4>
                    </div>
                    <span class="badge badge-terracotta">{{ $currentTasks->count() }} đơn đang xử lý</span>
                </div>
                
                @if($currentTasks->isEmpty())
                    <div class="alert bg-white border text-center p-5 rounded-4" style="border-color: var(--border-color) !important;">
                        <i class="bi bi-clipboard-check text-muted fs-1 mb-2 d-block" style="color: var(--bellroy-sage) !important;"></i>
                        <p class="mt-2 mb-0 fw-semibold text-dark">Hiện tại bạn không có nhiệm vụ nào cần thực hiện.</p>
                        <small class="text-muted">Khi có đơn hàng mới được giao, thông tin sẽ tự động hiển thị tại đây.</small>
                    </div>
                @else
                    <div class="accordion" id="ordersAccordion">
                        @foreach($currentTasks as $index => $order)
                            <div class="accordion-item border mb-3 rounded-3 overflow-hidden" style="border-color: var(--border-color) !important;">
                                <h2 class="accordion-header" id="heading{{ $order->id }}">
                                    <button class="accordion-button {{ $index !== 0 ? 'collapsed' : '' }} fw-bold bg-white text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $order->id }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $order->id }}">
                                        <span class="font-monospace me-2">#{{ $order->id }}</span> - {{ $order->name }}
                                        @if($order->delivery_status === 'completed')
                                            <span class="badge badge-sage ms-3">Đã hoàn thành</span>
                                        @elseif($order->delivery_status === 'assigned')
                                            <span class="badge badge-terracotta ms-3">Đang chờ giao</span>
                                        @elseif($order->delivery_status === 'customer_confirmed')
                                            <span class="badge badge-sage ms-3">Khách đã xác nhận nhận hàng</span>
                                        @endif
                                    </button>
                                </h2>
                                <div id="collapse{{ $order->id }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="heading{{ $order->id }}" data-bs-parent="#ordersAccordion">
                                    <div class="accordion-body delivery-accordion-body">
                                        <div class="row g-3">
                                            <div class="col-md-6" style="font-size: 0.9rem;">
                                                <p class="mb-1"><strong>Khách hàng:</strong> {{ $order->name }}</p>
                                                <p class="mb-1"><strong>SĐT:</strong> <a href="tel:{{ $order->phone }}" class="text-decoration-none" style="color: var(--bellroy-orange);">{{ $order->phone }}</a></p>
                                                <p class="mb-1"><strong>Địa chỉ:</strong> {{ $order->address }}</p>
                                                <p class="mb-1"><strong>Thanh toán:</strong> 
                                                    @if(in_array($order->payment_method, ['payos', 'vnpay', 'banking', 'online']) || $order->status === 'paid' || $order->payment_method !== 'cod_install')
                                                        <span class="badge badge-sage">{{ strtoupper($order->payment_method) }} (Đã TT online)</span>
                                                    @else
                                                        <span class="badge badge-terracotta">Thu tiền mặt/CK khi lắp</span>
                                                    @endif
                                                </p>
                                                @if(in_array($order->payment_method, ['payos', 'vnpay', 'banking', 'online']) || $order->status === 'paid' || $order->payment_method !== 'cod_install')
                                                    <p class="mb-0"><strong>Số tiền cần thu:</strong> <span class="fw-bold text-success"><i class="bi bi-check-circle-fill me-1"></i>0 đ (Đã thanh toán Online)</span></p>
                                                @else
                                                    <p class="mb-0"><strong>Tổng tiền thu:</strong> <span class="fw-bold" style="color: var(--bellroy-orange);">{{ number_format($order->total_price, 0, ',', '.') }} đ</span></p>
                                                @endif
                                            </div>
                                            <div class="col-md-6" style="font-size: 0.9rem;">
                                                <strong>Sản phẩm cần giao:</strong>
                                                <ul class="list-unstyled mt-2 mb-0">
                                                    @foreach($order->items as $item)
                                                        <li class="mb-1.5 d-flex align-items-center">
                                                            <i class="bi bi-check2 me-2" style="color: var(--bellroy-sage);"></i>
                                                            <span>{{ $item->product->name ?? 'Sản phẩm' }} (x{{ $item->quantity }})</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>

                                        <hr style="border-color: var(--border-color);">

                                        @if($order->delivery_status === 'issue')
                                            <div class="alert rounded-3 border-0 mb-0" style="background-color: var(--bellroy-orange-subtle); color: var(--bellroy-orange);">
                                                <h6 class="fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Đã báo cáo sự cố!</h6>
                                                <p class="mb-0 mt-1">Chi tiết: {{ $order->delivery_issue }}</p>
                                                <small class="d-block mt-2 opacity-75">Đang chờ Admin xử lý. Bạn chưa thể báo cáo hoàn thành lúc này.</small>
                                            </div>
                                        @elseif($order->delivery_status === 'assigned')
                                            <div class="alert rounded-3 border-0 mb-3" style="background-color: #fef8ee; color: var(--bellroy-amber);">
                                                <h6 class="fw-bold"><i class="bi bi-hourglass-split me-2"></i>Chờ khách hàng xác nhận</h6>
                                                <p class="mb-0 mt-1 small">Khách hàng cần nhấn nút <strong>"Đã nhận đủ hàng"</strong> trên trang cá nhân của họ trước khi bạn có thể tải lên minh chứng hoàn thành.</p>
                                            </div>
                                            <div>
                                                <button type="button" class="btn btn-outline-dark rounded-pill px-4 fw-bold w-100" data-bs-toggle="modal" data-bs-target="#modalIssue_{{ $order->id }}">
                                                    <i class="bi bi-exclamation-triangle me-1"></i> Báo cáo sự cố
                                                </button>
                                            </div>
                                        @elseif($order->delivery_status === 'customer_confirmed')
                                            <form action="{{ route('delivery.uploadProof', $order->id) }}" method="POST" enctype="multipart/form-data" class="d-inline-block w-100 mb-2">
                                                @csrf
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small text-dark">Tải lên minh chứng (Hình ảnh giao hàng, nhận tiền...)</label>
                                                    <input type="file" name="proof_images[]" class="form-control @error('proof_images') is-invalid @enderror @error('proof_images.*') is-invalid @enderror" multiple accept="image/*" required>
                                                    @error('proof_images')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                    @error('proof_images.*')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted">Có thể chọn nhiều ảnh (Dung lượng mỗi ảnh &lt; 5MB).</small>
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <button type="submit" class="btn btn-premium rounded-pill px-4 fw-bold flex-grow-1">
                                                        <i class="bi bi-cloud-arrow-up me-1"></i> Báo cáo hoàn thành
                                                    </button>
                                                </div>
                                            </form>
                                            <div>
                                                <button type="button" class="btn btn-outline-dark rounded-pill px-4 fw-bold w-100" data-bs-toggle="modal" data-bs-target="#modalIssue_{{ $order->id }}">
                                                    <i class="bi bi-exclamation-triangle me-1"></i> Báo cáo sự cố
                                                </button>
                                            </div>
                                        @else
                                            <div class="alert rounded-3 border-0 mb-0" style="background-color: var(--bellroy-sage-subtle); color: var(--bellroy-sage);">
                                                <i class="bi bi-check-circle-fill me-2"></i> Nhiệm vụ này đã được báo cáo hoàn thành.
                                            </div>
                                            @if($order->delivery_proof)
                                                <div class="mt-3">
                                                    <p class="fw-bold mb-2 small text-dark">Ảnh minh chứng đã tải lên:</p>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        @foreach($order->delivery_proof as $img)
                                                            <a href="{{ asset($img) }}" target="_blank">
                                                            <img src="{{ asset($img) }}" alt="Proof" class="delivery-proof-image rounded border p-1" style="width: 90px; height: 90px; object-fit: cover; background: #fff;">
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Lịch sử giao hàng -->
            <div class="card card-premium p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <span class="small fw-bold text-secondary text-uppercase" style="letter-spacing: 0.8px; font-size: 0.75rem;">LỊCH SỬ</span>
                        <h4 class="serif-title mb-0 text-dark" style="font-size: 1.4rem;">Lịch sử nhiệm vụ đã hoàn thành</h4>
                    </div>
                </div>
                
                @if($historyTasks->isEmpty())
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-clock-history fs-2 text-secondary opacity-50"></i>
                        <p class="mt-2 small">Chưa có lịch sử giao hàng.</p>
                    </div>
                @else
                    <div class="accordion" id="historyAccordion">
                        @foreach($historyTasks as $index => $order)
                            <div class="accordion-item border mb-3 rounded-3 overflow-hidden" style="border-color: var(--border-color) !important;">
                                <h2 class="accordion-header" id="headingHistory{{ $order->id }}">
                                    <button class="accordion-button collapsed fw-bold bg-white text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHistory{{ $order->id }}" aria-expanded="false" aria-controls="collapseHistory{{ $order->id }}">
                                        <span class="font-monospace me-2">#{{ $order->id }}</span> - {{ $order->name }}
                                        @if($order->delivery_status === 'completed')
                                            @if(in_array($order->payment_method, ['payos', 'vnpay', 'banking', 'online']) || $order->status === 'paid' || $order->payment_method !== 'cod_install' || $order->cash_remitted)
                                                <span class="badge badge-premium ms-3">Hoàn tất</span>
                                            @else
                                                <span class="badge badge-sage ms-3">Đã xong (Chờ nộp tiền)</span>
                                            @endif
                                        @elseif($order->delivery_status === 'done')
                                            <span class="badge badge-premium ms-3">Hoàn tất</span>
                                        @elseif($order->delivery_status === 'cancelled')
                                            <span class="badge bg-secondary ms-3">Đã hủy</span>
                                        @elseif($order->delivery_status === 'issue')
                                            <span class="badge badge-terracotta ms-3">Sự cố</span>
                                        @endif
                                    </button>
                                </h2>
                                <div id="collapseHistory{{ $order->id }}" class="accordion-collapse collapse" aria-labelledby="headingHistory{{ $order->id }}" data-bs-parent="#historyAccordion">
                                    <div class="accordion-body delivery-accordion-body" style="font-size: 0.9rem;">
                                        <p class="mb-2"><strong>Ngày hoàn thành:</strong> {{ $order->updated_at->format('d/m/Y H:i') }}</p>
                                        
                                        @if($order->delivery_status === 'issue')
                                            <div class="alert rounded-3 border-0" style="background-color: var(--bellroy-orange-subtle); color: var(--bellroy-orange);">
                                                <strong>Sự cố đã báo cáo:</strong> {{ $order->delivery_issue }}
                                            </div>
                                        @endif

                                        @if(in_array($order->delivery_status, ['completed', 'done']))
                                            @if(in_array($order->payment_method, ['payos', 'vnpay', 'banking', 'online']) || $order->status === 'paid' || $order->payment_method !== 'cod_install')
                                                <div class="alert rounded-3 border-0" style="background-color: var(--bellroy-sage-subtle); color: var(--bellroy-sage);">
                                                    <i class="bi bi-check-circle-fill me-2"></i> Đơn hàng đã thanh toán Online ({{ strtoupper($order->payment_method) }}) - Không thu/nộp tiền mặt.
                                                </div>
                                            @elseif($order->payment_method === 'cod_install')
                                                @if($order->cash_remitted || $order->delivery_status === 'done')
                                                    <div class="alert rounded-3 border-0" style="background-color: var(--bellroy-sage-subtle); color: var(--bellroy-sage);">
                                                        <i class="bi bi-check-circle-fill me-2"></i> Đã nộp đủ tiền mặt về công ty.
                                                    </div>
                                                @else
                                                    <div class="alert rounded-3 border-0 fw-bold" style="background-color: var(--bellroy-orange-subtle); color: var(--bellroy-orange);">
                                                        <i class="bi bi-exclamation-triangle-fill me-2"></i> CHƯA NỘP TIỀN VỀ CÔNG TY
                                                        <p class="mb-0 mt-1 fw-normal small">Bạn cần nộp {{ number_format($order->total_price, 0, ',', '.') }} đ tiền mặt cho Admin.</p>
                                                    </div>
                                                @endif
                                            @endif
                                        @endif
                                        
                                        @if($order->delivery_proof)
                                            <div class="mt-3">
                                                <p class="fw-bold mb-2 small text-dark">Ảnh minh chứng:</p>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach($order->delivery_proof as $img)
                                                        <a href="{{ asset($img) }}" target="_blank">
                                                            <img src="{{ asset($img) }}" alt="Proof" class="delivery-proof-image rounded border p-1" style="width: 80px; height: 80px; object-fit: cover; background: #fff;">
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('modals')
<!-- Modals Báo Cáo Sự Cố -->
@foreach($currentTasks as $order)
    @if($order->delivery_status !== 'completed' && $order->delivery_status !== 'issue')
        <div class="modal fade" id="modalIssue_{{ $order->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content card-premium border-0 shadow-lg">
                    <form action="{{ route('delivery.reportIssue', $order->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header text-white border-0 p-3 px-4" style="background: var(--bellroy-charcoal);">
                            <h5 class="modal-title fw-bold text-white"><i class="bi bi-exclamation-triangle me-2" style="color: var(--bellroy-orange);"></i> Báo Cáo Sự Cố Đơn #{{ $order->id }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark small">Chi tiết sự cố:</label>
                                <textarea name="delivery_issue" class="form-control" rows="4" placeholder="Nhập mô tả sự cố (Khách hẹn lại, lỗi thiết bị, không liên lạc được...)" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark small">Hình ảnh minh chứng (Tùy chọn):</label>
                                <input type="file" name="issue_images[]" class="form-control @error('issue_images') is-invalid @enderror @error('issue_images.*') is-invalid @enderror" multiple accept="image/*">
                                @error('issue_images')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                @error('issue_images.*')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Tải lên hình ảnh minh chứng sự cố (dung lượng &lt; 5MB).</small>
                            </div>
                        </div>
                        <div class="modal-footer border-0 p-3 px-4 bg-light">
                            <button type="button" class="btn btn-outline-premium" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-premium">Gửi báo cáo</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach
@endsection
