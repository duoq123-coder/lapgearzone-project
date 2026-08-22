    @extends('layouts.app')
    @section('title', $product->name)
    @section('content')
    <div class="container py-4 animate-slide-up">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="font-size: 0.9rem;">
                <li class="breadcrumb-item"><a href="{{ route('welcome') }}" class="text-decoration-none text-muted"><i class="bi bi-house me-1"></i>Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index', ['category' => $product->category->id]) }}" class="text-decoration-none text-muted">{{ $product->category?->name ?? 'Danh mục' }}</a></li>
                <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible border-0 shadow-sm rounded-4 p-3 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                <div>
                    <strong>Thành công!</strong> {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Product Detail Section -->
        <div class="row mb-5 g-4">
            <!-- Product Images -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                    <!-- Main Image -->
                    <div class="card-body p-4 bg-light d-flex align-items-center justify-content-center" style="min-height: 420px; background-color: #f8fafc !important;">
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

                        @if (!empty($mainImagePath))
                            <img id="mainImage" 
                                src="{{ asset('storage/'.$mainImagePath) }}" 
                                alt="{{ $product->name }}" 
                                class="img-fluid rounded-3" 
                                style="max-height: 350px; width: 100%; object-fit: contain;">
                        @else
                            <div class="d-flex align-items-center justify-content-center h-100 bg-white rounded">
                                <div class="text-center text-muted">
                                    <i class="bi bi-image" style="font-size: 3rem;"></i>
                                    <p class="mt-2">Chưa có ảnh</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Thumbnail Images (ảnh chính + ảnh chi tiết) -->
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

                    @if ($allThumbnails->count() > 1)
                        <div class="card-footer bg-white border-top-0 p-3">
                            <div class="row g-2 justify-content-center">
                                @foreach ($allThumbnails as $idx => $thumb)
                                    <div class="col-2 col-md-3">
                                        <img src="{{ asset('storage/'.$thumb->image_path) }}" 
                                            alt="{{ $product->name }}" 
                                            class="img-fluid rounded cursor-pointer border {{ $idx === 0 ? 'border-primary border-2' : '' }}" 
                                            onclick="changeMainImage(this, '{{ asset('storage/'.$thumb->image_path) }}')"
                                            style="height: 70px; width: 100%; object-fit: cover; cursor: pointer; transition: all 0.3s;">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-lg-6 ps-lg-5">
                <!-- Category Badge -->
                <div class="mb-3">
                    <span class="badge badge-premium">
                        <i class="bi bi-tag-fill me-1"></i> {{ $product->category?->name ?? 'Chưa phân loại' }}
                    </span>
                </div>

                <!-- Product Name -->
                <h1 class="fw-bold mb-3 display-font" style="font-size: 2.2rem; letter-spacing: -0.5px;">{{ $product->name }}</h1>

                <!-- Rating & Reviews -->
                <div class="mb-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="text-warning">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-half"></i>
                        </div>
                        <small class="text-muted fw-semibold">(45 đánh giá của khách hàng)</small>
                    </div>
                </div>

                <!-- Price & Stock Specifications Panel (Samsung-style specs block) -->
                <div class="card border-0 rounded-4 p-4 mb-4" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(0, 0, 0, 0.08) !important;">
                    <div class="row align-items-center">
                        <div class="col-md-6 border-end border-md-none pe-md-4" style="border-color: rgba(0, 0, 0, 0.08) !important;">
                            <small class="text-secondary fw-semibold d-block mb-1">Giá bán hiện tại</small>
                            <div class="fw-bold display-font" style="font-size: 2rem; background: linear-gradient(135deg, #ffffff 0%, #8e8e93 50%, #007aff 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                                {{ number_format($product->price, 0, ',', '.') }} đ
                            </div>
                        </div>
                        <div class="col-md-6 ps-md-4 mt-3 mt-md-0">
                            <small class="text-secondary fw-semibold d-block mb-1">Tình trạng kho hàng</small>
                            @if ($product->quantity > 0)
                                <span class="badge bg-success bg-opacity-10 text-success fs-6 py-2 px-3 rounded-pill fw-bold">
                                    <i class="bi bi-check-circle-fill me-1"></i> Còn hàng sẵn
                                </span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger fs-6 py-2 px-3 rounded-pill fw-bold">
                                    <i class="bi bi-x-circle-fill me-1"></i> Hết hàng tạm thời
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Stock progress bar -->
                <div class="mb-4">
    <div class="d-flex justify-content-between mb-1 align-items-center">
        <small class="fw-semibold" style="font-size: 1.05rem; color: #ffffffff;">
            Kho sẵn có: <span class="text-dark fw-bold" style="color: #ffffffff;">{{ $product->quantity }} sản phẩm</span>
        </small>
        <small class="text-muted">{{ min(round(($product->quantity / 50) * 100), 100) }}%</small>
    </div>
    <div class="progress rounded-pill" style="height: 6px; background-color: #f8f9fa;">
        <div class="progress-bar rounded-pill" role="progressbar" style="width: {{ min(($product->quantity / 50) * 100, 100) }}%; background: linear-gradient(90deg, #8e8e93 0%, #007aff 100%);" 
             aria-valuenow="{{ $product->quantity }}" aria-valuemin="0" aria-valuemax="50"></div>
    </div>
</div>

                <!-- Description -->
                <div class="mb-4">
   
    <h5 class="fw-bold pb-2 mb-3 d-flex align-items-center gap-2" 
        style="font-size: 1.1rem; border-bottom: 2px solid rgba(255, 255, 255, 1); color: #fffffdff;">
        
        <i class="bi bi-info-circle text-primary"></i> 
        Mô tả sản phẩm
    </h5>
    
    <p class="text-secondary lh-lg mb-0" style="white-space: pre-line; line-height: 1.8; font-size: 0.95rem;">
        {{ $product->description ?? 'Chưa có mô tả chi tiết cho sản phẩm này.' }}
    </p>
</div>

                <!-- Add to Cart Form -->
                @if ($product->quantity > 0)
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label for="quantity" class="form-label fw-bold text-secondary small">Số lượng:</label>
                                <input type="number" 
                                    name="quantity" 
                                    id="quantity" 
                                    min="1" 
                                    max="{{ $product->quantity }}" 
                                    value="1" 
                                    class="form-control form-control-lg border-2 rounded-3 text-center" 
                                    style="border-color: rgba(0, 0, 0, 0.08); background-color: #ffffff; color: #222222; font-weight: 700;">
                            </div>
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-premium btn-lg w-100 py-3 justify-content-center">
                                    <i class="bi bi-cart-plus me-2"></i> Thêm vào giỏ hàng
                                </button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="alert alert-danger border-0 rounded-4 p-3 mb-4 d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-circle-fill text-danger fs-4 me-3"></i>
                        <div>
                            <strong>Sản phẩm tạm thời hết hàng!</strong> Vui lòng liên hệ lại sau.
                        </div>
                    </div>
                @endif

                <!-- Additional Trust Badges -->
                <div class="card border-0 rounded-4" style="background-color: #f8f9fa; border: 1px solid rgba(0, 0, 0, 0.08) !important;">
                    <div class="card-body p-3">
                        <div class="row text-center g-2">
                            <div class="col-4">
                                <i class="bi bi-truck fs-5 text-primary mb-1 d-block"></i>
                                <span class="small fw-semibold text-secondary" style="font-size: 0.75rem;">Giao hàng nhanh</span>
                            </div>
                            <div class="col-4 border-start" style="border-color: rgba(0, 0, 0, 0.08) !important;">
                                <i class="bi bi-shield-check fs-5 text-success mb-1 d-block"></i>
                                <span class="small fw-semibold text-secondary" style="font-size: 0.75rem;">Bảo hành 12 tháng</span>
                            </div>
                            <div class="col-4 border-start" style="border-color: rgba(0, 0, 0, 0.08) !important;">
                                <i class="bi bi-arrow-repeat fs-5 text-warning mb-1 d-block"></i>
                                <span class="small fw-semibold text-secondary" style="font-size: 0.75rem;">Đổi mới 7 ngày</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="row mb-5 animate-fade-in">
            <div class="col-12">
                <h4 class="fw-bold text-dark mb-4 pb-2" style="letter-spacing: -0.5px; border-bottom: 2px solid rgba(142, 197, 252, 0.15);">
                    <i class="bi bi-star-fill text-warning me-2"></i>Đánh giá sản phẩm
                </h4>
            </div>
            
            <div class="col-lg-8">
                <!-- Danh sách Đánh giá -->
                @forelse($product->reviews as $review)
                    <div class="card border-0 shadow-sm rounded-4 mb-3">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-primary text-dark rounded-circle d-flex align-items-center justify-content-center fw-bold me-3 shadow-sm" style="width: 45px; height: 45px;">
                                    {{ substr($review->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark">{{ $review->user->name }}</h6>
                                    <small class="text-muted">{{ $review->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <div class="ms-auto">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star-fill {{ $i <= $review->rating ? 'text-warning' : 'text-secondary opacity-25' }}"></i>
                                    @endfor
                                </div>
                            </div>
                            @if($review->comment)
                                <p class="mb-0 mt-3 text-secondary lh-lg">{{ $review->comment }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="alert bg-light border-0 text-center p-5 rounded-4 mb-4">
                        <i class="bi bi-chat-left-dots display-4 text-muted opacity-50 mb-3"></i>
                        <p class="text-secondary mb-0">Chưa có đánh giá nào. Hãy là người đầu tiên đánh giá sản phẩm này!</p>
                    </div>
                @endforelse
            </div>
            
            <div class="col-lg-4 mt-4 mt-lg-0">
                <!-- Form Đánh giá -->
                <div class="card card-premium p-4 sticky-lg-top" style="top: 90px; border-radius: 16px;">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-pencil-square text-primary me-2"></i>Viết đánh giá</h5>
                    @auth
                        <form action="{{ route('reviews.store', $product->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label text-secondary small fw-bold">Đánh giá của bạn</label>
                                <select name="rating" class="form-select border-0 shadow-none" style="background-color: #ffffff; color: #222222;" required>
                                    <option value="5">⭐⭐⭐⭐⭐ 5 Sao - Tuyệt vời</option>
                                    <option value="4">⭐⭐⭐⭐ 4 Sao - Rất tốt</option>
                                    <option value="3">⭐⭐⭐ 3 Sao - Bình thường</option>
                                    <option value="2">⭐⭐ 2 Sao - Kém</option>
                                    <option value="1">⭐ 1 Sao - Tệ</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-secondary small fw-bold">Nhận xét chi tiết</label>
                                <textarea name="comment" class="form-control border-0 shadow-none" rows="4" placeholder="Chia sẻ cảm nhận của bạn về sản phẩm..." style="background-color: #ffffff; color: #222222; resize: none;"></textarea>
                            </div>
                            <button type="submit" class="btn btn-premium w-100 fw-bold py-2"><i class="bi bi-send-fill me-2"></i>Gửi đánh giá</button>
                        </form>
                    @else
                        <div class="text-center py-4">
                            <p class="text-secondary mb-3 small">Bạn cần đăng nhập để gửi đánh giá cho sản phẩm này.</p>
                            <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4">Đăng nhập ngay</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Related Products Section -->
        @if ($relatedProducts->isNotEmpty())
            <div class="row mb-5 animate-fade-in">
                <div class="col-12">
                    <h4 class="fw-bold text-dark mb-4 pb-2" style="letter-spacing: -0.5px; border-bottom: 2px solid rgba(142, 197, 252, 0.15);">
                        <i class="bi bi-lightning-fill text-warning me-2"></i>Sản phẩm liên quan
                    </h4>
                </div>
                @foreach ($relatedProducts as $related)
                    <div class="col-md-6 col-lg-3 mb-4">
                        <div class="card h-100 card-premium">
                            @php
                                // Ưu tiên ảnh chính (trường image) giống trang danh sách
                                $relatedImage = $related->image;
                                if (empty($relatedImage)) {
                                    $relatedImage = $related->images()->first()?->image_path;
                                }
                            @endphp
                            
                            <!-- Product Image with Zoom -->
                            <div class="card-img-zoom">
                                @if (!empty($relatedImage))
                                    <div class="position-relative" style="height: 180px;">
                                        <img src="{{ asset('storage/'.$relatedImage) }}" 
                                            alt="{{ $related->name }}" 
                                            class="w-100 h-100" 
                                            style="object-fit: cover;">
                                        @if ($related->quantity <= 0)
                                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-white shadow-sm bg-opacity-50 d-flex align-items-center justify-content-center">
                                                <span class="badge bg-danger rounded-pill px-3 py-1.5 fw-bold shadow-sm">Hết hàng</span>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="d-flex align-items-center justify-content-center bg-light position-relative" style="height: 180px;">
                                        <i class="bi bi-image text-muted" style="font-size: 2rem; opacity: 0.3;"></i>
                                        @if ($related->quantity <= 0)
                                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-white shadow-sm bg-opacity-50 d-flex align-items-center justify-content-center">
                                                <span class="badge bg-danger rounded-pill px-3 py-1.5 fw-bold shadow-sm">Hết hàng</span>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div class="card-body d-flex flex-column p-3">
                                <h6 class="card-title fw-bold text-dark text-truncate mb-1" title="{{ $related->name }}">{{ $related->name }}</h6>
                                <small class="text-muted mb-3">{{ $related->category?->name }}</small>
                                
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-danger fw-bold display-font" style="background: linear-gradient(135deg, #a78bfa 0%, #3b82f6 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                                            {{ number_format($related->price, 0, ',', '.') }}đ
                                        </span>
                                        <small class="text-secondary fw-semibold">Còn: {{ $related->quantity }}</small>
                                    </div>
                                    <a href="{{ route('products.show', $related->id) }}" class="btn btn-sm btn-outline-premium w-100 py-2 justify-content-center">
                                        Xem chi tiết
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
            <a href="{{ route('products.index') }}" class="btn btn-outline-premium px-4 py-2.5 btn-lg">
                <i class="bi bi-arrow-left me-2"></i> Quay lại danh sách sản phẩm
            </a>
        </div>
    </div>

    <style>
        .transition-all {
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        }

        #mainImage {
            transition: all 0.3s ease;
        }

        img[onclick] {
            cursor: pointer;
        }

        img[onclick]:hover {
            border-color: #007aff !important;
            box-shadow: 0 0 10px rgba(0, 122, 255, 0.3);
        }

        .thumbnail-active {
            border-color: #007aff !important;
            border-width: 2px !important;
            box-shadow: 0 0 8px rgba(0, 122, 255, 0.3);
        }
    </style>

    <script>
        function changeMainImage(thumbEl, imageUrl) {
            // Đổi ảnh chính
            document.getElementById('mainImage').src = imageUrl;
            
            // Bỏ highlight tất cả thumbnail
            document.querySelectorAll('.card-footer img').forEach(function(img) {
                img.classList.remove('border-primary', 'border-2', 'thumbnail-active');
            });
            
            // Highlight thumbnail đang chọn
            thumbEl.classList.add('border-primary', 'border-2', 'thumbnail-active');
        }
    </script>
    @endsection