@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基本信息</div>
        <div class="panel-body">
            <div class="col-lg-4">
                <strong>ID</strong>: {{ $zone->id }}
            </div>
            <div class="col-lg-4">
                <strong>物流分区</strong>: {{ $zone->name }}
            </div>
            <div class="col-lg-4">
                <strong>物流方式</strong>: {{ $zone->logistics->logistics_type }}
            </div>
            <div class="col-lg-4">
                <strong>国家</strong>: {{ $zone->countries }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-6">
                <strong>创建时间</strong>: {{ $zone->created_at }}
            </div>
            <div class="col-lg-6">
                <strong>更新时间</strong>: {{ $zone->updated_at }}
            </div>
        </div>
    </div>
@stop