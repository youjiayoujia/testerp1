@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>拣货单号</th>
    <th>拣货单类型</th>
    <th>package ID</th>
    <th>处理状态</th>
    <th>处理人</th>
    <th>处理时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $model)
        <tr>
            <td>{{ $model->id }}</td>
            <td>{{ $model->picklist ? $model->picklist->picknum : ''}}</td>
            <td>{{ $model->picklist ? ($model->picklist->type == 'SINGLE' ? '单单' : ($model->picklist->type == 'MULTI' ? '多多' : '单多')) : '' }} </td>
            <td>{{ $model->package ? $model->package->id : '' }}</td>
            <td>{{ $model->status ? '已处理' : '未处理' }}</td>
            <td>{{ $model->processByName ? $model->processByName->name : '' }}</td>
            <td>{{ $model->process_time }}</td>
            <td>
                <a href="{{ route('errorList.show', ['id'=>$model->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                @if(!$model->status)
                <a href="javascript:" data-id="{{ $model->id}}" class="btn btn-warning btn-xs process">
                    <span class="glyphicon glyphicon-pencil"></span> 处理
                </a>
                @endif
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $model->id }}"
                   data-url="{{ route('errorList.destroy', ['id' => $model->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
@section("childJs")
<script type='text/javascript'>
    $(document).ready(function(){
        $('.process').click(function(){
            id = $(this).data('id');
            $.ajax({
                url:"{{ route('errorList.ajaxProcess') }}",
                data:{id:id},
                dataType:'json',
                type:'get',
                success:function(result) {
                    if(result) {
                        location.reload();
                    } else {
                        alert('该记录有问题');
                    }
                }
            })
        });
    })
</script>
@stop
@section('tableToolButtons')@stop