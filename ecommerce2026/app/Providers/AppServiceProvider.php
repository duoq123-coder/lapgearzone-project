<?php
namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; // <-- 1. THÊM DÒNG KHOAI BÁO NÀY Ở ĐÂY
class AppServiceProvider extends ServiceProvider
{
/**
* Register any application services.
*/
public function register(): void
{
//
}
/**
* Bootstrap any application services.
*/
public function boot(): void
{
    // <-- 2. THÊM DÒNG LỆNH NÀY VÀO BÊN TRONG HÀM BOOT
    Paginator::useBootstrap();

    // View Composer cho khu vực Admin
    \Illuminate\Support\Facades\View::composer('admin.layouts.app', function ($view) {
        $outOfStockProducts = \App\Models\Product::where('quantity', '<=', 0)->get();
        // Bạn có thể thêm các loại thông báo khác tại đây (VD: đơn hàng chờ xử lý)
        // $pendingOrders = \App\Models\Order::where('status', 'pending')->get();
        
        $view->with('shopIssues', [
            'out_of_stock' => $outOfStockProducts,
            // 'pending_orders' => $pendingOrders,
        ]);
    });
}
}