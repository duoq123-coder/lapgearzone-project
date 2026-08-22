<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceChangePassword
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->must_change_password) {
            // Allow them to visit the password change routes
            if (!$request->routeIs('password.change.force') && !$request->routeIs('password.update.force') && !$request->routeIs('logout')) {
                return redirect()->route('password.change.force')->with('warning', 'Bạn phải thay đổi mật khẩu mặc định trước khi tiếp tục.');
            }
        }

        return $next($request);
    }
}
