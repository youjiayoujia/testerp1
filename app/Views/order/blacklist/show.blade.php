@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基本信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-2">
                <strong>收货人姓名</strong>: {{ $model->name }}
            </div>
            <div class="col-lg-2">
                <strong>邮箱</strong>: {{ $model->email }}
            </div>
            <div class="col-lg-2">
                <strong>收货人邮编</strong>: {{ $model->zipcode }}
            </div>
            <div class="col-lg-2">
                <strong>纳入白名单</strong>: {{ $model->whitelist == '1' ? '是' : '否' }}
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