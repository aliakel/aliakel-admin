<?php

namespace AliAkel\Admin\Form\Field;

use AliAkel\Admin\Form\Field;

class Nullable extends Field
{
    public function __construct()
    {
    }

    public function __call($method, $parameters)
    {
        return $this;
    }
}
