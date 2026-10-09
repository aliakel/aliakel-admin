<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ admin_is_rtl() ? 'rtl' : 'ltr' }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{config('admin.title')}} | {{ trans('admin.login') }}</title>

  @if(!is_null($favicon = Admin::favicon()))
  <link rel="shortcut icon" href="{{$favicon}}">
  @endif

  <link rel="stylesheet" href="{{ admin_asset("vendor/laravel-admin/laravel-admin/admin.css") }}">
  @if(admin_is_rtl())
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap">
  @endif
</head>
<body class="flex min-h-screen items-center justify-center bg-slate-100 px-4 {{ admin_is_rtl() ? 'font-cairo' : '' }}" data-skin="{{ config('admin.skin') }}" @if(config('admin.login_background_image'))style="background: url({{config('admin.login_background_image')}}) no-repeat;background-size: cover;"@endif>
  <div class="w-full max-w-sm rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
    @if(count($langs = admin_langs()) > 1)
    <div class="mb-4 flex flex-wrap justify-center gap-3 text-sm">
        @foreach($langs as $code => $label)
            <a class="la-locale {{ app()->getLocale() === $code ? 'font-semibold text-sky-700' : 'text-slate-500' }}" href="{{ admin_url('locale/'.$code) }}">{{ $label }}</a>
        @endforeach
    </div>
    @endif
    <a href="{{ admin_url('/') }}" class="mb-1 block text-center text-2xl font-semibold text-slate-900">{{config('admin.name')}}</a>
    <p class="mb-6 text-center text-sm text-slate-500">{{ trans('admin.login') }}</p>

    <form action="{{ admin_url('auth/login') }}" method="post" class="space-y-4">
      <div>
        @if($errors->has('username'))
          @foreach($errors->get('username') as $message)
            <p class="mb-1 text-sm text-red-600">{!! admin_icon('fa-times-circle-o') !!} {{$message}}</p>
          @endforeach
        @endif
        <input type="text" class="form-control" placeholder="{{ trans('admin.username') }}" name="username" value="{{ old('username') }}">
      </div>
      <div>
        @if($errors->has('password'))
          @foreach($errors->get('password') as $message)
            <p class="mb-1 text-sm text-red-600">{!! admin_icon('fa-times-circle-o') !!} {{$message}}</p>
          @endforeach
        @endif
        <input type="password" class="form-control" placeholder="{{ trans('admin.password') }}" name="password">
      </div>
      <div class="flex items-center justify-between gap-3">
        @if(config('admin.auth.remember'))
        <label class="inline-flex items-center gap-2 text-sm text-slate-600">
          <input type="checkbox" name="remember" value="1" {{ (!old('username') || old('remember')) ? 'checked' : '' }}>
          {{ trans('admin.remember_me') }}
        </label>
        @endif
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button type="submit" class="btn btn-primary">{{ trans('admin.login') }}</button>
      </div>
    </form>
  </div>
</body>
</html>
