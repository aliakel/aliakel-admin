<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        <div class="feather-picker" data-feather-picker>
            <div class="input-group">
                <span class="input-group-addon feather-picker-preview">{!! admin_icon($iconName) !!}</span>
                <input {!! $attributes !!} data-feather-input />
            </div>
            <div class="feather-picker-panel" hidden>
                <input type="search" class="form-control" placeholder="Search icons" data-feather-search autocomplete="off">
                <div class="feather-picker-grid"></div>
            </div>
        </div>

        @include('admin::form.help-block')

    </div>
</div>
