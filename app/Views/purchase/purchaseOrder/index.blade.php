@extends('common.table')
@section('tableToolButtons')
    <div class="btn-group">
        <a class="btn btn-info" id="batchexamine">
            <i class="glyphicon glyphicon-ok-circle"></i> 批量审核
        </a>
    </div>
    <div class="btn-group">
        <a class="btn btn-success" href="{{ route(request()->segment(1).'.create') }}">
            <i class="glyphicon glyphicon-plus"></i> 新增
        </a>
    </div>
@stop{{-- 工具按钮 --}}
@section('tableHeader')
	<th><input type="checkbox" isCheck="true" id="checkall" onclick="quanxuan()"> 全选</th>
    <th>ID</th> 
    <th>采购单状态</th> 
   	<th>供应商</th>
    <th>采购去向</th>
    <th>结算状态</th>
    <th>异常类型</th>
    <th>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $purchaseOrder)
        <tr>
       		<td>
                @if($purchaseOrder->status >0)
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
            @foreach(config('purchase.purchaseOrder.active') as $k=>$active)
            	@if($purchaseOrder->active == $k)
            	<td>{{ $active }}</td>
                @endif
            @endforeach            
            <td>{{ $purchaseOrder->created_at }}</td>
            <td>
                <a href="{{ route('purchaseOrder.show', ['id'=>$purchaseOrder->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('purchaseOrder.edit', ['id'=>$purchaseOrder->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                 @if($purchaseOrder->status == 0)
                    <a href="javascript:" class="btn btn-info btn-xs examine_model"
                       data-id="{{ $purchaseOrder->id }}">
                        <span class="glyphicon glyphicon-check"></span> <span class='examine_{{$purchaseOrder->id}}'>审核</span>
                    </a>
                @else
                    <a href="javascript:" class="btn btn-info btn-xs has_check">
                        <span class="glyphicon glyphicon-check"></span> <span>已审核</span>
                    </a>
                @endif
                
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $purchaseOrder->id }}"
                   data-url="{{ route('purchaseOrder.destroy', ['id' =>$purchaseOrder->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop

@section('childJs')
    <script type="text/javascript">
        //单个审核
        $('.examine_model').click(function () {
            var purchase_id = $(this).data('id');
            if($(".examine_"+purchase_id).hasClass("hasexamine_"+purchase_id)){
                alert("该采购单已审核");return;
            }
            if (confirm("确认审核?")) {
                var url = "purchaseOrder/examinePurchaseOrder";
                $.ajax({
                    url:url,
                    data:{purchase_ids:purchase_id},
                    dataType:'json',
                    type:'get',
                    success:function(result){
                        if(result==1){
                            $(".examine_"+purchase_id).text("已审核");
                            $(".examine_"+purchase_id).addClass("hasexamine_"+purchase_id);
                       }else{
                            alert("审核失败");
                       }                     
                    }                  
                })
            }
        });

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