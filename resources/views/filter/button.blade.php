<div class="btn-group">
    <button type="button" class="btn btn-sm btn-default {{ $btn_class }} {{ $expand ? 'active' : '' }}" title="{{ trans('admin.filter') }}">
        {!! admin_icon('fa-filter') !!}<span class="hidden-xs">&nbsp;{{ trans('admin.filter') }}</span>
    </button>

    @if($scopes->isNotEmpty())
    <button type="button" class="btn btn-sm btn-dropbox dropdown-toggle" data-toggle="dropdown">

        <span>{{ $label }}</span>
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu" role="menu">
        @foreach($scopes as $scope)
            {!! $scope->render() !!}
        @endforeach
        <li role="separator" class="divider"></li>
        <li><a href="{{ $cancel }}">{{ trans('admin.cancel') }}</a></li>
    </ul>
    @endif
</div>

<script>
var $btn = $('.{{ $btn_class }}');
var $filter = $('#{{ $filter_id }}');

$btn.off('click').on('click', function (e) {
    e.preventDefault();

    if ($filter.hasClass('hide')) {
        $filter.removeClass('hide');
        $btn.addClass('active');
    } else {
        $filter.addClass('hide');
        $btn.removeClass('active');
    }
});
</script>
