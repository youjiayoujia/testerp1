@extends('common.table')
@section('tableToolButtons')
    <div class="btn-group" >
        <a href="{{route('printPurchaseOrder.create')}}" class="btn btn-info" id="orderExcelOut"> 打印采购单
        </a>
    </div>
@stop{{-- 工具按钮 --}}
@section('tableHeader')
	
    <th>ID</th> 
    <th>sku</th>
    <th>状态</th>
    <th>图片</th>
    <th>采购单ID</th> 
    <th>采购人</th>
   	<th>供应商</th>
    <th>采购去向</th>
    <th>采购数量</th>
    <th>采购链接</th>
@stop
@section('tableBody')
    @foreach($data as $purchaseItem)
        <tr>
       		
            <td>{{ $purchaseItem->id }}</td> 
            <td>{{$purchaseItem->sku}}</td>
            @foreach(config('purchase.purchaseItem.status') as $k=>$statu)
            	@if($purchaseItem->status == $k)
            	<td>{{ $statu }}</td>
                @endif
            @endforeach 
            
            	<td>
                 @if($purchaseItem->item->product->default_image>0)
             <img src="{{$purchaseItem->item->product->image->src}}" height="50px"/>
            @else
           该图片不存在
            @endif
                </td>
              <td>{{ $purchaseItem->purchase_order_id }}</td> 
              <td>{{ $purchaseItem->purchaseOrder->assigner}}</td>       
    		<td>
            @if($purchaseItem->supplier_id >0)
            	{{ $purchaseItem->supplier->name}}
            @endif
            </td>
            <td>{{ $purchaseItem->warehouse->name}}</td>
            	<td>{{ $purchaseItem->purchase_num}}</td>  
            <td>
               {{ $purchaseItem->supplier->url}}    
            </td>
        </tr>
    @endforeach


@stop