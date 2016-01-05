@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-4">
                <strong>ID</strong>: {{ $channel->id }}
            </div>
            <div class="col-lg-4">
                <strong>名称</strong>: {{ $channel->name }}
            </div>
            <div class="col-lg-4">
                <strong>别名</strong>: {{ $channel->alias }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-6">
                <strong>创建时间</strong>: {{ $channel->created_at }}
            </div>
            <div class="col-lg-6">
                <strong>更新时间</strong>: {{ $channel->updated_at }}
            </div>
        </div>
    </div>
@stop