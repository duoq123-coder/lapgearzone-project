<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'address',
        'total_price',
        'coupon_code',
        'discount_amount',
        'status',
        'payment_method',
        'payos_order_code',
        'payos_payment_link_id',
        'delivery_staff_id',
        'delivery_status',
        'delivery_proof',
        'customer_issue',
    ];

    protected $casts = [
        'delivery_proof' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deliveryStaff()
    {
        return $this->belongsTo(User::class, 'delivery_staff_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
