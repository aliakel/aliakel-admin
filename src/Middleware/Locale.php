<?php

namespace AliAkel\Admin\Middleware;

use Closure;
use Illuminate\Http\Request;

class Locale
{
    /**
     * Apply the selected admin language.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $langs = array_keys(admin_langs());
        $locale = session('admin_locale');

        if (!in_array($locale, $langs, true)) {
            $locale = in_array(config('app.locale'), $langs, true)
                ? config('app.locale')
                : ($langs[0] ?? config('app.locale'));
        }

        if ($locale) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
