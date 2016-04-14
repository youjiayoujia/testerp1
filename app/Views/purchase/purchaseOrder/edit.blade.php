@extends('common.form')
@section('formAction')  {{ route('purchaseOrder.update', ['id' => $model->id]) }}  @stop
@section('formBody')  
 <input type="hidden" name="_method" value="PUT"/>
 <input type="hidden" name="update_userid" value="2"/>
 <input type="hidden" name="total_purchase_cost" value="0"/>
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
            <strong>采购单运单号</strong>:
                <input class="form-control" type="text" name='post_coding' value='{{$model->post_coding}}'/>
            </div>
         <div class="form-group col-lg-4">
            <strong>采购单运费</strong>:
                <input class="form-control" type="text" name='total_postage' value='{{$model->total_postage}}'/>
            </div>  
                    
        </div>

     <div class="panel panel-default">
        <div class="panel-heading">单身</div>
        <div class="panel-body">
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
            <td>购买链接</td> 
            <td>操作</td>           
        </tr>
    </thead>
    <tbody>
        @foreach($purchaseItems as $k=>$purchaseItem)
        <tr> 
            <td>{{$purchaseItem->id}}<input type="hidden" name="arr[{{$k}}][id]" value="{{$purchaseItem->id }}"/></td>
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
            @if($purchaseItem->active ==1)
            报缺
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
        @endforeach
    </tbody>
    </table>
        </div>
    </div>
@stop