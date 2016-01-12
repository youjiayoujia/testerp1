@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基本信息</div>
        <div class="panel-body">
            <div class="col-lg-4">
                <strong>ID</strong>: {{ $zonePricePacket->id }}
            </div>
            <div class="col-lg-4">
                <strong>物流分区价格</strong>: {{ $zonePricePacket->name }}
            </div>
            <div class="col-lg-4">
                <strong>种类</strong>: {{ $zonePricePacket->shipping }}
            </div>
            <div class="col-lg-4">
                <strong>价格</strong>: {{ $zonePricePacket->price }}
            </div>
            <div class="col-lg-4">
                <strong>其他费用</strong>: {{ $zonePricePacket->other_price }}
            </div>
            <div class="col-lg-4">
                <strong>最后折扣</strong>: {{ $zonePricePacket->discount }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-6">
                <strong>创建时间</strong>: {{ $zonePricePacket->created_at }}
            </div>
            <div class="col-lg-6">
                <strong>更新时间</strong>: {{ $zonePricePacket->updated_at }}
            </div>
        </div>
    </div>
@stop