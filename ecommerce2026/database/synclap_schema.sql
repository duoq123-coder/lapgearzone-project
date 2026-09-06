-- ====================================================================
-- SYNCLAP LAPTOP E-COMMERCE DATABASE SCHEMA (MySQL 8.0+)
-- Complete schema with full relationships, indexes, constraints & seed data
-- ====================================================================

CREATE DATABASE IF NOT EXISTS `synclap_db` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `synclap_db`;

-- --------------------------------------------------------------------
-- 1. Table: users
-- Quản lý người dùng (Buyer, Seller, Admin)
-- --------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('ADMIN', 'BUYER', 'SELLER') NOT NULL DEFAULT 'BUYER',
    `avatar` VARCHAR(500) DEFAULT NULL,
    `address` TEXT DEFAULT NULL,
    `phone` VARCHAR(20) DEFAULT NULL,
    `is_active` BOOLEAN NOT NULL DEFAULT TRUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_users_role` (`role`),
    INDEX `idx_users_email` (`email`)
) ENGINE=InnoDB;

-- --------------------------------------------------------------------
-- 2. Table: brands
-- Hãng laptop (Apple, Asus, Dell, Lenovo, MSI, HP, Acer, Gigabyte...)
-- --------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `brands` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL UNIQUE,
    `slug` VARCHAR(120) NOT NULL UNIQUE,
    `logo_url` VARCHAR(500) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- --------------------------------------------------------------------
-- 3. Table: categories
-- Phân khúc laptop (Gaming, Ultrabook, AI Laptop, Đồ họa - Kỹ thuật, Văn phòng...)
-- --------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categories` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL UNIQUE,
    `slug` VARCHAR(120) NOT NULL UNIQUE,
    `description` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- --------------------------------------------------------------------
-- 4. Table: products
-- Quản lý Laptop tổng thể (Landing Page & Catalog)
-- --------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `products` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `seller_id` BIGINT UNSIGNED NOT NULL,
    `brand_id` BIGINT UNSIGNED NOT NULL,
    `category_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `description` LONGTEXT DEFAULT NULL,
    `base_price` DECIMAL(15, 2) NOT NULL,
    `discount_price` DECIMAL(15, 2) DEFAULT NULL,
    `is_deal` BOOLEAN NOT NULL DEFAULT FALSE,
    `is_featured` BOOLEAN NOT NULL DEFAULT FALSE,
    `stock_quantity` INT NOT NULL DEFAULT 0,
    `rating` DECIMAL(3, 2) NOT NULL DEFAULT 0.00,
    `review_count` INT NOT NULL DEFAULT 0,
    `thumbnail` VARCHAR(500) DEFAULT NULL,
    `is_active` BOOLEAN NOT NULL DEFAULT TRUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    CONSTRAINT `fk_products_seller` FOREIGN KEY (`seller_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_products_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands`(`id`) ON DELETE RESTRICT,
    CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE RESTRICT,
    
    INDEX `idx_products_filter` (`brand_id`, `category_id`, `base_price`),
    INDEX `idx_products_deals` (`is_deal`, `discount_price`),
    INDEX `idx_products_featured` (`is_featured`),
    FULLTEXT INDEX `idx_products_name_search` (`name`)
) ENGINE=InnoDB;

-- --------------------------------------------------------------------
-- 5. Table: product_images
-- Hình ảnh thực tế chi tiết của laptop
-- --------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `product_images` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_id` BIGINT UNSIGNED NOT NULL,
    `image_url` VARCHAR(500) NOT NULL,
    `is_primary` BOOLEAN NOT NULL DEFAULT FALSE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_product_images_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- --------------------------------------------------------------------
-- 6. Table: product_variants
-- Cấu hình chi tiết (RAM, Ổ cứng, Màu sắc, CPU, GPU, Giá thêm)
-- --------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `product_variants` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_id` BIGINT UNSIGNED NOT NULL,
    `sku` VARCHAR(100) NOT NULL UNIQUE,
    `color` VARCHAR(50) NOT NULL,
    `ram` VARCHAR(50) NOT NULL,              -- e.g. '16GB DDR5', '32GB LPDDR5X'
    `storage` VARCHAR(50) NOT NULL,          -- e.g. '512GB NVMe Gen4', '1TB SSD'
    `cpu` VARCHAR(100) DEFAULT NULL,         -- e.g. 'Intel Core i9-14900HX', 'Apple M3 Pro'
    `gpu` VARCHAR(100) DEFAULT NULL,         -- e.g. 'NVIDIA RTX 4070 8GB'
    `additional_price` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `stock_quantity` INT NOT NULL DEFAULT 0,
    `image_url` VARCHAR(500) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    CONSTRAINT `fk_product_variants_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    INDEX `idx_variants_color_ram` (`color`, `ram`),
    INDEX `idx_variants_stock` (`stock_quantity`)
) ENGINE=InnoDB;

