<?php

namespace AliAkel\Admin\Grid\Tools;

use AliAkel\Admin\Grid;

class CreateButton extends AbstractTool
{
    /**
     * @var Grid
     */
    protected $grid;

    /**
     * Create a new CreateButton instance.
     *
     * @param Grid $grid
     */
    public function __construct(Grid $grid)
    {
        $this->grid = $grid;
    }

    /**
     * Render CreateButton.
     *
     * @return string
     */
    public function render()
    {
        if (!$this->grid->showCreateBtn()) {
            return '';
        }

        $new = trans('admin.new');

        $featherIcon1 = admin_icon('fa fa-plus');
        return <<<EOT

<div class="btn-group pull-right grid-create-btn">
    <a href="{$this->grid->getCreateUrl()}" class="btn btn-sm btn-success" title="{$new}">
        {$featherIcon1}<span class="hidden-xs">&nbsp;&nbsp;{$new}</span>
    </a>
</div>

EOT;
    }
}
