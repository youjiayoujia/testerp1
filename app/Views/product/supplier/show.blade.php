@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-2">
                <strong>名称</strong>: {{ $model->name }}
            </div>
            <div class="col-lg-2">
                <strong>省</strong>: {{ $model->province }}
            </div>
            <div class="col-lg-2">
                <strong>市</strong>: {{ $model->city }}
            </div>
            <div class="col-lg-2">
                <strong>公司</strong>: {{ $model->company }}
            </div>
            <div class="col-lg-2">
                <strong>详细地址</strong>: {{ $model->address }}
            </div>
            <div class="col-lg-2">
                <strong>供货商类型</strong>: {{ $model->type ? ($model->type == '1' ? '线上' : '做货') : '线下' }}
            </div>
            <div class="col-lg-2">
                <strong>供货商网址</strong>: {{ $model->url }}
            </div>
            <div class="col-lg-2">
                <strong>联系人</strong>: {{ $model->contact_name }}
            </div>
            <div class="col-lg-2">
                <strong>电话</strong>: {{ $model->telephone }}
            </div>
            <div class="col-lg-2">
                <strong>电子邮件</strong>: {{ $model->email }}
            </div>
            <div class="col-lg-2">
                <strong>采购员</strong>: {{ $model->purchaseName ? $model->purchaseName->name : '' }}
            </div>
            <div class="col-lg-2">
                <strong>供货商等级</strong>: {{ $model->levelByName ? $model->levelByName->name : '' }}
            </div>
            <div class="col-lg-4">
                <strong>评级描述</strong>: {{ $model->levelByName ? $model->levelByName->description : '' }}
            </div>
            <div class="col-lg-4">
                <strong>审核文件：</strong>
                <a href="../../{{config('product.product_supplier.file_path')}}{{$model->qualifications}}" target="_blank">{{$model->qualifications}}</a>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-4">
                <strong>创建人</strong>: {{ $model->createdByName ? $model->createdByName->name : '' }}
            </div>
            <div class="col-lg-4">
                <strong>创建时间</strong>: {{ $model->created_at }}
            </div>
            <div class="col-lg-4">
                <strong>更新时间</strong>: {{ $model->updated_at }}
            </div>
        </div>
    </div>
@stop