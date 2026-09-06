@extends('admin.layouts.app')

@section('title', 'Góp ý & Phản hồi Khách hàng - Cửa Hàng Công Nghệ')

@push('styles')
<style>
    .admin-reply {
        background-color: var(--bellroy-sage-subtle);
        color: var(--bellroy-sage);
        border: 1px solid rgba(78, 121, 105, 0.18);
    }

    .admin-reply__body {
        color: var(--bellroy-charcoal);
    }

    [data-bs-theme="dark"] .table td .admin-reply {
        background-color: #17352d !important;
        color: #b7e6cb !important;
        border-color: #356454 !important;
    }

    [data-bs-theme="dark"] .table td .admin-reply span,
    [data-bs-theme="dark"] .table td .admin-reply__body {
        color: #d8f5e4 !important;
    }

    [data-bs-theme="dark"] .table td .admin-reply .opacity-75 {
        color: #a6d8bb !important;
    }

    [data-bs-theme="light"] .contact-status-answered {
        background-color: #d9f0e3 !important;
        color: #216a48 !important;
        border: 1px solid #a9d9bd;
    }

    [data-bs-theme="light"] .contact-status-pending {
        background-color: #fff0e8 !important;
        color: #a6451f !important;
        border: 1px solid #f1c5b1;
    }

    [data-bs-theme="dark"] .contact-status-answered {
        background-color: #193d2b !important;
        color: #b8f0cc !important;
        border: 1px solid #32684b;
    }

    [data-bs-theme="dark"] .contact-status-pending {
        background-color: #4a281d !important;
        color: #ffcab6 !important;
        border: 1px solid #87503d;
    }
</style>
@endpush

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold mb-1 display-font" style="color: var(--text-main);"><i class="bi bi-chat-dots me-2" style="color: var(--bellroy-orange);"></i>Góp ý từ Khách hàng</h2>
            <p class="small mb-0" style="color: var(--text-secondary);">Quản lý và giải đáp phản hồi đóng góp ý kiến của người tiêu dùng</p>
        </div>
    </div>

    <div class="card overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 18%;">Khách hàng</th>
                            <th style="width: 18%;">Thông tin liên hệ</th>
                            <th style="width: 28%;">Nội dung góp ý</th>
                            <th style="width: 15%;">Trạng thái</th>
                            <th style="width: 11%;">Ngày gửi</th>
                            <th class="text-end pe-4" style="width: 10%;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contacts as $contact)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px; font-size: 0.85rem; background: var(--bellroy-charcoal); color: #ffffff; flex-shrink: 0;">
                                            {{ mb_strtoupper(mb_substr($contact->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold" style="font-size: 0.9rem; color: var(--text-main);">{{ $contact->name }}</div>
                                            <span style="font-size: 0.8rem; color: var(--text-muted);">#{{ $contact->id }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <a href="mailto:{{ $contact->email }}" class="text-decoration-none d-block small" style="color: var(--text-main);">
                                            <i class="bi bi-envelope me-1" style="color: var(--text-muted);"></i>{{ $contact->email }}
                                        </a>
                                        @if($contact->phone)
                                            <a href="tel:{{ $contact->phone }}" class="text-decoration-none small d-block mt-0.5" style="color: var(--bellroy-orange);">
                                                <i class="bi bi-telephone me-1"></i>{{ $contact->phone }}
                                            </a>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="p-2 rounded small" style="background-color: var(--surface-muted); color: var(--text-main); max-height: 90px; overflow-y: auto; border: 1px solid var(--border-color);">
                                        {{ $contact->message }}
                                    </div>
                                    @if($contact->admin_reply)
                                        <div class="admin-reply mt-2 p-2 rounded small">
                                            <div class="fw-bold d-flex align-items-center justify-content-between mb-1">
                                                <span><i class="bi bi-reply-fill me-1"></i>Phản hồi của Admin:</span>
                                                <span class="small opacity-75">{{ $contact->admin_replied_at ? $contact->admin_replied_at->format('d/m/Y H:i') : '' }}</span>
                                            </div>
                                            <div class="admin-reply__body" style="font-size: 0.82rem;">{{ $contact->admin_reply }}</div>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($contact->admin_reply)
                                        <span class="badge contact-status-answered">
                                            <i class="bi bi-check-circle-fill me-1"></i>Đã phản hồi
                                        </span>
                                    @else
                                        <span class="badge contact-status-pending">
                                            <i class="bi bi-clock-history me-1"></i>Chờ phản hồi
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="small" style="color: var(--text-main);">{{ $contact->created_at->format('d/m/Y') }}</span>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $contact->created_at->format('H:i') }}</div>
                                </td>
                                <td class="text-end pe-4">
                                    <button type="button" class="btn btn-sm {{ $contact->admin_reply ? 'btn-outline-premium' : 'btn-premium' }} py-1 px-3" data-bs-toggle="modal" data-bs-target="#replyModal{{ $contact->id }}">
                                        <i class="bi bi-reply-fill me-1"></i>{{ $contact->admin_reply ? 'Sửa' : 'Trả lời' }}
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                                    Chưa có thư góp ý nào từ khách hàng.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($contacts->hasPages())
            <div class="p-3 border-top d-flex justify-content-center" style="border-color: var(--border-color) !important;">
                {{ $contacts->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('modals')
{{-- Modals phản hồi cho từng contact --}}
@foreach($contacts as $contact)
<div class="modal fade" id="replyModal{{ $contact->id }}" tabindex="-1" aria-labelledby="replyModalLabel{{ $contact->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content card-premium border-0 shadow-lg">
            <div class="modal-header text-white border-0 px-4 py-3" style="background: var(--bellroy-charcoal);">
                <h5 class="modal-title fw-bold text-white fs-6" id="replyModalLabel{{ $contact->id }}">
                    <i class="bi bi-reply-fill me-2" style="color: var(--bellroy-orange);"></i>Phản hồi góp ý của {{ $contact->name }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.contacts.reply', $contact->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <!-- Góp ý gốc của khách -->
                    <div class="mb-3 p-3 rounded-3 border" style="background-color: var(--surface-muted); border-color: var(--border-color) !important;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-bold small" style="color: var(--text-main);">{{ $contact->name }} ({{ $contact->email }})</span>
                            <span style="font-size: 0.75rem; color: var(--text-muted);">{{ $contact->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <p class="mb-0 small" style="font-style: italic; color: var(--text-secondary);">
                            "{{ $contact->message }}"
                        </p>
                    </div>

                    <!-- Ô nhập phản hồi của admin -->
                    <div class="mb-3">
                        <label for="admin_reply_{{ $contact->id }}" class="form-label fw-bold small" style="color: var(--text-main);">
                            Nội dung giải đáp của Ban Quản Trị:
                        </label>
                        <textarea class="form-control" id="admin_reply_{{ $contact->id }}" name="admin_reply" rows="4" placeholder="Nhập câu trả lời hoặc cảm ơn khách hàng..." required style="resize: none; background-color: var(--surface-muted); color: var(--text-main); border-color: var(--border-color);">{{ old('admin_reply', $contact->admin_reply) }}</textarea>
                        <small class="text-muted mt-1 d-block">
                            Phản hồi này sẽ hiển thị trực tiếp cho khách hàng trên trang Góp ý.
                        </small>
                    </div>
                </div>
                <div class="modal-footer border-top px-4 pb-3 pt-3" style="border-color: var(--border-color) !important;">
                    <button type="button" class="btn btn-outline-premium" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-premium px-4">
                        <i class="bi bi-send me-1"></i>Lưu phản hồi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection
