<div class="flex h-14 items-center border-b border-slate-800 px-4">
    <a href="{{ admin_url('/') }}" class="truncate text-base font-semibold text-white">
        <span class="la-logo-full">{!! config('admin.logo', config('admin.name')) !!}</span>
        <span class="la-logo-mini">{!! config('admin.logo-mini', config('admin.name')) !!}</span>
    </a>
</div>

<div class="flex items-center gap-3 border-b border-slate-800 px-4 py-3">
    <img src="{{ Admin::user()->avatar }}" class="h-9 w-9 rounded-full object-cover" alt="">
    <div class="la-label min-w-0">
        <p class="truncate text-sm font-medium">{{ Admin::user()->name }}</p>
        <p class="text-xs text-emerald-400">{!! admin_icon('fa-circle') !!} {{ trans('admin.online') }}</p>
    </div>
</div>

@if(config('admin.enable_menu_search'))
<form class="sidebar-form px-3 py-3" onsubmit="return false;">
    <input type="text" autocomplete="off" class="autocomplete w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-1.5 text-sm text-white placeholder:text-slate-400" placeholder="Search...">
    <ul class="dropdown-menu" role="menu">
        @foreach(Admin::menuLinks() as $link)
        <li>
            <a href="{{ admin_url($link['uri']) }}">{!! admin_icon($link['icon']) !!} {{ admin_trans($link['title']) }}</a>
        </li>
        @endforeach
    </ul>
</form>
@endif

<nav class="sidebar-menu flex-1 overflow-y-auto px-2 py-3">
    <p class="la-label px-3 pb-2 text-xs font-semibold tracking-wide text-slate-400 uppercase">{{ trans('admin.menu') }}</p>
    <ul class="space-y-1">
        @each('admin::partials.menu', Admin::menu(), 'item')
    </ul>
</nav>
