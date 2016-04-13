@extends('common.table')
@section('tableHeader')
    <th class='sort' data-field='id'>ID</th>
    <th>sku</th>
    <th>仓库</th>
    <th>库位</th>
    <th>期初数量</th>
    <th>期初金额</th>
    <th>期末数量</th>
    <th>期末金额</th>
    <th>结转时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $model)
        <tr>
            <td>{{ $model->id }}</td>
            <td>{{ $model->stock ? $model->stock->items ? $model->stock->items->sku : '' : '' }}</td>
            <td>{{ $model->stock ? $model->stock->warehouse ? $model->stock->warehouse->name : '' : '' }}</td>
            <td>{{ $model->stock ? $model->stock->position ? $model->stock->position->name : '' : '' }}</td>
            <td>{{ $model->begin_quantity }} </td>
            <td>{{ $model->begin_amount }}</td>
            <td>{{ $model->over_quantity }}</td>
            <td>{{ $model->over_amount }}</td>
            <td>{{ $model->carry_over_time }}</td>
            <td>
                <a href="{{ route('stockCarryOver.show', ['id'=>$model->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
            </td>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')
<div class="btn-group">
    <a class="btn btn-success createCarryOver" href="{{ route('stockCarryOver.showStock') }}">
        <i class="glyphicon glyphicon-plus"></i> 查看库存
    </a>
</div>
<div class="btn-group">
    <a class="btn btn-success createCarryOver" href="javascript:">
        <i class="glyphicon glyphicon-plus"></i> 生成节点
    </a>
</div>
@stop
@section('childJs')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
<script type='text/javascript'>
$(document).ready(function(){    
    $('.createCarryOver').click(function(){
        $.ajax({
            url: "{{route('stockCarryOver.ajaxCreateCarryOver')}}",
            data: {id:12},
            dataType: 'json',
            type: 'get',
            success: function(result){
                location.reload();
            }
        })
        
    });
});
</script>
@stop