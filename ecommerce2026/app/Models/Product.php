<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Khai báo các cột được phép thêm dữ liệu (Mass Assignment)
    // Đã loại bỏ 'quantity' để bảo vệ dữ liệu tồn kho, chỉ cập nhật qua Nhập/Xuất kho
    protected $fillable = [
        'name',
        'description',
        'image',
        'price',
        'category_id',
        'is_featured',
    ];

    // Khai báo mối quan hệ: Một Sản phẩm (Product) sẽ thuộc về một Danh mục (Category)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Khai báo mối quan hệ: Một Sản phẩm có nhiều ảnh chi tiết
    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('order');
    }

    // Lấy ảnh chính của sản phẩm
    public function getPrimaryImageAttribute()
    {
        $primaryImage = $this->images()->where('is_primary', true)->first();
        return $primaryImage ? $primaryImage->image_path : $this->image;
    }

    // Quan hệ với Cart
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    // Quan hệ với Review
    public function reviews()
    {
        return $this->hasMany(Review::class)->latest();
    }

    // Quan hệ với Wishlist
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    // Quan hệ với OrderItem
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}