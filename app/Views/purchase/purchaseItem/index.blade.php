@extends('common.table')
@section('tableToolButtons')
	 <div class="btn-group">
        <a class="btn btn-info" id="checkPurchaseItem">
            <i class="glyphicon glyphicon-ok-circle"></i> 批量生成采购单
        </a>
    </div>
    <div class="btn-group">
        <a class="btn btn-success" href="{{ route(request()->segment(1).'.create') }}">
            <i class="glyphicon glyphicon-plus"></i> 新增
        </a>
    </div>
@stop{{-- 工具按钮 --}}
@section('tableHeader')
    <th><input type="checkbox" isCheck="true" id="checkall" onclick="quanxuan()"> 全选-
    ID</th>
    <th>sku</th>
    <th>采购类型</th>
    <th>订单itemId</th>
    <th>产品图片</th>
    <th>供应商</th>
    <th>采购去向</th>
    <th>采购需求/采购数目/仍需采购</th>
    <th>shop</th>
    <th>采购条目状态</th>
    <th>是否生成采购单</th>
    <th>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $purchaseItem)
        <tr>
            <td>
             @if($purchaseItem->purchase_order_id >0)
                <input type="checkbox" name="purchaseItem_id"  value="{{$purchaseItem->id}}" isexamine="1" >
                @else
                <input type="checkbox" name="purchaseItem_id"  value="{{$purchaseItem->id}}" isexamine="0" >
                @endif
            {{ $purchaseItem->id }}</td>
            <td>{{ $purchaseItem->sku}}</td>
            @foreach(config('purchase.purchaseItem.type') as $k=>$type)
            	@if($purchaseItem->type == $k)
            	<td>{{ $type }}</td>
                @endif
            @endforeach
            <td>{{ $purchaseItem->order_item_id}}</td>
            <td>
             @if($purchaseItem->item->product->default_image>0)
             <img src="{{$purchaseItem->item->product->image->src}}" height="50px"/>
            @else
           该图片不存在
            @endif
             </td>
            <td>{{ $purchaseItem->supplier->name}}</td>
            <td>{{ $purchaseItem->warehouse->name}}</td>
            <td>{{ $purchaseItem->purchase_num}}/{{ $purchaseItem->arrival_num}}/{{ $purchaseItem->lack_num}}</td>
             @foreach(config('purchase.purchaseItem.channels') as $k=>$channel)
            	@if($purchaseItem->platform_id == $k)
            	<td>{{ $channel }}</td>
                @endif
            @endforeach
            @foreach(config('purchase.purchaseItem.status') as $k=>$status)
            	@if($purchaseItem->status == $k)
            	<td>{{ $status }}</td>
                @endif
            @endforeach
            	@if($purchaseItem->purchase_order_id)
            	<td>已生成采购单</td>
                @else
                <td>未生成采购单</td>
                @endif           
            <td>{{ $purchaseItem->created_at }}</td>
            <td>
                <a href="{{ route('purchaseItem.show', ['id'=>$purchaseItem->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('purchaseItem.edit', ['id'=>$purchaseItem->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                
            </td>
        </tr>
    @endforeach
   
  

 <script type="text/javascript">		 
	$('#checkPurchaseItem').click(function () {
            if (confirm("是否将选择的条目生成采购单?")) {
                var checkbox = document.getElementsByName("purchaseItem_id");
                var purchase_ids = "";
                for (var i = 0; i < checkbox.length; i++) {
                    if(!checkbox[i].checked)continue;
                    if(checkbox[i].getAttribute('isexamine')==1){
                        alert("id为"+checkbox[i].value+"的条目已经生成采购单了");
                        return;
                    }
                    purchase_ids += checkbox[i].value+",";
                }
                purchase_ids = purchase_ids.substr(0,(purchase_ids.length)-1);
                $.ajax({
                    url:'addPurchaseOrder',
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
          var coll = document.getElementsByName("purchaseItem_id");
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