@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>ID</strong>: {{ $adjust->id }}
            </div>
            <div class="col-lg-2">
                <strong>仓库</strong>: {{ $adjust->warehouse ? $adjust->warehouse->name : '' }}
            </div>
            <div class="col-lg-2">
                <strong>备注</strong>: {{ $adjust->remark }}
            </div>
        </div>
    </div>
    @foreach($adjustments as $adjustment)
    <div class="panel panel-default">
        <div class="panel-heading">调动信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>sku</strong>: {{ $adjustment->items->sku }}
            </div>
            <div class="col-lg-2">
                <strong>type</strong>: {{ $adjustment->type == 'IN' ? '入库' : '出库'}}
            </div>
            <div class="col-lg-2">
                <strong>库位</strong>: {{ $adjustment->position ? $adjustment->position->name : '' }}
            </div>
            <div class="col-lg-2">
                <strong>数量</strong>: {{ $adjustment->quantity }}
            </div>
            <div class="col-lg-2">
                <strong>金额(￥)</strong>: {{ $adjustment->amount }}
            </div>
        </div>
    </div>
    @endforeach
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-4">
                <strong>调整人</strong>: {{ $adjust->adjustByName ? $adjust->adjustByName->name : '' }}
            </div>
            <div class="col-lg-4">
                <strong>审核状态</strong>: {{ $adjust->status == 'Y' ? '已审核' : '未审核' }}
            </div>
            <div class="col-lg-4">
                <strong>审核人</strong>: {{ $adjust->checkByName ? $adjust->checkByName->name : '' }}
            </div>
            <div class="col-lg-4">
                <strong>审核时间</strong>: {{ $adjust->check_time }}
            </div>
            <div class="col-lg-4">
                <strong>创建时间</strong>: {{ $adjust->created_at }}
            </div>
        </div>
    </div>
@stop