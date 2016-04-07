@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>订单号</th>
    <th>状态</th>
    <th class="sort" data-field="logistic_assigned_at">分配物流时间</th>
    <th class="sort" data-field="printed_at">打印时间</th>
    <th class="sort" data-field="shipped_at">发货时间</th>
    <th class="sort" data-field="delivered_at">妥投时间</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $package)
        <tr>
            <td>{{ $package->id }}</td>
            <td>{{ $package->order ? $package->order->ordernum : '' }}</td>
            <td>{{ $package->status }}</td>
            <td>{{ $package->logistic_assigned_at }}</td>
            <td>{{ $package->printed_at }}</td>
            <td>{{ $package->shipped_at }}</td>
            <td>{{ $package->delivered_at }}</td>
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
                @if($package->is_auto == '0' && $package->status != 'SHIPPED')
                <a href="{{ route('package.manualLogistic', ['id'=>$package->id])}}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 手工发货
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
@section('childJs')
<script type='text/javascript'>
$(document).ready(function(){
    $('.send').click(function(){
        id = $(this).data('id');
        $.ajax({
            url:"{{ route('package.ajaxPackageSend')}}",
            data:{'id':id},
            dataType:'json',
            type:'get',
            success:function(result) {
                location.reload();
            }
        });
    });
});
</script>
@stop
