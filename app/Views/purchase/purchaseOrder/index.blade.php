@extends('common.table')
@section('tableToolButtons')
    <div class="btn-group" >
        <a href="/purchaseOrder/purchaseOrdersOut" class="btn btn-info" id="orderExcelOut"> 采购单导出
        </a>
    </div>
@stop{{-- 工具按钮 --}}
@section('tableHeader')
	
    <th>ID</th> 
    <th>采购单信息</th> 
    <th>采购单审核状态</th>
    <th>采购人</th>
   	<th>供应商</th>
    <th>采购物品</th>
    <th>采购去向</th>
    <th>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $purchaseOrder)
        <tr>
       		
            <td>单据号：NO.{{ $purchaseOrder->id }}</br>
            	付款方式：{{$purchaseOrder->supplier->pay_type}}</br>
                外部单号：@if($purchaseOrder->purchase_post_num > 0) {{$purchaseOrder->purchase_post->post_coding}} @else 暂无单号 @endif
            </td>
           <td> @foreach(config('purchase.purchaseOrder.status') as $k=>$statu)
            	@if($purchaseOrder->status == $k)
            	{{ $statu }}
                @endif
            @endforeach </td>
            @foreach(config('purchase.purchaseOrder.examineStatus') as $k=>$statu)
            	@if($purchaseOrder->examineStatus == $k)
            	<td>{{ $statu }}</td>
                @endif
            @endforeach     
    		<td>{{ $purchaseOrder->assigner_name }}
            </td>
            <td>
            
            @if($purchaseOrder->supplier_id >0)
            @foreach(config('purchase.purchaseOrder.close_status') as $k=>$close_statu)
            	@if($purchaseOrder->close_status == $k)
            	{{ $close_statu}}
                @endif
            @endforeach
            	</br>供应商编号NO.{{ $purchaseOrder->supplier->id}}
            @endif
            </td>
            <td>
            @if($purchaseOrder->status <4)
                <table class="table table-bordered table-striped table-hover sortable">
                <thead>
                <tr>
                <th >sku</th>
                <th>名称</th>
                <th>采购数量</th>
                <th>已到货数量</th>
                <th>入库数量</th>
                <th>不合格</th>
                <th>预计到货日期</th>
                <th>实际到货日期</th>
                <th>状态</th>
                <th>单价</th>
                <th>系统采购价格</th>
                <th>小计</th>
                <th>入库金额</th>
                <th>审单备注</th>
                </tr>
                </thead>
                <tbody>
                @foreach($purchaseOrder->purchase_items as $purchase_item)
                <tr>
                <td>{{$purchase_item->sku}}</td>
                <td>{{$purchase_item->item->c_name}}</td>
                <td>{{$purchase_item->purchase_num}}</td>
                <td>{{$purchase_item->arrival_num}}</td>
                <td>{{$purchase_item->storage_qty}}</td>
                <td>{{$purchase_item->active_num}}</td>
                <td>{{$purchase_item->start_buying_time}}</td>
                <td>{{$purchase_item->arrival_time}}</td>
                <td>{{$purchase_item->status}}</td>
                <td>{{$purchase_item->purchase_cost}}</td>
                <td>{{$purchase_item->item->purchase_price}}</td>
                <td>{{$purchase_item->purchase_cost * $purchase_item->purchase_num}}</td>
                <td>{{$purchase_item->purchase_cost * $purchase_item->storage_qty}}</td>
                <td>{{$purchase_item->remark}}</td>
                </tr>
                @endforeach
                <tr>
                <th>合计</th>
                <th>&nbsp;</th>
                <th>{{ $purchaseOrder->sum_purchase_num}}</th>
                <th>{{ $purchaseOrder->sum_arrival_num}}</th>
                <th>{{ $purchaseOrder->sum_storage_qty}}</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>{{ $purchaseOrder->sum_purchase_account}}</th>
                <th>{{ $purchaseOrder->sum_purchase_storage_account}}</th>
                </tr>
                </tbody>
                </table>
                @endif
            </td>
            <td>{{ $purchaseOrder->warehouse->name}}</td>
                  
            <td>{{ $purchaseOrder->created_at }}</td>
            <td>
            	<a href="{{ route('purchaseOrder.edit', ['id'=>$purchaseOrder->id]) }}" title="审核" class="btn btn-info btn-xs">
                     <span class="glyphicon glyphicon-ok-sign"></span>
                </a>
                <a href="{{ route('purchaseOrder.show', ['id'=>$purchaseOrder->id]) }}"  title="详情"  class="btn btn-info btn-xs">
                     <span class="glyphicon glyphicon-eye-open"></span>  
                </a>
                 <a href="{{ route('purchaseOrder.edit', ['id'=>$purchaseOrder->id]) }}" title="修改" class="btn btn-warning btn-xs">
                   <span class="glyphicon glyphicon-pencil"></span>
                </a>
                 <a href="{{ route('purchaseOrder.edit', ['id'=>$purchaseOrder->id]) }}" title="核销" class="btn btn-success btn-xs">
                     <span class="glyphicon glyphicon-yen"></span>
                </a>
                 <a href="/purchaseOrder/cancelOrder/{{$purchaseOrder->id}}" title="退回" class="btn btn-danger btn-xs">
                    <span class="glyphicon glyphicon-remove-sign"></span>
                </a>
				<a href="{{ route('purchaseOrder.show', ['id'=>$purchaseOrder->id]) }}" title="打印" class="btn btn-primary btn-xs">
                    <span class="glyphicon glyphicon-print"></span>
                </a>                       
                
            </td>
        </tr>
    @endforeach


@stop