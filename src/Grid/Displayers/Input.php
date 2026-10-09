<?php

namespace AliAkel\Admin\Grid\Displayers;

use AliAkel\Admin\Admin;

class Input extends AbstractDisplayer
{
    public function display($mask = '')
    {
        return Admin::component('admin::grid.inline-edit.input', [
            'key'      => $this->getKey(),
            'value'    => $this->getValue(),
            'display'  => $this->getValue(),
            'name'     => $this->getPayloadName(),
            'resource' => $this->getResource(),
            'trigger'  => "ie-trigger-{$this->getClassName()}",
            'target'   => "ie-template-{$this->getClassName()}",
            'mask'     => $mask,
        ]);
    }
}
