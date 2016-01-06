@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-4">
                <img src="{{ asset($image->src) }}" width="100px">
            </div>
            <div class="col-lg-4">
                <strong>ID</strong>: {{ $image->id }}
            </div>
            <div class="col-lg-4">
                <strong>图片类型</strong>: {{$image ->type }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-6">
                <strong>创建时间</strong>: {{ $image->created_at }}
            </div>
            <div class="col-lg-6">
                <strong>更新时间</strong>: {{ $image->updated_at }}
            </div>
        </div>
    </div>
@stop
