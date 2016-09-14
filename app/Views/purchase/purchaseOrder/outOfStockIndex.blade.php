@extends('common.table')
@section('tableToolButtons')
    <div class="btn-group">
        <a href="javascript:" class="btn btn-warning download">CSV导出
            <i class="glyphicon glyphicon-arrow-down"></i>
        </a>
    </div>
@stop{{-- 工具按钮 --}}
@section('tableHeader')
    <th>SKU号</th>
    <th>所属仓库</th>
    <th>物品名称</th>
    <th>在途</th>
    <th>特采在途</th>
    <th>欠货数量</th>
    <th>虚库存</th>
    <th>实库存</th>
    <th>最近采购</th>
    <th>缺货时间</th>
@stop

@section('tableBody')
    @foreach($data as $key=>$item)
    	@foreach($warehouses as $warehouse)
	        <tr>
	            <td>{{$item->sku}}</td>
	            <td>{{$warehouse->name}}</td>
	            <td>{{$item->c_name}}</td>
	            <td>{{$item->transit_quantity[$warehouse->id]['normal']}}</td>
	            <td>{{$item->transit_quantity[$warehouse->id]['special']}}</td>
	            <td>{{$item->out_of_stock}}</td>
	            <td>{{$item->warehouse_quantity[$warehouse->id]['available_quantity']}}</td>
	            <td>{{$item->warehouse_quantity[$warehouse->id]['all_quantity']}}</td>
	            <td>{{$item->recently_purchase_time}}</td>
	            <td></td>
	        </tr>
        @endforeach
    @endforeach
@stop

@section("childJs")
<script type='text/javascript'>
    $(document).ready(function(){
        $(document).on('click', '.download', function(){
            location.href="{{ route('purchase.exportOutOfStockCsv')}}";
        })
    })
</script>
@stop