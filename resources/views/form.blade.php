<div class="la-card mb-6 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    {!! $form->open() !!}

    <div class="flex items-center justify-between gap-3 border-b border-slate-200 px-5 py-4">
        <h3 class="text-base font-semibold text-slate-900">{{ $form->title() }}</h3>

        <div class="flex flex-wrap items-center justify-end gap-2">
            {!! $form->renderHeaderSubmit() !!}
            {!! $form->renderTools() !!}
        </div>
    </div>

    <div class="p-5">

        @if(!$tabObj->isEmpty())
            @include('admin::form.tab', compact('tabObj'))
        @else
            <div class="fields-group flex flex-wrap">

                @if($form->hasRows())
                    @foreach($form->getRows() as $row)
                        {!! $row->render() !!}
                    @endforeach
                @else
                    @if($form->columnCount() > 1 && $layout->columns()->count() <= 1)
                        <div class="la-form-columns la-form-columns-{{ $form->columnCount() }}">
                            @foreach($layout->columns()->first()->fields() as $field)
                                <div class="la-form-column">
                                    {!! $field->render() !!}
                                </div>
                            @endforeach
                        </div>
                    @else
                        @foreach($layout->columns() as $column)
                            <div class="w-full px-3 @if((int) $column->width() < 12) md:w-{{ (int) $column->width() }}/12 @endif">
                                @foreach($column->fields() as $field)
                                    {!! $field->render() !!}
                                @endforeach
                            </div>
                        @endforeach
                    @endif
                @endif
            </div>
        @endif

    </div>
    <!-- /.box-body -->

    {!! $form->renderFooter() !!}

    @foreach($form->getHiddenFields() as $field)
        {!! $field->render() !!}
    @endforeach

<!-- /.box-footer -->
    {!! $form->close() !!}
</div>
