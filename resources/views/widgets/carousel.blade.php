<div {!! $attributes !!} style="max-width: {{ (int) $width }}px;">
    <ol class="carousel-indicators">

        @foreach($items as $key => $item)
        <li data-target="#{!! $id !!}" data-slide-to="{{$key}}" class="{{ $key == 0 ? 'active' : '' }}"></li>
        @endforeach

    </ol>
    <div class="carousel-inner">

        @foreach($items as $key => $item)
        <div class="item {{ $key == 0 ? 'active' : '' }}">
            <img src="{{ url($item['image']) }}" alt="{{ $item['caption'] }}" style="max-height: {{ (int) $height }}px;">
            <div class="carousel-caption">
                {{$item['caption']}}
            </div>
        </div>
        @endforeach

    </div>
    <a class="left carousel-control" href="#{!! $id !!}" data-slide="prev">
        {!! admin_icon('fa-angle-left') !!}
    </a>
    <a class="right carousel-control" href="#{!! $id !!}" data-slide="next">
        {!! admin_icon('fa-angle-right') !!}
    </a>
</div>
