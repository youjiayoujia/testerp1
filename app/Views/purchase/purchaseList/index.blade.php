@extends('common.table')
@section('tableToolButtons')
 <div class="btn-group">
        <a class="btn btn-info" id="batchexamine">
            <i class="glyphicon glyphicon-ok-circle"></i> 确认到货
        </a>
    </div>
@stop{{-- 工具按钮 --}}
@section('tableHeader')
	<th><input type="checkbox" isCheck="true" id="checkall" onclick="quanxuan()"> 全选</th>
    <th>ID</th>
    <th>采购单ID</th>
    <th>sku*采购数量</th>
    <th>产品图片</th>
    <th>供应商</th>
    <th>供应商sku</th>
    <th>重量</th>
    <th>采购去向</th>
    <th>国内物流号</th>
    <th>采购条目状态</th>
    <th>采购价格</th>
    <th>采购价格审核</th>
    <th>入库数量</th>
    <th>入库状态</th>
    <th>采购人</th>
   <!-- <th>异常状态</th>-->
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
                <input type="hidden" id="{{ $purchaseList->id }}_position_num"  value="{{$purchaseList['position_num']}}"  >
                
            </td>
        
            <td>{{ $purchaseList->id }}</td>
            <td>{{ $purchaseList->purchase_order_id }}</td>
            <td>{{ $purchaseList->sku}}*{{$purchaseList->purchase_num}}</td>
           
            <td><img src="{{ asset($purchaseList->item->product->image->src)}}" height="50px"/></td>
            <td>{{ $purchaseList->supplier->name}}</td>
            <td>{{ $purchaseList->item->supplier_sku}}</td>
            <td>
            @if($purchaseList->item->weight == 0)
            <input type="text" name="weight" id="{{ $purchaseList->id }}_weight" value="{{$purchaseList->item->weight}}" style="width:50px"/> 
              <a href="javascript:" class="btn btn-info btn-xs changeWeight" data-id="{{ $purchaseList->id }}">更新</a>
              @else
              {{$purchaseList->item->weight}}
              @endif
            </td>
            <td>{{ $purchaseList->warehouse->name}}</td>
            	<td>{{ $purchaseList->post_coding }}
                <!--<input type="text" name="post_coding" id="{{ $purchaseList->id }}_post_coding" value="{{ $purchaseList->post_coding }}" style="width:150px"/> 
            	<a href="javascript:" class="btn btn-info btn-xs change_post_coding" data-id="{{ $purchaseList->id }}">更新</a>--></td>
                
           <td> @foreach(config('purchase.purchaseItem.status') as $k=>$status)
            	@if($purchaseList->status == $k)
            	{{ $status }}
                @endif
            @endforeach</td>                  
            <td>{{ $purchaseList->purchase_cost }}</td>
            <td>
            <input  type="hidden" name="costExamineStatus" id="{{ $purchaseList->id }}_costExamineStatus" value="{{ $purchaseList->costExamineStatus }}"/>
            @if($purchaseList->costExamineStatus ==2)
            	价格审核通过
            @elseif($purchaseList->costExamineStatus ==1)
            	价格审核不通过
            @else
             @if($purchaseList->purchase_cost>0)
            	<a href="/purchaseItem/costExamineStatus/{{$purchaseList->id}}/1" class="btn btn-info btn-xs"> 审核不通过
                </a> 
                <a href="/purchaseItem/costExamineStatus/{{$purchaseList->id}}/2" class="btn btn-info btn-xs"> 审核通过
                </a>
              @endif
            @endif</td>
            
            <td>
           	
            @if($purchaseList->status == 2 || $purchaseList->status == 3)
            	<input type="text" name="storage_qty" id="{{ $purchaseList->id }}_storage_qty" value="{{ $purchaseList->storage_qty }}" style="width:150px"/> 
            	<a href="javascript:" class="btn btn-info btn-xs change_storage_qty" data-id="{{ $purchaseList->id }}">更新
                @else
                {{ $purchaseList->storage_qty }}
                @endif
                
                </td>
            <td>@if($purchaseList->storageStatus == 0)
            	未入库
                @elseif($purchaseList->storageStatus == 1)
                部分入库
                @else
                全部入库
                @endif
            </td>                  
            <td>{{ $purchaseList->purchaseOrder->assigner }}</td>
           <!-- <td> 
            <select id="{{$purchaseList->id}}_active" name="active">
          	@foreach(config('purchase.purchaseItem.active') as $k=>$vo)
            	<option value="{{$k}}" @if($purchaseList->active == $k && $purchaseList->active_status ==1) selected="selected" @endif>{{$vo}}</option>
            @endforeach
            </select>
            </td>-->
            <td>     
                @if($purchaseList->status >1)
           		<a href="/purchaseList/printBarCode/{{$purchaseList->id}}" class="btn btn-warning btn-xs">
                     打印条码
                </a>
                @endif
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
				var item_weight='';
				var costExamineStatus='';
				var position_num='';
                for (var i = 0; i < checkbox.length; i++) {
                    if(!checkbox[i].checked)continue;
                    if(checkbox[i].getAttribute('isexamine')==1){
                        alert("id为"+checkbox[i].value+"的采购条目已经对单了");
                        return;
                    }
					item_weight=$("#"+checkbox[i].value+"_weight" ).val();
					if(item_weight ==0){
                        alert("id为"+checkbox[i].value+"的采购条目没有重量");
                        return;
                    }
					costExamineStatus=$("#"+checkbox[i].value+"_costExamineStatus" ).val();
					if(costExamineStatus < 2){
                        alert("id为"+checkbox[i].value+"的采购条目价格审核未通过");
                        return;
                    }
					position_num=$("#"+checkbox[i].value+"_position_num" ).val();
					if(position_num == 0){
                        alert("id为"+checkbox[i].value+"的采购条目没有库位");
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
			
			//采购入库
			$('.change_storage_qty').click(function(){
			var purchase_id = $(this).data('id');
			var storage_qty = $("#"+purchase_id+"_storage_qty").val();
			$.ajax({
                    url:'/changePurchaseItemStorageQty',
                    data:{purchase_id:purchase_id,storage_qty:storage_qty},
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
 