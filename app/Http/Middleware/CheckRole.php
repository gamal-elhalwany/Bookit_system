<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;


class CheckRole
{

    public function handle(Request $request, Closure $next, $permission)
    {
        // جرب الأول على guard الموظفين
        if (Auth::guard('employee')->check()) {
            $permissions = Permission::where('role_id', Auth::guard('employee')->user()->role_id)->first();
        }
        // لو مش موظف، جرب على المستخدمين العاديين
        elseif (Auth::guard('web')->check()) {
            $permissions = Permission::where('role_id', Auth::guard('web')->user()->role_id)->first();
        } else {
            return redirect()->route('login')->with('danger', 'يجب تسجيل الدخول أولاً.');
        }

        if (!$permissions || !$permissions->$permission) {
            abort(403, 'ليس لديك صلاحية للوصول إلى هذه الصفحة');
        }

        return $next($request);
    }

}
