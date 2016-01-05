@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>ID</strong>: {{ $warehouse->id }}
            </div>
            <div class="col-lg-2">
                <strong>名称</strong>: {{ $warehouse->name }}
            </div>
            <div class="col-lg-1">
                <strong>省</strong>: {{ $warehouse->province }}
            </div>
            <div class="col-lg-1">
                <strong>市</strong>: {{ $warehouse->city }}
            </div>
            <div class="col-lg-2">
                <strong>类型</strong>: {{ $warehouse->type }}
            </div>
            <div class="col-lg-2">
                <strong>容积(m3)</strong>: {{ $warehouse->volumn }}
            </div>
            <div class="col-lg-2">
                <strong>是否启用</strong>: {{ $warehouse->is_available == 'Y' ? '是' : '否' }}
            </div>
            <div class="col-lg-2">
                <strong>是否是默认仓</strong>: {{ $warehouse->is_default == 'Y' ? '是' : '否' }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-4">
                <strong>创建时间</strong>: {{ $warehouse->created_at }}
            </div>
            <div class="col-lg-4">
                <strong>更新时间</strong>: {{ $warehouse->updated_at }}
            </div>
        </div>
    </div>
@stop