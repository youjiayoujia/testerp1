@extends('common.form')
@section('formAction')  {{ route('purchaseOrder.update', ['id' => $model->id]) }}  @stop
@section('formBody')  
 <input type="hidden" name="_method" value="PUT"/>
 <input type="hidden" name="update_userid" value="2"/>
 <input type="hidden" name="total_purchase_cost" value="0"/>
        <div class="panel-heading">单头</div>
        <div class="panel-body">
         <div class="form-group col-lg-4">
                <strong>标题: choies公司向 {{$model->supplier->name}} 采购单</strong>
            </div>
           
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
            <strong>采购单运单号</strong>:
                <input class="form-control" type="text" name='post_coding' value='{{$model->post_coding}}'/>
            </div>
         <div class="form-group col-lg-4">
            <strong>采购单运费</strong>:
                <input class="form-control" type="text" name='total_postage' value='{{$model->total_postage}}'/>
            </div>  
            <div class="form-group col-lg-4">
            	<strong>导出该订单</strong>:
                @if($model->supplier->type==1)
                	<a href="/purchaseOrder/excelOut/{{$model->id}}" class="btn btn-info btn-xs"> 导出该订单
                </a>
                @else
                <a href="{{ route('purchaseOrder.show', ['id'=>$model->id]) }}" class="btn btn-info btn-xs"> 打印该订单
                </a>
                @endif     
            </div> 
            <div class="form-group col-lg-4">
                <strong>采购人</strong>:
                <input class="form-control" type="text" name='assigner' value='{{$model->assigner}}'/>		
            </div>
            <div class="form-group col-lg-4">
            	<strong>采购单结算</strong>:
                 @if($model->close_status ==0)
                	<a href="{{ route('closePurchaseOrder.edit', ['id'=>$model->id]) }}" class="btn btn-info btn-xs"> 结算改采购单
                </a>
                @else
                已结算
                @endif  
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
            <td>model</td>
            <td>SKU*采购数量</td> 
            <td>供货商sku</td> 
            <td>样图</td>
            <td>状态</td>
            <td>物流单号+物流费</td>
            <td>采购价格</td>
            <td>采购价格审核</td>
            <td>所属平台</td>
            <td>购买链接</td> 
            <td>操作</td>           
        </tr>
    </thead>
    <tbody>
        @foreach($purchaseItems as $k=>$purchaseItem)
         @if($purchaseItem->storageStatus == 0)
        <tr> 
            <td>{{$purchaseItem->id}}<input type="hidden" name="arr[{{$k}}][id]" value="{{$purchaseItem->id }}"/></td>
            <td>
                @foreach(config('purchase.purchaseItem.type') as $key=>$v)
                    @if($purchaseItem->type == $key)
                        {{$v}}
                    @endif
                @endforeach
            </td>
            <td>{{$purchaseItem->item->product->model}}</td>
            <td>{{$purchaseItem->sku}}*{{$purchaseItem->purchase_num}}</td>
            <td>{{$purchaseItem->item->supplier_sku}}</td>   
            <td>
            @if($purchaseItem->item->product->default_image>0) 
            <img src="{{ asset($purchaseItem->item->product->image->src) }}" width="50px">
             @else
             暂无图片
             @endif
            </td>
            
            <td>
           	<select name="arr[{{$k}}][status]" >
             @foreach(config('purchase.purchaseItem.status') as $key=>$v)
             	@if($key < 2)
            	<option value="{{$key}}"  @if($purchaseItem->status == $key) selected = "selected" @endif>{{$v}}</option>
                @endif
             @endforeach
            </select>  
             </td>
            <td>
            物流单号：<input type="text" value="{{$purchaseItem->post_coding}}"  name="arr[{{$k}}][post_coding]"/>
            物流费：<input type="text" value="{{$purchaseItem->postage}}"  name="arr[{{$k}}][postage]" style="width:50px"/>
            </td>
            <td>
              <input type="text" value="{{$purchaseItem->purchase_cost}}"  name="arr[{{$k}}][purchase_cost]" style="width:50px"/>
 			</td>
            <td>
            @if($purchaseItem->costExamineStatus ==2)
            	价格审核通过
            @elseif($purchaseItem->costExamineStatus ==1)
            	价格审核不通过
            @else
             @if($purchaseItem->purchase_cost>0)
            	<a href="/purchaseItem/costExamineStatus/{{$purchaseItem->id}}/1" class="btn btn-info btn-xs"> 审核不通过
                </a> 
                <a href="/purchaseItem/costExamineStatus/{{$purchaseItem->id}}/2" class="btn btn-info btn-xs"> 审核通过
                </a>
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
             <td>
            	<a href="http://{{$purchaseItem->item->purchase_url}}" text-decoration: none;>{{$purchaseItem->item->purchase_url}}</a>
            </td>  
			<td>
            @if($purchaseItem->active ==1 )
                @if($purchaseItem->active_status ==1 )
                报缺
                @elseif($purchaseItem->active_status ==2 )
                核实报缺
                @else
                正常
                @endif
            <input type="hidden" name="arr[{{$k}}][active]}" value="{{$purchaseItem->active}}"/>
            @elseif($purchaseItem->active > 1)
             @foreach(config('purchase.purchaseItem.active') as $key=>$v)
             	@if($key ==$purchaseItem->active)
            	{{$v}}
            	@endif
             @endforeach
            <input type="hidden" name="arr[{{$k}}][active]}" value="{{$purchaseItem->active}}"/>
            @else
            <select name="arr[{{$k}}][active]}">
             @foreach(config('purchase.purchaseItem.active') as $key=>$v)
             	@if($key < 3)
            	<option value="{{$key}}" >{{$v}}</option>
            	@endif
             @endforeach
            </select>
             @endif
            </td>
        </tr>
        @endif
        @endforeach
    </tbody>
    </table>
    <div class="row">
         <div class="form-group col-lg-4">
                <strong>已入库条目</strong>:
            </div>
            </div>
    <table class="table table-bordered table-striped table-hover sortable">
    <thead>
        <tr>
            <td>采购条目ID</td> 
            <td>采购类型</td> 
            <td>SKU</td> 
            <td>样图</td>
            <td>入库状态</td>
            <td>已入库数量</td>
            <td>物流单号+物流费</td>
            <td>采购价格</td>
            <td>采购价格审核</td>
            <td>所属平台</td>
            <td>购买链接</td>           
        </tr>
    </thead>
    <tbody>
        @foreach($purchaseItems as $k=>$purchaseItem)
         @if($purchaseItem->storageStatus > 0)
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
            <td>
           
             @foreach(config('purchase.purchaseItem.storageStatus') as $key=>$v)
             	@if($key < 2)
            	 @if($purchaseItem->storageStatus == $key) {{$v}} @endif
                @endif
             @endforeach 
             </td>
            <td>{{$purchaseItem->storage_qty}}</td>
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
            @endif
            </td>    
            <td>
                @foreach(config('purchase.purchaseItem.channels') as $key=>$vo)
                    @if($purchaseItem->platform_id == $key)
                        {{$vo}}
                    @endif
                @endforeach
            </td>
             <td>
            	<a href="http://{{$purchaseItem->item->purchase_url}}" text-decoration: none;>{{$purchaseItem->item->purchase_url}}</a>
            </td>  
			
        </tr>
        @endif
        @endforeach
    </tbody>
    </table>
        </div>
    </div>
@stop