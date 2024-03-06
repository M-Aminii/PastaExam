<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherRoleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // بررسی اینکه کاربر وارد شده و نقش آن "معلم" باشد
        if (Auth::check() && Auth::user()->role === User::ROLE_TEACHER) {
            return $next($request);
        }

        // اگر نقش کاربر "معلم" نبود، ممنوعیت دسترسی را برمی‌گردانیم
        return response(['message' => 'شما مجوز دسترسی به این عملیات را ندارید'], 403);
    }
}

