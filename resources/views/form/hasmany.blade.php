<div id="has-many-{{$column}}" class="la-hasmany has-many-{{$column}}">
    <div class="la-hasmany-header">
        <h4 class="la-hasmany-title">{{ $label }}</h4>
    </div>

    <div class="has-many-{{$column}}-forms la-hasmany-forms">
        @foreach($forms as $pk => $form)
            <div class="has-many-{{$column}}-form fields-group la-hasmany-item">
                <div class="la-hasmany-item-body">
                    @foreach($form->fields() as $field)
                        {!! $field->render() !!}
                    @endforeach

                    @if($options['allowDelete'])
                        <div class="form-group la-hasmany-item-actions">
                            <label class="{{$viewClass['label']}} control-label"></label>
                            <div class="{{$viewClass['field']}}">
                                <div class="remove btn btn-warning btn-sm pull-right">{!! admin_icon('fa-trash') !!}&nbsp;{{ trans('admin.remove') }}</div>
                            </div>
                        </div>
                    @endif
                </div>
                <hr class="la-hasmany-item-divider">
            </div>
        @endforeach
    </div>

    <template class="{{$column}}-tpl">
        <div class="has-many-{{$column}}-form fields-group la-hasmany-item">
            <div class="la-hasmany-item-body">
                {!! $template !!}

                <div class="form-group la-hasmany-item-actions">
                    <label class="{{$viewClass['label']}} control-label"></label>
                    <div class="{{$viewClass['field']}}">
                        <div class="remove btn btn-warning btn-sm pull-right">{!! admin_icon('fa-trash') !!}&nbsp;{{ trans('admin.remove') }}</div>
                    </div>
                </div>
            </div>
            <hr class="la-hasmany-item-divider">
        </div>
    </template>

    @if($options['allowCreate'])
        <div class="form-group la-hasmany-add">
            <label class="{{$viewClass['label']}} control-label"></label>
            <div class="{{$viewClass['field']}}">
                <div class="add btn btn-success btn-sm">{!! admin_icon('fa-save') !!}&nbsp;{{ trans('admin.new') }}</div>
            </div>
        </div>
    @endif
</div>
