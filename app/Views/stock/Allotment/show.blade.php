@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>ID</strong>: {{ $allotment->id }}
            </div>
            <div class="col-lg-2">
                <strong>调拨单号</strong>: {{ $allotment->allotment_id }}
            </div>
            <div class="col-lg-2">
                <strong>调出仓库</strong>: {{ $allotment->outwarehouse->name }}
            </div>
            <div class="col-lg-2">
                <strong>调入仓库</strong>: {{ $allotment->inwarehouse->name }}
            </div>
            <div class="col-lg-2">
                <strong>备注</strong>: {{ $allotment->remark }}
            </div>
            <div class="col-lg-2">
                <strong>调拨人</strong>: {{ $allotment->allotment_man_id }}
            </div>
            <div class="col-lg-2">
                <strong>调拨时间</strong>: {{ $allotment->allotment_time }}
            </div>
            <div class="col-lg-2">
                <strong>调拨状态</strong>: {{ $allotment->status_name }}
            </div>
        </div>
    </div>
    @foreach($allotmentforms as $allotmentform)
    <div class="panel panel-default">
        <div class="panel-heading">调动信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>库位</strong>: {{ $allotmentform->position->name }}
            </div>
            <div class="col-lg-2">
                <strong>item号</strong>: {{ $allotmentform->item_id }}
            </div>
            <div class="col-lg-2">
                <strong>sku</strong>: {{ $allotmentform->sku }}
            </div>
            <div class="col-lg-2">
                <strong>数量</strong>: {{ $allotmentform->amount }}
            </div>
            <div class="col-lg-2">
                <strong>金额(￥)</strong>: {{ $allotmentform->total_amount }}
            </div>
        </div>
    </div>
    @endforeach
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>审核人</strong>: {{ $allotment->check_man_id }}
            </div>
            <div class="col-lg-2">
                <strong>审核状态</strong>: {{ $allotment->check_status == 'N' ? '未审核' : '已审核' }}
            </div>
            <div class="col-lg-2">
                <strong>审核时间</strong>: {{ $allotment->check_time }}
            </div>
            <div class="col-lg-2">
                <strong>创建时间</strong>: {{ $allotment->created_at }}
            </div>
            <div class="col-lg-2">
                <strong>更新时间</strong>: {{ $allotment->updated_at }}
            </div>
        </div>
    </div>
@stop