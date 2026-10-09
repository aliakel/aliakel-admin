<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Environment</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse">{!! admin_icon('fa-minus') !!}
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove">{!! admin_icon('fa-times') !!}</button>
        </div>
    </div>

    <!-- /.box-header -->
    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-striped">

                @foreach($envs as $env)
                <tr>
                    <td width="120px">{{ $env['name'] }}</td>
                    <td>{{ $env['value'] }}</td>
                </tr>
                @endforeach
            </table>
        </div>
        <!-- /.table-responsive -->
    </div>
    <!-- /.box-body -->
</div>