@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基本信息</div>
        <div class="panel-body">
            <div class="col-lg-3">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-3">
                <strong>物流商名称</strong>: {{ $model->name }}
            </div>
            <div class="col-lg-3">
                <strong>客户ID</strong>: {{ $model->customer_id }}
            </div>
            <div class="col-lg-3">
                <strong>密码</strong>: {{ $model->password }}
            </div>
            <div class="col-lg-3">
                <strong>URL</strong>: {{ $model->url }}
            </div>
            <div class="col-lg-3">
                <strong>密钥</strong>: {{ $model->secret_key }}
            </div>
            <div class="col-lg-3">
                <strong>客户经理</strong>: {{ $model->client_manager }}
            </div>
            <div class="col-lg-3">
                <strong>客户经理联系方式</strong>: {{ $model->manager_tel }}
            </div>
            <div class="col-lg-3">
                <strong>技术人员</strong>: {{ $model->technician }}
            </div>
            <div class="col-lg-3">
                <strong>技术联系方式</strong>: {{ $model->technician_tel }}
            </div>
            <div class="col-lg-3">
                <strong>是否有API</strong>: {{ $model->is_api == '1' ? '有' : '没有' }}
            </div>
            <div class="col-lg-3">
                <strong>备注</strong>: {{ $model->remark }}
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