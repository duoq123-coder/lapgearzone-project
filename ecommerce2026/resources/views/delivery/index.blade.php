@extends('layouts.app')

@section('title', 'Khu vực Nhân viên Giao hàng')

@section('content')
<div class="row g-4">
    <!-- Cột Profile -->
    <div class="col-lg-4">
        <div class="card-premium p-4 h-100 text-center">
            @if($user->avatar)
                <img src="{{ $user->avatar_url }}" alt="Avatar" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover; border: 4px solid var(--accent-gold);">
            @else
                <i class="bi bi-person-circle text-secondary mb-3" style="font-size: 5rem;"></i>
            @endif
            <h4 class="fw-bold text-dark">{{ $user->name }}</h4>
            <span class="badge-premium mb-3 d-inline-block">
                @if($user->role === 'admin')
                    Quản trị viên (Admin)
                @else
                    Nhân viên Giao hàng / Lắp đặt
                @endif
            </span>
            
            <ul class="list-unstyled text-start mt-4">
                <li class="mb-2"><i class="bi bi-envelope text-primary me-2"></i> {{ $user->email }}</li>
                <li class="mb-2"><i class="bi bi-telephone text-success me-2"></i> {{ $user->phone ?? 'Chưa cập nhật' }}</li>
                <li><i class="bi bi-geo-alt text-danger me-2"></i> {{ $user->address ?? 'Chưa cập nhật' }}</li>
            </ul>
        </div>
    </div>

    <!-- Cột Nhiệm vụ -->
    <div class="col-lg-8">
        <div class="card-premium p-4">
            <h4 class="fw-bold mb-4 gold-underline">Nhiệm vụ Giao hàng & Lắp đặt</h4>
            
            @if($currentTasks->isEmpty())
                <div class="alert alert-info border-0 shadow-sm rounded-4 text-center p-4">
                    <i class="bi bi-clipboard-check text-info" style="font-size: 3rem;"></i>
                    <p class="mt-3 mb-0 fw-semibold">Hiện tại bạn không có nhiệm vụ nào cần thực hiện.</p>
                </div>
            @else
                <div class="accordion mb-5" id="ordersAccordion">
                    @foreach($currentTasks as $index => $order)
                        <div class="accordion-item border-0 mb-3 shadow-sm rounded-4 overflow-hidden">
                            <h2 class="accordion-header" id="heading{{ $order->id }}">
                                <button class="accordion-button {{ $index !== 0 ? 'collapsed' : '' }} fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $order->id }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $order->id }}">
                                    Đơn hàng #{{ $order->id }} - {{ $order->name }}
                                    @if($order->delivery_status === 'completed')
                                        <span class="badge bg-success ms-3">Đã hoàn thành</span>
                                    @elseif($order->delivery_status === 'assigned')
                                        <span class="badge bg-warning text-dark ms-3">Đang chờ giao</span>
                                    @endif
                                </button>
                            </h2>
                            <div id="collapse{{ $order->id }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="heading{{ $order->id }}" data-bs-parent="#ordersAccordion">
                                <div class="accordion-body bg-light">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Khách hàng:</strong> {{ $order->name }}</p>
                                            <p><strong>SĐT:</strong> <a href="tel:{{ $order->phone }}" class="text-decoration-none">{{ $order->phone }}</a></p>
                                            <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>
                                            <p><strong>Thanh toán:</strong> 
                                                @if($order->payment_method == 'vnpay')
                                                    <span class="badge bg-primary">VNPay (Đã TT online)</span>
                                                @else
                                                    <span class="badge bg-success">Thu tiền mặt/CK khi lắp</span>
                                                @endif
                                            </p>
                                            <p><strong>Tổng tiền thu:</strong> <span class="text-danger fw-bold">{{ number_format($order->total_price, 0, ',', '.') }} ₫</span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Sản phẩm:</strong>
                                            <ul class="list-unstyled mt-2">
                                                @foreach($order->items as $item)
                                                    <li class="mb-1">
                                                        <i class="bi bi-check2-circle text-success me-1"></i>
                                                        {{ $item->product->name ?? 'Sản phẩm đã xóa' }} (x{{ $item->quantity }})
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>

                                    <hr>

                                    @if($order->delivery_status === 'issue')
                                        <div class="alert alert-danger border-0 mb-0">
                                            <h6 class="fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Đã báo cáo sự cố!</h6>
                                            <p class="mb-0 mt-1">Chi tiết: {{ $order->delivery_issue }}</p>
                                            <small class="d-block mt-2 opacity-75">Đang chờ Admin xử lý. Bạn chưa thể báo cáo hoàn thành lúc này.</small>
                                        </div>
                                    @elseif($order->delivery_status !== 'completed')
                                        <form action="{{ route('delivery.uploadProof', $order->id) }}" method="POST" enctype="multipart/form-data" class="d-inline-block w-100">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Tải lên minh chứng (Hình ảnh lắp đặt, nhận tiền...)</label>
                                                <input type="file" name="proof_images[]" class="form-control @error('proof_images') is-invalid @enderror @error('proof_images.*') is-invalid @enderror" multiple accept="image/*" required>
                                                @error('proof_images')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                                @error('proof_images.*')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                                <small class="text-muted">Có thể chọn nhiều ảnh cùng lúc (Dung lượng mỗi ảnh < 5MB).</small>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold flex-grow-1">
                                                    <i class="bi bi-cloud-arrow-up"></i> Báo cáo hoàn thành
                                                </button>
                                            </div>
                                        </form>
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-danger rounded-pill px-4 fw-bold w-100" data-bs-toggle="modal" data-bs-target="#modalIssue_{{ $order->id }}">
                                                <i class="bi bi-exclamation-triangle"></i> Báo cáo sự cố
                                            </button>
                                        </div>
                                    @else
                                        <div class="alert alert-success border-0 mb-0">
                                            <i class="bi bi-check-circle-fill me-2"></i> Nhiệm vụ này đã được báo cáo hoàn thành.
                                        </div>
                                        @if($order->delivery_proof)
                                            <div class="mt-3">
                                                <p class="fw-bold mb-2">Ảnh minh chứng đã tải lên:</p>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach($order->delivery_proof as $img)
                                                        <a href="{{ asset($img) }}" target="_blank">
                                                            <img src="{{ asset($img) }}" alt="Proof" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
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
        <div class="card-premium p-4 mt-4">
            <h4 class="fw-bold mb-4 gold-underline">Lịch sử nhiệm vụ</h4>
            
            @if($historyTasks->isEmpty())
                <div class="text-center text-muted py-3">
                    <i class="bi bi-clock-history fs-1"></i>
                    <p class="mt-2">Chưa có lịch sử giao hàng.</p>
                </div>
            @else
                <div class="accordion" id="historyAccordion">
                    @foreach($historyTasks as $index => $order)
                        <div class="accordion-item border-0 mb-3 shadow-sm rounded-4 overflow-hidden">
                            <h2 class="accordion-header" id="headingHistory{{ $order->id }}">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHistory{{ $order->id }}" aria-expanded="false" aria-controls="collapseHistory{{ $order->id }}">
                                    Đơn hàng #{{ $order->id }} - {{ $order->name }}
                                    @if($order->delivery_status === 'completed')
                                        <span class="badge bg-success ms-3">Đã xong (Chờ nộp tiền)</span>
                                    @elseif($order->delivery_status === 'done')
                                        <span class="badge bg-primary ms-3">Hoàn tất</span>
                                    @elseif($order->delivery_status === 'cancelled')
                                        <span class="badge bg-secondary ms-3">Đã hủy</span>
                                    @elseif($order->delivery_status === 'issue')
                                        <span class="badge bg-danger ms-3">Sự cố</span>
                                    @endif
                                </button>
                            </h2>
                            <div id="collapseHistory{{ $order->id }}" class="accordion-collapse collapse" aria-labelledby="headingHistory{{ $order->id }}" data-bs-parent="#historyAccordion">
                                <div class="accordion-body bg-light">
                                    <p><strong>Ngày giao:</strong> {{ $order->updated_at->format('d/m/Y H:i') }}</p>
                                    
                                    @if($order->delivery_status === 'issue')
                                        <div class="alert alert-danger">
                                            <strong>Sự cố đã báo cáo:</strong> {{ $order->delivery_issue }}
                                        </div>
                                    @endif

                                    @if(in_array($order->delivery_status, ['completed', 'done']) && $order->payment_method === 'cod_install')
                                        @if($order->cash_remitted || $order->delivery_status === 'done')
                                            <div class="alert alert-success border-0">
                                                <i class="bi bi-check-circle-fill me-2"></i> Đã nộp đủ tiền mặt về công ty.
                                            </div>
                                        @else
                                            <div class="alert alert-danger border-0 fw-bold">
                                                <i class="bi bi-exclamation-triangle-fill me-2"></i> CHƯA NỘP TIỀN VỀ CÔNG TY
                                                <p class="mb-0 mt-1 fw-normal small">Bạn cần nộp {{ number_format($order->total_price, 0, ',', '.') }} ₫ tiền mặt cho Admin.</p>
                                            </div>
                                        @endif
                                    @endif
                                    
                                    @if($order->delivery_proof)
                                        <div class="mt-3">
                                            <p class="fw-bold mb-2">Ảnh minh chứng:</p>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($order->delivery_proof as $img)
                                                    <a href="{{ asset($img) }}" target="_blank">
                                                        <img src="{{ asset($img) }}" alt="Proof" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
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

@endsection

@section('modals')
<!-- Modals Báo Cáo Sự Cố (Phải để ngoài các div overflow) -->
@foreach($currentTasks as $order)
    @if($order->delivery_status !== 'completed' && $order->delivery_status !== 'issue')
        <div class="modal fade" id="modalIssue_{{ $order->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow">
                    <form action="{{ route('delivery.reportIssue', $order->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header bg-danger text-white border-0">
                            <h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i> Báo Cáo Sự Cố</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Chi tiết sự cố:</label>
                                <textarea name="delivery_issue" class="form-control bg-light" rows="4" placeholder="Nhập mô tả sự cố (Khách không nhận hàng, lỗi sản phẩm, không liên lạc được...)" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Hình ảnh sự cố (Tùy chọn):</label>
                                <input type="file" name="issue_images[]" class="form-control @error('issue_images') is-invalid @enderror @error('issue_images.*') is-invalid @enderror" multiple accept="image/*">
                                @error('issue_images')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                @error('issue_images.*')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Tải lên hình ảnh minh chứng sự cố (nếu có, dung lượng < 5MB).</small>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Gửi báo cáo</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach
@endsection
