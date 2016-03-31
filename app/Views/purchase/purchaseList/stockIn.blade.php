@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="form-group col-lg-4">
                <strong>ID</strong>: {{ $model->id }}
            </div>
             <div class="form-group col-lg-4">
                <strong>采购类型</strong>:
               @foreach(config('purchase.purchaseItem.type') as $k=>$v)
            	@if($model->type == $k)
            		{{$v}}
                @endif
            	@endforeach
            </div>
            <div class="form-group col-lg-4">
                <strong>采购状态</strong>:
               @foreach(config('purchase.purchaseItem.status') as $k=>$val)
            	@if($model->status == $k)
            		{{$val}}
                @endif
            	@endforeach
            </div>
            @if($model->type==0)
            <div class="form-group col-lg-4">
                <strong>订单号</strong>:
                {{$model->order_id}}
            </div>
            @endif
            <div class="form-group col-lg-4">
                <strong>仓库</strong>:
              {{$model->warehouse->name}}
            </div>
             <div class="form-group col-lg-4">
                <strong>产品名</strong>:
                {{$model->purchaseItem->product->name}}
            </div>
            <div class="form-group col-lg-4">
                <strong>生成条码</strong>:
                <a href="/purchaseList/generateDarCode/{{$model->id}}">生成条码</a>
            </div>
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
