@extends('common.table')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('tableHeader')
    <th class="sort" data-url="{{ Sort::url('id') }}">ID{!! Sort::label('id') !!}</th>
    <th class="sort" data-url="{{ Sort::url('allotment_id') }}">调拨单号{!! Sort::label('allotment_id') !!}</th>
    <th>调出仓库</th>
    <th>调入仓库</th>
    <th>备注</th>  
    <th>调拨人</th>
    <th>调拨时间</th>
    <th>调拨状态</th>
    <th class="sort" data-url="{{ Sort::url('available_amount') }}">审核人{!! Sort::label('available_amount') !!}</th>
    <th>审核状态</th>
    <th class="sort" data-url="{{ Sort::url('hold_amount') }}">审核时间{!! Sort::label('hold_amount') !!}</th>
    <th>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $allotment)
        <tr>
            <td>{{ $allotment->id }}</td>
            <td>{{ $allotment->allotment_id }}</td>
            <td>{{ $allotment->outwarehouse->name }}</td>
            <td>{{ $allotment->inwarehouse->name }}</td>
            <td>{{ $allotment->remark }}</td>
            <td>{{ $allotment->allotment_man_id }}</td>
            <td>{{ $allotment->allotment_time }}</td>
            <td>{{ $allotment->status_name }}</td>
            <td>{{ $allotment->check_man_id }}</td>
            <td>{{ $allotment->check_status == 'N' ? '未审核' : '已审核' }}</td>
            <td>{{ $allotment->check_time }}</td>
            <td>{{ $allotment->created_at }}</td>
            <td>
                <a href="{{ route('stockAllotment.show', ['id'=>$allotment->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('stockAllotment.edit', ['id'=>$allotment->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-success btn-xs check_time" data-id="{{ $allotment->id }}">
                    <span class="glyphicon glyphicon-pencil"></span>审核
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $allotment->id }}"
                   data-url="{{ route('stockAllotment.destroy', ['id' => $allotment->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop

<script type='text/javascript'>
$(document).ready(function(){    
    $('.check_time').click(function(){
        if($(this).parent().prev().prev().prev().text() == '未审核') {
            if(confirm('确认审核?')) {
                tmp = $(this);
                id = tmp.data('id');
                $.ajax({
                    url:"{{ route('allotmentcheck') }}",
                    data:{id:id},
                    dataType:'json',
                    type:'get',
                    success:function(result){
                        check = tmp.parent().prev().prev();
                        check.text(result);
                        check.prev().text('已审核');
                    }
                });
            }
        } else {
            alert('已审核');
        }
    });
});
</script>