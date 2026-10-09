<?php

namespace AliAkel\Admin\Grid\Displayers;

use AliAkel\Admin\Admin;

class Orderable extends AbstractDisplayer
{
    public function display()
    {
        if (!trait_exists('\Spatie\EloquentSortable\SortableTrait')) {
            throw new \Exception('To use orderable grid, please install package [spatie/eloquent-sortable] first.');
        }

        Admin::script($this->script());

        $featherIcon1 = admin_icon('fa fa-caret-down fa-fw');
        $featherIcon2 = admin_icon('fa fa-caret-up fa-fw');
        return <<<EOT

<div class="btn-group">
    <button type="button" class="btn btn-xs btn-info {$this->grid->getGridRowName()}-orderable" data-id="{$this->getKey()}" data-direction="1">
        {$featherIcon2}
    </button>
    <button type="button" class="btn btn-xs btn-default {$this->grid->getGridRowName()}-orderable" data-id="{$this->getKey()}" data-direction="0">
        {$featherIcon1}
    </button>
</div>

EOT;
    }

    protected function script()
    {
        return <<<EOT

$('.{$this->grid->getGridRowName()}-orderable').on('click', function() {

    var key = $(this).data('id');
    var direction = $(this).data('direction');

    $.post('{$this->getResource()}/' + key, {_method:'PUT', _token:LA.token, _orderable:direction}, function(data){
        if (data.status) {
            $.pjax.reload('#pjax-container');
            toastr.success(data.message);
        }
    });

});
EOT;
    }
}
