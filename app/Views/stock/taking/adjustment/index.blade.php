@extends('common.table')
@section('tableHeader')
    <th class='sort' data-field='id'>ID</th>
    <th>调整单号</th>
    <th>调整人</th>
    <th>调整时间</th>
    <th>审核人</th>
    <th>审核状态</th>
    <th>审核时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $takingAdjustment)
        <tr>
            <td>{{ $takingAdjustment->id }}</td>
            <td>{{ $takingAdjustment->taking ? $takingAdjustment->taking->taking_id : '' }}</td>
            <td>{{ $takingAdjustment->adjustmentByName ? $takingAdjustment->adjustmentByName->name : '' }}</td>
            <td>{{ $takingAdjustment->adjustment_time }}</td>
            <td>{{ $takingAdjustment->checkByName ? $takingAdjustment->checkByName->name : '' }}</td>
            <td>{{ $takingAdjustment->check_status == '0' ? '未审核' : '已审核' }}</td>
            <td>{{ $takingAdjustment->check_time }}</td>
            <td>
                <a href="{{ route('stockTakingAdjustment.show', ['id'=>$takingAdjustment->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                @if($takingAdjustment->check_status == '0')
                    <a href="{{ route('takingadjustmentcheck', ['id'=>$takingAdjustment->id])}}" class="btn btn-info btn-xs check">
                        <span class="glyphicon glyphicon-eye-open"></span> 审核
                    </a>
                @endif
                @if($takingAdjustment->check_status != '1')
                    <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                       data-id="{{ $takingAdjustment->id }}"
                       data-url="{{ route('stockTakingAdjustment.destroy', ['id' => $takingAdjustment->id]) }}">
                        <span class="glyphicon glyphicon-trash"></span> 删除
                    </a>
                @endif
            </td>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')
    <div class="btn-group">
        <a class="btn btn-success quantitycheck" href="">
            批量审核
        </a>
    </div>
@stop
@section('childJs')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
<script type='text/javascript'>
$(document).ready(function(){   
    $(document).on('click', '.check', function(){
        block = $(this).parent().parent();
        id = block.find('td:eq(1)').text();
        $.ajax({
            url:"{{route('takingadjustmentcheck')}}",
            data:{id:id},
            dataType:'json',
            type:'get',
            success:function(result) {
                location.reload();
            }
        })
    });
});
</script>
@stop