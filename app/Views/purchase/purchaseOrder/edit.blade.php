@extends('common.form')
@section('formAction')  {{ route('purchaseOrder.update', ['id' => $model->id]) }}  @stop
@section('formBody')  
 <input type="hidden" name="_method" value="PUT"/>
 <input type="hidden" name="update_userid" value="2"/>
 <input type="hidden" name="total_purchase_cost" value="0"/>
        <div class="row">
         <div class="form-group col-lg-4">
                <strong>标题: choies公司向 {{$model->supplier->name}} 采购单</strong>
            </div>
            <div class="form-group col-lg-4">
                <strong>采购单ID</strong>: {{ $model->id }}
            </div>
             <div class="form-group col-lg-4">
                <strong>订单成本:
                物流费{{$purchaseSumPostage}}+商品采购价格{{ $model->total_purchase_cost}}  总成本{{$purchaseSumPostage + $purchaseSumPostage}}</strong>
            </div>
             
            </div>
           <div class="row">
           <div class="form-group col-lg-4">
                <strong>采购仓库</strong>:
                {{ $model->warehouse->name}}
            </div>
            <div class="form-group col-lg-4">
                <strong>仓库地址</strong>:
                {{ $model->warehouse->province}}{{ $model->warehouse->city}}{{ $model->warehouse->address}}
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
           
            </div>
           <div class="row">
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
               
                <a href="{{ route('purchaseOrder.show', ['id'=>$model->id]) }}" class="btn btn-info btn-xs"> 打印该订单
                </a>     
            </div> 
             <div class="form-group col-lg-4">
            	<strong>取消采购单</strong>:
                	<a href="/purchaseOrder/cancelOrder/{{$model->id}}" class="btn btn-info btn-xs"> 取消该采购单</a>  
            </div>
             </div>
           <div class="row">
            <div class="form-group col-lg-4">
                <strong>采购人</strong>:
                <select name="assigner">
                @foreach($model->users as $k=>$user)
                <option value="{{$user->id}}" {{$model->assigner == $k ? 'selected' : ''}}>{{$user->name}}</option>
                @endforeach
                </select>		
            </div>
            
            <div class="form-group col-lg-4">
            	<strong>为该采购单添加新采购条目</strong>:
                	<a href="/purchaseOrder/addItem/{{$model->id}}" class="btn btn-info btn-xs"> 添加
                </a>
               
            </div>
            
            <div class="form-group col-lg-4">
            	<strong>审核该采购单</strong>:
                @if($model->examineStatus == 0 || $model->examineStatus == 2)
                {{$model->examineStatus == 0 ? '未审核':'二次审核'}}
                <a href="/purchaseOrder/changeExamineStatus/{{$model->id}}/1" class="btn btn-info btn-xs"> 审核通过
                </a> 
                <a href="/purchaseOrder/changeExamineStatus/{{$model->id}}/3" class="btn btn-info btn-xs"> 审核不通过
                </a>
                @endif
                @if($model->examineStatus == 1)
                审核通过
                @endif
                @if($model->examineStatus == 3)
                审核不通过
                @endif
            </div>
           
      </div>

     <div class="panel panel-default">
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
            <td>model</td>
            <td>SKU*采购数量</td> 
            <td>供货商sku</td> 
            <td>样图</td>
            <td>状态</td>
            <td>物流单号: <input  type="text" id="batch_post_coding" onChange="batchPostCoding()" value='' /></td>
            <td>采购价格</td>
            <td>采购价格审核</td>
            <td>购买链接</td> 
            <td>操作</td>           
        </tr>
    </thead>
    <tbody>
        @foreach($purchaseItems as $k=>$purchaseItem)
            @if($purchaseItem->status == 1)
            <tr> 
                <td>{{$purchaseItem->id}}<input type="hidden" name="arr[{{$k}}][id]" value="{{$purchaseItem->id }}"/></td>
                <td>{{$purchaseItem->item->product->model}}</td>
                <td>{{$purchaseItem->sku}}*<input type="text" value="{{$purchaseItem->purchase_num}}"  name="arr[{{$k}}][purchase_num]" style="width:50px"/></td>
                <td>{{$purchaseItem->item->supplier_sku}}</td>   
                <td>
                @if($purchaseItem->item->product->default_image>0) 
                <img src="{{ asset($purchaseItem->item->product->image->src) }}" width="50px">
                 @else
                 暂无图片
                 @endif
                </td>

                <td>
                @if($purchaseItem->status ==0)
                	<select name="arr[{{$k}}][status]" >
                 @foreach(config('purchase.purchaseItem.status') as $key=>$v)
                 	@if($key < 2)
                	<option value="{{$key}}"  @if(1 == $key) selected = "selected" @endif>{{$v}}</option>
                    @endif
                 @endforeach
                </select> 
                @else
                @foreach(config('purchase.purchaseItem.status') as $key=>$v)
                	@if($purchaseItem->status == $key) {{$v}} @endif
                 @endforeach
                 <input type="hidden" name="arr[{{$k}}][status]" value="{{$purchaseItem->status}}"/>
                @endif 
                 </td>
                <td>
                物流单号：<input type="text" value="{{$purchaseItem->post_coding}}" class="itemPostCoding" name="arr[{{$k}}][post_coding]"/>
                </td>
                <td>
                    @if($model->close_status ==0)
                        <input type="text" value="{{$purchaseItem->purchase_cost}}"  name="arr[{{$k}}][purchase_cost]" style="width:50px"/>
                    @else
                        {{$purchaseItem->purchase_cost}}
                        <input type="hidden" value="{{$purchaseItem->purchase_cost}}"  name="arr[{{$k}}][purchase_cost]" style="width:50px"/>
                    @endif
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
                    
                  @endif
                @endif
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
                @elseif($purchaseItem->active == 2)
                 报等
                 @if($purchaseItem->wait_time)
                 {{$purchaseItem->wait_time}}
                 备注：{{$purchaseItem->wait_remark}}
                @else
                <a href="/purchaseOrder/updateWaitTime/{{$purchaseItem->id}}">添加报等时间</a>
                @endif
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
            <td>购买链接</td>           
        </tr>
    </thead>
    <tbody>
        @foreach($purchaseItems as $k=>$purchaseItem)
        @if($purchaseItem->status == 2||$purchaseItem->status == 3||$purchaseItem->status == 4)
        <tr> 
            <td>{{$purchaseItem->id}}</td>
            <td>{{config('product.product_supplier.type')[$purchaseItem->supplier->type]}}</td>
            <td>{{$purchaseItem->sku}}</td>
            <td>
            @if($purchaseItem->item->product->default_image>0) 
            <img src="{{ asset($purchaseItem->item->product->image->src) }}" width="50px">
             @else
             暂无图片
             @endif
            </td>
            <td>
            {{config('purchase.purchaseItem.status')[$purchaseItem->status]}}
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
            	<a href="http://{{$purchaseItem->productItem?$purchaseItem->productItem->purchase_url:''}}" text-decoration: none;>{{$purchaseItem->productItem?$purchaseItem->productItem->purchase_url:''}}</a>
            </td>  
			
        </tr>
        @endif
        @endforeach
    </tbody>
    </table>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">产品信息</div>
        <div class="panel-body" id="itemDiv">
            <div class='row'>
                <div class="form-group col-sm-2">
                    <label for="sku" class='control-label'>物流号</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div> 
                 <div class="form-group col-sm-2">
                    <label for="sku" class='control-label'>物流费</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>             
            </div>       
           
             @foreach($purchasePostage as $key=>$post)
              <div class='row'>
                <div class="form-group col-sm-2">
                    <input type='text' class="form-control post_coding" id="post[{{$key}}][post_coding]" name='post[{{$key}}][post_coding]' value="{{$post->post_coding}}">
                </div>
               
                <div class="form-group col-sm-1">
                    <input type='text' class="form-control postage" id="post[{{$key}}][postage]" placeholder="物流费" name='post[{{$key}}][postage]' value="{{$post->postage}}">
                </div>
                <button type='button' class='btn btn-danger bt_right'><i class='glyphicon glyphicon-trash'></i></button>
                </div>
                 @endforeach 
                 	@if($current>0)
                    <input type="hidden" id="currrent" value="{{$current}}">
                    @else
                    <input type="hidden" id="currrent" value="1">
                    @endif    
        </div>
        <div class="panel-footer">
            <div class="create" id="addItem"><i class="glyphicon glyphicon-plus"></i><strong>新增采购单号和物流费</strong></div>
        </div>
    </div> 
@stop
@section('pageJs')
    <script type='text/javascript'>
	//批量输入采购单号
	function batchPostCoding(){
		 var batch_post_coding=$('#batch_post_coding').val(); 
			$(".itemPostCoding").val(batch_post_coding);
		}
		//新增物流号对应物流费
        $(document).ready(function () {
            var current = $('#currrent').val();
            $('#addItem').click(function () {
                $.ajax({
                    url: "{{ route('postAdd') }}",
                    data: {current: current},
                    dataType: 'html',
                    type: 'get',
                    success: function (result) {
                        $('#itemDiv').append(result);
                    }
                });
                current++;
            });

 			$(document).on('click', '.bt_right', function () {
				if(current >1) {
                $(this).parent().remove();
                current--; 
                }
            });
           
        });
    </script>
@stop