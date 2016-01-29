@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-3">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-3">
                <strong>渠道</strong>: {{ $model->channel->name }}
            </div>
            <div class="col-lg-3">
                <strong>账号</strong>: {{ $model->account }}
            </div>
            <div class="col-lg-3">
                <strong>名称</strong>: {{ $model->title }}
            </div>
            <div class="col-lg-3">
                <strong>前缀</strong>: {{ $model->prefix }}
            </div>
            <div class="col-lg-3">
                <strong>国家</strong>: {{ $model->country }}
            </div>
            <div class="col-lg-3">
                <strong>币种</strong>: {{ $model->currency }}
            </div>
            <div class="col-lg-12">
                <strong>简介</strong>: {{ $model->brief }}
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