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
    <th>skuID</th>
    <th>sku</th>
    <th>中文名</th>
    <th>可用库存</th>
    <th>实库存</th>
    <th>在途</th>
    <th>近30天销量</th>
    <th>近14天销量</th>
    <th>近7天销量</th>
    <th>建议采购数量</th>
    <th>趋势系数</th>
    <th>平均利润率</th>
    <th>退款率</th>
    <th>状态</th>
@stop
@section('tableBody')
    @foreach($data as $item)
        <tr>
            <td>
             @if($item->status !="selling")
                <input type="checkbox" name="requireItem_id"  value="{{$item->id}}" isexamine="1" >
                @else
                <input type="checkbox" name="requireItem_id"  value="{{$item->id}}" isexamine="0" >
                @endif
            {{ $item->id }}</td>
            <td>{{ $item->item_id}}</td>
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