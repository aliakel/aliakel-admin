<?php

namespace AliAkel\Admin\Form\Field;

use AliAkel\Admin\Icons\Feather;

class Icon extends Text
{
    protected $default = 'edit';

    protected $view = 'admin::form.icon';

    public function render()
    {
        $this->withoutIcon();

        $raw = old($this->elementName ?: $this->column, $this->value());
        $iconName = Feather::canonical((string) $raw);

        if ($iconName === '') {
            $iconName = Feather::canonical((string) $this->getDefault()) ?: 'edit';
        }

        $this->attribute('value', $iconName);
        $this->attribute('autocomplete', 'off');
        $this->addVariables([
            'iconName' => $iconName,
        ]);

        return parent::render();
    }
}
