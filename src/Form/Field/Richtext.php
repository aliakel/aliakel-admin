<?php

namespace AliAkel\Admin\Form\Field;

use AliAkel\Admin\Form\Field;

class Richtext extends Field
{
    /**
     * @var string
     */
    protected $view = 'admin::form.richtext';

    /**
     * @var array
     */
    protected static $css = [
        '/vendor/laravel-admin/quill/quill.snow.css',
    ];

    /**
     * @var array
     */
    protected static $js = [
        '/vendor/laravel-admin/quill/quill.js',
    ];

}
