<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        <div class="la-richtext" data-upload="{{ admin_url('richtext/image') }}">
            <div id="{{$id}}-editor"></div>
        </div>
        <textarea id="{{$id}}" name="{{$name}}" class="hide">{{ old($column, $value) }}</textarea>

        @include('admin::form.help-block')

    </div>
</div>
