<div class="la-card mb-6 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm grid-box">
    @if(isset($title))
    <div class="border-b border-slate-200 px-4 py-3">
        <h3 class="text-base font-semibold text-slate-900"> {{ $title }}</h3>
    </div>
    @endif

    @if ( $grid->showTools() || $grid->showExportBtn() || $grid->showCreateBtn() )
    <div class="grid-toolbar flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 px-4 py-3">
        @if ( $grid->showTools() )
        <div class="flex flex-wrap items-center gap-2">
            {!! $grid->renderHeaderTools() !!}
        </div>
        @endif
        <div class="ml-auto flex flex-wrap items-center gap-2">
            {!! $grid->renderColumnSelector() !!}
            {!! $grid->renderExportButton() !!}
            {!! $grid->renderCreateButton() !!}
        </div>
    </div>
    @endif

    {!! $grid->renderFilter() !!}

    {!! $grid->renderHeader() !!}

    <!-- /.box-header -->
    <div class="grid-table-wrap">
        <table class="table table-hover grid-table" id="{{ $grid->tableID }}">
            <thead>
                <tr>
                    @foreach($grid->visibleColumns() as $column)
                    <th {!! $column->formatHtmlAttributes() !!}>{!! $column->getLabel() !!}{!! $column->renderHeader() !!}</th>
                    @endforeach
                </tr>
            </thead>

            @if ($grid->hasQuickCreate())
                {!! $grid->renderQuickCreate() !!}
            @endif

            <tbody>

                @if($grid->rows()->isEmpty() && $grid->showDefineEmptyPage())
                    @include('admin::grid.empty-grid')
                @endif

                @foreach($grid->rows() as $row)
                <tr {!! $row->getRowAttributes() !!}>
                    @foreach($grid->visibleColumnNames() as $name)
                    <td {!! $row->getColumnAttributes($name) !!}>
                        {!! $row->column($name) !!}
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>

            {!! $grid->renderTotalRow() !!}

        </table>

    </div>

    {!! $grid->renderFooter() !!}

    <div class="border-t border-slate-200 px-4 py-3">
        {!! $grid->paginator() !!}
    </div>
    <!-- /.box-body -->
</div>
