<?php

namespace AliAkel\Admin\Grid\Displayers;

use AliAkel\Admin\Facades\Admin;

class Secret extends AbstractDisplayer
{
    public function display($dotCount = 6)
    {
        $this->addScript();

        $dots = str_repeat('*', $dotCount);

        $featherIcon1 = admin_icon('eye', 'cursor-pointer');
        return <<<HTML
<span class="secret-wrapper">
    {$featherIcon1}
    &nbsp;
    <span class="secret-placeholder" style="vertical-align: middle;">{$dots}</span>
    <span class="secret-content" style="display: none;">{$this->getValue()}</span>
</span>
HTML;
    }

    protected function addScript()
    {
        $script = <<<'SCRIPT'
$('.secret-wrapper [data-feather]').click(function () {
    LA.toggleFeather(this, 'eye', 'eye-off');
    $(this).parent().find('.secret-placeholder,.secret-content').toggle();
});
SCRIPT;

        Admin::script($script);
    }
}
