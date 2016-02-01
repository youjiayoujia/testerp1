@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-4">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-4">
                <strong>物流商物流方式</strong>: {{ $model->logistics_type }}
            </div>
            <div class="col-lg-4">
                <strong>物流商</strong>: {{ $model->supplier->name }}
            </div>
            <div class="col-lg-4">
                <strong>备注</strong>: {{ $model->remark }}
            </div>
        </div>
    </div>
@stop