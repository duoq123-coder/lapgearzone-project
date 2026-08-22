<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class DeliveryMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'delivery') {
            return redirect()->route('welcome')->with('error', 'Bạn không có quyền truy cập vào khu vực này.');
        }

        return $next($request);
    }
}
