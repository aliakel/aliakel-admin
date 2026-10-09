<?php

namespace AliAkel\Admin\Grid\Displayers;

use AliAkel\Admin\Admin;

class RowSelector extends AbstractDisplayer
{
    /**
     * @var array<string, bool>
     */
    protected static $scripts = [];

    public function display()
    {
        $key = $this->grid->getGridRowName();

        if (!isset(static::$scripts[$key])) {
            Admin::script($this->script());
            static::$scripts[$key] = true;
        }

        return <<<EOT
<input type="checkbox" class="{$this->grid->getGridRowName()}-checkbox" data-id="{$this->getKey()}"  autocomplete="off"/>
EOT;
    }

    protected function script()
    {
        $all = $this->grid->getSelectAllName();
        $row = $this->grid->getGridRowName();

        $selected = trans('admin.grid_items_selected');

        return <<<EOT
var \$rows = $('.{$row}-checkbox');
var \$batch = $('.{$all}-btn');

function laSyncBatch() {
    var selected = $.admin.grid.selected().length;

    if (selected > 0) {
        \$batch.removeClass('hide');
    } else {
        \$batch.addClass('hide');
    }

    \$batch.find('.selected').html("{$selected}".replace('{n}', selected));
}

\$rows.on('change', function () {
    var id = $(this).data('id');

    if (this.checked) {
        $.admin.grid.select(id);
    } else {
        $.admin.grid.unselect(id);
    }

    $(this).closest('tr').toggleClass('la-row-selected', this.checked);
    laSyncBatch();
});

$('.{$all}').on('change', function () {
    $.admin.grid.selects = {};

    \$rows.each(function () {
        this.checked = \$('.{$all}').prop('checked');

        if (this.checked) {
            $.admin.grid.select($(this).data('id'));
        }

        $(this).closest('tr').toggleClass('la-row-selected', this.checked);
    });

    laSyncBatch();
});

EOT;
    }
}
