@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>ID</strong>: {{ $supplier->id }}
            </div>
            <div class="col-lg-2">
                <strong>名称</strong>: {{ $supplier->name }}
            </div>
            <div class="col-lg-1">
                <strong>省</strong>: {{ $supplier->province }}
            </div>
            <div class="col-lg-1">
                <strong>市</strong>: {{ $supplier->city }}
            </div>
            <div class="col-lg-2">
                <strong>是否是线上供货商</strong>: {{ $supplier->type }}
            </div>
            <div class="col-lg-2">
                <strong>线上供货商地址</strong>: {{ $supplier->url }}
            </div>
            <div class="col-lg-2">
                <strong>电话</strong>: {{ $supplier->telephone }}
            </div>
            <div class="col-lg-2">
                <strong>采购员</strong>: {{ $supplier->purchase_id }}
            </div>
            <div class="col-lg-2">
                <strong>评级</strong>: {{ $supplier->level }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-4">
                <strong>创建人</strong>: {{ $supplier->created_by }}
            </div>
            <div class="col-lg-4">
                <strong>创建时间</strong>: {{ $supplier->created_at }}
            </div>
            <div class="col-lg-4">
                <strong>更新时间</strong>: {{ $supplier->updated_at }}
            </div>
        </div>
    </div>
@stop