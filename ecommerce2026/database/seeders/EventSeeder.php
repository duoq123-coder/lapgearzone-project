<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Top Laptops (Top selling or first 5 products)
        $eventTop = Event::firstOrCreate(
            ['slug' => 'top-laptops'],
            [
                'name' => 'Top Laptops',
                'headline' => 'Laptop Nổi Bật Tuần Này',
                'description' => 'Những mẫu laptop được người dùng quan tâm và tin chọn nhiều nhất.',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        $topProductsStats = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'paid')
            ->select('order_items.product_id', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('order_items.product_id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->pluck('product_id')
            ->toArray();

        if (empty($topProductsStats)) {
            $topProductIds = Product::where('quantity', '>', 0)->latest()->take(5)->pluck('id')->toArray();
        } else {
            $topProductIds = $topProductsStats;
            if (count($topProductIds) < 5) {
                $more = Product::where('quantity', '>', 0)->whereNotIn('id', $topProductIds)->take(5 - count($topProductIds))->pluck('id')->toArray();
                $topProductIds = array_merge($topProductIds, $more);
            }
        }

        $syncTop = [];
        foreach ($topProductIds as $idx => $id) {
            $syncTop[$id] = ['sort_order' => $idx + 1];
        }
        $eventTop->products()->sync($syncTop);

        // 2. Sản phẩm mới của năm (New arrival products)
        $eventNewYear = Event::firstOrCreate(
            ['slug' => 'san-pham-moi-cua-nam'],
            [
                'name' => 'Sản phẩm mới của năm',
                'headline' => 'Công Nghệ Mới Đón Đầu Xu Hướng 2026',
                'description' => 'Khám phá các thế hệ máy tính xách tay với cải tiến vượt trội về vi xử lý AI và thời lượng pin.',
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        $newProductIds = Product::where('quantity', '>', 0)
            ->where('is_featured', true)
            ->take(5)
            ->pluck('id')
            ->toArray();

        if (count($newProductIds) < 5) {
            $moreNew = Product::where('quantity', '>', 0)
                ->whereNotIn('id', $newProductIds)
                ->latest()
                ->take(5 - count($newProductIds))
                ->pluck('id')
                ->toArray();
            $newProductIds = array_merge($newProductIds, $moreNew);
        }

        $syncNew = [];
        foreach ($newProductIds as $idx => $id) {
            $syncNew[$id] = ['sort_order' => $idx + 1];
        }
        $eventNewYear->products()->sync($syncNew);

        // 3. Sản phẩm mùa (Seasonal products collection)
        $eventSeasonal = Event::firstOrCreate(
            ['slug' => 'san-pham-mua'],
            [
                'name' => 'Sản phẩm mùa',
                'headline' => 'Bộ Sưu Tập Laptop Mùa Mới',
                'description' => 'Lựa chọn laptop lý tưởng phục vụ học tập, sáng tạo và làm việc di động.',
                'sort_order' => 3,
                'is_active' => true,
            ]
        );

        $seasonalProductIds = Product::where('quantity', '>', 0)
            ->orderBy('price', 'asc')
            ->take(5)
            ->pluck('id')
            ->toArray();

        $syncSeasonal = [];
        foreach ($seasonalProductIds as $idx => $id) {
            $syncSeasonal[$id] = ['sort_order' => $idx + 1];
        }
        $eventSeasonal->products()->sync($syncSeasonal);
    }
}
