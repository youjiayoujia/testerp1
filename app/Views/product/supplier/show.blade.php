@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-1">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-1">
                <strong>名称</strong>: {{ $model->name }}
            </div>
            <div class="col-lg-1">
                <strong>省</strong>: {{ $model->province }}
            </div>
            <div class="col-lg-1">
                <strong>市</strong>: {{ $model->city }}
            </div>
            <div class="col-lg-2">
                <strong>详细地址</strong>: {{ $model->address }}
            </div>
            <div class="col-lg-1">
                <strong>线上/线下</strong>: {{ $model->type == '0' ? '线下' : '线上' }}
            </div>
            <div class="col-lg-2">
                <strong>供货商网址</strong>: {{ $model->url }}
            </div>
            <div class="col-lg-2">
                <strong>电话</strong>: {{ $model->telephone }}
            </div>
            <div class="col-lg-1">
                <strong>采购员</strong>: {{ $model->purchaseName ? $model->purchaseName->name : '' }}
            </div>
            <div class="col-lg-1">
                <strong>评级</strong>: {{ $model->level }}
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