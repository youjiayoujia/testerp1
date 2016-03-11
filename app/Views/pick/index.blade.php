@extends('common.table')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('tableHeader')
    <th class='sort' data-field='id'>ID</th>
    <th>拣货单号</th>
    <th>类型</th>
    <th>状态</th>
    <th class='sort' data-field='created_at'>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $pick)
        <tr>
            <td>{{ $pick->id }}</td>
            <td>{{ $pick->pick_id }}</td>
            <td>{{ $pick->type == '0' ? '单单' : ($pick->type == '1' ? '单多' : '多多')}}
            <td>{{ $pick->status }}</td>
            <td>{{ $pick->created_at }}</td>
            <td>
                <a href="{{ route('pick.show', ['id'=>$pick->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="javascript:" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 打印拣货单
                </a>
                <a href="javascript:" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 分拣
                </a>
                <a href="javascript:" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 包装
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $pick->id }}"
                   data-url="{{ route('pick.destroy', ['id' => $pick->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')
<div class='col-lg-2'>
    <select name='type' class='form-control type'>
        <option value='oo'>单单</option>
        <option value='om'>单多</option>
        <option value='mm'>多多</option>
    </select>
</div>
<div class="btn-group">
    <a href="javascript:" class="btn btn-success createpick" >
        生成拣货单
    </a>
</div>
@stop
@section('childJs')
<script type='text/javascript'>
$(document).ready(function(){
    $('.type').change(function(){
        type = $(this).val();
        $.ajax({
            url:"{{ route('pick.type')}}",
            data:{type:type},
            dataType:'json',
            type:'get',
            success:function(result) {
                alert(result);
            }
        });
    });

    $('.createpick').click(function(){
        type = $('.type').val();
        $.ajax({
            url:"{{ route('pick.createpick')}}",
            data:{type:type},
            dataType:'json',
            type:'get',
            success:function(result) {
                alert(result);
            }
        });
    });
});
</script>   
@stop
