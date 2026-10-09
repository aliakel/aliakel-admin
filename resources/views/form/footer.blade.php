<div class="flex flex-wrap items-center gap-3 border-t border-slate-200 px-5 py-4">

    {{ csrf_field() }}

    <div class="hidden md:block md:w-{{$width['label']}}/12"></div>

    <div class="flex w-full flex-wrap items-center gap-3 md:w-{{$width['field']}}/12">

        @if(in_array('submit', $buttons))
        <div class="btn-group ml-auto">
            <button type="submit" class="btn btn-primary">{{ trans('admin.submit') }}</button>
        </div>

        @foreach($submit_redirects as $value => $redirect)
            @if(in_array($redirect, $checkboxes))
            <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" class="after-submit" name="after-save" value="{{ $value }}" {{ ($default_check == $redirect) ? 'checked' : '' }}> {{ trans("admin.{$redirect}") }}
            </label>
            @endif
        @endforeach

        @endif

        @if(in_array('reset', $buttons))
        <div class="btn-group">
            <button type="reset" class="btn btn-warning">{{ trans('admin.reset') }}</button>
        </div>
        @endif
    </div>
</div>