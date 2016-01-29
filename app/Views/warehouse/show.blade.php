@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-2">
                <strong>名称</strong>: {{ $model->name }}
            </div>
            <div class="col-lg-1">
                <strong>省</strong>: {{ $model->province }}
            </div>
            <div class="col-lg-1">
                <strong>市</strong>: {{ $model->city }}
            </div>
            <div class="col-lg-2">
                <strong>类型</strong>: {{ $model->type }}
            </div>
            <div class="col-lg-2">
                <strong>容积(m3)</strong>: {{ $model->volumn }}
            </div>
            <div class="col-lg-2">
                <strong>是否启用</strong>: {{ $model->is_available == 'Y' ? '是' : '否' }}
            </div>
            <div class="col-lg-2">
                <strong>是否是默认仓</strong>: {{ $model->is_default == 'Y' ? '是' : '否' }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-4">
                <strong>创建时间</strong>: {{ $model->created_at }}
            </div>
            <div class="col-lg-4">
                <strong>更新时间</strong>: {{ $model->updated_at }}
            </div>
        </div>
    </div>
@stop