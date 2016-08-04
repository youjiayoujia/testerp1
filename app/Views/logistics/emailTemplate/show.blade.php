@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基本信息</div>
        <div class="panel-body">
            <div class="col-lg-3">
                <strong>编号</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-3">
                <strong>协议客户</strong>: {{ $model->customer }}
            </div>
            <div class="col-lg-6">
                <strong>发件地址</strong>: {{ $model->address }}
            </div>
            <div class="col-lg-3">
                <strong>邮编</strong>: {{ $model->zipcode }}
            </div>
            <div class="col-lg-3">
                <strong>电话</strong>: {{ $model->phone }}
            </div>
            <div class="col-lg-6">
                <strong>退件单位</strong>: {{ $model->unit }}
            </div>
            <div class="col-lg-3">
                <strong>国家代码</strong>: {{ $model->country_code }}
            </div>
            <div class="col-lg-3">
                <strong>省份</strong>: {{ $model->province }}
            </div>
            <div class="col-lg-3">
                <strong>城市</strong>: {{ $model->city }}
            </div>
            <div class="col-lg-3">
                <strong>寄件人</strong>: {{ $model->sender }}
            </div>
            <div class="col-lg-9">
                <strong>备注</strong>: {{ $model->remark }}
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