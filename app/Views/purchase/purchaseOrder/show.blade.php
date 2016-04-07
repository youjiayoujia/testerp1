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
           @if($model->examineStatus==2)
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
            @endif
            <div class="form-group col-lg-4">
                <strong>采购单状态</strong>:
               @foreach(config('purchase.purchaseOrder.status') as $k=>$val)
            	@if($model->status == $k)
            		{{$val}}
                @endif
            	@endforeach
            </div>
            <div class="form-group col-lg-4">
                <strong>采购单审核状态</strong>:     
            	@if($model->examineStatus == 0)
                    <a href="/purchaseOrder/changeExamineStatus/{{$model->id}}/1" class="btn btn-info btn-xs"> 审核不通过
                </a> 
                <a href="/purchaseOrder/changeExamineStatus/{{$model->id}}/2" class="btn btn-info btn-xs"> 审核通过
                </a>
                 @elseif($model->examineStatus == 1)
                 审核不通过
                 @else
                 审核通过
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
            <td>SKU</td> 
            <td>样图</td>
            <td>采购数量/已到数量/仍需采购数量</td>
            <td>供应商</td>
            <td>仓库</td>
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
            <td>{{$purchaseItem->sku}}</td>
            <td><img src="{{ asset($purchaseItem->item->product->image->src) }}" width="50px"></td>
            <td>{{$purchaseItem->purchase_num}}/{{$purchaseItem->arrival_num}}/{{$purchaseItem->lack_num}}</td>
            <td>{{$purchaseItem->supplier->name}}</td>
            <td>{{$purchaseItem->warehouse->name}}</td>
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
@stop
