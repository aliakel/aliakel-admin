<div class="la-subcard form-group">
    @if($title || $description)
        <div class="la-subcard-header">
            @if($title)
                <h4 class="la-subcard-title">{{ $title }}</h4>
            @endif
            @if($description)
                <p class="la-subcard-description">{{ $description }}</p>
            @endif
        </div>
    @endif
    <div class="la-subcard-body">
        @if(!empty($fields))
            @foreach($fields as $field)
                {!! $field->render() !!}
            @endforeach
        @elseif(!empty($content))
            {!! $content !!}
        @endif
    </div>
</div>
