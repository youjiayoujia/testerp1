@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-4">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-4">
                <strong>物流方式简码</strong>: {{ $model->short_code }}
            </div>
            <div class="col-lg-4">
                <strong>物流方式名称</strong>: {{ $model->logistics_type }}
            </div>
            <div class="col-lg-4">
                <strong>种类</strong>: {{ $model->species == 'express' ? '快递' : '小包' }}
            </div>
            <div class="col-lg-4">
                <strong>仓库</strong>: {{ $model->warehouse->name }}
            </div>
            <div class="col-lg-4">
                <strong>物流商</strong>: {{ $model->supplier->name }}
            </div>
            <div class="col-lg-4">
                <strong>物流商物流方式</strong>: {{ $model->type }}
            </div>
            <div class="col-lg-4">
                <strong>物流追踪网址</strong>: {{ $model->url }}
            </div>
            <div class="col-lg-4">
                <strong>对接方式</strong>: {{ $model->docking }}
            </div>
            <div class="col-lg-4">
                <strong>是否启用</strong>: {{ $model->is_enable == '1' ? '是' : '否' }}
            </div>
            <div class="col-lg-12">
                <strong>物流限制</strong>: {{ $model->limit }}
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