-- --------------------------------------------------------------------
-- 7. Table: carts & cart_items
-- Quản lý giỏ hàng người dùng
-- --------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `carts` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL UNIQUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_carts_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `cart_items` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `cart_id` BIGINT UNSIGNED NOT NULL,
    `product_variant_id` BIGINT UNSIGNED NOT NULL,
    `quantity` INT UNSIGNED NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY `unique_cart_variant` (`cart_id`, `product_variant_id`),
    CONSTRAINT `fk_cart_items_cart` FOREIGN KEY (`cart_id`) REFERENCES `carts`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_cart_items_variant` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- --------------------------------------------------------------------
-- 8. Table: orders & order_items
-- Đơn hàng và Snapshot cấu hình laptop tại thời điểm mua
-- --------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `orders` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_code` VARCHAR(50) NOT NULL UNIQUE,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `total_amount` DECIMAL(15, 2) NOT NULL,
    `status` ENUM('PENDING', 'PROCESSING', 'SHIPPING', 'DELIVERED', 'CANCELLED') NOT NULL DEFAULT 'PENDING',
    `payment_method` ENUM('COD', 'VNPAY', 'MOMO', 'BANK_TRANSFER', 'CREDIT_CARD') NOT NULL DEFAULT 'COD',
    `payment_status` ENUM('UNPAID', 'PAID', 'REFUNDED') NOT NULL DEFAULT 'UNPAID',
    `shipping_name` VARCHAR(100) NOT NULL,
    `shipping_phone` VARCHAR(20) NOT NULL,
    `shipping_address` TEXT NOT NULL,
    `note` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE RESTRICT,
    INDEX `idx_orders_user_status` (`user_id`, `status`),
    INDEX `idx_orders_code` (`order_code`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `order_items` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id` BIGINT UNSIGNED NOT NULL,
    `product_variant_id` BIGINT UNSIGNED NOT NULL,
    `product_name` VARCHAR(255) NOT NULL,
    `variant_info` VARCHAR(255) NOT NULL, -- Snapshot: 'Màu Bạc | RAM 16GB DDR5 | 1TB SSD'
    `price` DECIMAL(15, 2) NOT NULL,      -- Giá chốt tại thời điểm mua
    `quantity` INT UNSIGNED NOT NULL,
    `subtotal` DECIMAL(15, 2) NOT NULL,
    
    CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_order_items_variant` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- --------------------------------------------------------------------
-- 9. Table: saved_items (Wishlist)
-- Danh sách laptop yêu thích
-- --------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `saved_items` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `product_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    UNIQUE KEY `unique_user_product_wishlist` (`user_id`, `product_id`),
    CONSTRAINT `fk_saved_items_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_saved_items_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- --------------------------------------------------------------------
-- 10. Table: reviews
-- Đánh giá sản phẩm từ người mua hàng
-- --------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `reviews` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `rating` TINYINT UNSIGNED NOT NULL CHECK (`rating` BETWEEN 1 AND 5),
    `comment` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    CONSTRAINT `fk_reviews_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX `idx_reviews_product` (`product_id`)
) ENGINE=InnoDB;

-- ====================================================================
-- SEED DỮ LIỆU MẪU SẴN SÀNG CHO LANDING PAGE SYNCLAP
-- ====================================================================

-- 1. Users mẫu (Mật khẩu hash BCrypt cho '123456')
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `avatar`, `address`, `phone`) VALUES
(1, 'Admin SyncLap', 'admin@synclap.com', '$2a$12$e8Y5lq0m8F9V7fV9j6jLh.8K1A4uX3V7b1v5Y6Z7Q9e8r2t1y3u4i', 'ADMIN', 'https://images.unsplash.com/photo-1534528741775-53994a69daeb', 'Hà Nội, Việt Nam', '0988888888'),
(2, 'Official Asus Store', 'seller.asus@synclap.com', '$2a$12$e8Y5lq0m8F9V7fV9j6jLh.8K1A4uX3V7b1v5Y6Z7Q9e8r2t1y3u4i', 'SELLER', 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d', 'TP. Hồ Chí Minh', '0977777777'),
(3, 'Nguyễn Văn Buyer', 'buyer@synclap.com', '$2a$12$e8Y5lq0m8F9V7fV9j6jLh.8K1A4uX3V7b1v5Y6Z7Q9e8r2t1y3u4i', 'BUYER', 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde', 'Đà Nẵng', '0966666666')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- 2. Brands
INSERT INTO `brands` (`id`, `name`, `slug`, `logo_url`) VALUES
(1, 'Apple', 'apple', 'https://upload.wikimedia.org/wikipedia/commons/f/fa/Apple_logo_black.svg'),
(2, 'ASUS ROG', 'asus-rog', 'https://upload.wikimedia.org/wikipedia/commons/2/2e/ASUS_Logo.svg'),
(3, 'Dell', 'dell', 'https://upload.wikimedia.org/wikipedia/commons/4/48/Dell_Logo.svg'),
(4, 'Lenovo', 'lenovo', 'https://upload.wikimedia.org/wikipedia/commons/b/b8/Lenovo_logo_2015.svg'),
(5, 'MSI', 'msi', 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/0c/MSI_Logo.svg/512px-MSI_Logo.svg.png')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- 3. Categories
INSERT INTO `categories` (`id`, `name`, `slug`, `description`) VALUES
(1, 'Gaming Laptops', 'gaming-laptops', 'Laptop hiệu năng đỉnh cao cho game thủ và lập trình viên'),
(2, 'Ultrabook & AI Laptops', 'ultrabook-ai', 'Mỏng nhẹ cao cấp, pin trâu và tích hợp NPU AI'),
(3, 'Workstation Đồ Họa', 'workstation', 'Laptop chuyên dụng cho Render 3D, kiến trúc và kỹ thuật')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- 4. Products
INSERT INTO `products` (`id`, `seller_id`, `brand_id`, `category_id`, `name`, `slug`, `description`, `base_price`, `discount_price`, `is_deal`, `is_featured`, `stock_quantity`, `rating`, `review_count`, `thumbnail`) VALUES
(1, 2, 2, 1, 'ASUS ROG Zephyrus G16 (2026) OLED AI', 'asus-rog-zephyrus-g16-2026', 'Laptop gaming mỏng nhẹ nhất thế giới màn hình OLED 240Hz, CPU Intel Core Ultra 9, RTX 4080.', 54990000.00, 49990000.00, 1, 1, 15, 4.90, 48, 'https://images.unsplash.com/photo-1603302576837-37561b2e2302'),
(2, 2, 1, 2, 'MacBook Pro 16 inch M3 Max Space Black', 'macbook-pro-16-m3-max', 'Siêu phẩm đồ họa chuyên nghiệp chip Apple M3 Max 16-Core CPU, 40-Core GPU, màn hình Liquid Retina XDR.', 79990000.00, 75990000.00, 1, 1, 20, 5.00, 112, 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8'),
(3, 2, 3, 2, 'Dell XPS 14 (2026) InfinityEdge', 'dell-xps-14-2026', 'Thiết kế tối giản tương lai với thanh cảm ứng liền mạch, CPU Intel Core Ultra 7.', 42990000.00, NULL, 0, 1, 30, 4.75, 29, 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- 5. Product Variants
INSERT INTO `product_variants` (`id`, `product_id`, `sku`, `color`, `ram`, `storage`, `cpu`, `gpu`, `additional_price`, `stock_quantity`) VALUES
(1, 1, 'ROG-G16-BLK-32-1TB', 'Eclipse Gray', '32GB LPDDR5X', '1TB NVMe PCIe 4.0', 'Intel Core Ultra 9 185H', 'NVIDIA RTX 4080 12GB', 0.00, 10),
(2, 1, 'ROG-G16-WHT-64-2TB', 'Platinum White', '64GB LPDDR5X', '2TB NVMe PCIe 4.0', 'Intel Core Ultra 9 185H', 'NVIDIA RTX 4090 16GB', 12000000.00, 5),
(3, 2, 'MBP16-M3MAX-36-1TB', 'Space Black', '36GB Unified', '1TB SSD', 'Apple M3 Max 14-core', '30-core GPU', 0.00, 12),
(4, 2, 'MBP16-M3MAX-64-2TB', 'Silver', '64GB Unified', '2TB SSD', 'Apple M3 Max 16-core', '40-core GPU', 15000000.00, 8)
ON DUPLICATE KEY UPDATE `sku`=VALUES(`sku`);
