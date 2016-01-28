@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-2">
                <strong>item号</strong>: {{ $model->item_id }}
            </div>
            <div class="col-lg-2">
                <strong>sku</strong>: {{ $model->sku }}
            </div>
            <div class="col-lg-2">
                <strong>数量</strong>: {{ $model->amount }}
            </div>
            <div class="col-lg-2">
                <strong>总金额(￥)</strong>: {{ $model->total_amount }}
            </div>
            <div class="col-lg-2">
                <strong>仓库</strong>: {{ $model->warehouse->name }}
            </div>
            <div class="col-lg-2">
                <strong>库位</strong>: {{ $model->position->name }}
            </div>
            <div class="col-lg-2">
                <strong>入库类型</strong>: {{ $model->type_name }}
            </div>
            <div class="col-lg-2">
                <strong>入库类型id</strong>: {{ $model->relation_id }}
            </div>
            <div class="col-lg-2">
                <strong>remark</strong>: {{ $model->remark }}
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