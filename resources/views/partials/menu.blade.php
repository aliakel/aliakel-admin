@if(Admin::user()->visible(\Illuminate\Support\Arr::get($item, 'roles', [])) && Admin::user()->can(\Illuminate\Support\Arr::get($item, 'permission')))
    @if(!isset($item['children']))
        <li>
            @if(url()->isValidUrl($item['uri']))
                <a href="{{ $item['uri'] }}" target="_blank" class="flex items-center gap-3 rounded-md px-3 py-2 text-sm text-slate-200 hover:bg-slate-800">
            @else
                 <a href="{{ admin_url($item['uri']) }}" class="flex items-center gap-3 rounded-md px-3 py-2 text-sm text-slate-200 hover:bg-slate-800">
            @endif
                {!! admin_icon($item['icon'], 'h-4 w-4 shrink-0') !!}
                <span class="la-label truncate">{{ admin_menu_title($item) }}</span>
            </a>
        </li>
    @else
        <li class="treeview">
            <a href="#" class="flex items-center gap-3 rounded-md px-3 py-2 text-sm text-slate-200 hover:bg-slate-800">
                {!! admin_icon($item['icon'], 'h-4 w-4 shrink-0') !!}
                <span class="la-label flex-1 truncate">{{ admin_menu_title($item) }}</span>
                {!! admin_icon('fa-angle-right', 'la-label la-chevron') !!}
            </a>
            <ul class="treeview-menu space-y-1 py-1 ps-4">
                @foreach($item['children'] as $item)
                    @include('admin::partials.menu', $item)
                @endforeach
            </ul>
        </li>
    @endif
@endif
