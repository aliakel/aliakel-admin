<?php

namespace AliAkel\Admin\Controllers;

use Illuminate\Routing\Controller;

class LocaleController extends Controller
{
    /**
     * Store the chosen admin language.
     *
     * @param string $locale
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($locale)
    {
        abort_unless(array_key_exists($locale, admin_langs()), 404);

        session(['admin_locale' => $locale]);

        return back();
    }
}
