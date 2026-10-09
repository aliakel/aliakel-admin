<?php

namespace AliAkel\Admin\Grid\Displayers;

use AliAkel\Admin\Facades\Admin;
use Illuminate\Support\Str;

class Limit extends AbstractDisplayer
{
    protected function addScript()
    {
        $script = <<<'JS'
$('.limit-more').click(function () {
    $(this).parent('.limit-text').toggleClass('hide').siblings().toggleClass('hide');
});
JS;

        Admin::script($script);
    }

    public function display($limit = 100, $end = '...')
    {
        $this->addScript();

        $value = Str::limit($this->value, $limit, $end);

        $original = $this->getColumn()->getOriginal();

        if ($value == $original) {
            return $value;
        }

        $featherIcon1 = admin_icon('fa fa-angle-double-up');
        $featherIcon2 = admin_icon('fa fa-angle-double-down');
        return <<<HTML
<div class="limit-text">
    <span class="text">{$value}</span>
    &nbsp;<a href="javascript:void(0);" class="limit-more">&nbsp;{$featherIcon2}</a>
</div>
<div class="limit-text hide">
    <span class="text">{$original}</span>
    &nbsp;<a href="javascript:void(0);" class="limit-more">&nbsp;{$featherIcon1}</a>
</div>
HTML;
    }
}
