@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
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
            	<strong>采购类型</strong>:
                {{$model->supplier->type}}
                @if($model->supplier->type == 'online')
                	<a href="/purchaseOrder/excelOut/{{$model->id}}" class="btn btn-info btn-xs"> 导出该订单
                </a>
                @else
                <a href="/purchaseOrder/printOrder/{{$model->id}}" class="btn btn-info btn-xs"> 打印该订单
                </a>
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
            <td>采购类型</td> 
            <td>SKU_ID</td> 
            <td>样图</td>
            <td>采购数量/已到货数量/仍需采购数量</td>
            <td>状态</td>
            <td>物流单号+物流费</td>
           <!-- <td>采购价格</td>-->
            <td>库存</td>
            <td>参考价格</td>
            <td>所属平台</td>
            <td>购买链接</td>
            <td>创建人</td>
            <td>创建时间</td>            
        </tr>
    </thead>
    <tbody>
        @foreach($purchaseItems as $purchaseItem)
        <tr> 
            <td>{{$purchaseItem->id}}</td>
            <td>
                @foreach(config('purchase.purchaseItem.type') as $key=>$v)
                    @if($purchaseItem->type == $key)
                        {{$v}}
                    @endif
                @endforeach
            </td>
            <td>{{$purchaseItem->sku_id}}</td>
            <td><img src="{{ asset($purchaseItem->purchaseItem->product->image->src) }}" width="50px"></td>
            <td>{{$purchaseItem->purchase_num}}/{{$purchaseItem->arrival_num}}/{{$purchaseItem->lack_num}}</td>
            <td>
           	<select id="itemStatus_{{$purchaseItem->id}}">
             @foreach(config('purchase.purchaseItem.status') as $key=>$v)
            	<option value="{{$key}}" 
                @if($purchaseItem->status == $key)
                        selected = "selected"
                    @endif>{{$v}}</option>
             @endforeach
            </select> 
             
              <a href="javascript:" class="btn btn-info btn-xs examine_model" data-id="{{ $purchaseItem->id }}">
                        <span class="glyphicon glyphicon-check"></span> <span class='examine_{{$purchaseItem->id}}'>确认</span>
                    </a>
             </td>
            <td>物流单号：<input type="text" value="{{$purchaseItem->post_coding}}" id="postCoding_{{$purchaseItem->id}}"/>
            物流费：<input type="text" value="{{$purchaseItem->postage}}" id="postFee_{{$purchaseItem->id}}"/>
              <a href="javascript:" class="btn btn-info btn-xs form_postCoding" data-id="{{ $purchaseItem->id }}">
                        <span class="glyphicon glyphicon-check"></span> <span class='examine_{{$purchaseItem->id}}'>确认</span>
                    </a></td>
              <!--<td><input type="text" value="{{$purchaseItem->supplier_cost}}" id="supplierCost_{{$purchaseItem->id}}"/>
              <a href="javascript:" class="btn btn-info btn-xs form_supplierCost" data-id="{{ $purchaseItem->id }}">
                        <span class="glyphicon glyphicon-check"></span> <span class='examine_{{$purchaseItem->id}}'>确认</span>
                    </a></td>   --> 
            <td>{{$purchaseItem->stock}}</td>
            <td>{{$purchaseItem->cost}}</td>
            <td>
                @foreach(config('purchase.purchaseItem.platforms') as $key=>$vo)
                    @if($purchaseItem->platform_id == $key)
                        {{$vo}}
                    @endif
                @endforeach
            </td>
             <td>
            <a href="http://{{$purchaseItem->supplier->url}}" text-decoration: none;>链接</a>
            </td> 
            <td>{{$purchaseItem->user_id}}</td>
            <td>{{$purchaseItem->created_at}}</td> 
        </tr>
        @endforeach
    </tbody>
    </table>
        </div>
    </div>
    
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-6">
                <strong>创建时间</strong>: {{ $model->created_at }}
            </div>
            <div class="col-lg-6">
                <strong>更新时间</strong>: {{ $model->updated_at }}
            </div>
        </div>
    </div>

 <script type="text/javascript">
 $('.examine_model').click(function () {
            var purchase_id = $(this).data('id');
			var itemStatus=$('#itemStatus_'+purchase_id).val();
            var url = "/purchaseOrder/changeStatus";
                $.ajax({
                    url:url,
                    data:{purchaseItem_id:purchase_id,itemStatus:itemStatus},
                    dataType:'json',
                    type:'get',
                    success:function(result){
                        if(result==1){
                            alert("成功更改状态");
                       }                    
                    }                  
                })
        });
 $('.form_postCoding').click(function () {
            var purchase_id = $(this).data('id');
			var postCoding=$('#postCoding_'+purchase_id).val();
			var postFee=$('#postFee_'+purchase_id).val();
            var url = "/purchaseOrder/form_postCoding";
                $.ajax({
                    url:url,
                    data:{purchaseItem_id:purchase_id,postCoding:postCoding,postFee:postFee},
                    dataType:'json',
                    type:'get',
                    success:function(result){
                        if(result==1){
                            alert("已提交物流单号");
                       }                    
                    }                  
                })
        });
 </script>
@stop