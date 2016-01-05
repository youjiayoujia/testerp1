@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基本信息</div>
        <div class="panel-body">
            <div class="col-lg-4">
                <strong>ID</strong>: {{ $supplier->id }}
            </div>
            <div class="col-lg-4">
                <strong>物流商名称</strong>: {{ $supplier->name }}
            </div>
            <div class="col-lg-4">
                <strong>客户ID</strong>: {{ $supplier->customer_id }}
            </div>
            <div class="col-lg-4">
                <strong>密钥</strong>: {{ $supplier->secret_key }}
            </div>
            <div class="col-lg-4">
                <strong>是否有API</strong>: {{ $supplier->is_api == 'Y' ? '有' : '没有' }}
            </div>
            <div class="col-lg-4">
                <strong>客户经理</strong>: {{ $supplier->client_manager }}
            </div>
            <div class="col-lg-4">
                <strong>客户经理联系方式</strong>: {{ $supplier->manager_tel }}
            </div>
            <div class="col-lg-4">
                <strong>技术人员</strong>: {{ $supplier->technician }}
            </div>
            <div class="col-lg-4">
                <strong>技术联系方式</strong>: {{ $supplier->technician_tel }}
            </div>
            <div class="col-lg-4">
                <strong>备注</strong>: {{ $supplier->remark }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-6">
                <strong>创建时间</strong>: {{ $supplier->created_at }}
            </div>
            <div class="col-lg-6">
                <strong>更新时间</strong>: {{ $supplier->updated_at }}
            </div>
        </div>
    </div>
@stop