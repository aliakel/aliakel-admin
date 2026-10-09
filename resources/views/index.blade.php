<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ admin_is_rtl() ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ Admin::title() }} @if($header) | {{ $header }}@endif</title>
    <meta content="width=device-width, initial-scale=1" name="viewport">

    @if(!is_null($favicon = Admin::favicon()))
    <link rel="shortcut icon" href="{{$favicon}}">
    @endif

    {!! Admin::css() !!}
    @if(admin_is_rtl())
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap">
    @endif

    <script src="{{ Admin::jQuery() }}"></script>
    {!! Admin::headerJs() !!}
</head>

<body class="bg-slate-100 text-slate-800 antialiased {{ admin_is_rtl() ? 'font-cairo' : '' }} {{ in_array('sidebar-collapse', config('admin.layout', []), true) ? 'sidebar-collapsed' : '' }}" data-skin="{{ config('admin.skin') }}"@if($menuActiveColor = config('admin.menu_active_color')) style="--la-menu-active-text: {{ $menuActiveColor }}"@endif>

@if($alert = config('admin.top_alert'))
    <div class="bg-amber-100 px-3 py-1 text-center text-xs text-red-600">
        {!! $alert !!}
    </div>
@endif

<aside class="la-sidebar">
    @include('admin::partials.sidebar')
</aside>

<div class="la-main">
    @include('admin::partials.header')

    <div id="pjax-container">
        {!! Admin::style() !!}
        <div id="app">
        @yield('content')
        </div>
        {!! Admin::script() !!}
        {!! Admin::html() !!}
    </div>

    @include('admin::partials.footer')
</div>

<button id="totop" title="Go to top" style="display: none;">{!! admin_icon('chevron-up') !!}</button>

<script>
    function LA() {}
    LA.token = "{{ csrf_token() }}";
    LA.user = @json($_user_);
    LA.featherSprite = @json(\AliAkel\Admin\Icons\Feather::spriteUrl());
</script>

{!! Admin::js() !!}

</body>
</html>
