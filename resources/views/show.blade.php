<div class="row">
    <div class="w-full px-3">
        {!! $panel !!}
    </div>

    <div class="w-full px-3">
        @foreach($relations as $relation)
            {!!  $relation->render() !!}
        @endforeach
    </div>
</div>