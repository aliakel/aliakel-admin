<div id="embed-{{$column}}" class="la-embeds embed-{{$column}}">
    <div class="la-embeds-header">
        <h4 class="la-embeds-title">{{ $label }}</h4>
    </div>

    <div class="embed-{{$column}}-forms la-embeds-body">
        <div class="embed-{{$column}}-form fields-group">
            @foreach($form->fields() as $field)
                {!! $field->render() !!}
            @endforeach
        </div>
    </div>
</div>
