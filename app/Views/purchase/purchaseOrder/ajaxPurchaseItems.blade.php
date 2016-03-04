<div class="btn-group">
        <a class="btn btn-info" id="batchexamine" onClick="addPurchaseOrder()">
            <i class="glyphicon glyphicon-ok-circle"></i> 添加入采购单
        </a>
    </div>
<table class="table table-bordered table-striped table-hover sortable">
    <thead>
        <tr>
            <td><input type="checkbox" isCheck="true" id="checkall" onclick="quanxuan()"> 全选</td>
            <td>采购条目ID</td> 
            <td>采购类型</td> 
            <td>SKU_ID</td> 
            <td>采购数量</td>
            <td>仓库</td>
            <td>供应商</td>
            <td>核实价格</td>
            <td>所属平台</td>
            <td>创建人</td>
            <td>创建时间</td>    
        </tr>
    </thead>
    <tbody>
    	@foreach($data as $purchaseItem)
        <tr>
        	<td>
                <input type="checkbox" name="purchaseItem_id"  value="{{$purchaseItem->id}}" isexamine="1" >
               </td>
            <td>{{$purchaseItem->id}}</td>
            <td>
            	@foreach(config('purchase.purchaseItem.type') as $key=>$v)
            		@if($purchaseItem->type == $key)
                    	{{$v}}
                	@endif
                @endforeach
            </td>
            <td>{{$purchaseItem->sku_id}}</td>
            <td>{{$purchaseItem->purchase_num}}</td>
            <td>{{$purchaseItem->supplier->name}}</td>
            <td>{{$purchaseItem->warehouse->name}}</td>
            <td>{{$purchaseItem->stock}}</td>
            <td>
            	@foreach(config('purchase.purchaseItem.platforms') as $key=>$vo)
            		@if($purchaseItem->platform_id == $key)
                    	{{$vo}}
                	@endif
                @endforeach
            </td>
            <td>{{$purchaseItem->user_id}}</td>
            <td>{{$purchaseItem->created_at}}</td>  
        </tr>
        @endforeach
    </tbody>
</table>
 