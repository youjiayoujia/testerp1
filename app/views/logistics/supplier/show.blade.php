@extends('layouts.default')
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">物流商详情 : {{ $supplier->name }} {{ $supplier->customer_id }}</div>
        <div class="panel-body">
            <dl class="dl-horizontal">
                <dt>ID</dt>
                <dd>{{ $supplier->id }}</dd>
                <dt>物流商名称</dt>
                <dd>{{ $supplier->name }}</dd>
                <dt>客户ID</dt>
                <dd>{{ $supplier->customer_id }}</dd>
                <dt>密钥</dt>
                <dd>{{ $supplier->secret_key }}</dd>
                <dt>是否有API</dt>
                <dd>{{ $supplier->is_api == 'Y' ? '有' : '没有' }}</dd>
                <dt>客户经理</dt>
                <dd>{{ $supplier->client_manager }}</dd>
                <dt>客户经理联系方式</dt>
                <dd>{{ $supplier->manager_tel }}</dd>
                <dt>技术人员</dt>
                <dd>{{ $supplier->technician }}</dd>
                <dt>技术联系方式</dt>
                <dd>{{ $supplier->technician_tel }}</dd>
                <dt>备注</dt>
                <dd>{{ $supplier->remark }}</dd>
                <dt>创建时间</dt>
                <dd>{{ $supplier->created_at }}</dd>
                <dt>更新时间</dt>
                <dd>{{ $supplier->updated_at }}</dd>
            </dl>
        </div>
    </div>
@stop