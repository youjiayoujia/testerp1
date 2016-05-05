@extends('common.table')
@section('tableHeader')
    <th><input type='checkbox' name='select_all' class='select_all'></th>
    <th class="sort" data-field="id">ID</th>
    <th>订单号</th>
    <th>仓库</th>
    <th>状态</th>
    <th>类型</th>
    <th>物流方式</th>
    <th>物流单号</th>
    <th class="sort" data-field="logistic_assigned_at">分配物流时间</th>
    <th class="sort" data-field="logistic_assigned_at">物流下单时间</th>
    <th class="sort" data-field="printed_at">打印时间</th>
    <th class="sort" data-field="shipped_at">发货时间</th>
    <th class="sort" data-field="delivered_at">妥投时间</th>
    <th>发货类型</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $package)
        <tr>
            <td><input type='checkbox' name='single[]' class='single'></td>
            <td>{{ $package->id }}</td>
            <td>{{ $package->order ? $package->order->ordernum : '' }}</td>
            <td>{{ $package->warehouse ? $package->warehouse->name : '' }}</td>
            <td>{{ $package->status_name }}</td>
            <td>{{ $package->type == 'SINGLE' ? '单单' : ($package->type == 'SINGLEMULTI' ? '单多' : '多多') }}</td>
            <td>{{ $package->logistics ? $package->logistics->short_code : '' }}</td>
            <td>{{ $package->tracking_no }}</td>
            <td>{{ $package->logistics_assigned_at }}</td>
            <td>{{ $package->logistics_order_at }}</td>
            <td>{{ $package->printed_at }}</td>
            <td>{{ $package->shipped_at }}</td>
            <td>{{ $package->delivered_at }}</td>
            <td>{{ $package->is_auto ? '自动' : '手动' }}</td>
            <td>{{ $package->created_at }}</td>
            <td>{{ $package->updated_at }}</td>
            <td>
                <a href="{{ route('package.show', ['id'=>$package->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('package.edit', ['id'=>$package->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                @if($package->status == 'PACKED')
                    <a href="javascript:" class="btn btn-warning btn-xs send" data-id="{{ $package->id }}">
                        <span class="glyphicon glyphicon-pencil"></span> 发货
                    </a>
                @endif
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $package->id }}"
                   data-url="{{ route('package.destroy', ['id' => $package->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="glyphicon glyphicon-filter"></i> 批量回传运费运单号
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="javascript:" class='returnTrackno' data-status='1'>回传运单号</a></li>
            <li><a href="javascript:" class='returnFee' data-type='1'>回传一次运费</a></li>
            <li><a href="javascript:" class='returnFee' data-type='2'>回传二次运费</a></li>
        </ul>
    </div>
    <div class="btn-group">
        <a class="btn btn-success export" href="javascript:">
            批量导出手工发货package信息
        </a>
    </div>
    <div class="btn-group">
        <a class="btn btn-success" href="{{ route('package.shipping') }}">
            执行发货
        </a>
    </div>
    <div class="btn-group">
        <a class="btn btn-success" href="{{ route('package.shippingStatistics') }}">
            发货统计
        </a>
    </div>
    @parent
@stop
@section('childJs')
    <script type='text/javascript'>
        $(document).ready(function () {
            $('.returnTrackno').click(function () {
                location.href = "{{ route('package.returnTrackno')}}";
            });

            $('.returnFee').click(function () {
                type = $(this).data('type');
                location.href = "{{ route('package.returnFee')}}?type=" + type;
            })

            $('.export').click(function () {
                arr = new Array();
                i = 0;
                $.each($('.single:checked'), function () {
                    tmp = $(this).parent().next().text();
                    arr[i] = tmp;
                    i++;
                })
                if (arr.length) {
                    location.href = "{{ route('package.exportManualPackage') }}?arr=" + arr.join('|');
                } else {
                    alert('未选择包裹信息');
                }
            });

            $('.select_all').click(function () {
                if ($(this).prop('checked') == true) {
                    $('.single').prop('checked', true);
                } else {
                    $('.single').prop('checked', false);
                }
            });

            $('.send').click(function () {
                id = $(this).data('id');
                $.ajax({
                    url: "{{ route('package.ajaxPackageSend')}}",
                    data: {'id': id},
                    dataType: 'json',
                    type: 'get',
                    success: function (result) {
                        location.reload();
                    }
                });
            });
        });
    </script>
@stop
