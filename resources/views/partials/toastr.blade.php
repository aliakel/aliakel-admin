@if(Session::has('toastr'))
    @php
        $toastr = admin_flash_messages(Session::pull('toastr'));
        $type = \Illuminate\Support\Arr::get($toastr, 'type.0', 'success');
        $message = \Illuminate\Support\Arr::get($toastr, 'message.0', '');
        $options = \Illuminate\Support\Arr::get($toastr, 'options', []);
        $options = json_encode(is_array($options) && array_is_list($options) ? new \stdClass : $options);
    @endphp
    <script>
        $(function () {
            toastr.{{$type}}('{!!  $message  !!}', null, {!! $options !!});
        });
    </script>
@endif
