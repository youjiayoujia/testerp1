@extends('common.table')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('tableHeader')
    <th class="sort" data-url="{{ Sort::url('id') }}">ID{!! Sort::label('id') !!}</th>
    <th class="sort" data-url="{{ Sort::url('adjust_form_id') }}">调整单号{!! Sort::label('adjust_form_id') !!}</th>
    <th>仓库</th>
    <th>备注</th>
    <th>调整人</th>
    <th>调整时间</th>
    <th>状态</th>
    <th>审核人</th>
    <th class="sort" data-url="{{ Sort::url('check_time') }}">审核时间{!! Sort::label('check_time') !!}</th>
    <th class="sort" data-url="{{ Sort::url('created_at') }}">创建时间{!! Sort::label('created_at') !!}</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($adjusts as $adjust)
        <tr>
            <td>{{ $adjust->id }}</td>
            <td>{{ $adjust->adjust_form_id }}</td>
            <td>{{ $adjust->warehouse->name }}</td>
            <td>{{ $adjust->remark }}</td>
            <td>{{ $adjust->adjust_man_id }} </td>
            <td>{{ $adjust->adjust_time }}</td>
            <td>{{ $adjust->status == 'Y' ? '已审核' : '未审核' }}</td>
            <td>{{ $adjust->check_man_id }}</td>
            <td>{{ $adjust->check_time }}</td>
            <td>{{ $adjust->created_at }}</td>
            <td>
                <a href="{{ route('stockAdjustment.show', ['id'=>$adjust->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('stockAdjustment.edit', ['id'=>$adjust->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:"  class="btn btn-info btn-xs check_time" data-id="{{ $adjust->id }}">
                    <span class="glyphicon glyphicon-pencil"></span>审核
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $adjust->id }}"
                   data-url="{{ route('stockAdjustment.destroy', ['id' => $adjust->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop

<script type='text/javascript'>
$(document).ready(function(){    
    $('.check_time').click(function(){
        if($(this).parent().prev().prev().prev().prev().text() == '未审核') {
            if(confirm('确认审核?')) {
                tmp = $(this);
                id = tmp.data('id');
                $.ajax({
                    url:"{{ route('check') }}",
                    data:{id:id},
                    dataType:'json',
                    type:'get',
                    success:function(result){
                        check = tmp.parent().prev().prev();
                        check.text(result);
                        check.prev().prev().text('已审核');
                    }
                });
            }
        } else {
            alert('已审核');
        }
    });
});
</script>