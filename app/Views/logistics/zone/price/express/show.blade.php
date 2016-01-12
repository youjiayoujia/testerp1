@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基本信息</div>
        <div class="panel-body">
            <div class="col-lg-4">
                <strong>ID</strong>: {{ $zonePriceExpress->id }}
            </div>
            <div class="col-lg-4">
                <strong>物流分区价格</strong>: {{ $zonePriceExpress->name }}
            </div>
            <div class="col-lg-4">
                <strong>种类</strong>: {{ $zonePriceExpress->logistics->species }}
            </div>
            <div class="col-lg-4">
                <strong>首重</strong>: {{ $zonePriceExpress->fixed_weight }}
            </div>
            <div class="col-lg-4">
                <strong>首重价格</strong>: {{ $zonePriceExpress->fixed_price }}
            </div>
            <div class="col-lg-4">
                <strong>续重</strong>: {{ $zonePriceExpress->continued_weight }}
            </div>
            <div class="col-lg-4">
                <strong>续重价格</strong>: {{ $zonePriceExpress->continued_price }}
            </div>
            <div class="col-lg-4">
                <strong>其他固定费用</strong>: {{ $zonePriceExpress->other_fixed_price }}
            </div>
            <div class="col-lg-4">
                <strong>其他比例费用</strong>: {{ $zonePriceExpress->other_scale_price }}
            </div>
            <div class="col-lg-4">
                <strong>最后折扣</strong>: {{ $zonePriceExpress->discount }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-6">
                <strong>创建时间</strong>: {{ $zonePriceExpress->created_at }}
            </div>
            <div class="col-lg-6">
                <strong>更新时间</strong>: {{ $zonePriceExpress->updated_at }}
            </div>
        </div>
    </div>
@stop