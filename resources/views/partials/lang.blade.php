@if(count($langs = admin_langs()) > 1)
<li class="dropdown">
    <a href="#" class="dropdown-toggle inline-flex items-center gap-2 rounded-md px-2 py-1 text-sm text-slate-700 hover:bg-slate-100" data-toggle="dropdown">
        {!! admin_icon('fa-globe') !!}
        <span>{{ $langs[app()->getLocale()] ?? strtoupper(app()->getLocale()) }}</span>
    </a>
    <ul class="dropdown-menu dropdown-menu-right min-w-36">
        @foreach($langs as $code => $label)
            <li>
                <a class="la-locale {{ app()->getLocale() === $code ? 'font-semibold text-sky-700' : '' }}" href="{{ admin_url('locale/'.$code) }}">{{ $label }}</a>
            </li>
        @endforeach
    </ul>
</li>
@endif
