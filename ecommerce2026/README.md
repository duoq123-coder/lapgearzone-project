# Duong E-Commerce Store 2026

**Một nền tảng bán hàng trực tuyến chuyên nghiệp được xây dựng với Laravel 11 và Bootstrap 5**

![Laravel](https://img.shields.io/badge/Laravel-11.0-red?logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?logo=php)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?logo=bootstrap)
![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-00758F?logo=mysql)

---

## 🎯 Tính Năng Chính

### 👥 Cho Khách Hàng
- ✅ **Đăng ký & Đăng nhập** - Tài khoản người dùng an toàn
- ✅ **Quên Mật Khẩu** - Reset mật khẩu dễ dàng với token bảo mật (24h)
- ✅ **Xem Danh Sách Sản Phẩm** - Phân trang, lọc theo danh mục
- ✅ **Chi Tiết Sản Phẩm** - Xem ảnh chi tiết, mô tả đầy đủ
- ✅ **Ảnh Sản Phẩm** - Gallery ảnh đặc biệt với thumbnail
- ✅ **Giỏ Hàng** - Thêm, sửa, xóa sản phẩm
- ✅ **Thanh Toán** - Checkout nhanh chóng, tồn kho tự động giảm
- ✅ **Responsive Design** - Mobile-first, tương thích tất cả thiết bị

### 🔐 Cho Quản Trị Viên
- ✅ **Dashboard** - Tổng quan các sản phẩm và danh mục
- ✅ **Quản Lý Sản Phẩm** - CRUD đầy đủ cho sản phẩm
- ✅ **Quản Lý Ảnh Chi Tiết** - Upload hàng loạt, drag & drop sắp xếp
- ✅ **Quản Lý Danh Mục** - Tạo, chỉnh sửa, xóa danh mục
- ✅ **Role-Based Access** - Chỉ admin mới truy cập khu quản trị

---

## 📋 Yêu Cầu Hệ Thống

- PHP 8.1+
- Laravel 11
- MySQL 8.0+
- Composer
- Node.js (tùy chọn)

---

## 🚀 Hướng Dẫn Cài Đặt

### 1. Clone Repository
```bash
cd c:\xampp\htdocs\ecommerce2026
```

### 2. Cài Đặt Dependencies
```bash
composer install
npm install && npm run build  # Nếu có assets
```

### 3. Thiết Lập Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Tạo Database
```bash
# Cập nhật .env với thông tin database
DB_DATABASE=ecommerce2026
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Chạy Migrations
```bash
php artisan migrate
php artisan storage:link
```

### 6. Tạo Tài Khoản Test (Tùy Chọn)
```bash
php artisan tinker
>>> User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('password'), 'role' => 'admin']);
>>> User::create(['name' => 'Customer', 'email' => 'customer@example.com', 'password' => bcrypt('password'), 'role' => 'customer']);
```

### 7. Chạy Development Server
```bash
php artisan serve
```

Truy cập: **http://127.0.0.1:8000**

---

## 📁 Cấu Trúc Thư Mục

```
ecommerce2026/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php (Password Reset)
│   │   │   ├── ProductController.php (+ Product Images)
│   │   │   ├── CartController.php (NEW)
│   │   │   └── ...
│   ├── Models/
│   │   ├── Product.php
│   │   ├── ProductImage.php (NEW)
│   │   ├── Cart.php (NEW)
│   │   └── ...
├── database/
│   ├── migrations/
│   │   ├── 2026_08_13_100000_create_product_images_table.php
│   │   ├── 2026_08_13_100001_create_carts_table.php
│   │   └── ...
├── resources/
│   ├── views/
│   │   ├── auth/
│   │   │   ├── forgot-password.blade.php (NEW)
│   │   │   ├── reset-password.blade.php (NEW)
│   │   │   └── ...
│   │   ├── products/
│   │   │   └── show.blade.php (Updated - Gallery)
│   │   ├── cart/
│   │   │   └── index.blade.php (NEW)
│   │   ├── admin/products/
│   │   │   └── edit-images.blade.php (NEW)
│   │   └── ...
├── routes/
│   └── web.php (Updated)
├── FEATURES_GUIDE.md (NEW)
├── SETUP_GUIDE.md (NEW)
└── ...
```

---

## 🎨 Công Nghệ Sử Dụng

| Công Nghệ | Phiên Bản | Mục Đích |
|-----------|---------|---------|
| Laravel | 11.x | Backend Framework |
| Bootstrap | 5.3 | Frontend Framework |
| PHP | 8.1+ | Server Language |
| MySQL | 8.0+ | Database |
| Sortable.js | 1.15.0 | Drag & Drop |

---

## 📚 Tài Liệu & Hướng Dẫn

### Cho Người Dùng
- 📖 [Hướng Dẫn Tính Năng](./FEATURES_GUIDE.md#hướng-dẫn-cho-khách-hàng)
  - Quên mật khẩu
  - Xem chi tiết sản phẩm
  - Quản lý giỏ hàng

### Cho Quản Trị Viên
- 📖 [Hướng Dẫn Admin](./FEATURES_GUIDE.md#hướng-dẫn-cho-quản-trị-viên)
  - Quản lý ảnh sản phẩm
  - Upload đa ảnh
  - Sắp xếp thứ tự ảnh

### Setup & Development
- 📖 [Setup Guide](./SETUP_GUIDE.md)
  - Cài đặt dependencies
  - Tạo tài khoản test
  - Troubleshooting
  - Useful commands

---

## 🔄 Các Routes Quan Trọng

### Xác Thực
```
GET    /login                    → Đăng nhập
POST   /login                    → Xử lý đăng nhập
GET    /register                 → Đăng ký
POST   /register                 → Xử lý đăng ký
POST   /logout                   → Đăng xuất
GET    /forgot-password          → Quên mật khẩu
GET    /reset-password/{token}   → Reset mật khẩu
```

### Sản Phẩm & Danh Mục
```
GET    /products                 → Danh sách sản phẩm
GET    /products/{id}            → Chi tiết sản phẩm
GET    /categories               → Danh sách danh mục
GET    /categories/{id}          → Chi tiết danh mục
```

### Giỏ Hàng
```
GET    /cart                     → Xem giỏ hàng
POST   /cart/add/{id}            → Thêm vào giỏ
PATCH  /cart/{id}/update         → Cập nhật số lượng
DELETE /cart/{id}/remove         → Xóa sản phẩm
POST   /cart/checkout            → Thanh toán
```

### Admin
```
GET    /admin/dashboard                       → Dashboard
GET    /admin/products                        → Danh sách sản phẩm
GET    /admin/products/{id}/images            → Quản lý ảnh
POST   /admin/products/{id}/images            → Upload ảnh
```

---

## 🔐 Bảo Mật

- ✅ **Password Hashing** - Sử dụng bcrypt
- ✅ **CSRF Protection** - Token cho tất cả form
- ✅ **SQL Injection Prevention** - Eloquent ORM
- ✅ **Role-Based Authorization** - Middleware admin
- ✅ **Password Reset Tokens** - Hết hạn 24 giờ
- ✅ **File Upload Validation** - Kiểm tra type & size

---

## 💾 Cơ Sở Dữ Liệu

### Bảng Mới Được Tạo
1. **product_images** - Lưu trữ ảnh chi tiết sản phẩm
2. **carts** - Giỏ hàng của khách hàng
3. **password_reset_tokens** - Token reset mật khẩu

### Các Bảng Hiện Tại
- users
- products
- categories
- migrations
- password_reset_tokens
- sessions
- etc.

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test tests/Feature/CartTest.php

# Show code coverage
php artisan test --coverage
```

