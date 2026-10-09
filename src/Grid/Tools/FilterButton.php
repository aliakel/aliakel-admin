<?php

namespace AliAkel\Admin\Grid\Tools;

use AliAkel\Admin\Admin;

class FilterButton extends AbstractTool
{
    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $label = '';
        $filter = $this->grid->getFilter();

        if ($scope = $filter->getCurrentScope()) {
            $label = "&nbsp;{$scope->getLabel()}&nbsp;";
        }

        return Admin::component('admin::filter.button', [
            'scopes'    => $filter->getScopes(),
            'label'     => $label,
            'cancel'    => $filter->urlWithoutScopes(),
            'btn_class' => 'la-'.uniqid().'-filter-btn',
            'expand'    => $filter->expand,
            'filter_id' => $filter->getFilterID(),
        ]);
    }
}
