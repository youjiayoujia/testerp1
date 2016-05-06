@extends('common.table')
@section('tableToolButtons')
 <div class="btn-group">
        <a class="btn btn-info" id="batchexamine">
            <i class="glyphicon glyphicon-ok-circle"></i> 确认对单
        </a>
    </div>
@stop{{-- 工具按钮 --}}
@section('tableHeader')
	<th><input type="checkbox" isCheck="true" id="checkall" onclick="quanxuan()"> 全选</th>
    <th>ID</th>
    <th>采购单ID</th>
    <th>sku</th>
    <th>采购类型</th>
    <th>产品图片</th>
    <th>供应商</th>
    <th>供应商sku</th>
    <th>重量</th>
    <th>采购去向</th>
    <th>采购需求/采购数目/仍需采购</th>
    <th>国内物流号</th>
    <th>采购条目状态</th>
    <th>入库状态</th>
    <th>采购人</th>
    <th>异常状态</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $purchaseList)
        <tr>
        <td>
                @if($purchaseList->status ==2)
                <input type="checkbox" name="purchaseList_id"  value="{{$purchaseList->id}}" isexamine="1" >
                @else
                <input type="checkbox" name="purchaseList_id"  value="{{$purchaseList->id}}" isexamine="0" >
                @endif
            </td>
        
            <td>{{ $purchaseList->id }}</td>
            <td>{{ $purchaseList->purchase_order_id }}</td>
            <td>{{ $purchaseList->sku}}</td>
            @foreach(config('purchase.purchaseItem.type') as $k=>$type)
            	@if($purchaseList->type == $k)
            	<td>{{ $type }}</td>
                @endif
            @endforeach
           
            <td><img src="{{ asset($purchaseList->item->product->image->src)}}" height="50px"/></td>
            <td>{{ $purchaseList->supplier->name}}</td>
            <td>{{ $purchaseList->item->supplier_sku}}</td>
            <td>
            <input type="text" name="weight" id="{{ $purchaseList->id }}_weight" value="{{$purchaseList->item->weight}}"/> 
              <a href="javascript:" class="btn btn-info btn-xs changeWeight" data-id="{{ $purchaseList->id }}">更新</a>
            </td>
            <td>{{ $purchaseList->warehouse->name}}</td>
            <td>{{ $purchaseList->purchase_num}}/{{ $purchaseList->arrival_num}}/{{ $purchaseList->lack_num}}</td>
             
            	<td><input type="text" name="weight" id="{{ $purchaseList->id }}_post_coding" value="{{ $purchaseList->post_coding }}"/> 
            	<a href="javascript:" class="btn btn-info btn-xs change_post_coding" data-id="{{ $purchaseList->id }}">更新</a></td>
                
           <td> @foreach(config('purchase.purchaseItem.status') as $k=>$status)
            	@if($purchaseList->status == $k)
            	{{ $status }}
                @endif
            @endforeach</td>
             <td> @foreach(config('purchase.purchaseItem.storageStatus') as $kt=>$va)
            	@if($purchaseList->storageStatus == $kt)
            	{{ $va}}
                @endif
            @endforeach</td>                    
            <td>{{ $purchaseList->purchaseOrder->assigner }}</td>
            <td> 
            <select id="{{$purchaseList->id}}_active" name="active">
          	@foreach(config('purchase.purchaseItem.active') as $k=>$vo)
            	<option value="{{$k}}" @if($purchaseList->active == $k && $purchaseList->active_status ==1) selected="selected" @endif>{{$vo}}</option>
            @endforeach
            </select>
            </td>
            <td>     
                @if($purchaseList->status >1)
           		<a href="/purchaseList/printBarCode/{{$purchaseList->id}}" class="btn btn-warning btn-xs">
                     打印条码
                </a>
                @endif
                 
               <!-- <a href="{{ route('purchaseList.edit', ['id'=>$purchaseList->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 去对单
                </a>-->
            </td>
          
        </tr>
    @endforeach
   <script type="text/javascript"> 
   		
        //批量对单
        $('#batchexamine').click(function () {
            if (confirm("确认对单?")) {
                var checkbox = document.getElementsByName("purchaseList_id");
                var purchase_ids = "";
				var purcahse_active="";
                for (var i = 0; i < checkbox.length; i++) {
                    if(!checkbox[i].checked)continue;
                    if(checkbox[i].getAttribute('isexamine')==1){
                        alert("id为"+checkbox[i].value+"的采购条目已经对单了");
                        return;
                    }
					purcahse_active +=checkbox[i].value+"+"+$("#"+checkbox[i].value+"_active" ).val()+",";
                    purchase_ids += checkbox[i].value+",";
                }
				purcahse_active = purcahse_active.substr(0,(purcahse_active.length)-1);
                //purchase_ids = purchase_ids.substr(0,(purchase_ids.length)-1);
                $.ajax({
                    url:'examinePurchaseItem',
                    data:{purcahse_active:purcahse_active},
                    dataType:'json',
                    type:'get',
                    success:function(result){
                        window.location.reload();
                    }                    
                })
            }
        });
		//回传重量
		$('.changeWeight').click(function(){
			var purchase_id = $(this).data('id');
			var item_weight = $("#"+purchase_id+"_weight").val();
			$.ajax({
                    url:'/changeItemWeight',
                    data:{purchase_id:purchase_id,item_weight:item_weight},
                    dataType:'json',
                    type:'get',
                    success:function(result){
                        window.location.reload();
                    }                    
                })
			});
			//回传物流单号
			$('.change_post_coding').click(function(){
			var purchase_id = $(this).data('id');
			var post_coding = $("#"+purchase_id+"_post_coding").val();
			$.ajax({
                    url:'/changePurchaseItemPostcoding',
                    data:{purchase_id:purchase_id,post_coding:post_coding},
                    dataType:'json',
                    type:'get',
                    success:function(result){
                        window.location.reload();
                    }                    
                })
			});
        //全选
        function quanxuan()
        {
          var collid = document.getElementById("checkall");
          var coll = document.getElementsByName("purchaseList_id");
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
 