@extends('common.table')
@section('tableToolButtons')
    <div class="btn-group" >
        <a href="/purchaseOrder/purchaseOrdersOut" class="btn btn-info" id="orderExcelOut"> 采购单导出
        </a>
    </div>
@stop{{-- 工具按钮 --}}
@section('tableHeader')
	
    <th>ID</th> 
    <th>采购单状态</th> 
    <th>采购单审核状态</th>
   	<th>供应商</th>
    <th>采购去向</th>
    <th>结算状态</th>
    <th>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $purchaseOrder)
        <tr>
       		
            <td>{{ $purchaseOrder->id }}</td> 
            @foreach(config('purchase.purchaseOrder.status') as $k=>$statu)
            	@if($purchaseOrder->status == $k)
            	<td>{{ $statu }}</td>
                @endif
            @endforeach 
            @foreach(config('purchase.purchaseOrder.examineStatus') as $k=>$statu)
            	@if($purchaseOrder->examineStatus == $k)
            	<td>{{ $statu }}</td>
                @endif
            @endforeach     
    		<td>
            @if($purchaseOrder->supplier_id >0)
            	{{ $purchaseOrder->supplier->name}}
            @endif
            </td>
            <td>{{ $purchaseOrder->warehouse->name}}</td>
             @foreach(config('purchase.purchaseOrder.close_status') as $k=>$close_statu)
            	@if($purchaseOrder->close_status == $k)
            	<td>{{ $close_statu}}</td>
                @endif
            @endforeach
                    
            <td>{{ $purchaseOrder->created_at }}</td>
            <td>
                <a href="{{ route('purchaseOrder.show', ['id'=>$purchaseOrder->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                @if($purchaseOrder->examineStatus == 2)
                <a href="{{ route('purchaseOrder.edit', ['id'=>$purchaseOrder->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span>去采购
                </a>
                @endif       
                
            </td>
        </tr>
    @endforeach


@stop