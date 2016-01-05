@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>ID</strong>: {{ $stockin->id }}
            </div>
            <div class="col-lg-2">
                <strong>item号</strong>: {{ $stockin->item_id }}
            </div>
            <div class="col-lg-2">
                <strong>sku</strong>: {{ $stockin->sku }}
            </div>
            <div class="col-lg-2">
                <strong>数量</strong>: {{ $stockin->amount }}
            </div>
            <div class="col-lg-2">
                <strong>总金额(￥)</strong>: {{ $stockin->total_amount }}
            </div>
            <div class="col-lg-2">
                <strong>仓库</strong>: {{ $stockin->warehouse->name }}
            </div>
            <div class="col-lg-2">
                <strong>库位</strong>: {{ $stockin->position->name }}
            </div>
            <div class="col-lg-2">
                <strong>入库类型</strong>: {{ $stockin->type_name }}
            </div>
            <div class="col-lg-2">
                <strong>入库类型id</strong>: {{ $stockin->relation_id }}
            </div>
            <div class="col-lg-2">
                <strong>remark</strong>: {{ $stockin->remark }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-4">
                <strong>创建时间</strong>: {{ $stockin->created_at }}
            </div>
            <div class="col-lg-4">
                <strong>更新时间</strong>: {{ $stockin->updated_at }}
            </div>
        </div>
    </div>
@stop