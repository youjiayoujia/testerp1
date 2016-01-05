@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基本信息</div>
        <div class="panel-body">
            <div class="col-lg-4">
                <strong>ID</strong>: {{ $code->id }}
            </div>
            <div class="col-lg-4">
                <strong>物流方式</strong>: {{ $code->logistics_id }}
            </div>
            <div class="col-lg-4">
                <strong>跟踪号</strong>: {{ $code->code }}
            </div>
            <div class="col-lg-4">
                <strong>包裹ID</strong>: {{ $code->package_id }}
            </div>
            <div class="col-lg-4">
                <strong>状态</strong>: {{ $code->status == 'Y' ? '启用' : '未启用' }}
            </div>
            <div class="col-lg-4">
                <strong>使用时间</strong>: {{ $code->used_at }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-6">
                <strong>创建时间</strong>: {{ $code->created_at }}
            </div>
            <div class="col-lg-6">
                <strong>更新时间</strong>: {{ $code->updated_at }}
            </div>
        </div>
    </div>
@stop