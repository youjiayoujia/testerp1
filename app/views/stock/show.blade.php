@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>ID</strong>: {{ $stock->id }}
            </div>
            <div class="col-lg-2">
                <strong>item号</strong>: {{ $stock->item_id }}
            </div>
            <div class="col-lg-1">
                <strong>sku</strong>: {{ $stock->sku }}
            </div>
            <div class="col-lg-1">
                <strong>仓库</strong>: {{ $stock->warehouse->name }}
            </div>
            <div class="col-lg-2">
                <strong>库位</strong>: {{ $stock->position->name }}
            </div>
            <div class="col-lg-2">
                <strong>总数量</strong>: {{ $stock->all_amount }}
            </div>
            <div class="col-lg-2">
                <strong>可用数量</strong>: {{ $stock->available_amount }}
            </div>
            <div class="col-lg-2">
                <strong>hold数量</strong>: {{ $stock->hold_amount }}
            </div>
            <div class="col-lg-2">
                <strong>总金额(￥)</strong>: {{ $stock->total_amount }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-4">
                <strong>创建时间</strong>: {{ $stock->created_at }}
            </div>
            <div class="col-lg-4">
                <strong>更新时间</strong>: {{ $stock->updated_at }}
            </div>
        </div>
    </div>
@stop