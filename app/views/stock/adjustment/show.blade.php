@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>ID</strong>: {{ $adjustment->id }}
            </div>
            <div class="col-lg-2">
                <strong>item号</strong>: {{ $adjustment->item_id }}
            </div>
            <div class="col-lg-2">
                <strong>sku</strong>: {{ $adjustment->sku }}
            </div>
            <div class="col-lg-2">
                <strong>类型</strong>: {{ $adjustment->type }}
            </div>

            <div class="col-lg-2">
                <strong>仓库</strong>: {{ $adjustment->warehouse->name }}
            </div>
            <div class="col-lg-2">
                <strong>库位</strong>: {{ $adjustment->position->name }}
            </div>
            <div class="col-lg-2">
                <strong>数量</strong>: {{ $adjustment->amount }}
            </div>
            <div class="col-lg-2">
                <strong>金额(￥)</strong>: {{ $adjustment->total_amount }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-4">
                <strong>调整人</strong>: {{ $adjustment->adjust_man_id }}
            </div>
            <div class="col-lg-4">
                <strong>调整时间</strong>: {{ $adjustment->adjust_time }}
            </div>
            <div class="col-lg-4">
                <strong>审核状态</strong>: {{ $adjustment->status == 'Y' ? '已审核' : '未审核' }}
            </div>
            <div class="col-lg-4">
                <strong>审核人</strong>: {{ $adjustment->check_man_id }}
            </div>
            <div class="col-lg-4">
                <strong>审核时间</strong>: {{ $adjustment->check_time }}
            </div>
            <div class="col-lg-4">
                <strong>创建时间</strong>: {{ $adjustment->created_at }}
            </div>
        </div>
    </div>
@stop