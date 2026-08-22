@extends('admin.layouts.app')
@section('title', 'Hồ sơ nhân viên')

@section('content')
<div class="container-fluid py-4" style="min-height: 80vh;">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header/Breadcrumb -->
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('admin.attendance') }}" class="btn btn-sm btn-outline-secondary rounded-circle me-3" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h4 class="mb-0 fw-bold text-dark display-font">Hồ Sơ Nhân Viên</h4>
            </div>

            <!-- Profile Card -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <!-- Cover Image (Optional decorative) -->
                <div class="bg-primary bg-opacity-10" style="height: 120px;"></div>
                
                <div class="card-body px-5 pb-5 position-relative">
                    <!-- Avatar -->
                    <div class="position-absolute" style="top: -60px; left: 40px;">
                        @if(!empty($staff->avatar) && str_starts_with($staff->avatar, 'uploads/'))
                            <img src="{{ asset($staff->avatar) }}" class="rounded-circle border border-4 border-white object-fit-cover shadow-sm" width="120" height="120" alt="Avatar">
                        @else
                            <div class="rounded-circle border border-4 border-white bg-white d-flex align-items-center justify-content-center shadow-sm" style="width: 120px; height: 120px; font-size: 3rem;">
                                {{ $staff->avatar ?? '👤' }}
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end mb-4">
                        @if($staff->status == 'Đang làm việc')
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-bold d-inline-flex align-items-center">
                                <i class="bi bi-circle-fill small me-2" style="font-size: 8px;"></i> Đang làm việc
                            </span>
                        @else
                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 fw-bold d-inline-flex align-items-center">
                                <i class="bi bi-circle-fill small me-2" style="font-size: 8px;"></i> Không đi làm
                            </span>
                        @endif
                    </div>

                    <!-- Profile Info -->
                    <div class="mt-2">
                        <h2 class="fw-bold text-dark mb-1">{{ $staff->name }}</h2>
                        <p class="text-primary fw-semibold mb-4 fs-5">{{ $staff->role }}</p>

                        <div class="row g-4 mt-2">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 bg-light rounded-3 border border-secondary border-opacity-10">
                                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm me-3" style="width: 48px; height: 48px;">
                                        <i class="bi bi-telephone text-primary fs-5"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block fw-bold mb-1">Số điện thoại</small>
                                        <span class="text-dark fw-semibold fs-6">{{ $staff->phone ?? 'Chưa cập nhật' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 bg-light rounded-3 border border-secondary border-opacity-10">
                                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm me-3" style="width: 48px; height: 48px;">
                                        <i class="bi bi-person-vcard text-info fs-5"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block fw-bold mb-1">CCCD / CMND</small>
                                        <span class="text-dark fw-semibold fs-6">{{ $staff->cccd ?? 'Chưa cập nhật' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 bg-light rounded-3 border border-secondary border-opacity-10">
                                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm me-3" style="width: 48px; height: 48px;">
                                        <i class="bi bi-geo-alt text-danger fs-5"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block fw-bold mb-1">Nơi ở hiện tại</small>
                                        <span class="text-dark fw-semibold fs-6">{{ $staff->address ?? 'Chưa cập nhật' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 bg-light rounded-3 border border-secondary border-opacity-10">
                                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm me-3" style="width: 48px; height: 48px;">
                                        <i class="bi bi-calendar-check text-success fs-5"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block fw-bold mb-1">Ngày tham gia</small>
                                        <span class="text-dark fw-semibold fs-6">{{ $staff->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection
