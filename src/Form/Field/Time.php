<?php

namespace AliAkel\Admin\Form\Field;

class Time extends Date
{
    protected $format = 'HH:mm:ss';

    public function render()
    {
        $this->prepend(admin_icon('fa fa-clock-o fa-fw'))
            ->defaultAttribute('style', 'width: 150px');

        return parent::render();
    }
}
