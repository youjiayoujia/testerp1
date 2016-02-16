@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-2">
                <strong>调拨单号</strong>: {{ $model->allotment_id }}
            </div>
            <div class="col-lg-2">
                <strong>调出仓库</strong>: {{ $model->outwarehouse->name }}
            </div>
            <div class="col-lg-2">
                <strong>调入仓库</strong>: {{ $model->inwarehouse->name }}
            </div>
            <div class="col-lg-2">
                <strong>备注</strong>: {{ $model->remark }}
            </div>
            <div class="col-lg-2">
                <strong>调拨人</strong>: {{ $model->allotment_man_id }}
            </div>
            <div class="col-lg-2">
                <strong>调拨时间</strong>: {{ $model->allotment_time }}
            </div>
            <div class="col-lg-2">
                <strong>调拨状态</strong>: {{ $model->status_name }}
            </div>
            <div class="col-lg-2">
                <strong>对单人</strong>: {{ $model->checkform_man_id }}
            </div>
            <div class="col-lg-2">
                <strong>对单时间</strong>: {{ $model->checkform_time }}
            </div>
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
                <strong>数量</strong>: {{ $allotmentform->quantity }}
            </div>
            <div class="col-lg-2">
                <strong>金额(￥)</strong>: {{ $allotmentform->amount }}
            </div>
            <div class="col-lg-2">
                <strong>入库数量</strong>: {{ isset($allotmentform->receive_quantity) ? $allotmentform->receive_quantity : '' }}
            </div>
            <div class="col-lg-2">
                <strong>入库库位</strong>: {{ isset($allotmentform->inposition->name) ? $allotmentform->inposition->name : '' }}
            </div>
        </div>
    </div>
    @endforeach
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>审核人</strong>: {{ $model->check_man_id }}
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