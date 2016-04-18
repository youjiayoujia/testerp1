@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基本信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-2">
                <strong>优先级</strong>: {{ $model->priority }}
            </div>
            <div class="col-lg-2">
                <strong>重量从(kg)</strong>: {{ $model->weight_from }}
            </div>
            <div class="col-lg-2">
                <strong>重量至(kg)</strong>: {{ $model->weight_to }}
            </div>
            <div class="col-lg-2">
                <strong>订单金额($)</strong>: {{ $model->order_amount }}
            </div>
            <div class="col-lg-2">
                <strong>是否通关</strong>: {{ $model->is_clearance == '1' ? '是' : '否' }}
            </div>
            <div class="col-lg-12">
                <strong>国家</strong>: {{ $model->country }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-6">
                <strong>创建时间</strong>: {{ $model->created_at }}
            </div>
            <div class="col-lg-6">
                <strong>更新时间</strong>: {{ $model->updated_at }}
            </div>
        </div>
    </div>
@stop