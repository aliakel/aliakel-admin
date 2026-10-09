<div class="{{$viewClass['form-group']}} {!! ($errors->has($errorKey['start'].'start') || $errors->has($errorKey['end'].'end')) ? 'has-error' : ''  !!}">

    <label for="{{$id['start']}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        <div class="la-range-inputs">
            <div class="input-group">
                <span class="input-group-addon">{!! admin_icon('fa-calendar') !!}</span>
                <input type="text" name="{{$name['start']}}" value="{{ old($column['start'], $value['start'] ?? null) }}" class="form-control {{$class['start']}}" autocomplete="off" {!! $attributes !!} />
            </div>

            <span class="la-range-sep" aria-hidden="true">–</span>

            <div class="input-group">
                <span class="input-group-addon">{!! admin_icon('fa-calendar') !!}</span>
                <input type="text" name="{{$name['end']}}" value="{{ old($column['end'], $value['end'] ?? null) }}" class="form-control {{$class['end']}}" autocomplete="off" {!! $attributes !!} />
            </div>
        </div>

        @include('admin::form.help-block')

    </div>
</div>
