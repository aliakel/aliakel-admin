<?php

namespace AliAkel\Admin\Form\Field;

class Mobile extends Text
{
    protected static $js = [
        '/vendor/laravel-admin/AdminLTE/plugins/input-mask/jquery.inputmask.bundle.min.js',
    ];

    /**
     * @see https://github.com/RobinHerbots/Inputmask#options
     *
     * @var array
     */
    protected $options = [
        'mask' => '99999999999',
    ];

    public function render()
    {
        $this->inputmask($this->options);

        $this->prepend(admin_icon('fa fa-phone fa-fw'))
            ->defaultAttribute('style', 'width: 150px');

        return parent::render();
    }
}
