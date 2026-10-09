@if(Session::has('toastr'))
    @php
        $toastr = admin_flash_messages(Session::pull('toastr'));
        $type = \Illuminate\Support\Arr::get($toastr, 'type.0', 'success');
        $message = \Illuminate\Support\Arr::get($toastr, 'message.0', '');
        if (is_string($message) && \Illuminate\Support\Facades\Lang::has($message)) {
            $message = __($message);
        }
        $options = \Illuminate\Support\Arr::get($toastr, 'options', []);
        if (is_array($options) && array_is_list($options)) {
            $options = [];
        }
        if (!is_array($options)) {
            $options = [];
        }
        if (empty($options['positionClass'])) {
            $options['positionClass'] = admin_is_rtl() ? 'toast-top-left' : 'toast-top-right';
        }
        $options = json_encode($options ?: new \stdClass);
    @endphp
    <script>
        $(function () {
            toastr.{{$type}}({!! json_encode($message, JSON_UNESCAPED_UNICODE) !!}, null, {!! $options !!});
        });
    </script>
@endif
