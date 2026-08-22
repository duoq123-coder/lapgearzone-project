# Setup Guide - E-Commerce 2026

## 📦 Installation & Setup

### 1. Prerequisites
- PHP 8.1+
- Laravel 11
- MySQL 8.0+
- Composer
- Node.js (for assets - optional)

### 2. Database Setup

Các migrations được tạo tự động. Chỉ cần chạy:

```bash
php artisan migrate
```

Các bảng mới sẽ được tạo:
- `product_images` - Ảnh chi tiết sản phẩm
- `carts` - Giỏ hàng
- `password_reset_tokens` - Token reset mật khẩu (nếu chưa tồn tại)

### 3. Storage Setup

Đảm bảo thư mục storage được cấu hình:

```bash
php artisan storage:link
```

Các ảnh sẽ được lưu tại:
- `storage/app/public/products/` - Ảnh sản phẩm chính
- `storage/app/public/product-details/` - Ảnh chi tiết sản phẩm

### 4. Tài Khoản Test

#### Admin Account
- Email: `admin@example.com`
- Password: `password`
- Role: `admin`

#### Customer Account
- Email: `customer@example.com`
- Password: `password`
- Role: `customer`

Để tạo admin:
```bash
php artisan tinker
>>> User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => Hash::make('password'), 'role' => 'admin']);
>>> User::create(['name' => 'Customer', 'email' => 'customer@example.com', 'password' => Hash::make('password'), 'role' => 'customer']);
```

### 5. Development Server

```bash
php artisan serve
```

Truy cập: `http://127.0.0.1:8000`

### 6. Seeders

Để tạo dữ liệu test, hãy update `DatabaseSeeder.php`:

```php
public function run(): void
{
    // Create admin
    User::factory()->create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => Hash::make('password'),
        'role' => 'admin',
    ]);

    // Create customers
    User::factory(10)->create(['role' => 'customer']);

    // Create categories
    $electronics = Category::create(['name' => 'Điện Tử']);
    $fashion = Category::create(['name' => 'Thời Trang']);

    // Create products
    $products = Product::factory(20)->create();
    
    // Attach random categories
    $products->each(function ($product) use ($electronics, $fashion) {
        $product->update(['category_id' => collect([$electronics, $fashion])->random()->id]);
    });
}
```

Chạy:
```bash
php artisan db:seed
```

---

## 📝 Các File Mới Được Thêm

### Models
- `app/Models/ProductImage.php`
- `app/Models/Cart.php`

### Controllers
- `app/Http/Controllers/CartController.php`
- Updated: `app/Http/Controllers/ProductController.php`
- Updated: `app/Http/Controllers/AuthController.php`

### Migrations
- `database/migrations/2026_08_13_100000_create_product_images_table.php`
- `database/migrations/2026_08_13_100001_create_carts_table.php`

### Views
#### Authentication
- `resources/views/auth/forgot-password.blade.php`
- `resources/views/auth/reset-password.blade.php`
- Updated: `resources/views/auth/login.blade.php`

#### Products
- Updated: `resources/views/products/show.blade.php` (với image gallery)

#### Admin
- `resources/views/admin/products/edit-images.blade.php`
- Updated: `resources/views/admin/products/edit.blade.php`
- Updated: `resources/views/admin/products/index.blade.php`

#### Cart
- `resources/views/cart/index.blade.php`

### Routes
- Updated: `routes/web.php`

### Layout
- Updated: `resources/views/layouts/app.blade.php` (với cart icon)

---

## 🐛 Troubleshooting

### Problem: "Table password_reset_tokens already exists"
**Solution:** Xóa migration file `2026_08_13_100002_create_password_reset_tokens_table.php` vì table đã tồn tại sẵn.

### Problem: Ảnh không hiển thị
**Solution:** 
1. Chạy `php artisan storage:link`
2. Kiểm tra đường dẫn file có tồn tại
3. Kiểm tra permissions của thư mục storage

### Problem: Cart không lưu được
**Solution:**
1. Kiểm tra user đã đăng nhập
2. Kiểm tra constraint unique (user_id, product_id)
3. Xem error log tại `storage/logs/`

### Problem: Password reset không gửi email
**Solution:**
1. Config MAIL_DRIVER trong `.env`
2. Xem log file tại `storage/logs/`
3. Trong development, check database `password_reset_tokens`

---

## 📚 Useful Commands

```bash
# Run migrations
php artisan migrate

# Create tables for specific migration
php artisan migrate:refresh --seed

# Tinker shell
php artisan tinker

# Clear all cache
php artisan cache:clear
php artisan config:clear

# View routes
php artisan route:list

# Run tests
php artisan test

# Seed database
php artisan db:seed
```

---

## 🔒 Security Notes

1. **Password Reset Token**
   - Token được hash với SHA256
   - Hết hạn sau 24 giờ
   - Only work once (được xóa sau dùng)

2. **Shopping Cart**
   - Cart linked to authenticated user
   - User chỉ có thể access cart của mình (middleware auth)
   - Quantity validation against stock

3. **File Upload**
   - Validate file type (image only)
   - Max size: 2MB
   - Store outside web root

4. **CSRF Protection**
   - Tất cả form đều có @csrf token
   - Routes protected by middleware

---

## 📞 Support & Docs

- Laravel Docs: https://laravel.com/docs
- Bootstrap Docs: https://getbootstrap.com/docs
- Project Guide: [FEATURES_GUIDE.md](./FEATURES_GUIDE.md)

---

**Last Updated:** 2026-08-13  
**Version:** 1.0
