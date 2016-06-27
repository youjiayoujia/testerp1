

        
        <div style="width:800px;border:2px solid #000">
        <div style="text-align:center;margin:0 auto" >
       <strong>采购单</strong> </div>
             <div style="width:50%;height:134px;float:left;border:1px solid #000;margin:-1px;">
             <div style="text-align:center;margin:0 auto" >
       <strong>供应商信息</strong> </div>
             <div >
                <strong>供应商名称</strong>:
                {{$model->supplier->name ?  $model->supplier->name : '暂无名称'}}
            </div>
            <div >
                <strong>地址</strong>:
                {{ $model->supplier->supplier_address ? $model->supplier->supplier_address : '暂无地址'}}
            </div>
            
             <div style="width:50%;">
                <strong>联系电话</strong>: {{ $model->supplier->telephone ? $model->supplier->telephone : '暂无电话'}}
            </div>
             <div style="width:50%;">
            	<strong>联系人</strong>:
                {{$model->assigner > 0 ? $model->assigner_name : '暂无联系人'}}
            </div>
            <div >
            	<strong>付款方式:
                @foreach(config('product.product_supplier.pay_type') as $k=>$v)
                	{{$model->pay_type == $k ? $v : ''}}
                @endforeach
                </strong>
            </div>
          </div>  
          <div style="width:50%;height:134px;float:left;border:1px solid #000;margin:-1px;">
          <div style="text-align:center;margin:0 auto" >
       <strong>收货资料</strong> </div>
            <div style="width:50%;">
                <strong>交货地址</strong>:
                {{$model->warehouse->warehouse_address}}
              <!--  物流费{{ $model->total_postage}}+商品采购价格{{ $model->total_purchase_cost}}  总成本{{ $model->total_postage + $model->total_purchase_cost}}-->
            </div>
            <div style="width:50%;">
                <strong>采购联系人</strong>:
               {{$model->assigner > 0 ? $model->assigner_name : '暂无联系人'}}
            </div> 
            <div class="form-group col-lg-4">
                <strong>仓库名</strong>:
                {{$model->warehouse->name > 0 ? $model->warehouse->name : '暂无联系人'}}
            </div>
            <div class="form-group col-lg-4">
                <strong>预计到货日期:
                {{$model->arrival_day}}</strong>
            </div>
             </div>
         
       
		<div style="clear:left"></div>
        
    <table border="0"  width="100%" cellpadding="0" cellspacing="1" bgcolor="#000">
    <thead >
        <tr bgcolor="#fff">
            <td>序号</td>
            <td>采购条目ID</td> 
            <td>单据号</td>
            <td>SKU</td>
            <td>产品描述</td> 
            <td>采购数量</td> 
            <td>入库数量</td>
            <td>不良数量</td>
            <td>采购价格</td>
            <td>小计</td>           
        </tr>
    </thead>
    <tbody>
        @foreach($purchaseItems as $k=>$purchaseItem)  
        <tr bgcolor="#fff"> 
            <td>{{$k+1}}</td>
            <td>{{$purchaseItem->id}}</td>
            
            <td>{{$purchaseItem->post_coding}}</td>
            <td>{{$purchaseItem->sku}}</td>
            <td>{{$purchaseItem->item->c_name}}</td>
            <td>{{$purchaseItem->purchase_num}}</td>   
            
            <td>       	
             {{$purchaseItem->storage_qty}}
             </td>
            <td>
            {{$purchaseItem->purchase_num - $purchaseItem->arrival_num}}
            </td>
            <td>
              {{$purchaseItem->purchase_cost}}
 			</td>
            <td>
            {{$purchaseItem->purchase_cost * $purchaseItem->purchase_num}}
            </td>    	
        </tr>
        @endforeach 
        <tr bgcolor="#fff">
        <td colspan="4"><strong>合计</strong></td>
        <td>{{$purchase_num_sum}}</td>
        <td>{{$storage_qty_sum}}</td>
        <td>{{$purchase_num_sum - $storage_qty_sum}}</td>
        <td></td>
        <td>{{$purchaseAccount}} + YF{{$postage_sum}} = 总{{$purchaseAccount + $postage_sum}}</td>
        </tr>
        <tr bgcolor="#fff">
       <td colspan="9"><strong> 备注： {{$model->remark}}</strong></td>
        </tr> 
    </tbody>
    </table>
    
            <div class="form-group col-lg-4">
                <strong>打印日期</strong>:
                <?php echo date('Y-m-d H:i:s',time());?>
            </div>
   </div>
<input type="button" value="打印" onclick="window.print();"/>
