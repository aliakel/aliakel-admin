<style>
    .ext-icon {
        color: rgba(0,0,0,0.5);
        margin-left: 10px;
    }
    .installed {
        color: #00a65a;
        margin-right: 10px;
    }
</style>
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Available extensions</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse">{!! admin_icon('fa-minus') !!}
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove">{!! admin_icon('fa-times') !!}</button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <ul class="products-list product-list-in-box">

            @foreach($extensions as $extension)
            <li class="item">
                <div class="product-img">
                    {!! admin_icon($extension['icon'], 'feather-lg ext-icon') !!}
                </div>
                <div class="product-info">
                    <a href="{{ $extension['link'] }}" target="_blank" class="product-title">
                        {{ $extension['name'] }}
                    </a>
                    @if($extension['installed'])
                        <span class="pull-right installed">{!! admin_icon('fa-check') !!}</span>
                    @endif
                </div>
            </li>
            @endforeach

            <!-- /.item -->
        </ul>
    </div>
    <!-- /.box-body -->
    <div class="box-footer text-center">
        <a href="https://github.com/laravel-admin-extensions" target="_blank" class="uppercase">View All Extensions</a>
    </div>
    <!-- /.box-footer -->
</div>