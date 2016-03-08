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
        </div>
    </div>
     <div class="panel panel-default">
        <div class="panel-heading">单身</div>
        <div class="panel-body">
        @foreach($purchaseItems as $key=>$v)
             <div class="form-group col-lg-3">
                <strong>仓库</strong>:
              {{$v->warehouse->name}}
            </div>
             <div class="form-group col-lg-3">
                <strong>产品名</strong>:
                {{$v->purchaseItemImage->product->name}}
            </div>
            <div class="form-group col-lg-3">
                <strong>采购数量/已到数量/仍需采购数量</strong>:
              	{{$v->purchase_num}}/{{$v->arrival_num}}/{{$v->lack_num}}
            </div>
            <div class="form-group col-lg-3">
           		<strong>产品样图</strong>:
                 <img src="{{ asset($v->purchaseItemImage->product->image->src) }}" width="50px">
            </div>
            @endforeach
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
