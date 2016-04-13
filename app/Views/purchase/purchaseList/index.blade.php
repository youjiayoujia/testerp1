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
    <th>Item_ID</th>
    <th>采购类型</th>
    <th>产品图片</th>
    <th>供应商</th>
    <th>采购去向</th>
    <th>采购需求/采购数目/仍需采购</th>
    <th>shop</th>
    <th>采购条目状态</th>
    <th>创建时间</th>
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
        @if($purchaseList->purchase_order_id > 0)
            <td>{{ $purchaseList->id }}</td>
            <td>{{ $purchaseList->sku}}</td>
            @foreach(config('purchase.purchaseItem.type') as $k=>$type)
            	@if($purchaseList->type == $k)
            	<td>{{ $type }}</td>
                @endif
            @endforeach
           
            <td><img src="{{ asset($purchaseList->item->product->image->src)}}" height="50px"/></td>
            <td>{{ $purchaseList->supplier->name}}</td>
            <td>{{ $purchaseList->warehouse->name}}</td>
            <td>{{ $purchaseList->purchase_num}}/{{ $purchaseList->arrival_num}}/{{ $purchaseList->lack_num}}</td>
             @foreach(config('purchase.purchaseItem.channels') as $k=>$platform)
            	@if($purchaseList->platform_id == $k)
            	<td>{{ $platform }}</td>
                @endif
            @endforeach
            @foreach(config('purchase.purchaseItem.status') as $k=>$status)
            	@if($purchaseList->status == $k)
            	<td>{{ $status }}</td>
                @endif
            @endforeach
                      
            <td>{{ $purchaseList->created_at }}</td>
            <td> 
           @foreach(config('purchase.purchaseItem.active') as $k=>$vo)
            	@if($purchaseList->active == $k)  
            	{{ $vo }}
                @if($k >0)- @endif
                @endif
            @endforeach
            @if($purchaseList->active==1)
            @foreach(config('purchase.purchaseItem.active_status.1') as $key => $v)
                    @if($purchaseList->active_status==$key) {{$v}} @endif 
            @endforeach
            @elseif($purchaseList->active==2)
            @foreach(config('purchase.purchaseItem.active_status.2') as $key => $v)
                   @if($purchaseList->active_status==$key) {{$v}} @endif 
            @endforeach
            @elseif($purchaseList->active==3)
            @foreach(config('purchase.purchaseItem.active_status.3') as $key => $v)
                   @if($purchaseList->active_status==$key) {{$v}} @endif 
            @endforeach
            @elseif($purchaseList->active==4)
            @foreach(config('purchase.purchaseItem.active_status.4') as $key => $v)
                   @if($purchaseList->active_status==$key) {{$v}} @endif 
            @endforeach
            @endif
            </td>
            <td>
                <a href="{{ route('purchaseList.show', ['id'=>$purchaseList->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>       
                @if($purchaseList->active_status>0)
                <!--<a href="/purchaseList/activeChange/{{$purchaseList->id}}" class="btn btn-warning btn-xs">
                     处理异常
                </a>-->
                @endif
                 
                <a href="{{ route('purchaseList.edit', ['id'=>$purchaseList->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
            </td>
            @endif
        </tr>
    @endforeach
   <script type="text/javascript"> 
        $('.has_check').click(function () {
            alert("该产品已对单");
        });
        //批量审核
        $('#batchexamine').click(function () {
            if (confirm("确认对单?")) {
                var checkbox = document.getElementsByName("purchaseList_id");
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
                    url:'examinePurchaseItem',
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
                var checkbox = document.getElementsByName("purchaseList_id");
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
 