@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class='row'>
                <div class="col-lg-2">
                    <strong>ID</strong>: {{ $model->id }}
                </div>
                <div class="col-lg-4">
                    <strong>名称</strong>: {{ $model->name }}
                </div>
                <div class="col-lg-2">
                    <strong>API类型</strong>: {{ $model->drive }}
                </div>
                <div class="col-lg-4">
                    <strong>描述</strong>: {{ $model->brief }}
                </div>
            </div>
            <div class='row'>
                <div class="col-lg-2">
                    <strong>固定费用类型</strong>: {{ $model->flat_rate == 'channel' ? '渠道' : '品类' }}
                </div>
                <div class="col-lg-4">
                    <strong>固定费用值</strong>: {{ $model->flat_rate_value }}
                </div>
                <div class="col-lg-2">
                    <strong>费率类型</strong>: {{ $model->rate == 'channel' ? '渠道' : '品类' }}
                </div>
                <div class="col-lg-4">
                    <strong>费率值</strong>: {{ $model->rate_value }}
                </div>
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