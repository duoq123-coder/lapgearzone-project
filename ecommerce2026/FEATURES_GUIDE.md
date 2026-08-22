# Hướng Dẫn Sử Dụng - E-Commerce Nâng Cấp 2026

## 📋 Mục Lục
1. [Tính Năng Mới](#tính-năng-mới)
2. [Hướng Dẫn Cho Khách Hàng](#hướng-dẫn-cho-khách-hàng)
3. [Hướng Dẫn Cho Quản Trị Viên](#hướng-dẫn-cho-quản-trị-viên)
4. [Cấu Trúc Cơ Sở Dữ Liệu](#cấu-trúc-cơ-sở-dữ-liệu)
5. [Các Route Quan Trọng](#các-route-quan-trọng)

---

## 🎉 Tính Năng Mới

### 1. **Quên Mật Khẩu (Password Reset)**
- Khách hàng có thể yêu cầu reset mật khẩu khi quên
- Hệ thống tạo token bảo mật để xác minh danh tính
- Link reset hết hạn sau 24 giờ để bảo mật

#### Các View:
- `auth/forgot-password.blade.php` - Form yêu cầu reset
- `auth/reset-password.blade.php` - Form nhập mật khẩu mới

#### Các Route:
```
GET  /forgot-password           → auth.password.request
POST /forgot-password           → auth.password.email
GET  /reset-password/{token}    → auth.password.reset
POST /reset-password            → auth.password.update
```

---

### 2. **Ảnh Chi Tiết Sản Phẩm (Product Images)**
- Mỗi sản phẩm có thể có nhiều ảnh chi tiết
- Admin có thể upload hàng loạt ảnh
- Khách hàng xem thumbnail và click để xem ảnh lớn
- Thích hợp cho website chuyên nghiệp

#### Models:
- `ProductImage` - Lưu trữ ảnh chi tiết của sản phẩm

#### Database Fields:
```php
- id: bigint
- product_id: foreignId (liên kết đến Product)
- image_path: string (đường dẫn ảnh)
- is_primary: boolean (ảnh chính)
- order: integer (thứ tự hiển thị)
- created_at, updated_at: timestamp
```

#### Tính Năng Admin:
- Upload nhiều ảnh cùng lúc
- Drag & drop để sắp xếp thứ tự
- Đặt ảnh làm ảnh chính
- Xóa ảnh chi tiết từng cái

#### Các Route:
```
GET    /admin/products/{product}/images           → admin.products.images.edit
POST   /admin/products/{product}/images           → admin.products.images.store
DELETE /admin/product-images/{productImage}       → admin.product-images.destroy
PATCH  /admin/product-images/{productImage}/primary → admin.product-images.primary
POST   /admin/products/{product}/images/reorder   → admin.products.images.reorder
```

#### Các View:
- `admin/products/edit-images.blade.php` - Giao diện quản lý ảnh

---

### 3. **Giỏ Hàng (Shopping Cart)**
- Khách hàng thêm sản phẩm vào giỏ
- Xem và quản lý các item trong giỏ
- Cập nhật số lượng hoặc xóa sản phẩm
- Tính tổng tiền tự động
- Chức năng thanh toán (checkout)

#### Models:
- `Cart` - Lưu trữ sản phẩm trong giỏ hàng

#### Database Fields:
```php
- id: bigint
- user_id: foreignId (liên kết đến User)
- product_id: foreignId (liên kết đến Product)
- quantity: integer (số lượng)
- unique constraint: [user_id, product_id]
- created_at, updated_at: timestamp
```

#### Tính Năng:
- Thêm sản phẩm vào giỏ
- Cập nhật số lượng
- Xóa sản phẩm khỏi giỏ
- Xóa toàn bộ giỏ
- Thanh toán (checkout) - trừ tồn kho tự động

#### Các Route:
```
GET    /cart                           → cart.index
POST   /cart/add/{product}             → cart.add
PATCH  /cart/{cart}/update             → cart.update
DELETE /cart/{cart}/remove             → cart.remove
DELETE /cart/clear                     → cart.clear
POST   /cart/checkout                  → cart.checkout
```

#### Các View:
- `cart/index.blade.php` - Trang giỏ hàng

---

## 👥 Hướng Dẫn Cho Khách Hàng

### A. Quên Mật Khẩu

1. **Truy cập trang Đăng Nhập**
   - Click "Đăng Nhập" ở góc trên phải
   - Hoặc truy cập: `http://127.0.0.1:8000/login`

2. **Click "Quên Mật Khẩu?"**
   - Bạn sẽ chuyển đến trang `forgot-password`

3. **Nhập Email**
   - Nhập địa chỉ email đã đăng ký
   - Click "Gửi Link Reset"

4. **Kiểm Tra Log (Phát Triển)**
   - Email thật tế sẽ được gửi trong production
   - Trong dev, check file log hoặc database
   - Token được tạo và lưu trong `password_reset_tokens`

5. **Click Link Reset**
   - Truy cập link `http://127.0.0.1:8000/reset-password/{token}`
   - Nhập email, mật khẩu mới, xác nhận
   - Click "Đặt Lại Mật Khẩu"

6. **Đăng Nhập Lại**
   - Bạn sẽ được chuyển về login
   - Đăng nhập với mật khẩu mới

### B. Xem Chi Tiết Sản Phẩm

1. **Truy Cập Danh Sách Sản Phẩm**
   - Click "Sản Phẩm" ở navbar
   - Hoặc: `http://127.0.0.1:8000/products`

2. **Click Vào Sản Phẩm**
   - Truy cập: `http://127.0.0.1:8000/products/{id}`
   - Ví dụ: `http://127.0.0.1:8000/products/2`

3. **Xem Ảnh Chi Tiết**
   - Ảnh lớn hiển thị ở phía trái
   - Thumbnail ở phía dưới
   - Click thumbnail để xem ảnh khác

4. **Thông Tin Sản Phẩm**
   - Giá bán, trạng thái tồn kho
   - Mô tả chi tiết
   - Thông tin thêm (vận chuyển, bảo hành, đổi trả)

5. **Sản Phẩm Liên Quan**
   - Xem các sản phẩm tương tự ở dưới
   - Click để xem chi tiết sản phẩm khác

### C. Giỏ Hàng

1. **Thêm Vào Giỏ**
   - Chọn số lượng
   - Click "Thêm Vào Giỏ"
   - Thông báo thành công

2. **Xem Giỏ Hàng**
   - Click icon giỏ hàng trên navbar
   - Badge hiển thị số lượng item
   - Hoặc: `http://127.0.0.1:8000/cart`

3. **Quản Lý Giỏ**
   - Cập nhật số lượng: nhập số mới, tự động lưu
   - Xóa sản phẩm: click nút Xóa
   - Xóa toàn bộ: click "Xóa Tất Cả"

4. **Thanh Toán**
   - Xem tóm tắt đơn hàng (tiền hàng, phí, tổng)
   - Click "Tiến Hành Thanh Toán"
   - Thanh toán thành công, giỏ được xóa

---

## 🔐 Hướng Dẫn Cho Quản Trị Viên

### A. Quản Lý Ảnh Chi Tiết Sản Phẩm

1. **Truy Cập Giao Diện Admin**
   - Đăng nhập với tài khoản admin
   - Click "Quản Trị" ở navbar

2. **Quản Lý Sản Phẩm**
   - Click "Quản Lý Sản Phẩm"
   - Bạn sẽ thấy danh sách sản phẩm

3. **Upload Ảnh Chi Tiết**
   - Chọn sản phẩm, click nút hình ảnh (ở cột Hành Động)
   - Hoặc: Truy cập `/admin/products/{id}/images`
   - Chọn 1 hoặc nhiều ảnh
   - Click "Tải Lên"

4. **Quản Lý Ảnh**
   - Xem tất cả ảnh chi tiết
   - **Drag & Drop** để sắp xếp thứ tự
   - Click **sao vàng** để đặt làm ảnh chính
   - Click **thùng rác** để xóa ảnh
   - Click "Lưu Thứ Tự Ảnh" để lưu

5. **Các Điều Chú Ý**
   - Tối đa 2MB/ảnh (JPG, PNG, GIF, WebP)
   - Ảnh chính sẽ hiển thị đầu tiên trên trang chi tiết
   - Thứ tự ảnh ảnh hưởng đến UX

### B. Kiểm Tra Giỏ Hàng

1. **Database**
   - Bảng `carts` lưu trữ item
   - Mỗi user có thể có nhiều item
   - Unique constraint (user_id, product_id)

2. **Theo Dõi**
   - Xem log hoặc query trực tiếp database
   - Kiểm tra `users`, `carts`, `products`

3. **Dữ Liệu Checkout**
   - Khi checkout, tồn kho (quantity) tự động giảm
   - Giỏ hàng được xóa sau checkout thành công

---

## 📊 Cấu Trúc Cơ Sở Dữ Liệu

### Bảng `product_images`
```sql
CREATE TABLE `product_images` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `product_id` bigint UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `is_primary` tinyint(1) DEFAULT 0,
  `order` int DEFAULT 0,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
);
```

### Bảng `carts`
```sql
CREATE TABLE `carts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int DEFAULT 1,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  UNIQUE KEY `unique_user_product` (`user_id`, `product_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
);
```

### Bảng `password_reset_tokens`
```sql
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL PRIMARY KEY,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL
);
```

---

## 🛣️ Các Route Quan Trọng

### Authentication Routes
```
GET    /login                        → auth.login
POST   /login                        → auth.login (action)
GET    /register                     → auth.register
POST   /register                     → auth.register (action)
POST   /logout                       → auth.logout
GET    /forgot-password              → auth.password.request
POST   /forgot-password              → auth.password.email
GET    /reset-password/{token}       → auth.password.reset
POST   /reset-password               → auth.password.update
```

### Product Routes (User)
```
GET    /products                     → products.index
GET    /products/{product}           → products.show
GET    /categories                   → categories.index
GET    /categories/{category}        → categories.show
```

### Cart Routes (Protected)
```
GET    /cart                         → cart.index
POST   /cart/add/{product}           → cart.add
PATCH  /cart/{cart}/update           → cart.update
DELETE /cart/{cart}/remove           → cart.remove
DELETE /cart/clear                   → cart.clear
POST   /cart/checkout                → cart.checkout
```

### Admin Routes (Protected + Admin Role)
```
GET    /admin/dashboard                        → admin.dashboard
GET    /admin/products                         → admin.products.index
POST   /admin/products                         → admin.products.store
GET    /admin/products/create                  → admin.products.create
GET    /admin/products/{product}               → admin.products.show
PUT    /admin/products/{product}               → admin.products.update
DELETE /admin/products/{product}               → admin.products.destroy
DELETE /admin/products/{product}/image         → admin.products.image.destroy
GET    /admin/products/{product}/images        → admin.products.images.edit
POST   /admin/products/{product}/images        → admin.products.images.store
DELETE /admin/product-images/{productImage}    → admin.product-images.destroy
PATCH  /admin/product-images/{productImage}/primary → admin.product-images.primary
POST   /admin/products/{product}/images/reorder    → admin.products.images.reorder
```

---

## 💡 Tips & Tricks

### Cho Khách Hàng
1. Khi thêm sản phẩm, bạn chỉ có thể thêm tối đa bằng tồn kho
2. Giỏ hàng được lưu theo user, không bị mất khi thoát
3. Token reset mật khẩu hết hạn sau 24 giờ
4. Vận chuyển miễn phí cho tất cả đơn hàng

### Cho Admin
1. Drag & drop ảnh rất tiện lợi, hỗ trợ Sortable.js
2. Ảnh chính sẽ hiển thị trên trang danh sách sản phẩm
3. Xóa sản phẩm sẽ xóa tất cả ảnh chi tiết (ON CASCADE)
4. Luôn backup ảnh trước khi xóa

---

## 🧪 Testing Checklist

- [ ] Quên mật khẩu - gửi link
- [ ] Reset mật khẩu - tạo mật khẩu mới
- [ ] Upload ảnh chi tiết
- [ ] Drag sắp xếp ảnh
- [ ] Đặt ảnh chính
- [ ] Xóa ảnh
- [ ] Thêm sản phẩm vào giỏ
- [ ] Cập nhật số lượng trong giỏ
- [ ] Xóa sản phẩm khỏi giỏ
- [ ] Checkout và kiểm tra tồn kho

---

**Phiên Bản:** 1.0  
**Ngày Cập Nhật:** 2026-08-13  
**Tác Giả:** GitHub Copilot  
