<div {!! $attributes !!}>
    <div class="inner">
        <p>{{ $name }}</p>
        <h3>{{ $info }}</h3>
    </div>
    <div class="icon">
        {!! admin_icon($icon) !!}
    </div>
    <a href="{{ $link }}" class="small-box-footer">
        <span>{{ trans('admin.more') }}</span>
        {!! admin_icon('fa-arrow-circle-right') !!}
    </a>
</div>