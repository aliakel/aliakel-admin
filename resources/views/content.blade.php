@extends('admin::index', ['header' => strip_tags($header)])

@section('content')
    @php
        $pageTitle = admin_translate_label($header ?: '') ?: ($header ?: trans('admin.title'));
        $crumbs = $breadcrumb ?: (config('admin.enable_default_breadcrumb') ? admin_default_breadcrumb() : []);
    @endphp
    <div class="border-b border-slate-200 bg-white px-6 py-4">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">
                    {!! $pageTitle !!}
                </h1>
                <p class="text-sm text-slate-500">{!! $description ?: trans('admin.description') !!}</p>
            </div>

            @if (!empty($crumbs))
            <ol class="breadcrumb">
                <li>
                    <a href="{{ admin_url('/') }}">
                        {!! admin_icon('fa-dashboard') !!}
                        {{ admin_translate_label('Dashboard') ?: __('Home') }}
                    </a>
                </li>
                @foreach($crumbs as $item)
                    @if($loop->last)
                        <li>
                            @if (\Illuminate\Support\Arr::has($item, 'icon'))
                                {!! admin_icon($item['icon']) !!}
                            @endif
                            {{ admin_translate_label($item['text'] ?? '') ?: ($item['text'] ?? '') }}
                        </li>
                    @else
                    <li>
                        @if (\Illuminate\Support\Arr::has($item, 'url'))
                            <a href="{{ admin_url(\Illuminate\Support\Arr::get($item, 'url')) }}">
                                @if (\Illuminate\Support\Arr::has($item, 'icon'))
                                    {!! admin_icon($item['icon']) !!}
                                @endif
                                {{ admin_translate_label($item['text'] ?? '') ?: ($item['text'] ?? '') }}
                            </a>
                        @else
                            @if (\Illuminate\Support\Arr::has($item, 'icon'))
                                {!! admin_icon($item['icon']) !!}
                            @endif
                            {{ admin_translate_label($item['text'] ?? '') ?: ($item['text'] ?? '') }}
                        @endif
                    </li>
                    @endif
                @endforeach
            </ol>
            @endif
        </div>
    </div>

    <div class="px-6 py-5">
        @include('admin::partials.alerts')
        @include('admin::partials.exception')
        @include('admin::partials.toastr')

        @if($_view_)
            @include($_view_['view'], $_view_['data'])
        @else
            {!! $_content_ !!}
        @endif
    </div>
@endsection
