@if($error = admin_flash_messages(session()->get('error')))
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4>{!! admin_icon('fa-ban') !!}{{ \Illuminate\Support\Arr::get($error, 'title.0') }}</h4>
        <p>{!!  \Illuminate\Support\Arr::get($error, 'message.0') !!}</p>
    </div>
@elseif ($errors = session()->get('errors'))
    @if ($errors->hasBag('error'))
      <div class="alert alert-danger alert-dismissable">

        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        @foreach($errors->getBag("error")->toArray() as $message)
            <p>{!!  \Illuminate\Support\Arr::get($message, 0) !!}</p>
        @endforeach
      </div>
    @endif
@endif

@if($success = admin_flash_messages(session()->get('success')))
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4>{!! admin_icon('fa-check') !!}{{ \Illuminate\Support\Arr::get($success, 'title.0') }}</h4>
        <p>{!!  \Illuminate\Support\Arr::get($success, 'message.0') !!}</p>
    </div>
@endif

@if($info = admin_flash_messages(session()->get('info')))
    <div class="alert alert-info alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4>{!! admin_icon('fa-info') !!}{{ \Illuminate\Support\Arr::get($info, 'title.0') }}</h4>
        <p>{!!  \Illuminate\Support\Arr::get($info, 'message.0') !!}</p>
    </div>
@endif

@if($warning = admin_flash_messages(session()->get('warning')))
    <div class="alert alert-warning alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4>{!! admin_icon('fa-warning') !!}{{ \Illuminate\Support\Arr::get($warning, 'title.0') }}</h4>
        <p>{!!  \Illuminate\Support\Arr::get($warning, 'message.0') !!}</p>
    </div>
@endif
