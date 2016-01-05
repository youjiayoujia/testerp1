@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-4">
                <strong>ID</strong>: {{ $logistics->id }}
            </div>
            <div class="col-lg-4">
                <strong>物流方式简码</strong>: {{ $logistics->short_code }}
            </div>
            <div class="col-lg-4">
                <strong>物流方式名称</strong>: {{ $logistics->logistics_type }}
            </div>
            <div class="col-lg-4">
                <strong>种类</strong>: {{ $logistics->species == 'express' ? '快递' : '小包' }}
            </div>
            <div class="col-lg-4">
                <strong>仓库</strong>: {{ $logistics->warehouse->name }}
            </div>
            <div class="col-lg-4">
                <strong>物流商</strong>: {{ $logistics->supplier->name }}
            </div>
            <div class="col-lg-4">
                <strong>物流商物流方式</strong>: {{ $logistics->type }}
            </div>
            <div class="col-lg-4">
                <strong>物流追踪网址</strong>: {{ $logistics->url }}
            </div>
            <div class="col-lg-4">
                <strong>API对接方式</strong>: {{ $logistics->api_docking }}
            </div>
            <div class="col-lg-4">
                <strong>是否启用</strong>: {{ $logistics->is_enable == 'Y' ? '是' : '否' }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-6">
                <strong>创建时间</strong>: {{ $logistics->created_at }}
            </div>
            <div class="col-lg-6">
                <strong>更新时间</strong>: {{ $logistics->updated_at }}
            </div>
        </div>
    </div>
@stop