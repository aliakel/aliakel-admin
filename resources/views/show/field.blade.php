<div class="form-group">
    <label class="{{ $width['label'] ? 'control-label mb-2 block text-sm font-medium text-slate-700' : 'control-label' }}">{{ $label }}</label>
    <div class="{{ $width['field'] ? 'w-full' : '' }}">
        @if($wrapped)
        <div class="box box-solid box-default no-margin box-show">
            <!-- /.box-header -->
            <div class="box-body">
                @if($escape)
                    {{ $content }}&nbsp;
                @else
                    {!! $content !!}&nbsp;
                @endif
            </div><!-- /.box-body -->
        </div>
        @else
            @if($escape)
                {{ $content }}
            @else
                {!! $content !!}
            @endif
        @endif
    </div>
</div>