@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-2">
                <strong>sku</strong>: {{ $model->stock ? $model->stock->items ? $model->stock->items->sku : '' : '' }}
            </div>
            <div class="col-lg-2">
                <strong>仓库</strong>: {{ $model->stock ? $model->stock->warehouse ? $model->stock->warehouse->name : '' : '' }}
            </div>
            <div class="col-lg-2">
                <strong>库位</strong>: {{ $model->stock ? $model->stock->position ? $model->stock->position->name : '' : '' }}
            </div>
            <div class="col-lg-2">
                <strong>期初数量</strong>: {{ $model->begin_quantity }}
            </div>
            <div class="col-lg-2">
                <strong>期初金额</strong>: {{ $model->begin_amount }}
            </div>
            <div class="col-lg-2">
                <strong>期末数量</strong>: {{ $model->over_quantity }}
            </div>
            <div class="col-lg-2">
                <strong>期末金额</strong>: {{ $model->over_amount }}
            </div>
            <div class="col-lg-2">
                <strong>结转时间</strong>: {{ $model->carry_over_time }}
            </div>
        </div>
    </div>
@stop