@if($inbox ?? true)
<div class="box box-primary">
    @if(!empty($title))
        <div class="box-header with-border">
            <h3 class="box-title">{{ $title }}</h3>
        </div>
    @endif
    <div class="box-body">
@endif

<form {!! $attributes !!}>
    <div class="fields-group">
        @php
            $visibleFields = collect($fields)->reject(function ($field) {
                return $field instanceof \AliAkel\Admin\Form\Field\Hidden;
            });
            $hiddenFields = collect($fields)->filter(function ($field) {
                return $field instanceof \AliAkel\Admin\Form\Field\Hidden;
            });
            $columns = max(1, (int) ($columnCount ?? 1));
        @endphp

        <div class="la-form-columns la-form-columns-{{ $columns }}">
            @foreach($visibleFields as $field)
                <div class="la-form-column">
                    {!! $field->render() !!}
                </div>
            @endforeach
        </div>

        @foreach($hiddenFields as $field)
            {!! $field->render() !!}
        @endforeach
    </div>

    @if ($method != 'GET')
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    @endif

    @if(count($buttons) > 0)
    <div class="box-footer mt-4 flex items-center justify-end gap-2 px-0">
        @if(in_array('reset', $buttons))
            <button type="reset" class="btn btn-warning">{{ trans('admin.reset') }}</button>
        @endif

        @if(in_array('submit', $buttons))
            <button type="submit" class="btn btn-primary">{{ trans('admin.submit') }}</button>
        @endif
    </div>
    @endif
</form>

@if($inbox ?? true)
    </div>
</div>
@endif
