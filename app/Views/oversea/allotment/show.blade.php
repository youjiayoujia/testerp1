@extends('common.detail')
@section('detailBody')
<div class="panel panel-default">
    <div class="panel-heading">调拨单基础信息</div>
    <div class="panel-body">
        <div class="col-lg-2">
            <strong>ID</strong>: {{ $model->id }}
        </div>
        <div class="col-lg-2">
            <strong>调拨单号</strong>: {{ $model->allotment_num }}
        </div>
        <div class="col-lg-2">
            <strong>调出仓库</strong>: {{ $model->outWarehouse ? $model->outWarehouse->name : '' }}
        </div>
        <div class="col-lg-2">
            <strong>调入仓库</strong>: {{ $model->inWarehouse ? $model->inWarehouse->name : '' }}
        </div>
        <div class="col-lg-2">
            <strong>调拨人</strong>: {{ $model->allotmentBy ? $model->allotmentBy->name : '' }}
        </div>
        <div class="col-lg-2">
            <strong>状态</strong>: {{ config('oversea.allotmentStatus')[$model->status] }}
        </div>
        <div class="col-lg-2">
            <strong>审核人</strong>: {{ $model->checkBy ? $model->checkBy->name : '' }}
        </div>
        <div class="col-lg-2">
            <strong>审核状态</strong>: {{ $model->check_status == 'new' ? '未审核' : ($model->check_status == 'fail' ? '未审核' : '已审核') }}
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">调拨单sku列表</div>
    <div class="panel-body">
    @foreach($allotments as $allotment)
    <div class='row'>
        <div class="col-lg-2">
            <strong>sku</strong>: {{ $allotment->item ? $allotment->item->sku : '' }}
        </div>
        <div class="col-lg-2">
            <strong>库位</strong>: {{ $allotment->position ? $allotment->position->name : '' }}
        </div>
        <div class="col-lg-2">
            <strong>数量</strong>: {{ $allotment->quantity }}
        </div>
    </div>
    @endforeach
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">日志信息</div>
    <div class="panel-body">
        <div class="col-lg-2">
            <strong>创建时间</strong>: {{ $model->created_at }}
        </div>
        <div class="col-lg-2">
            <strong>更新时间</strong>: {{ $model->updated_at }}
        </div>
    </div>
</div>
@stop