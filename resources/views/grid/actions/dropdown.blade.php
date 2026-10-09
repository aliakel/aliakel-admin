<div class="grid-dropdown-actions dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        {!! admin_icon('fa-ellipsis-v') !!}
    </a>
    <ul class="dropdown-menu">

        @foreach($default as $action)
            <li>{!! $action->render() !!}</li>
        @endforeach

        @if(!empty($custom))

            @if(!empty($default))
                <li class="divider"></li>
            @endif

            @foreach($custom as $action)
                <li>{!! $action->render() !!}</li>
            @endforeach
        @endif
    </ul>
</div>

@yield('child')
