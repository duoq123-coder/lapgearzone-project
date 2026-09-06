<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'max_discount_amount',
        'min_order_value',
        'usage_limit',
        'used',
        'starts_at',
        'ends_at',
        'is_active',
        'required_tier',
        'is_auto_apply',
    ];

    protected $casts = [
        'starts_at'           => 'datetime',
        'ends_at'             => 'datetime',
        'is_active'           => 'boolean',
        'is_auto_apply'       => 'boolean',
        'value'               => 'decimal:2',
        'min_order_value'     => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
    ];

    /**
     * Kiểm tra voucher đã hết hạn chưa.
     */
    public function isExpired(): bool
    {
        return $this->ends_at !== null && $this->ends_at->isPast();
    }

    /**
     * Kiểm tra voucher đã đến thời gian bắt đầu chưa.
     */
    public function hasStarted(): bool
    {
        return $this->starts_at === null || $this->starts_at->isPast();
    }

    /**
     * Kiểm tra tính hợp lệ toàn diện về mặt thời gian và trạng thái.
     */
    public function isValidNow(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if (!$this->hasStarted() || $this->isExpired()) {
            return false;
        }

        if ($this->usage_limit !== null && $this->used >= $this->usage_limit) {
            return false;
        }

        return true;
    }
}
