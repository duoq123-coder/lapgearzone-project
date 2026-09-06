<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;

// 1. Trang chủ (Dùng chung logic với trang sản phẩm — DRY)
Route::get('/', [ProductController::class, 'userIndex'])->name('welcome');

// --- Policy Routes ---
Route::view('/chinh-sach-bao-hanh', 'policies.warranty')->name('policies.warranty');
Route::view('/chinh-sach-doi-tra', 'policies.return')->name('policies.return');
Route::view('/chinh-sach-van-chuyen', 'policies.shipping')->name('policies.shipping');
Route::view('/bao-mat-thong-tin', 'policies.privacy')->name('policies.privacy');

// 2. Route xác thực dành cho KHÁCH CHƯA ĐĂNG NHẬP (guest)
Route::middleware('guest')->group(function () {
    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);

    // Xác minh OTP khi đăng ký tài khoản mới
    Route::get('register/verify-otp', [AuthController::class, 'showVerifyRegisterOtpForm'])->name('register.verify.otp.form');
    Route::post('register/verify-otp', [AuthController::class, 'verifyRegisterOtp'])->name('register.verify.otp');
    Route::post('register/resend-otp', [AuthController::class, 'resendRegisterOtp'])->name('register.resend.otp');

    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    
    // Password Reset Routes (Gửi mã OTP về email)
    Route::get('forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('forgot-password', [AuthController::class, 'sendResetCodeEmail'])->name('password.email');
    Route::get('verify-reset-code', [AuthController::class, 'showVerifyResetCodeForm'])->name('password.verify.form');
    Route::post('verify-reset-code', [AuthController::class, 'verifyResetCode'])->name('password.verify');
    Route::get('reset-password', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// 3. Đăng xuất (Chỉ dành cho người ĐÃ ĐĂNG NHẬP)
Route::post('logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Bắt buộc đổi mật khẩu
Route::middleware(['auth'])->group(function () {
    Route::get('/force-change-password', [\App\Http\Controllers\ProfileController::class, 'showForceChangePassword'])->name('password.change.force');
    Route::post('/force-change-password', [\App\Http\Controllers\ProfileController::class, 'forceUpdatePassword'])->name('password.update.force');
});

// 4. Route dành cho ADMIN (Yêu cầu đăng nhập + quyền admin)
Route::middleware(['auth', 'admin', 'force_change_password'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [\App\Http\Controllers\AdminController::class, 'profile'])->name('profile');
    Route::get('/contacts', [\App\Http\Controllers\AdminController::class, 'contacts'])->name('contacts');
    Route::post('/contacts/{contact}/reply', [\App\Http\Controllers\AdminController::class, 'replyContact'])->name('contacts.reply');
    
    // Quản lý Settings (QR code & Cổng thanh toán PayOS)
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/confirm-payos-webhook', [\App\Http\Controllers\Admin\SettingController::class, 'confirmPayOSWebhook'])->name('settings.confirmPayOSWebhook');
    
    // Quản lý Banner Video & Trang chủ
    Route::get('/banner', [\App\Http\Controllers\Admin\BannerController::class, 'index'])->name('banner.index');
    Route::post('/banner', [\App\Http\Controllers\Admin\BannerController::class, 'update'])->name('banner.update');
    
    // Quản lý hình ảnh sản phẩm
    Route::delete('products/{product}/image', [ProductController::class, 'destroyImage'])->name('products.image.destroy');
    
    // Quản lý ảnh chi tiết sản phẩm
    Route::get('products/{product}/images', [ProductController::class, 'editProductImages'])->name('products.images.edit');
    Route::post('products/{product}/images', [ProductController::class, 'uploadProductImages'])->name('products.images.store');
    Route::delete('product-images/{productImage}', [ProductController::class, 'destroyProductImage'])->name('product-images.destroy');
    Route::patch('product-images/{productImage}/primary', [ProductController::class, 'setPrimaryImage'])->name('product-images.primary');
    Route::post('products/{product}/images/reorder', [ProductController::class, 'reorderImages'])->name('products.images.reorder');
    
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::post('tags/quick-store', [\App\Http\Controllers\Admin\TagController::class, 'quickStore'])->name('tags.quickStore');
    Route::resource('tags', \App\Http\Controllers\Admin\TagController::class)->except(['create', 'show', 'edit']);

    // Quản lý Sự Kiện & Bộ Sưu Tập Động (Dynamic Events)
    Route::patch('/events/{event}/toggle-active', [\App\Http\Controllers\Admin\EventController::class, 'toggleActive'])->name('events.toggleActive');
    Route::resource('events', \App\Http\Controllers\Admin\EventController::class);

    // Quản lý Nhập / Xuất kho (Dashboard CRUD)
    Route::post('/imports', [\App\Http\Controllers\AdminController::class, 'storeImport'])->name('imports.store');
    Route::put('/imports/{import}', [\App\Http\Controllers\AdminController::class, 'updateImport'])->name('imports.update');
    Route::patch('/imports/confirm-batch/{code}', [\App\Http\Controllers\AdminController::class, 'confirmImportBatch'])->name('imports.confirmBatch');
    Route::delete('/imports/{import}', [\App\Http\Controllers\AdminController::class, 'destroyImport'])->name('imports.destroy');

    Route::post('/exports', [\App\Http\Controllers\AdminController::class, 'storeExport'])->name('exports.store');
    Route::put('/exports/{export}', [\App\Http\Controllers\AdminController::class, 'updateExport'])->name('exports.update');
    Route::delete('/exports/{export}', [\App\Http\Controllers\AdminController::class, 'destroyExport'])->name('exports.destroy');
    Route::patch('/exports/{export}/complete', [\App\Http\Controllers\AdminController::class, 'completeExport'])->name('exports.complete');
    
    // ==========================================
    // Quản lý Voucher (Coupons)
    // ==========================================
    Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class)->except(['create', 'edit', 'show']);

    // ==========================================
    // Thêm Route In hóa đơn xuất kho ở đây
    // ==========================================
    Route::get('/exports/print-all', [\App\Http\Controllers\AdminController::class, 'printAllExports'])->name('exports.printAll');
    Route::get('/exports/{id}/invoice', [\App\Http\Controllers\AdminController::class, 'printInvoice'])->name('exports.invoice');

    // Quản lý Đơn hàng (Dashboard CRUD)
    Route::put('/orders/{order}', [\App\Http\Controllers\AdminController::class, 'updateOrder'])->name('orders.update');
    Route::patch('/orders/{order}/receive-payment', [\App\Http\Controllers\AdminController::class, 'receivePayment'])->name('orders.receivePayment');
    Route::post('/orders/{order}/assign-delivery', [\App\Http\Controllers\AdminController::class, 'assignDelivery'])->name('orders.assignDelivery');
    Route::patch('/orders/{order}/cancel', [\App\Http\Controllers\AdminController::class, 'cancelOrder'])->name('orders.cancel');
    Route::patch('/orders/{order}/resolve-issue', [\App\Http\Controllers\AdminController::class, 'resolveDeliveryIssue'])->name('orders.resolveIssue');
    Route::patch('/orders/{order}/confirm-cod', [\App\Http\Controllers\AdminController::class, 'confirmCodRemittance'])->name('orders.confirmCod');
    
    // ==========================================
    // Quản lý Nhân sự & Chấm công
    // ==========================================
    Route::get('/attendance', [\App\Http\Controllers\AdminController::class, 'attendance'])->name('attendance');
    Route::post('/attendance/toggle', [\App\Http\Controllers\AdminController::class, 'toggleAttendance'])->name('attendance.toggle');
    Route::get('/staff/{id}', [\App\Http\Controllers\AdminController::class, 'showStaffProfile'])->name('staff.profile');
    Route::post('/staff', [\App\Http\Controllers\AdminController::class, 'storeStaff'])->name('staff.store');
    Route::put('/staff/{id}', [\App\Http\Controllers\AdminController::class, 'updateStaff'])->name('staff.update');
    Route::patch('/staff/{id}/status', [\App\Http\Controllers\AdminController::class, 'updateStaffStatus'])->name('staff.updateStatus');
    Route::delete('/staff/{id}', [\App\Http\Controllers\AdminController::class, 'destroyStaff'])->name('staff.destroy');
    
    // Toggle sản phẩm bán chạy lên Banner
    Route::patch('/products/{product}/toggle-featured', [\App\Http\Controllers\AdminController::class, 'toggleFeatured'])->name('products.toggleFeatured');

    // ==========================================
    // Quản lý Tin tức Công nghệ (News CRUD)
    // ==========================================
    Route::resource('news', \App\Http\Controllers\Admin\NewsController::class);
    Route::patch('/news/{news}/toggle-publish', [\App\Http\Controllers\Admin\NewsController::class, 'togglePublish'])->name('news.togglePublish');
});

// 5. Route dành cho Nhân viên Giao hàng
Route::middleware(['auth', 'delivery', 'force_change_password'])->prefix('delivery')->name('delivery.')->group(function () {
    Route::get('/', [\App\Http\Controllers\DeliveryController::class, 'index'])->name('index');
    Route::post('/{orderId}/proof', [\App\Http\Controllers\DeliveryController::class, 'uploadProof'])->name('uploadProof');
    Route::post('/{orderId}/issue', [\App\Http\Controllers\DeliveryController::class, 'reportIssue'])->name('reportIssue');
});

// 6. Route xem Sản phẩm & Liên hệ dành cho tất cả mọi người (Khách vãng lai & Thành viên)
Route::get('/products', [ProductController::class, 'userIndex'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show_normal'])->name('products.show');

// Route Tin tức & Xu hướng công nghệ (Public)
Route::get('/tin-tuc', [\App\Http\Controllers\NewsController::class, 'index'])->name('news.index');
Route::get('/tin-tuc/{slug}', [\App\Http\Controllers\NewsController::class, 'show'])->name('news.show');

// Góp ý của khách hàng
Route::get('/lien-he', function () {
    $user = auth()->user();
    $userContacts = $user ? \App\Models\Contact::where('user_id', $user->id)
        ->orWhere('email', $user->email)
        ->when(!empty($user->phone), function ($query) use ($user) {
            $query->orWhere('phone', $user->phone);
        })
        ->latest()
        ->get() : collect();

    return view('contact', compact('userContacts'));
})->name('contact.index');

Route::post('/lien-he', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'name'    => 'required|string|max:255',
        'phone'   => ['required', 'string', 'regex:/^(0[3|5|7|8|9])+([0-9]{8})$/'],
        'email'   => ['required', 'email:rfc,dns', 'max:255'],
        'message' => 'required|string',
    ], [
        'phone.regex' => 'Số điện thoại không hợp lệ. Vui lòng nhập SĐT Việt Nam (VD: 0345678901).',
        'email.email' => 'Địa chỉ email không hợp lệ.',
    ]);

    $data = $request->only('name', 'phone', 'email', 'message');
    if (auth()->check()) {
        $data['user_id'] = auth()->id();
    }

    \App\Models\Contact::create($data);

    return back()->with('success', 'Cảm ơn bạn! Góp ý của bạn đã được gửi thành công. Bạn có thể theo dõi phản hồi của Admin ngay bên dưới.');
})->name('contact.store');

// 7. Route dành cho NGƯỜI DÙNG BÌNH THƯỜNG đã đăng nhập
Route::middleware(['auth', 'force_change_password'])->group(function () {
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/info', [ProfileController::class, 'updateInfo'])->name('profile.updateInfo');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.updateAvatar');
    Route::post('/profile/banner', [ProfileController::class, 'updateBanner'])->name('profile.updateBanner');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
    Route::post('/profile/password/send-otp', [ProfileController::class, 'sendPasswordOtp'])->name('profile.password.sendOtp');
    Route::post('/profile/password/verify-otp', [ProfileController::class, 'verifyPasswordOtp'])->name('profile.password.verifyOtp');
    Route::post('/profile/password/resend-otp', [ProfileController::class, 'resendPasswordOtp'])->name('profile.password.resendOtp');
    
    // Order Tracking for Customer
    Route::post('/profile/orders/{order}/confirm-received', [ProfileController::class, 'confirmOrderReceived'])->name('profile.confirmOrderReceived');
    
    // Đánh giá sản phẩm
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    
    // Yêu thích sản phẩm
    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    
    // Shopping Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{cart}/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    
    // Áp dụng Coupon
    Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.applyCoupon');
    Route::post('/cart/remove-coupon', [CartController::class, 'removeCoupon'])->name('cart.removeCoupon');
    
    Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/payment/{order}', [\App\Http\Controllers\CheckoutController::class, 'payment'])->name('checkout.payment');
});

// PayOS Routes (Public callback, success, cancel & webhook)
Route::post('/payos/webhook', [\App\Http\Controllers\PayOSController::class, 'webhook'])->name('payos.webhook');
Route::get('/checkout/payos/success', [\App\Http\Controllers\PayOSController::class, 'success'])->name('checkout.payos.success');
Route::get('/checkout/payos/cancel', [\App\Http\Controllers\PayOSController::class, 'cancel'])->name('checkout.payos.cancel');