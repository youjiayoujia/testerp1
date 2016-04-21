@extends('common.form')
@section('formAction')  {{ route('closePurchaseOrder.update', ['id' => $model->id]) }}  @stop
@section('formBody')  
 <input type="hidden" name="_method" value="PUT"/>
 <input type="hidden" name="update_userid" value="2"/>
        <div class="panel-heading">单头</div>
        <div class="panel-body">
            <div class="form-group col-lg-4">
                <strong>ID</strong>: {{ $model->id }}
            </div>
             <div class="form-group col-lg-4">
                <strong>采购仓库</strong>:
                {{ $model->warehouse->name}}
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
                <strong>采购单结算状态</strong>:
          		@if($model->close_status ==0)
                <select name="close_status">
               @foreach(config('purchase.purchaseOrder.close_status') as $k=>$val)           	
            		<option value="{{$k}}">{{$val}}</option>
            	@endforeach
                </select>
                @endif
            </div> 
         <div class="form-group col-lg-4">
            <strong>采购单运单号</strong>:
                {{$model->post_coding}}
            </div>
         <div class="form-group col-lg-4">
            <strong>采购单运费</strong>:
                {{$model->total_postage}}
            </div>  
                    
        </div>

     <div class="panel panel-default">
        <div class="panel-heading">单身</div>
        <div class="panel-body">
        <div class="row">
         <div class="form-group col-lg-4">
                <strong>未入库条目</strong>:
            </div>
            </div>
    <table class="table table-bordered table-striped table-hover sortable">
    <thead>
        <tr>
            <td>采购条目ID</td> 
            <td>采购类型</td> 
            <td>SKU</td> 
            <td>样图</td>
            <td>采购数量</td>
            <td>状态</td>
            <td>物流单号+物流费</td>
            <td>采购价格</td>
            <td>采购价格审核</td>
            <td>所属平台</td>         
        </tr>
    </thead>
    <tbody>
        @foreach($purchaseItems as $k=>$purchaseItem)
         @if($purchaseItem->storageStatus == 0)
        <tr> 
            <td>{{$purchaseItem->id}}</td>
            <td>
                @foreach(config('purchase.purchaseItem.type') as $key=>$v)
                    @if($purchaseItem->type == $key)
                        {{$v}}
                    @endif
                @endforeach
            </td>
            <td>{{$purchaseItem->sku}}</td>
            <td>
            @if($purchaseItem->item->product->default_image>0) 
            <img src="{{ asset($purchaseItem->item->product->image->src) }}" width="50px">
             @else
             暂无图片
             @endif
            </td>
            <td>{{$purchaseItem->purchase_num}}</td>
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
             @if($purchaseItem->purchase_cost>0)
            	 审核不通过
                 审核通过
                
              @endif
            @endif
            </td>    
            <td>
                @foreach(config('purchase.purchaseItem.channels') as $key=>$vo)
                    @if($purchaseItem->platform_id == $key)
                        {{$vo}}
                    @endif
                @endforeach
            </td>
               
			
        </tr>
        @endif
        @endforeach
    </tbody>
    </table>
    
@stop