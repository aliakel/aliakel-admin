<?php

namespace AliAkel\Admin\Middleware;

use Closure;
use AliAkel\Admin\Facades\Admin;
use Illuminate\Http\Request;

class Bootstrap
{
    public function handle(Request $request, Closure $next)
    {
        Admin::bootstrap();

        return $next($request);
    }
}
