@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-1">
                <strong>sku</strong>: {{ $model->items ? $model->items->sku : '' }}
            </div>
            <div class="col-lg-1">
                <strong>仓库</strong>: {{ $model->warehouse ? $model->warehouse->name : ''}}
            </div>
            <div class="col-lg-2">
                <strong>库位</strong>: {{ $model->position ? $model->position->name : ''}}
            </div>
            <div class="col-lg-2">
                <strong>总数量</strong>: {{ $model->all_quantity }}
            </div>
            <div class="col-lg-2">
                <strong>可用数量</strong>: {{ $model->available_quantity }}
            </div>
            <div class="col-lg-2">
                <strong>hold数量</strong>: {{ $model->hold_quantity }}
            </div>
            <div class="col-lg-2">
                <strong>单价(￥)</strong>: {{ round($model->amount/$model->all_quantity, 2) }}
            </div>
            <div class="col-lg-2">
                <strong>总金额(￥)</strong>: {{ $model->amount }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-4">
                <strong>创建时间</strong>: {{ $model->created_at }}
            </div>
            <div class="col-lg-4">
                <strong>更新时间</strong>: {{ $model->updated_at }}
            </div>
        </div>
    </div>
@stop