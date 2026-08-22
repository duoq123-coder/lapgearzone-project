<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Coupon::insert([
            [
                'code' => 'DISCOUNT50K',
                'type' => 'fixed',
                'value' => 50000,
                'min_order_value' => 200000,
                'usage_limit' => 100,
                'used' => 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'TET2026',
                'type' => 'percent',
                'value' => 10, // 10%
                'min_order_value' => 500000,
                'usage_limit' => 50,
                'used' => 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
