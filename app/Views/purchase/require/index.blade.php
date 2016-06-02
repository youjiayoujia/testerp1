@extends('common.table')
@section('tableToolButtons')
	 <div class="btn-group">
     <a class="btn btn-info" id="aKeyToGenerate">
             一键生成采购单
        </a>
        </div>
        <div class="btn-group">
        <a class="btn btn-info" id="checkPurchaseItem">
             批量生成采购单
        </a>
    </div>
@stop{{-- 工具按钮 --}}
@section('tableHeader')
    <th><input type="checkbox" isCheck="true" id="checkall" onclick="quanxuan()"> 全选-
    ID</th>
    <th>sku</th>
    <th>产品图片</th>
    <th>供应商</th>
    <th>采购去向</th>
    <th>订单需求</th>
    <th>库存数量</th>
    <th>采购中</th>
    <th>近30天销量</th>
    <th>近14天销量</th>
    <th>近7天销量</th>
    <th>建议采购数量</th>
    <th>趋势系数</th>
    <th>备注</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $requireItem)
        <tr>
            <td>
             @if($requireItem->purchase_order_id >0)
                <input type="checkbox" name="requireItem_id"  value="{{$requireItem->id}}" isexamine="1" >
                @else
                <input type="checkbox" name="requireItem_id"  value="{{$requireItem->id}}" isexamine="0" >
                @endif
            {{ $requireItem->id }}</td>
            <td>{{ $requireItem->sku}}</td>   
            <td>
             @if($requireItem->item->product->default_image>0)
             <img src="{{$requireItem->item->product->image->src}}" height="50px"/>
            @else
           该图片不存在
            @endif
            </td>
            <td>{{$requireItem->item->supplier->name}}</td>
            <td>{{ $requireItem->warehouse->name}}</td>
            <td>{{ $requireItem->order_need_num}}</td>
            <td>{{$requireItem->all_quantity}}</td>
            <td>{{$requireItem->purchaseing_quantity}}</td>
            <td>{{$requireItem->thirty_time}}</td>
            <td>{{$requireItem->fourteen_time}}</td>
            <td>{{$requireItem->seven_time}}</td>
            <td>{{$requireItem->ProposedpurchaseQuantity}}</td>
            <td>@if($requireItem->sell_status == 1)
            	上涨
            @elseif($requireItem->sell_status == 2)
            	下跌
            @elseif($requireItem->sell_status == 3)
            	持平
            @elseif($requireItem->sell_status == 4)
            	无销量
            @endif
            </td>
            <td>{{ $requireItem->remark}}</td>
            <td><a href="{{ route('require.show', ['id'=>$requireItem->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a></td> 
        </tr>
    @endforeach
 <script type="text/javascript">		 
	$('#checkPurchaseItem').click(function () {
            if (confirm("是否将选择的条目生成采购单?")) {
                var checkbox = document.getElementsByName("requireItem_id");
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
				if(purchase_ids){
                $.ajax({
                    url:'addPurchaseOrder',
                    data:{purchase_ids:purchase_ids},
                    dataType:'json',
                    type:'get',
                    success:function(result){
                        if(result==1){
							alert("已经成功生成采购单及采购条目！");
                        window.location.reload();
						}
                    }                    
                })
				}else{
					alert("请选择需要生成采购单的采购需求！");
					}
            }
        });
		$('#aKeyToGenerate').click(function(){
			var purchase_ids='';
			 $.ajax({
                    url:'addPurchaseOrder',
                    data:{purchase_ids:purchase_ids},
                    dataType:'json',
                    type:'get',
                    success:function(result){
						if(result==1){
							alert("已经成功生成采购单及采购条目！");
                        window.location.reload();
						}
                    }                    
                })
			});	 
	//全选
        function quanxuan()
        {
          var collid = document.getElementById("checkall");
          var coll = document.getElementsByName("requireItem_id");
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