<?php

namespace AliAkel\Admin\Form\Field;

use AliAkel\Admin\Admin;

class CheckboxCard extends CheckboxButton
{
    protected function addStyle()
    {
        $style = <<<'STYLE'
.card-group label {
    cursor: pointer;
    font-weight: 400;
}
STYLE;

        Admin::style($style);
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->addStyle();

        return parent::render();
    }
}
