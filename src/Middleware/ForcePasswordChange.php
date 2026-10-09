<?php

namespace AliAkel\Admin\Middleware;

use Closure;
use AliAkel\Admin\Facades\Admin;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Admin::user();

        if ($user && method_exists($user, 'passwordMustBeChanged') && $user->passwordMustBeChanged()) {
            if (!$this->shouldPassThrough($request)) {
                admin_toastr('admin.password_change_required', 'warning');

                return redirect(admin_url('auth/setting'));
            }
        }

        return $next($request);
    }

    /**
     * Routes allowed while a password change is required.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected function shouldPassThrough($request)
    {
        $excepts = [
            'auth/setting',
            'auth/logout',
            'locale/*',
        ];

        return collect($excepts)
            ->map('admin_base_path')
            ->contains(function ($except) use ($request) {
                $except = trim($except, '/');

                return $request->is($except);
            });
    }
}
