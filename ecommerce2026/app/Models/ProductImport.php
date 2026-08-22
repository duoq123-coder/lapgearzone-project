<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImport extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'product_id',
        'supplier',
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
