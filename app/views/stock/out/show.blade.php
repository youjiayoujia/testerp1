@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>ID</strong>: {{ $stockout->id }}
            </div>
            <div class="col-lg-2">
                <strong>sku</strong>: {{ $stockout->sku }}
            </div>
            <div class="col-lg-2">
                <strong>数量</strong>: {{ $stockout->amount }}
            </div>
            <div class="col-lg-2">
                <strong>总金额(￥)</strong>: {{ $stockout->total_amount }}
            </div>
            <div class="col-lg-2">
                <strong>仓库</strong>: {{ $stockout->warehouse->name }}
            </div>
            <div class="col-lg-2">
                <strong>库位</strong>: {{ $stockout->position->name }}
            </div>
            <div class="col-lg-2">
                <strong>出库类型</strong>: {{ $stockout->type_name }}
            </div>
            <div class="col-lg-2">
                <strong>出库类型id</strong>: {{ $stockout->relation_id }}
            </div>
            <div class="col-lg-2">
                <strong>备注</strong>: {{ $stockout->remark }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-4">
                <strong>创建时间</strong>: {{ $stockout->created_at }}
            </div>
            <div class="col-lg-4">
                <strong>更新时间</strong>: {{ $stockout->updated_at }}
            </div>
        </div>
    </div>
@stop