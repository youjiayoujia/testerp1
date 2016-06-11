@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基本信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-2">
                <strong>物流方式</strong>: {{ $model->logistics->type }}
            </div>
            <div class="col-lg-2">
                <strong>物流方式简码</strong>: {{ $model->logistics->short_code }}
            </div>
            <div class="col-lg-2">
                <strong>优先级</strong>: {{ $model->priority }}
            </div>
            <div class="col-lg-2">
                <strong>重量从(kg)</strong>: {{ $model->weight_from }}
            </div>
            <div class="col-lg-2">
                <strong>重量至(kg)</strong>: {{ $model->weight_to }}
            </div>
            <div class="col-lg-2">
                <strong>起始订单金额($)</strong>: {{ $model->order_amount_from }}
            </div>
            <div class="col-lg-2">
                <strong>结束订单金额($)</strong>: {{ $model->order_amount_to }}
            </div>
            <div class="col-lg-2">
                <strong>是否通关</strong>: {{ $model->is_clearance == '1' ? '是' : '否' }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">国家</div>
        <div class="panel-body">
        <div class='form-group'>
            @foreach($countries as $country)
            <div class='col-lg-2'>
                <input type='text' class='form-control' value="{{ $country->cn_name}}">
            </div>
            @endforeach
        </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">渠道</div>
        <div class="panel-body">
        <div class='form-group'>
            @foreach($channels as $channel)
            <div class='col-lg-2'>
                <input type='text' class='form-control' value="{{ $channel->name}}">
            </div>
            @endforeach
        </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">品类</div>
        <div class="panel-body">
            <div class='form-group'>
                @foreach($catalogs as $catalog)
                <div class='col-lg-2'>
                    <input type='text' class='form-control' value="{{ $catalog->name}}">
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">物流限制</div>
        <div class="panel-body">
        <div class='form-group'>
            @foreach($limits as $limit)
            <div class='col-lg-2'>
                <label>{{ $limit->name }}</label>
                <input type='text' class='form-control' value="{{ $limit->pivot->type == '0' ? '含' : ($limit->pivot->type == '1' ? '不含' : '可以含')}}">
            </div>
            @endforeach
        </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-6">
                <strong>创建时间</strong>: {{ $model->created_at }}
            </div>
            <div class="col-lg-6">
                <strong>更新时间</strong>: {{ $model->updated_at }}
            </div>
        </div>
    </div>
@stop