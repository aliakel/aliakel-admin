<header class="sticky top-0 z-20 flex h-14 items-center gap-3 border-b border-slate-200 bg-white px-4">
    <button type="button" data-sidebar-toggle class="inline-flex h-9 w-9 items-center justify-center rounded-md text-slate-600 hover:bg-slate-100" aria-label="Toggle navigation">
        {!! admin_icon('fa-bars') !!}
    </button>

    <ul class="hidden items-center gap-1 lg:flex">
        {!! Admin::getNavbar()->render('left') !!}
    </ul>

    <div class="ms-auto">
        <ul class="flex items-center gap-1">
            {!! Admin::getNavbar()->render() !!}

            @include('admin::partials.lang')

            <li class="dropdown">
                <a href="#" class="dropdown-toggle inline-flex items-center gap-2 rounded-md px-2 py-1 text-sm text-slate-700 hover:bg-slate-100" data-toggle="dropdown">
                    <img src="{{ Admin::user()->avatar }}" class="h-8 w-8 rounded-full object-cover" alt="">
                    <span class="hidden sm:inline">{{ Admin::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-right w-64 overflow-hidden p-0">
                    <li class="border-b border-slate-200 px-4 py-4 text-center">
                        <img src="{{ Admin::user()->avatar }}" class="mx-auto h-16 w-16 rounded-full object-cover" alt="">
                        <p class="mt-2 text-sm font-medium text-slate-900">{{ Admin::user()->name }}</p>
                        <p class="text-xs text-slate-500">Member since {{ Admin::user()->created_at }}</p>
                    </li>
                    <li class="flex items-center justify-between gap-2 px-4 py-3">
                        <a href="{{ admin_url('auth/setting') }}" class="btn btn-default btn-sm">{{ trans('admin.setting') }}</a>
                        <a href="{{ admin_url('auth/logout') }}" class="btn btn-default btn-sm">{{ trans('admin.logout') }}</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</header>
