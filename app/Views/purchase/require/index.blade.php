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
    SKUID</th>
    <th>sku</th>
    <th>中文名</th>
    <th class="sort" data-field="available_quantity">可用库存</th>
    <th class="sort" data-field="all_quantity">实库存</th>
    <th class="sort" data-field="zaitu_num">在途</th>
    <th class="sort" data-field="thirty_sales">近30天销量</th>
    <th class="sort" data-field="fourteen_sales">近14天销量</th>
    <th class="sort" data-field="seven_sales">近7天销量</th>
    <th class="sort" data-field="need_purchase_num">建议采购数量</th>
    <th>趋势系数</th>
    <th>平均利润率</th>
    <th class="sort" data-field="refund_rate">退款率</th>
    <th>SKU状态</th>
    <th>采购状态</th>
    <th>采购采购员</th>
@stop
@section('tableBody')
    @foreach($data as $item)
        <tr>
            <td>
             @if($item->require_create !="1")
                <input type="checkbox" name="requireItem_id"  value="{{$item->item_id}}" isexamine="1" >
                @else
                <input type="checkbox" name="requireItem_id"  value="{{$item->item_id}}" isexamine="0" >
                @endif
            {{ $item->item_id }}</td>
            <td>{{ $item->sku}}</td>   
            <td>
            {{$item->c_name}}
            </td>
            
            <td>{{$item->available_quantity}}</td>
            <td>{{$item->all_quantity}}</td>
            <td>{{$item->zaitu_num}}</td>
            <td>{{$item->thirty_sales}}</td>
            <td>{{$item->fourteen_sales}}</td>
            <td>{{$item->seven_sales}}</td>
            <td>{{$item->need_purchase_num>0?$item->need_purchase_num:0}}</td>
            <td>@if($item->thrend == 1)
            	上涨
            @elseif($item->thrend == 2)
            	下跌
            @elseif($item->thrend == 3)
            	无销量
            @elseif($item->thrend == 4)
            	持平
            @endif
            </td>
            <td>{{$item->profit*100}}%</td>
            <td>{{$item->refund_rate*100}}%</td>
            <td>{{config('item.status')[$item->status]}}</td>
            <td>{{config('purchase.require')[$item->require_create]}}</td>
            <td>{{$item->user?$item->user->name:''}}</td>
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
                        alert("id为"+checkbox[i].value+"的条目无需生成采购单");
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