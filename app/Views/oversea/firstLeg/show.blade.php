@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-4">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-4">
                <strong>仓库</strong>: {{ $model->warehouse ? $model->warehouse->name : '' }}
            </div>
            <div class="col-lg-4">
                <strong>物流方式</strong>: {{ $model->name }}
            </div>
            <div class="col-lg-4">
                <strong>运输方式</strong>: {{ $model->transport }}
            </div>
            <div class="col-lg-4">
                <strong>公式</strong>: {{ $model->formula }}
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