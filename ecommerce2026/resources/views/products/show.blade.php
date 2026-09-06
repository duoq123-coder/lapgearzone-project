@extends('layouts.app')
@section('title', $product->name . ' - Chi tiết sản phẩm')
@section('content')
<div class="container py-4 animate-slide-up">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="font-size: 0.88rem;">
            <li class="breadcrumb-item"><a href="{{ route('welcome') }}" class="text-decoration-none text-muted"><i class="bi bi-house me-1"></i>Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index', ['category' => $product->category->id]) }}" class="text-decoration-none text-muted">{{ $product->category?->name ?? 'Danh mục' }}</a></li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>


    <!-- Product Detail Section -->
    <div class="row mb-5 g-4">
        <!-- Product Images -->
        <div class="col-lg-6">
            <div class="card card-premium overflow-hidden">
                <!-- Main Image / 3D Viewer Container -->
                <div class="card-body p-4 d-flex align-items-center justify-content-center position-relative" style="min-height: 420px; background-color: #f8f6f2 !important;">
                    @php
                        // Luôn ưu tiên ảnh chính của sản phẩm (trường image) giống trang danh sách
                        $mainImagePath = $product->image;
                        // Nếu không có ảnh chính, fallback sang ảnh primary trong product_images
                        if (empty($mainImagePath)) {
                            $primaryImg = $product->images()->where('is_primary', true)->first();
                            if (!$primaryImg) {
                                $primaryImg = $product->images()->first();
                            }
                            $mainImagePath = $primaryImg?->image_path;
                        }
                    @endphp

                    <!-- Thẻ ảnh bình thường -->
                    @if (!empty($mainImagePath))
                        <img id="mainImage" 
                            src="{{ asset('storage/'.$mainImagePath) }}" 
                            alt="{{ $product->name }}" 
                            class="img-fluid" 
                            style="max-height: 360px; width: 100%; object-fit: contain; filter: drop-shadow(0 15px 20px rgba(0,0,0,0.1)); {{ $product->model_3d ? 'display: none;' : 'display: block;' }}">
                    @else
                        <!-- Chỉ hiện nếu không có ảnh và không có cả 3D -->
                        @if(!$product->model_3d)
                        <div id="mainImage" class="d-flex align-items-center justify-content-center h-100 bg-white rounded-3 p-5" style="width: 100%;">
                            <div class="text-center text-muted">
                                <i class="bi bi-laptop" style="font-size: 3rem; color: var(--text-secondary);"></i>
                                <p class="mt-2 small">Chưa có ảnh chi tiết</p>
                            </div>
                        </div>
                        @else
                        <!-- Thẻ ẩn để code JS đổi ảnh không bị lỗi -->
                        <img id="mainImage" src="" alt="{{ $product->name }}" class="img-fluid" style="display: none; max-height: 360px; width: 100%; object-fit: contain;">
                        @endif
                    @endif

                    <!-- Thẻ Model 3D -->
                    @if ($product->model_3d)
                        <model-viewer id="main3DViewer"
                            src="{{ asset('storage/'.$product->model_3d) }}" 
                            alt="{{ $product->name }}" 
                            auto-rotate 
                            camera-controls
                            shadow-intensity="1"
                            style="width: 100%; height: 360px; display: block; outline: none;">
                        </model-viewer>
                    @endif
                </div>

                <!-- Thumbnail Images (ảnh chính + ảnh chi tiết + 3D) -->
                @php
                    $allThumbnails = collect();
                    // Thêm ảnh chính (product->image) làm thumbnail đầu tiên
                    if (!empty($product->image)) {
                        $allThumbnails->push((object)['image_path' => $product->image, 'is_main' => true]);
                    }
                    // Thêm tất cả ảnh chi tiết từ product_images
                    foreach ($product->images as $img) {
                        $allThumbnails->push((object)['image_path' => $img->image_path, 'is_main' => false]);
                    }
                @endphp

                @if ($allThumbnails->count() > 1 || $product->model_3d)
                    <div class="card-footer bg-white border-top p-3" style="border-color: var(--border-color) !important;">
                        <div class="row g-2 justify-content-center">
                            
                            <!-- Thumbnail cho 3D -->
                            @if ($product->model_3d)
                                <div class="col-2 col-md-3">
                                    <div class="d-flex flex-column align-items-center justify-content-center cursor-pointer border thumbnail-item thumbnail-active" 
                                        onclick="show3DViewer(this)"
                                        style="height: 70px; width: 100%; background: #f8f6f2; cursor: pointer; transition: all 0.25s ease; border-radius: 2px;">
                                        <i class="bi bi-badge-3d" style="font-size: 1.8rem; line-height: 1; color: var(--bellroy-orange);"></i>
                                        <span class="fw-bold text-dark" style="font-size: 0.65rem; font-family: 'Space Grotesk', sans-serif;">Xem 3D</span>
                                    </div>
                                </div>
                            @endif

                            <!-- Thumbnail Ảnh -->
                            @foreach ($allThumbnails as $idx => $thumb)
                                <div class="col-2 col-md-3">
                                    <img src="{{ asset('storage/'.$thumb->image_path) }}" 
                                        alt="{{ $product->name }}" 
                                        class="img-fluid cursor-pointer border thumbnail-item {{ ($idx === 0 && !$product->model_3d) ? 'thumbnail-active' : '' }}" 
                                        onclick="changeMainImage(this, '{{ asset('storage/'.$thumb->image_path) }}')"
                                        style="height: 70px; width: 100%; object-fit: contain; background: #f8f6f2; cursor: pointer; transition: all 0.25s ease; border-radius: 2px;">
                                </div>
                            @endforeach

                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Product Info (Giữ nguyên không đổi) -->
        <div class="col-lg-6 ps-lg-4">
            <!-- Category & Tech Tags Badges -->
            <div class="mb-3 d-flex flex-wrap align-items-center gap-2">
                <span class="badge badge-terracotta">
                    {{ $product->category?->name ?? 'Sản phẩm công nghệ' }}
                </span>
                @if($product->tags && $product->tags->count() > 0)
                    @foreach($product->tags as $t)
                        <a href="{{ route('welcome', ['tag' => $t->slug]) }}" 
                           class="badge text-decoration-none" 
                           style="background: rgba(205, 76, 32, 0.1); color: #CD4C20; border: 1px solid rgba(205, 76, 32, 0.25); font-size: 0.8rem; font-weight: 600; padding: 5px 10px;"
                           title="Xem các sản phẩm có tag {{ $t->name }}">
                            <i class="bi bi-tag-fill me-1" style="font-size: 11px;"></i>#{{ $t->name }}
                        </a>
                    @endforeach
                @endif
            </div>

            <!-- Product Name -->
            <h1 class="fw-bold mb-3 text-dark" style="font-size: 2.1rem; letter-spacing: -0.4px; line-height: 1.3;">
                {{ $product->name }}
            </h1>

            @php
                $reviewCount = $product->reviews->count();
                $averageRating = $reviewCount > 0 ? round($product->reviews->avg('rating'), 1) : 0;
                $fullStars = floor($averageRating);
                $hasHalfStar = ($averageRating - $fullStars) >= 0.5;
                $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
            @endphp
            <!-- Rating & Reviews -->
            <div class="mb-4">
                <div class="d-flex align-items-center gap-2">
                    <div style="color: #e59819;">
                        @for ($i = 0; $i < $fullStars; $i++)
                            <i class="bi bi-star-fill"></i>
                        @endfor
                        @if ($hasHalfStar)
                            <i class="bi bi-star-half"></i>
                        @endif
                        @for ($i = 0; $i < $emptyStars; $i++)
                            <i class="bi bi-star text-secondary opacity-25"></i>
                        @endfor
                    </div>
                    <small class="text-muted fw-semibold">({{ $reviewCount }} đánh giá từ khách hàng)</small>
                </div>
            </div>

            <!-- Price & Stock Specifications Panel -->
            <div class="card card-premium p-4 mb-4" style="background: #ffffff;">
                <div class="row align-items-center">
                    <div class="col-md-6 border-end pe-md-4" style="border-color: var(--border-color) !important;">
                        <small class="text-secondary fw-semibold d-block mb-1 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Giá niêm yết</small>
                        <div class="fw-bold display-font text-dark" style="font-size: 2rem; color: var(--text-main);">
                            {{ number_format($product->price, 0, ',', '.') }} đ
                        </div>
                    </div>
                    <div class="col-md-6 ps-md-4 mt-3 mt-md-0">
                        <small class="text-secondary fw-semibold d-block mb-1 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Trạng thái kho</small>
                        @if ($product->quantity > 0)
                            <span class="badge badge-sage fs-6 py-2 px-3 fw-bold">
                                <i class="bi bi-check-circle-fill me-1"></i> Còn {{ $product->quantity }} máy sẵn hàng
                            </span>
                        @else
                            <span class="badge badge-terracotta fs-6 py-2 px-3 fw-bold">
                                <i class="bi bi-x-circle-fill me-1"></i> Tạm hết hàng
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <h5 class="fw-bold pb-2 mb-3 d-flex align-items-center gap-2 text-dark" 
                    style="font-size: 1.05rem; border-bottom: 1px solid var(--border-color); font-family: 'Space Grotesk', sans-serif;">
                    <i class="bi bi-info-circle me-1" style="color: var(--bellroy-orange);"></i> 
                    Mô tả sản phẩm
                </h5>
                <p class="text-secondary lh-lg mb-0" style="white-space: pre-line; line-height: 1.8; font-size: 0.92rem; color: var(--text-muted) !important;">
                    {{ $product->description ?? 'Chưa có mô tả chi tiết cho sản phẩm này.' }}
                </p>
            </div>

            <!-- Add to Cart Form -->
            @if ($product->quantity > 0)
                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mb-4">
                    @csrf
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="quantity" class="form-label fw-bold text-dark small" style="font-family: 'Space Grotesk', sans-serif;">Số lượng:</label>
                            <input type="number" 
                                name="quantity" 
                                id="quantity" 
                                min="1" 
                                max="{{ $product->quantity }}" 
                                value="1" 
                                class="form-control form-control-lg text-center" 
                                style="border-radius: 2px; font-weight: 700; font-family: 'Space Mono', monospace; border: 1.5px solid var(--border-color);">
                        </div>
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-premium btn-lg w-100 py-3 justify-content-center">
                                <i class="bi bi-bag-plus me-2"></i> Thêm vào giỏ hàng
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <div class="alert alert-danger border-0 p-3 mb-4 d-flex align-items-center" style="background-color: var(--bellroy-orange-subtle); color: var(--bellroy-orange); border-radius: 2px;" role="alert">
                    <i class="bi bi-exclamation-circle-fill fs-4 me-3"></i>
                    <div>
                        <strong>Sản phẩm tạm thời hết hàng!</strong> Vui lòng liên hệ hotline để nhận thông báo khi có hàng.
                    </div>
                </div>
            @endif

            <!-- Trust Badges -->
            <div class="card card-premium" style="background-color: #faf9f6;">
                <div class="card-body p-3">
                    <div class="row text-center g-2">
                        <div class="col-4">
                            <i class="bi bi-truck fs-5 mb-1 d-block" style="color: var(--bellroy-orange);"></i>
                            <span class="small fw-semibold text-secondary" style="font-size: 0.75rem;">Giao hàng an toàn</span>
                        </div>
                        <div class="col-4 border-start" style="border-color: var(--border-color) !important;">
                            <i class="bi bi-shield-check fs-5 mb-1 d-block" style="color: var(--bellroy-sage);"></i>
                            <span class="small fw-semibold text-secondary" style="font-size: 0.75rem;">Bảo hành 12 - 24T</span>
                        </div>
                        <div class="col-4 border-start" style="border-color: var(--border-color) !important;">
                            <i class="bi bi-arrow-repeat fs-5 mb-1 d-block" style="color: var(--bellroy-amber);"></i>
                            <span class="small fw-semibold text-secondary" style="font-size: 0.75rem;">Đổi mới trong 7 ngày</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="row mb-5 animate-fade-in">
        <div class="col-12">
            <h4 class="serif-title text-dark mb-4 pb-2" style="border-bottom: 1px solid var(--border-color);">
                Đánh Giá &amp; Nhận Xét Của Khách Hàng
            </h4>
        </div>
        
        <div class="col-lg-8">
            <!-- Danh sách Đánh giá -->
            @forelse($product->reviews as $review)
                <div class="card card-premium mb-3">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="d-flex align-items-center justify-content-center fw-bold me-3 shadow-sm" style="width: 38px; height: 38px; background: var(--bellroy-charcoal); color: #ffffff; font-size: 0.9rem; border-radius: 2px; font-family: 'Space Mono', monospace;">
                                {{ substr($review->user->name, 0, 1) }}
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.92rem;">{{ $review->user->name }}</h6>
                                <small class="text-muted" style="font-size: 0.78rem;">{{ $review->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                            <div class="ms-auto" style="color: #e59819;">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star-fill {{ $i <= $review->rating ? '' : 'text-secondary opacity-25' }}" style="font-size: 0.85rem;"></i>
                                @endfor
                            </div>
                        </div>
                        @if($review->comment)
                            <p class="mb-0 mt-3 text-secondary lh-lg" style="font-size: 0.9rem; color: var(--text-muted) !important;">{{ $review->comment }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="alert bg-white border text-center p-5 mb-4" style="border-color: var(--border-color) !important; border-radius: 2px;">
                    <i class="bi bi-chat-left-dots display-4 text-muted opacity-50 mb-3"></i>
                    <p class="text-secondary mb-0">Chưa có đánh giá nào cho sản phẩm này. Hãy mua sắm và chia sẻ trải nghiệm đầu tiên của bạn!</p>
                </div>
            @endforelse
        </div>
        
        <div class="col-lg-4 mt-4 mt-lg-0">
            <!-- Form Đánh giá -->
            <div class="card card-premium p-4 sticky-lg-top" style="top: 90px;">
                <h5 class="fw-bold text-dark mb-3" style="font-size: 1.05rem;"><i class="bi bi-pencil-square me-2" style="color: var(--bellroy-orange);"></i>Gửi nhận xét</h5>
                @auth
                    @php
                        $hasPurchased = \App\Models\Order::where('user_id', auth()->id())
                            ->whereIn('status', ['paid', 'completed', 'done'])
                            ->whereHas('items', function($q) use ($product) {
                                $q->where('product_id', $product->id);
                            })->exists();
                        
                        $hasReviewed = \App\Models\Review::where('user_id', auth()->id())
                            ->where('product_id', $product->id)->exists();
                    @endphp
                    
                    @if($hasPurchased && !$hasReviewed)
                        <form action="{{ route('reviews.store', $product->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label text-secondary small fw-bold">Chất lượng sản phẩm:</label>
                                <select name="rating" class="form-select" required>
                                    <option value="5">⭐⭐⭐⭐⭐ 5 Sao - Tuyệt vời</option>
                                    <option value="4">⭐⭐⭐⭐ 4 Sao - Rất tốt</option>
                                    <option value="3">⭐⭐⭐ 3 Sao - Bình thường</option>
                                    <option value="2">⭐⭐ 2 Sao - Kém</option>
                                    <option value="1">⭐ 1 Sao - Rất tệ</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-secondary small fw-bold">Chi tiết trải nghiệm:</label>
                                <textarea name="comment" class="form-control" rows="4" placeholder="Chia sẻ cảm nhận của bạn về độ bền, tốc độ, màn hình..." style="resize: none;"></textarea>
                            </div>
                            <button type="submit" class="btn btn-premium w-100 fw-bold py-2.5"><i class="bi bi-send-fill me-2"></i>Gửi đánh giá</button>
                        </form>
                    @elseif($hasReviewed)
                        <div class="text-center py-4">
                            <i class="bi bi-check-circle-fill fs-1 d-block mb-2" style="color: var(--bellroy-sage);"></i>
                            <p class="text-secondary mb-0 small">Cảm ơn bạn! Bạn đã gửi đánh giá cho sản phẩm này.</p>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-bag-check fs-1 d-block mb-2 text-muted opacity-50"></i>
                            <p class="text-secondary mb-0 small">Chỉ khách hàng đã đặt mua và thanh toán sản phẩm mới có thể gửi nhận xét.</p>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <p class="text-secondary mb-3 small">Vui lòng đăng nhập để đánh giá sản phẩm này.</p>
                        <a href="{{ route('login') }}" class="btn btn-premium px-4">Đăng nhập ngay</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <!-- Related Products Section -->
    @if ($relatedProducts->isNotEmpty())
        <div class="row mb-5 animate-fade-in">
            <div class="col-12">
                <h4 class="serif-title text-dark mb-4 pb-2" style="border-bottom: 1px solid var(--border-color);">
                    Có Thể Bạn Cũng Thích
                </h4>
            </div>
            @foreach ($relatedProducts as $related)
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="bento-laptop-card h-100 d-flex flex-column">
                        @php
                            $relatedImage = $related->image;
                            if (empty($relatedImage)) {
                                $relatedImage = $related->images()->first()?->image_path;
                            }
                        @endphp
                        
                        <!-- Studio Image Stage -->
                        <div class="bento-img-stage" style="height: 200px;">
                            <!-- Category Badge (Top Left) -->
                            <span class="bento-cat-badge">
                                {{ $related->category?->name ?? 'Công nghệ' }}
                            </span>

                            <!-- Wishlist Button (Top Right) -->
                            <button class="bento-wishlist-btn btn-wishlist" 
                                    data-product-id="{{ $related->id }}" 
                                    onclick="toggleWishlist(event, this, {{ $related->id }})"
                                    title="Yêu thích">
                                <i class="bi {{ in_array($related->id, $wishlistIds ?? []) ? 'bi-heart-fill text-danger' : 'bi-heart text-secondary' }}"></i>
                            </button>

                            <a href="{{ route('products.show', $related->id) }}" class="d-flex align-items-center justify-content-center w-100 h-100 text-decoration-none">
                                @if (!empty($relatedImage))
                                    <img src="{{ asset('storage/'.$relatedImage) }}" 
                                         alt="{{ $related->name }}" 
                                         class="img-fluid" 
                                         style="max-height: 170px; object-fit: contain;">
                                @else
                                    <i class="bi bi-laptop display-1 text-muted opacity-50"></i>
                                @endif
                            </a>

                            @if ($related->quantity <= 0)
                                <div class="position-absolute top-0 start-0 w-100 h-100 bg-white bg-opacity-75 d-flex align-items-center justify-content-center" style="z-index: 3;">
                                    <span class="badge badge-premium px-3 py-1.5 fw-bold">Hết hàng</span>
                                </div>
                            @endif
                        </div>

                        <!-- Product Info -->
                        <div class="d-flex flex-column flex-grow-1">
                            <h4 class="bento-product-title mb-2" title="{{ $related->name }}" style="font-size: 1rem;">
                                <a href="{{ route('products.show', $related->id) }}">
                                    {{ $related->name }}
                                </a>
                            </h4>
                            
                            <!-- Price & Stock -->
                            <div class="bento-price-wrap mb-3">
                                <div class="bento-price" style="font-size: 1.2rem;">
                                    {{ number_format($related->price, 0, ',', '.') }}<span class="currency">đ</span>
                                </div>
                                <div class="bento-stock-tag">
                                    Kho: <strong>{{ $related->quantity }}</strong> máy
                                </div>
                            </div>
                            
                            <div class="bento-actions-wrap mt-auto">
                                <a href="{{ route('products.show', $related->id) }}" class="btn-bento-action flex-grow-1" style="padding: 8px 14px; font-size: 0.8rem;">
                                    <span>Xem Chi Tiết</span>
                                    <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Back Button -->
    <div class="text-center mb-5">
        <a href="{{ route('products.index') }}" class="btn btn-outline-premium px-4 py-2.5">
            <i class="bi bi-arrow-left me-2"></i> Quay lại danh sách sản phẩm
        </a>
    </div>
</div>

<style>
    .thumbnail-active {
        border-color: var(--bellroy-orange) !important;
        border-width: 2px !important;
        box-shadow: 2px 2px 0px rgba(205, 76, 32, 0.4);
    }
</style>

<!-- Import thư viện script cho 3D -->
<script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.4.0/model-viewer.min.js"></script>

<script>
    function changeMainImage(thumbEl, imageUrl) {
        // Hiện ảnh, ẩn 3D
        let imgEl = document.getElementById('mainImage');
        if (imgEl) {
            imgEl.src = imageUrl;
            imgEl.style.display = 'block';
        }
        
        let viewer3d = document.getElementById('main3DViewer');
        if (viewer3d) {
            viewer3d.style.display = 'none';
        }
        
        // Bỏ highlight tất cả thumbnail
        document.querySelectorAll('.thumbnail-item').forEach(function(el) {
            el.classList.remove('thumbnail-active');
        });
        
        // Highlight thumbnail đang chọn
        thumbEl.classList.add('thumbnail-active');
    }

    function show3DViewer(thumbEl) {
        // Hiện 3D, ẩn ảnh
        let imgEl = document.getElementById('mainImage');
        if (imgEl) {
            imgEl.style.display = 'none';
        }
        
        let viewer3d = document.getElementById('main3DViewer');
        if (viewer3d) {
            viewer3d.style.display = 'block';
        }
        
        // Bỏ highlight tất cả thumbnail
        document.querySelectorAll('.thumbnail-item').forEach(function(el) {
            el.classList.remove('thumbnail-active');
        });
        
        // Highlight thumbnail 3D đang chọn
        thumbEl.classList.add('thumbnail-active');
    }

    // Tự động ghi nhận vào Lịch sử vừa xem (Recently Viewed) ngay khi vào trang chi tiết
    (function() {
        try {
            const currentItem = {
                id: {{ $product->id }},
                name: {!! json_encode($product->name) !!},
                price: {{ (float) $product->price }},
                price_format: '{{ number_format($product->price, 0, ',', '.') }}đ',
                image: '{{ $product->image ? asset("storage/".$product->image) : "" }}',
                category: {!! json_encode($product->category?->name ?? "Laptop") !!},
                url: {!! json_encode(route('products.show', $product)) !!}
            };
            const RECENTLY_VIEWED_KEY = 'laptopking_recently_viewed';
            let list = [];
            try {
                list = JSON.parse(localStorage.getItem(RECENTLY_VIEWED_KEY) || '[]');
                if (!Array.isArray(list)) list = [];
            } catch (err) {
                list = [];
            }
            // Loại bỏ sản phẩm nếu đã có và đưa lên đầu tiên (Top 1)
            list = list.filter(p => p && p.id !== currentItem.id);
            list.unshift(currentItem);
            if (list.length > 6) list = list.slice(0, 6);
            localStorage.setItem(RECENTLY_VIEWED_KEY, JSON.stringify(list));
        } catch (e) {
            console.warn('Cannot save recently viewed:', e);
        }
    })();
</script>
@endsection