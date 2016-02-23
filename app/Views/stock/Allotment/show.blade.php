@extends('common.detail')
@section('detailBody')
<div class="panel panel-default">
    <div class="panel-heading">调拨单基础信息</div>
    <div class="panel-body">
        <div class="col-lg-2">
            <strong>ID</strong>: {{ $model->id }}
        </div>
        <div class="col-lg-2">
            <strong>调拨单号</strong>: {{ $model->allotment_id }}
        </div>
        <div class="col-lg-2">
            <strong>调出仓库</strong>: {{ $model->outwarehouse ? $model->outwarehouse->name : '' }}
        </div>
        <div class="col-lg-2">
            <strong>调入仓库</strong>: {{ $model->inwarehouse ? $model->inwarehouse->name : '' }}
        </div>
        <div class="col-lg-2">
            <strong>备注</strong>: {{ $model->remark }}
        </div>
        <div class="col-lg-2">
            <strong>调拨人</strong>: {{ $model->allotment_by }}
        </div>
        <div class="col-lg-2">
            <strong>调拨时间</strong>: {{ $model->allotment_time }}
        </div>
        <div class="col-lg-2">
            <strong>调拨状态</strong>: {{ $model->status_name }}
        </div>
        <div class="col-lg-2">
            <strong>对单人</strong>: {{ $model->checkform_by }}
        </div>
        <div class="col-lg-2">
            <strong>对单时间</strong>: {{ $model->checkform_time }}
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">调拨单sku列表</div>
    <div class="panel-body">
    @foreach($allotments as $allotment)
    <div class='row'>
        <div class="col-lg-2">
            <strong>sku</strong>: {{ $allotment->items ? $allotment->items->sku : '' }}
        </div>
        <div class="col-lg-2">
            <strong>库位</strong>: {{ $allotment->position ? $allotment->position->name : '' }}
        </div>
        <div class="col-lg-2">
            <strong>数量</strong>: {{ $allotment->quantity }}
        </div>
        <div class="col-lg-2">
            <strong>金额</strong>: {{ $allotment->amount }}
        </div>
    </div>
    @endforeach
    </div>
   
</div>
<div class="panel panel-default">
    <div class="panel-heading">物流信息</div>
    <div class="panel-body">
        <div class="col-lg-2">
            <strong>物流名称</strong>: {{ isset($model->logistics->type) ? $model->logistics->type : '' }}
        </div>
        <div class="col-lg-2">
            <strong>物流号</strong>: {{ isset($model->logistics->code) ? $model->logistics->code : '' }}
        </div>
        <div class="col-lg-2">
            <strong>物流费</strong>: {{ isset($model->logistics->fee) ? $model->logistics->fee : ''}}
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">出库信息</div>
    <div class="panel-body">
    @foreach($stockouts as $stockout)
        <div class='row'>
            <div class="col-lg-2">
                <strong>sku</strong>: {{ $stockout->stock ? $stockout->stock->items ? $stockout->stock->items->sku : '' : '' }}
            </div>
            <div class="col-lg-2">
                <strong>出库数量</strong>: {{ $stockout->quantity }}
            </div>
            <div class="col-lg-2">
                <strong>出库金额(￥)</strong>: {{ $stockout->amount }}
            </div>
            <div class="col-lg-2">
                <strong>出库库位</strong>: {{ $stockout->stock ? $stockout->stock->position ? $stockout->stock->position->name : '' : '' }}
            </div>
        </div>
    @endforeach
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">入库信息</div>
    <div class="panel-body">
    @foreach($stockins as $stockin)
    <div class='row'>
        <div class="col-lg-2">
            <strong>sku</strong>: {{ $stockin->stock ? $stockin->stock->items ? $stockin->stock->items->sku : '' : '' }}
        </div>
        <div class="col-lg-2">
            <strong>入库数量</strong>: {{ $stockin->quantity }}
        </div>
        <div class="col-lg-2">
            <strong>入库金额(￥)</strong>: {{ $stockin->amount }}
        </div>
        <div class="col-lg-2">
            <strong>入库库位</strong>: {{ $stockin->stock ? $stockin->stock->position ? $stockin->stock->position->name : '' : '' }}
        </div>
    </div>
    @endforeach
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">日志信息</div>
    <div class="panel-body">
        <div class="col-lg-2">
            <strong>审核人</strong>: {{ $model->check_by }}
        </div>
        <div class="col-lg-2">
            <strong>审核状态</strong>: {{ $model->check_status == 'N' ? '未审核' : '已审核' }}
        </div>
        <div class="col-lg-2">
            <strong>审核时间</strong>: {{ $model->check_time }}
        </div>
        <div class="col-lg-2">
            <strong>创建时间</strong>: {{ $model->created_at }}
        </div>
        <div class="col-lg-2">
            <strong>更新时间</strong>: {{ $model->updated_at }}
        </div>
    </div>
</div>
@stop