<div class="row">
    @foreach($fields as $field)
    <div class="w-full px-3 @if((int) $field['width'] < 12) md:w-{{ (int) $field['width'] }}/12 @endif">
        {!! $field['element']->render() !!}
    </div>
    @endforeach
</div>