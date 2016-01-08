@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>ID</strong>: {{ $position->id }}
            </div>
            <div class="col-lg-2">
                <strong>名称</strong>: {{ $position->name }}
            </div>
            <div class="col-lg-1">
                <strong>仓库名</strong>: {{ $position->warehouse->name }}
            </div>
            <div class="col-lg-1">
                <strong>备注</strong>: {{ $position->remark }}
            </div>
            <div class="col-lg-2">
                <strong>库位大小</strong>: {{ $position->size }}
            </div>
            <div class="col-lg-2">
                <strong>是否启用</strong>: {{ $position->is_available == 'Y' ? '是' : '否' }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-4">
                <strong>创建时间</strong>: {{ $position->created_at }}
            </div>
            <div class="col-lg-4">
                <strong>更新时间</strong>: {{ $position->updated_at }}
            </div>
        </div>
    </div>
@stop