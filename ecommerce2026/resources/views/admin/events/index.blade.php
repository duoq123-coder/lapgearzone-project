@extends('admin.layouts.app')

@section('title', 'Quản Lý Sự Kiện & Bộ Sưu Tập Sản Phẩm')

@section('content')
<div class="container-fluid py-4 px-md-4">
    <!-- Header Section -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge" style="background: rgba(205, 76, 32, 0.15); color: var(--bellroy-orange); font-family: 'Space Mono', monospace; font-size: 0.75rem;">
                    SYSTEM // CAMPAIGNS & EVENTS
                </span>
                <span class="text-muted small font-monospace">DYNAMIC TABS</span>
            </div>
            <h2 class="h3 fw-bold mb-1" style="font-family: 'Space Grotesk', sans-serif; letter-spacing: -0.02em;">
                <i class="bi bi-calendar-event me-2" style="color: var(--bellroy-orange);"></i>Quản Lý Sự Kiện & Bộ Sưu Tập
            </h2>
            <p class="text-muted small mb-0">
                Tạo và tùy biến các tab hiển thị sản phẩm động trên trang Cửa Hàng (Ví dụ: <em>Top Laptops</em>, <em>Sản phẩm mới của năm</em>, <em>Sản phẩm mùa</em>...).
            </p>
        </div>
        <div class="d-flex gap-2 mt-3 mt-md-0">
            <a href="{{ route('admin.events.create') }}" class="btn btn-premium px-3">
                <i class="bi bi-plus-lg me-1"></i>Tạo Sự Kiện Mới
            </a>
        </div>
    </div>


    <!-- Main Card -->
    <div class="card shadow-sm border">
        <div class="card-header bg-transparent py-3 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 fw-bold d-flex align-items-center gap-2" style="font-size: 1rem;">
                <i class="bi bi-collection-play text-muted"></i>
                Danh Sách Sự Kiện Đang Có ({{ $events->count() }})
            </h5>
            <span class="text-muted small font-monospace">
                Thứ tự tab hiển thị theo số <strong>Thứ Tự (Sort Order)</strong> tăng dần
            </span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 80px;" class="text-center font-monospace">THỨ TỰ</th>
                        <th style="min-width: 220px;">TÊN SỰ KIỆN & TAB PREVIEW</th>
                        <th style="min-width: 240px;">TIÊU ĐỀ NỔI BẬT (HEADLINE)</th>
                        <th style="min-width: 260px;">SẢN PHẨM TRONG SỰ KIỆN</th>
                        <th style="width: 140px;" class="text-center">HIỂN THỊ</th>
                        <th style="width: 160px;" class="text-center">THAO TÁC</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                        @php
                            $isTopLaptops = ($event->slug === 'top-laptops' || \Illuminate\Support\Str::slug($event->name) === 'top-laptops');
                        @endphp
                        <tr>
                            <!-- Sort Order -->
                            <td class="text-center">
                                <span class="badge font-monospace fs-6 px-2 py-1" style="background: var(--surface-muted); color: var(--text-main); border: 1px solid var(--border-color);">
                                    #{{ $event->sort_order }}
                                </span>
                            </td>

                            <!-- Event Name & Tab Preview -->
                            <td>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="fw-bold fs-6 text-dark">
                                        {{ $event->name }}
                                    </span>
                                    @if($event->display_type === 'banner')
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 font-monospace" style="font-size: 0.68rem;">
                                            <i class="bi bi-image me-1"></i>BANNER
                                        </span>
                                    @elseif($event->display_type === 'both')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 font-monospace" style="font-size: 0.68rem;">
                                            <i class="bi bi-layers me-1"></i>KẾT HỢP
                                        </span>
                                    @else
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 font-monospace" style="font-size: 0.68rem;">
                                            <i class="bi bi-collection-play me-1"></i>SLIDER
                                        </span>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <!-- Tab Preview Badge -->
                                    <span class="badge text-uppercase font-monospace px-2 py-1" style="background: #CD4C20; color: #fff; font-size: 0.72rem; letter-spacing: 0.05em; border-radius: 2px;">
                                        TAB: {{ $event->name }}
                                    </span>
                                    <span class="text-muted small font-monospace">slug: {{ $event->slug }}</span>
                                    @if($isTopLaptops)
                                        <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-50 small font-monospace">
                                            <i class="bi bi-stars text-danger me-1"></i>Tự đồng bộ Nổi Bật
                                        </span>
                                    @endif
                                </div>

                                <!-- Banner Preview if exists -->
                                @if($event->banner_image)
                                    <div class="mt-2 d-flex align-items-center gap-2">
                                        <div class="rounded border overflow-hidden position-relative shadow-sm" style="width: 90px; height: 32px; background: #000;">
                                            <img src="{{ $event->banner_url }}" alt="Banner {{ $event->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                        <span class="badge bg-light text-dark border font-monospace" style="font-size: 10px;">
                                            <i class="bi bi-check-circle-fill text-success me-1"></i>Có Banner
                                        </span>
                                    </div>
                                @endif

                                @if($event->description)
                                    <div class="text-muted small mt-1 text-truncate" style="max-width: 320px;" title="{{ $event->description }}">
                                        {{ $event->description }}
                                    </div>
                                @endif
                            </td>

                            <!-- Headline -->
                            <td>
                                <div class="fw-bold" style="font-family: 'Space Grotesk', sans-serif; color: var(--text-main);">
                                    {{ $event->headline ?: '—' }}
                                </div>
                                <div class="text-muted small">
                                    (Tiêu đề lớn xuất hiện trên đầu slider / banner)
                                </div>
                                @if($event->banner_link)
                                    <div class="small text-truncate mt-1" style="max-width: 220px;" title="Link CTA: {{ $event->banner_link }}">
                                        <a href="{{ $event->banner_link }}" target="_blank" class="text-decoration-none font-monospace small" style="color: var(--bellroy-orange); font-size: 11px;">
                                            <i class="bi bi-box-arrow-up-right me-1"></i>{{ $event->banner_link }}
                                        </a>
                                    </div>
                                @endif
                            </td>

                            <!-- Products -->
                            <td>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    @if($event->display_type === 'banner' && $event->products_count === 0)
                                        <span class="badge px-2 py-1 font-monospace bg-info bg-opacity-10 text-info border border-info border-opacity-25">
                                            <i class="bi bi-card-image me-1"></i>Banner độc lập (0 sản phẩm)
                                        </span>
                                    @else
                                        <span class="badge px-2 py-1 font-monospace {{ $event->products_count > 0 ? 'bg-success bg-opacity-10 text-success border border-success border-opacity-25' : 'bg-secondary bg-opacity-10 text-secondary' }}">
                                            <i class="bi bi-laptop me-1"></i>{{ $event->products_count }} sản phẩm
                                        </span>
                                    @endif
                                </div>
                                <!-- Small Thumbnails Stack -->
                                <div class="d-flex align-items-center flex-wrap gap-1">
                                    @forelse($event->products->take(6) as $p)
                                        <div class="position-relative" title="{{ $p->name }} - {{ number_format($p->price, 0, ',', '.') }}đ">
                                            @if($p->image)
                                                <img src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->name }}" class="rounded border" style="width: 34px; height: 34px; object-fit: cover; background: #fff;">
                                            @else
                                                <div class="rounded border d-flex align-items-center justify-content-center bg-light text-muted" style="width: 34px; height: 34px; font-size: 14px;">
                                                    <i class="bi bi-laptop"></i>
                                                </div>
                                            @endif
                                        </div>
                                    @empty
                                        @if($event->display_type !== 'banner')
                                            <span class="text-muted small fst-italic">Chưa có sản phẩm nào</span>
                                        @endif
                                    @endforelse
                                    @if($event->products_count > 6)
                                        <span class="badge bg-light text-dark border small">+{{ $event->products_count - 6 }}</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Active Switch -->
                            <td class="text-center">
                                @if($isTopLaptops)
                                    <span class="badge bg-success px-2 py-1 font-monospace" title="Mục cố định luôn luôn bật để chiếu sản phẩm nổi bật">
                                        <i class="bi bi-check-circle-fill me-1"></i>CỐ ĐỊNH
                                    </span>
                                @else
                                    <form action="{{ route('admin.events.toggleActive', $event) }}" method="POST" class="d-inline toggle-event-form">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm px-2 py-1 border-0 shadow-none" title="Bấm để {{ $event->is_active ? 'Tắt' : 'Bật' }}">
                                            @if($event->is_active)
                                                <span class="badge bg-success px-2 py-1 d-inline-flex align-items-center gap-1 font-monospace">
                                                    <i class="bi bi-check-circle-fill"></i> ĐANG BẬT
                                                </span>
                                            @else
                                                <span class="badge bg-secondary px-2 py-1 d-inline-flex align-items-center gap-1 font-monospace">
                                                    <i class="bi bi-dash-circle"></i> ĐÃ TẮT
                                                </span>
                                            @endif
                                        </button>
                                    </form>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="text-center">
                                @if($isTopLaptops)
                                    <span class="badge py-2 px-3 text-wrap font-monospace" style="background: rgba(245, 158, 11, 0.12); color: #b45309; border: 1px dashed rgba(245, 158, 11, 0.4); font-size: 0.75rem; line-height: 1.3;" title="Sự kiện này cố định của hệ thống, không sửa/xóa thủ công để tự đồng bộ qua Sản phẩm Nổi Bật">
                                        <i class="bi bi-lock-fill me-1"></i>Không sửa được<br><small class="text-muted fw-normal">(Tự đồng bộ Nổi bật)</small>
                                    </span>
                                @else
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-outline-premium btn-sm" title="Chỉnh sửa chi tiết & danh sách sản phẩm">
                                            <i class="bi bi-pencil me-1"></i>Sửa
                                        </a>
                                        <form action="{{ route('admin.events.destroy', $event) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sự kiện \'{{ $event->name }}\'?\nHành động này không thể hoàn tác!')" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa sự kiện">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="mb-3">
                                    <i class="bi bi-calendar-x display-4 opacity-50"></i>
                                </div>
                                <h5 class="fw-bold">Chưa có sự kiện nào được tạo</h5>
                                <p class="small text-muted mb-3">Tạo sự kiện đầu tiên để hiển thị các tab sản phẩm linh hoạt ngoài cửa hàng!</p>
                                <a href="{{ route('admin.events.create') }}" class="btn btn-premium btn-sm">
                                    <i class="bi bi-plus-lg me-1"></i>Tạo Sự Kiện Mới
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
