<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductExport extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'customer_name',
        'product_id',
        'shipping',
        'quantity',
        'unit_price',
        'total',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
