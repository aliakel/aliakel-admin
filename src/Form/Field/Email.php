<?php

namespace AliAkel\Admin\Form\Field;

class Email extends Text
{
    protected $rules = 'nullable|email';

    public function render()
    {
        $this->prepend(admin_icon('fa fa-envelope fa-fw'))
            ->defaultAttribute('type', 'email');

        return parent::render();
    }
}
