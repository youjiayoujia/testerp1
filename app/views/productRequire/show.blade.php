@extends('layouts.default')
@section('title') 选款需求详情 : {{ $productRequire->name }} {{ $productRequire->address }} @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('productRequire.index') }}">供应商</a></li>
        <li class="active"><strong>选款需求详情 : {{ $productRequire->name }} {{ $productRequire->address }}</strong></li>
    </ol>
@stop
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">选款需求详情 : {{ $productRequire->name }} {{ $productRequire->address }}</div>
        <div class="panel-body">
            <dl class="dl-horizontal">
                <dt>ID</dt>
                <dd>{{ $productRequire->id }}</dd>
                
                @if($productRequire->img1)
                    <dt>图片1{{ $productRequire->img1 }}</dt>
                  <dd><img src="{{ $productRequire->img1 }}" alt='' class='img-rounded' width='170px' height='100px'/></dd> 
                @endif
                 @if($productRequire->img2)
                    <dt>图片2{{ $productRequire->img2 }}</dt>
                  <dd><img src="{{ $productRequire->img2 }}" alt='' class='img-rounded' width='170px' height='100px'/></dd> 
                @endif
                @if($productRequire->img3)
                    <dt>图片3{{ $productRequire->img3 }}</dt>
                  <dd><img src="{{ $productRequire->img3 }}" alt='' class='img-rounded' width='170px' height='100px'/></dd> 
                @endif
                @if($productRequire->img4)
                    <dt>图片4{{ $productRequire->img4 }}</dt>
                  <dd><img src="{{ $productRequire->img4 }}" alt='' class='img-rounded' width='170px' height='100px'/></dd> 
                @endif
                @if($productRequire->img5)
                    <dt>图片5{{ $productRequire->img5 }}</dt>
                  <dd><img src="{{ $productRequire->img5 }}" alt='' class='img-rounded' width='170px' height='100px'/></dd> 
                @endif
                @if($productRequire->img6)
                    <dt>图片6{{ $productRequire->img6 }}</dt>
                  <dd><img src="{{ $productRequire->img6 }}" alt='' class='img-rounded' width='170px' height='100px'/></dd> 
                @endif

                <dt>选款需求名</dt>
                <dd>{{ $productRequire->name }}</dd>
                <dt>货物源地址</dt>
                <dd>{{ $productRequire->address }}</dd>
                <dt>类似款sku</dt>
                <dd>{{ $productRequire->similar_sku }}</dd>
                <dt>竞争产品url</dt>
                <dd>{{ $productRequire->competition_url }}</dd>
                <dt>选款备注</dt>
                <dd>{{ $productRequire->remark }}</dd>
                <dt>期待上传日期</dt>
                <dd>{{ $productRequire->expected_date }}</dd>
                <dt>需求人id</dt>
                <dd>{{ $productRequire->needer_id }}</dd>
                <dt>需求店铺id</dt>
                <dd>{{ $productRequire->needer_shop_id }}</dd>
                <dt>处理状态</dt>
                <dd>{{ $productRequire->status }}</dd>
                <dt>处理者id</dt>
                <dd>{{ $productRequire->user_id }}</dd>
                <dt>处理时间</dt>
                <dd>{{ $productRequire->handle_time }}</dd>
            </dl>
        </div>
    </div>
@stop