<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">

        @foreach($tabObj->getTabs() as $tab)
            <li {{ $tab['active'] ? 'class=active' : '' }}>
                <a href="#tab-{{ $tab['id'] }}" data-toggle="tab">
                    {{ $tab['title'] }} {!! admin_icon('fa-exclamation-circle', 'text-red hide') !!}
                </a>
            </li>
        @endforeach

    </ul>
    <div class="tab-content fields-group">

        @foreach($tabObj->getTabs() as $tab)
            <div class="tab-pane {{ $tab['active'] ? 'active' : '' }}" id="tab-{{ $tab['id'] }}">
                @foreach($tab['fields'] as $field)
                    {!! $field->render() !!}
                @endforeach
            </div>
        @endforeach

    </div>
</div>