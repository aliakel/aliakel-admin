<?php

use Illuminate\Support\MessageBag;

if (!function_exists('admin_path')) {

    /**
     * Get admin path.
     *
     * @param string $path
     *
     * @return string
     */
    function admin_path($path = '')
    {
        return ucfirst(config('admin.directory')).($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

if (!function_exists('admin_url')) {
    /**
     * Get admin url.
     *
     * @param string $path
     * @param mixed  $parameters
     * @param bool   $secure
     *
     * @return string
     */
    function admin_url($path = '', $parameters = [], $secure = null)
    {
        if (\Illuminate\Support\Facades\URL::isValidUrl($path)) {
            return $path;
        }

        $secure = $secure ?: (config('admin.https') || config('admin.secure'));

        return url(admin_base_path($path), $parameters, $secure);
    }
}

if (!function_exists('admin_langs')) {

    /**
     * Supported admin languages as code => label.
     *
     * @return array<string, string>
     */
    function admin_langs(): array
    {
        $langs = config('admin.langs', []);
        $labels = [
            'ar' => 'العربية',
            'en' => 'English',
            'fr' => 'Français',
            'de' => 'Deutsch',
            'es' => 'Español',
            'tr' => 'Türkçe',
            'fa' => 'فارسی',
            'he' => 'עברית',
            'ur' => 'اردو',
        ];

        if (!is_array($langs) || $langs === []) {
            $locale = (string) config('app.locale', 'en');

            return [$locale => $labels[$locale] ?? strtoupper($locale)];
        }

        if (array_is_list($langs)) {
            $normalized = [];

            foreach ($langs as $code) {
                $code = (string) $code;
                $normalized[$code] = $labels[$code] ?? strtoupper($code);
            }

            return $normalized;
        }

        return $langs;
    }
}

if (!function_exists('admin_is_rtl')) {

    /**
     * Whether the current admin language is Arabic.
     */
    function admin_is_rtl(): bool
    {
        return app()->getLocale() === 'ar' && array_key_exists('ar', admin_langs());
    }
}

if (!function_exists('admin_menu_primary_locale')) {

    /**
     * Locale used as the stored fallback title.
     */
    function admin_menu_primary_locale(): string
    {
        $langs = admin_langs();
        $locale = (string) config('app.locale', 'en');

        if (array_key_exists($locale, $langs)) {
            return $locale;
        }

        return (string) (array_key_first($langs) ?: $locale);
    }
}

if (!function_exists('admin_menu_titles')) {

    /**
     * @param array|object $item
     *
     * @return array<string, string>
     */
    function admin_menu_titles($item): array
    {
        $titles = is_array($item) ? ($item['titles'] ?? null) : ($item->titles ?? null);

        if (is_string($titles)) {
            $decoded = json_decode($titles, true);
            $titles = is_array($decoded) ? $decoded : [];
        }

        if (!is_array($titles)) {
            $titles = [];
        }

        $clean = [];

        foreach ($titles as $locale => $label) {
            $label = trim((string) $label);

            if ($label !== '') {
                $clean[(string) $locale] = $label;
            }
        }

        return $clean;
    }
}

if (!function_exists('admin_menu_title')) {

    /**
     * Menu label for the active locale.
     *
     * @param array|object $item
     */
    function admin_menu_title($item): string
    {
        $titles = admin_menu_titles($item);
        $locale = app()->getLocale();

        if (!empty($titles[$locale])) {
            return $titles[$locale];
        }

        $stored = trim((string) (is_array($item) ? ($item['title'] ?? '') : ($item->title ?? '')));
        $key = 'admin.menu_titles.'.trim(str_replace(' ', '_', strtolower($stored)));

        if ($stored !== '' && \Illuminate\Support\Facades\Lang::has($key)) {
            return (string) __($key);
        }

        $primary = admin_menu_primary_locale();

        if (!empty($titles[$primary])) {
            return $titles[$primary];
        }

        foreach ($titles as $label) {
            return $label;
        }

        return $stored;
    }
}

if (!function_exists('admin_base_path')) {
    /**
     * Get admin url.
     *
     * @param string $path
     *
     * @return string
     */
    function admin_base_path($path = '')
    {
        $prefix = '/'.trim(config('admin.route.prefix'), '/');

        $prefix = ($prefix == '/') ? '' : $prefix;

        $path = trim($path, '/');

        if (is_null($path) || strlen($path) == 0) {
            return $prefix ?: '/';
        }

        return $prefix.'/'.$path;
    }
}

if (!function_exists('admin_toastr')) {

    /**
     * Flash a toastr message bag to session.
     *
     * @param string $message
     * @param string $type
     * @param array  $options
     */
    function admin_toastr($message = '', $type = 'success', $options = [])
    {
        $toastr = new MessageBag(get_defined_vars());

        session()->flash('toastr', $toastr);
    }
}

if (!function_exists('admin_flash_messages')) {

    /**
     * Normalize a flashed message bag for PHP and JSON session serialization.
     *
     * @param mixed $value
     *
     * @return array
     */
    function admin_flash_messages($value): array
    {
        if ($value instanceof MessageBag) {
            return $value->getMessages();
        }

        return is_array($value) ? $value : [];
    }
}

if (!function_exists('admin_success')) {

    /**
     * Flash a success message bag to session.
     *
     * @param string $title
     * @param string $message
     */
    function admin_success($title, $message = '')
    {
        admin_info($title, $message, 'success');
    }
}

if (!function_exists('admin_error')) {

    /**
     * Flash a error message bag to session.
     *
     * @param string $title
     * @param string $message
     */
    function admin_error($title, $message = '')
    {
        admin_info($title, $message, 'error');
    }
}

if (!function_exists('admin_warning')) {

    /**
     * Flash a warning message bag to session.
     *
     * @param string $title
     * @param string $message
     */
    function admin_warning($title, $message = '')
    {
        admin_info($title, $message, 'warning');
    }
}

if (!function_exists('admin_info')) {

    /**
     * Flash a message bag to session.
     *
     * @param string $title
     * @param string $message
     * @param string $type
     */
    function admin_info($title, $message = '', $type = 'info')
    {
        $message = new MessageBag(get_defined_vars());

        session()->flash($type, $message);
    }
}

if (!function_exists('admin_asset')) {

    /**
     * @param $path
     *
     * @return string
     */
    function admin_asset($path)
    {
        return (config('admin.https') || config('admin.secure')) ? secure_asset($path) : asset($path);
    }
}

if (!function_exists('admin_trans')) {

    /**
     * Translate the given message.
     *
     * @param string $key
     * @param array  $replace
     * @param string $locale
     *
     * @return \Illuminate\Contracts\Translation\Translator|string|array|null
     */
    function admin_trans($key = null, $replace = [], $locale = null)
    {
        $line = __($key, $replace, $locale);

        if (!is_string($line)) {
            return $key;
        }

        return $line;
    }
}

if (!function_exists('array_delete')) {

    /**
     * Delete from array by value.
     *
     * @param array $array
     * @param mixed $value
     */
    function array_delete(&$array, $value)
    {
        $value = \Illuminate\Support\Arr::wrap($value);

        foreach ($array as $index => $item) {
            if (in_array($item, $value)) {
                unset($array[$index]);
            }
        }
    }
}

if (!function_exists('class_uses_deep')) {

    /**
     * To get ALL traits including those used by parent classes and other traits.
     *
     * @param $class
     * @param bool $autoload
     *
     * @return array
     */
    function class_uses_deep($class, $autoload = true)
    {
        $traits = [];

        do {
            $traits = array_merge(class_uses($class, $autoload), $traits);
        } while ($class = get_parent_class($class));

        foreach ($traits as $trait => $same) {
            $traits = array_merge(class_uses($trait, $autoload), $traits);
        }

        return array_unique($traits);
    }
}

if (!function_exists('admin_dump')) {

    /**
     * @param $var
     *
     * @return string
     */
    function admin_dump($var)
    {
        ob_start();

        dump(...func_get_args());

        $contents = ob_get_contents();

        ob_end_clean();

        return $contents;
    }
}

if (!function_exists('file_size')) {

    /**
     * Convert file size to a human readable format like `100mb`.
     *
     * @param int $bytes
     *
     * @return string
     *
     * @see https://stackoverflow.com/a/5501447/9443583
     */
    function file_size($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2).' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2).' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2).' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes.' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes.' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}

if (!function_exists('prepare_options')) {

    /**
     * @param array $options
     *
     * @return array
     */
    function prepare_options(array $options)
    {
        $original = [];
        $toReplace = [];

        foreach ($options as $key => &$value) {
            if (is_array($value)) {
                $subArray = prepare_options($value);
                $value = $subArray['options'];
                $original = array_merge($original, $subArray['original']);
                $toReplace = array_merge($toReplace, $subArray['toReplace']);
            } elseif (strpos($value, 'function(') === 0) {
                $original[] = $value;
                $value = "%{$key}%";
                $toReplace[] = "\"{$value}\"";
            }
        }

        return compact('original', 'toReplace', 'options');
    }
}

if (!function_exists('json_encode_options')) {

    /**
     * @param array $options
     *
     * @return string
     *
     * @see http://web.archive.org/web/20080828165256/http://solutoire.com/2008/06/12/sending-javascript-functions-over-json/
     */
    function json_encode_options(array $options)
    {
        $data = prepare_options($options);

        $json = json_encode($data['options']);

        return str_replace($data['toReplace'], $data['original'], $json);
    }
}

if (!function_exists('admin_get_route')) {
    function admin_get_route(string $name): string
    {
        return config('admin.route.prefix').'.'.$name;
    }
}

if (!function_exists('admin_icon')) {

    /**
     * Render a Feather icon.
     *
     * Accepts a Feather name (`menu`, `users`) or a legacy Font Awesome name (`fa-bars`).
     *
     * @param string $name
     * @param string $class
     *
     * @return string
     */
    function admin_icon($name = '', $class = ''): string
    {
        return \AliAkel\Admin\Icons\Feather::render($name, $class);
    }
}
