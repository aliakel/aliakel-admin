<?php

namespace AliAkel\Admin\Form\Field;

class Date extends Text
{
    protected static $css = [];

    protected static $js = [];

    protected $format = 'YYYY-MM-DD';

    public function format($format)
    {
        $this->format = $format;

        return $this;
    }

    public function prepare($value)
    {
        if ($value === '') {
            $value = null;
        }

        return $value;
    }

    public function render()
    {
        $this->options['format'] = $this->format;
        $this->options['locale'] = array_key_exists('locale', $this->options) ? $this->options['locale'] : config('app.locale');
        $this->options['allowInputToggle'] = true;

        $this->script = "$('{$this->getElementClassSelector()}').parent().datetimepicker(".json_encode($this->options).');';

        $this->prepend(admin_icon('fa fa-calendar fa-fw'))
            ->defaultAttribute('style', 'width: 110px');

        return parent::render();
    }
}
