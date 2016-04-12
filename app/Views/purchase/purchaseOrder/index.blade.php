@extends('common.table')
@section('tableToolButtons')
    <div class="btn-group">
        <a class="btn btn-info" id="batchexamine">
            <i class="glyphicon glyphicon-ok-circle"></i> 批量审核
        </a>
    </div>
    <div class="btn-group" >
        <a href="/purchaseOrder/purchaseOrdersOut" class="btn btn-info" id="orderExcelOut"> 采购单导出
        </a>
    </div>
@stop{{-- 工具按钮 --}}
@section('tableHeader')
	<th><input type="checkbox" isCheck="true" id="checkall" onclick="quanxuan()"> 全选</th>
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
       		<td>
                @if($purchaseOrder->examineStatus >0)
                <input type="checkbox" name="purchaseOrder_id"  value="{{$purchaseOrder->id}}" isexamine="1" >
                @else
                <input type="checkbox" name="purchaseOrder_id"  value="{{$purchaseOrder->id}}" isexamine="0" >
                @endif
            </td>
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

<script type="text/javascript"> 
        $('.has_check').click(function () {
            alert("该产品已审核");
        });
        //批量审核
        $('#batchexamine').click(function () {
            if (confirm("确认审核?")) {
                var checkbox = document.getElementsByName("purchaseOrder_id");
                var purchase_ids = "";
                for (var i = 0; i < checkbox.length; i++) {
                    if(!checkbox[i].checked)continue;
                    if(checkbox[i].getAttribute('isexamine')==1){
                        alert("id为"+checkbox[i].value+"的采购单已经审核了");
                        return;
                    }
                    purchase_ids += checkbox[i].value+",";
                }
                purchase_ids = purchase_ids.substr(0,(purchase_ids.length)-1);
                $.ajax({
                    url:'purchaseOrder/examinePurchaseOrder',
                    data:{purchase_ids:purchase_ids},
                    dataType:'json',
                    type:'get',
                    success:function(result){
                        window.location.reload();
                    }                    
                })
            }
        });
		
		 //批量导出采购单
       /* $('#orderExcelOut').click(function () {
            if (confirm("确认导出这些采购单为一个excel?")) {
                var checkbox = document.getElementsByName("purchaseOrder_id");
                var purchase_ids = "";
                for (var i = 0; i < checkbox.length; i++) {
                    if(!checkbox[i].checked)continue;
                    if(checkbox[i].getAttribute('isexamine')==1){
                        alert("id为"+checkbox[i].value+"的采购单已经审核了");
                        return;
                    }
                    purchase_ids += checkbox[i].value+",";
                }
                purchase_ids = purchase_ids.substr(0,(purchase_ids.length)-1);
                $.ajax({
                    url:'purchaseOrder/examinePurchaseOrder',
                    data:{purchase_ids:purchase_ids},
                    dataType:'json',
                    type:'get',
                    success:function(result){
                        window.location.reload();
                    }                    
                })
            }
        });*/

        //全选
        function quanxuan()
        {
          var collid = document.getElementById("checkall");
          var coll = document.getElementsByName("purchaseOrder_id");
          if (collid.checked){
             for(var i = 0; i < coll.length; i++)
               coll[i].checked = true;
          }else{
             for(var i = 0; i < coll.length; i++)
               coll[i].checked = false;
          }
        }
    </script>
@stop