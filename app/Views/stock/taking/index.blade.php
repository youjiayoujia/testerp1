@extends('common.table')
@section('tableHeader')
    <th class='sort' data-field='id'>ID</th>
    <th>盘点表id</th>
    <th>盘点人</th>
    <th>盘点时间</th>
    <th class='sort' data-field='created_at'>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $taking)
        <tr>
            <td>{{ $taking->id }}</td>
            <td>{{ $taking->taking_id}}</td>
            <td>{{ $taking->stockTakingByName ? $taking->stockTakingByName->name : '' }}</td>
            <td>{{ $taking->stock_taking_time }}</td>
            <td>{{ $taking->created_at }}</td>
            <td>
                <a href="{{ route('stockTaking.show', ['id'=>$taking->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                @if($taking->stockTakingAdjustment ? $taking->stockTakingAdjustment->check_status != '1' : '1')
                    <a href="{{ route('stockTaking.edit', ['id'=>$taking->id]) }}" class="btn btn-warning btn-xs">
                        <span class="glyphicon glyphicon-pencil"></span> 录入实盘
                    </a>
                @endif
                @if(($taking->stockTakingAdjustment ? $taking->stockTakingAdjustment->check_status != '1' : '1') && $taking->create_taking_adjustment == '1')
                    <a href="javascript:" class='btn btn-info btn-xs check'>
                        <span class="glyphicon glyphicon-eye-open"></span> 生成调整单
                    </a>
                @endif
                @if($taking->stockTakingAdjustment ? $taking->stockTakingAdjustment->check_status != '1' : '1')
                    <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                       data-id="{{ $taking->id }}"
                       data-url="{{ route('stockTaking.destroy', ['id' => $taking->id]) }}">
                        <span class="glyphicon glyphicon-trash"></span> 删除
                    </a>
                @endif
            </td>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')
    <div class="btn-group">
        <a class="btn btn-success" href="{{ route('createtaking') }}">
            生成盘点表
        </a>
    </div>
@stop
@section('childJs')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
<script type='text/javascript'>
$(document).ready(function(){   
    $(document).on('click', '.check', function(){
        block = $(this).parent().parent();
        id = block.find('td:eq(0)').text();
        $.ajax({
            url:"{{route('takingcheck')}}",
            data:{id:id},
            dataType:'json',
            type:'get',
            success:function(result) {
                location.href="{{route('stockTakingAdjustment.index')}}";
            }
        })
    });
});
</script>
@stop