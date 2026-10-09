@if($help)
<span class="help-block">
    {!! admin_icon(\Illuminate\Support\Arr::get($help, 'icon')) !!}&nbsp;{!! \Illuminate\Support\Arr::get($help, 'text') !!}
</span>
@endif