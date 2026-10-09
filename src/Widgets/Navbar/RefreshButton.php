<?php

namespace AliAkel\Admin\Widgets\Navbar;

use AliAkel\Admin\Admin;
use Illuminate\Contracts\Support\Renderable;

class RefreshButton implements Renderable
{
    public function render()
    {
        return Admin::component('admin::components.refresh-btn');
    }
}
