<?php

namespace AliAkel\Admin\Form\Field;

class Password extends Text
{
    public function render()
    {
        $this->withoutIcon();
        $this->append(
            '<button type="button" class="la-password-toggle" data-password-toggle aria-pressed="false" aria-label="'.e(trans('admin.show')).'">'
            .admin_icon('eye')
            .'</button>'
        );
        $this->defaultAttribute('type', 'password');

        return parent::render();
    }
}
