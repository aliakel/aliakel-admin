<div class="{{$viewClass['form-group']}}">

    <label class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">
        <table class="table table-hover la-keyvalue-table">
            <thead>
            <tr>
                <th>{{ __('Key') }}</th>
                <th>{{ __('Value') }}</th>
                <th class="la-keyvalue-actions"></th>
            </tr>
            </thead>
            <tbody class="kv-{{$column}}-table">

            @foreach(old("{$column}.keys", ($value ?: [])) as $k => $v)

                @php($keysErrorKey = "{$column}.keys.{$loop->index}")
                @php($valsErrorKey = "{$column}.values.{$loop->index}")

                <tr>
                    <td>
                        <div class="form-group {{ $errors->has($keysErrorKey) ? 'has-error' : '' }} mb-0">
                            <input name="{{ $name }}[keys][]" value="{{ old("{$column}.keys.{$k}", $k) }}" class="form-control" required/>

                            @if($errors->has($keysErrorKey))
                                @foreach($errors->get($keysErrorKey) as $message)
                                    <label class="control-label" for="inputError">{!! admin_icon('fa-times-circle-o') !!} {{$message}}</label><br/>
                                @endforeach
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="form-group {{ $errors->has($valsErrorKey) ? 'has-error' : '' }} mb-0">
                            <input name="{{ $name }}[values][]" value="{{ old("{$column}.values.{$k}", $v) }}" class="form-control" />
                            @if($errors->has($valsErrorKey))
                                @foreach($errors->get($valsErrorKey) as $message)
                                    <label class="control-label" for="inputError">{!! admin_icon('fa-times-circle-o') !!} {{$message}}</label><br/>
                                @endforeach
                            @endif
                        </div>
                    </td>

                    <td class="la-keyvalue-actions">
                        <button type="button" class="{{$column}}-remove btn btn-warning btn-sm">
                            {!! admin_icon('fa-trash') !!}&nbsp;{{ __('admin.remove') }}
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td class="la-keyvalue-actions">
                        <button type="button" class="{{ $column }}-add btn btn-success btn-sm">
                            {!! admin_icon('fa-save') !!}&nbsp;{{ __('admin.new') }}
                        </button>
                    </td>
                </tr>
            </tfoot>
        </table>

        @include('admin::form.help-block')
    </div>
    <template class="{{$column}}-tpl">
        <tr>
            <td>
                <div class="form-group mb-0">
                    <input name="{{ $name }}[keys][]" class="form-control" required/>
                </div>
            </td>
            <td>
                <div class="form-group mb-0">
                    <input name="{{ $name }}[values][]" class="form-control" />
                </div>
            </td>

            <td class="la-keyvalue-actions">
                <button type="button" class="{{$column}}-remove btn btn-warning btn-sm">
                    {!! admin_icon('fa-trash') !!}&nbsp;{{ __('admin.remove') }}
                </button>
            </td>
        </tr>
    </template>
</div>
