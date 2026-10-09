<style>
    td .form-group {
        margin-bottom: 0 !important;
    }

    .table-has-many > thead > tr > th {
        text-align: center;
        vertical-align: middle;
    }
</style>

<hr style="margin-top: 0; margin-bottom: 15px;">

<div class="{{$viewClass['form-group']}}">
    <label class="{{$viewClass['label']}} control-label">{{ $label }}</label>
    <div class="{{$viewClass['field']}}">
        <div id="has-many-{{$column}}">
            <table class="table table-has-many has-many-{{$column}}">
                <thead>
                <tr>
                    @foreach($headers as $header)
                        <th>{{ $header }}</th>
                    @endforeach

                    <th class="hidden"></th>

                    @if($options['allowDelete'])
                        <th></th>
                    @endif
                </tr>
                </thead>
                <tbody class="has-many-{{$column}}-forms">
                @foreach($forms as $pk => $form)
                    <tr class="has-many-{{$column}}-form fields-group">

                        <?php $hidden = ''; ?>

                        @foreach($form->fields() as $field)

                            @if (is_a($field, \AliAkel\Admin\Form\Field\Hidden::class))
                                <?php $hidden .= $field->render(); ?>
                                @continue
                            @endif

                            <td>{!! $field->setLabelClass(['hidden'])->setWidth(12, 0)->render() !!}</td>
                        @endforeach

                        <td class="hidden">{!! $hidden !!}</td>

                        @if($options['allowDelete'])
                            <td class="form-group">
                                <div>
                                    <div class="remove btn btn-warning btn-sm pull-right">{!! admin_icon('fa-trash') !!}&nbsp;{{ trans('admin.remove') }}</div>
                                </div>
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>

            <template class="{{$column}}-tpl">
                <tr class="has-many-{{$column}}-form fields-group">

                    {!! $template !!}

                    <td class="form-group">
                        <div>
                            <div class="remove btn btn-warning btn-sm pull-right">{!! admin_icon('fa-trash') !!}&nbsp;{{ trans('admin.remove') }}</div>
                        </div>
                    </td>
                </tr>
            </template>

            @if($options['allowCreate'])
                <div class="form-group" style="margin-bottom: 0;">
                    <div class="add btn btn-success btn-sm">{!! admin_icon('fa-save') !!}&nbsp;{{ trans('admin.new') }}</div>
                </div>
            @endif
        </div>
    </div>
</div>
