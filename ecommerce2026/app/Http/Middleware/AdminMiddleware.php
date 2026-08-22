<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Nếu chưa đăng nhập -> chuyển đến trang đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        // Nếu đã đăng nhập nhưng không phải admin -> chuyển về trang welcome
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('welcome')->with('error', 'Bạn không có quyền truy cập vào khu vực quản trị.');
        }

        // Là admin -> cho phép tiếp tục
        return $next($request);
    }
}