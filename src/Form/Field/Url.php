<?php

namespace AliAkel\Admin\Form\Field;

class Url extends Text
{
    protected $rules = 'nullable|url';

    public function render()
    {
        $this->prepend(admin_icon('fa fa-internet-explorer fa-fw'))
            ->defaultAttribute('type', 'url');

        return parent::render();
    }
}