### Checklist Testing
- [ ] Đăng ký tài khoản mới
- [ ] Đăng nhập/Đăng xuất
- [ ] Quên mật khẩu (reset link)
- [ ] Xem danh sách sản phẩm
- [ ] Xem chi tiết sản phẩm
- [ ] Xem ảnh chi tiết
- [ ] Thêm vào giỏ hàng
- [ ] Cập nhật số lượng
- [ ] Xóa khỏi giỏ hàng
- [ ] Checkout

---

## 🐛 Troubleshooting

### Ảnh Không Hiển Thị
```bash
php artisan storage:link
```

### Database Error
```bash
php artisan migrate:refresh
```

### Cache Issues
```bash
php artisan cache:clear
php artisan config:clear
```

Xem [SETUP_GUIDE.md](./SETUP_GUIDE.md#troubleshooting) để biết chi tiết.

---

## 🤝 Đóng Góp

Mọi đóng góp đều được hoan nghênh! Vui lòng:
1. Fork repository
2. Tạo branch mới (`git checkout -b feature/AmazingFeature`)
3. Commit thay đổi (`git commit -m 'Add AmazingFeature'`)
4. Push lên branch (`git push origin feature/AmazingFeature`)
5. Mở Pull Request

---

## 📄 Giấy Phép

Dự án này được cấp giấy phép dưới [MIT License](LICENSE).

---

## 📞 Liên Hệ & Hỗ Trợ

- 📧 Email: support@duong-store.com
- 🌐 Website: https://duong-store.com
- 💬 Issues: GitHub Issues
- 📚 Docs: [FEATURES_GUIDE.md](./FEATURES_GUIDE.md)

---

## 🙌 Cảm Ơn

- **Laravel Team** - Excellent framework
- **Bootstrap Team** - Beautiful UI framework
- **GitHub Copilot** - AI-powered development

---

**Phiên Bản:** 1.0.0  
**Cập Nhật Lần Cuối:** 2026-08-13  
**Trạng Thái:** ✅ Production Ready


## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
