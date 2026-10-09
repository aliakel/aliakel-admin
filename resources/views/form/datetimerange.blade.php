<div class="{{$viewClass['form-group']}} {!! ($errors->has($errorKey['start']) || $errors->has($errorKey['end'])) ? 'has-error' : ''  !!}">

    <label for="{{$id['start']}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @if($errors->has($errorKey['start']) || $errors->has($errorKey['end']))
            @foreach(['start', 'end'] as $side)
                @if($errors->has($errorKey[$side]))
                    @foreach($errors->get($errorKey[$side]) as $message)
                        <label class="control-label" for="inputError">{!! admin_icon('fa-times-circle-o') !!} {{$message}}</label><br/>
                    @endforeach
                @endif
            @endforeach
        @endif

        <div class="la-range-inputs la-range-inputs-wide">
            <div class="input-group">
                <span class="input-group-addon">{!! admin_icon('fa-calendar') !!}</span>
                <input type="text"
                       name="{{$name['start']}}"
                       value="{{ old($column['start'], $value['start'] ?? null) }}"
                       class="form-control {{$class['start']}}"
                       autocomplete="off"
                       {!! $attributes !!}
                />
            </div>

            <span class="la-range-sep" aria-hidden="true">–</span>

            <div class="input-group">
                <span class="input-group-addon">{!! admin_icon('fa-calendar') !!}</span>
                <input type="text"
                       name="{{$name['end']}}"
                       value="{{ old($column['end'], $value['end'] ?? null) }}"
                       class="form-control {{$class['end']}}"
                       autocomplete="off"
                       {!! $attributes !!}
                />
            </div>
        </div>

        @include('admin::form.help-block')

    </div>
</div>
