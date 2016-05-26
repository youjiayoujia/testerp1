
<div class="panel panel-default">
        
        <div class="panel-body">
             <div class="form-group col-lg-4">
                <strong>采购仓库</strong>:
                {{ $model->warehouse->name}}
            </div>
            <div class="form-group col-lg-4">
                <strong>仓库地址</strong>:
                {{ $model->warehouse->province}}{{ $model->warehouse->city}}{{ $model->warehouse->address}}
            </div>
            
             <div class="form-group col-lg-4">
                <strong>采购单ID</strong>: {{ $model->id }}
            </div>
             <div class="form-group col-lg-4">
            	<strong>供应商信息</strong>:
                名：{{$model->supplier->name}}&nbsp;电话：{{$model->supplier->telephone}} &nbsp;地址：{{$model->supplier->province}}{{$model->supplier->city}}{{$model->supplier->address}}
                &nbsp;
                @if($model->supplier->type==1)
                	线上采购
                @else
                	线下采购
                @endif
            </div>
            <div class="form-group col-lg-4">
                <strong>订单成本</strong>:
                物流费{{ $model->total_postage}}+商品采购价格{{ $model->total_purchase_cost}}  总成本{{ $model->total_postage + $model->total_purchase_cost}}
            </div>
            <div class="form-group col-lg-4">
                <strong>采购单状态</strong>:
               @foreach(config('purchase.purchaseOrder.status') as $k=>$val)
            	@if($model->status == $k)
            		{{$val}}
                @endif
            	@endforeach
            </div> 
            <div class="form-group col-lg-4">
                <strong>采购人</strong>:
                {{$model->assigner}}
            </div>
         <div class="form-group col-lg-4">
            <strong>采购单运单号</strong>:
                @if(!$model->post_coding)
                暂无运单号
                @else
                {{$model->post_coding}}
                @endif
            </div>
         <div class="form-group col-lg-4">
            <strong>采购单运费</strong>:
             @if(!$model->post_coding)
                暂无运费上报
                @else
                {{$model->total_postage}}
                @endif
                
            </div>  
          
            <div class="form-group col-lg-4">
                <strong>采购单审核状态</strong>:     
            	@if($model->examineStatus == 0)
                    未审核
                 @elseif($model->examineStatus == 1)
                 审核通过
                 @elseif($model->examineStatus == 2)
                 待复审
                 @else
                 审核不通过
                @endif
            </div>   
            <div class="form-group col-lg-4">
            	<strong>采购单结算状态</strong>:
                @if($model->close_status ==0)
                	未结算
                @else
                已结算
                @endif    
            </div>        
        </div>
		</div>
     <div class="panel panel-default">
        <div class="panel-heading">单身</div>
        <div class="panel-body">
        
    <table class="table table-bordered table-striped table-hover sortable">
    <thead>
        <tr>
            <td>采购条目ID</td> 
            <td>model</td>
            <td>SKU*采购数量</td> 
            <td>供货商sku</td> 
            <td>状态</td>
            <td>物流单号+物流费</td>
            <td>采购价格</td>
            <td>采购价格审核</td>
            <td>操作</td>           
        </tr>
    </thead>
    <tbody>
        @foreach($purchaseItems as $k=>$purchaseItem)  
        <tr> 
            <td>{{$purchaseItem->id}}</td>
            
            <td>{{$purchaseItem->item->product->model}}</td>
            <td>{{$purchaseItem->sku}}*{{$purchaseItem->purchase_num}}</td>
            <td>{{$purchaseItem->item->supplier_sku}}</td>   
            
            <td>       	
             @foreach(config('purchase.purchaseItem.status') as $key=>$v)     	
            	 @if($purchaseItem->status == $key) {{$v}} @endif
             @endforeach
             </td>
            <td>
            物流单号：{{$purchaseItem->post_coding}}
            物流费：{{$purchaseItem->postage}}
            </td>
            <td>
              {{$purchaseItem->purchase_cost}}
 			</td>
            <td>
            @if($purchaseItem->costExamineStatus ==2)
            	价格审核通过
            @elseif($purchaseItem->costExamineStatus ==1)
            	价格审核不通过
            @else
             	成本价格未审核
            @endif
            </td>    
          
             
			<td>
            @if($purchaseItem->active ==1 )
                @if($purchaseItem->active_status ==1 )
                报缺
                @elseif($purchaseItem->active_status ==2 )
                核实报缺
                @else
                恢复正常
                @endif
             @elseif($purchaseItem->active ==0)     
             	正常
             @endif
            </td>
        </tr>
        @endforeach
    </tbody>
    </table>
   
        </div>
    </div>
     <div class="panel panel-default">
        <div class="panel-heading">尾部</div>
        <div class="row">
            <div class="form-group col-lg-4">
                <strong>采购日期</strong>:
                  {{$model->start_buying_time}}
            </div>
            <div class="form-group col-lg-4">
                <strong>打印日期</strong>:
                <?php echo date('Y-m-d',time());?>
            </div>
             <div class="form-group col-lg-4">
                <strong>采购人</strong>:
                {{$model->assigner}}
            </div>
         </div>   
    </div><input type="button" value="打印" onclick="window.print();"/>
