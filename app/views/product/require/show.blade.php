@extends('layouts.default')
@section('title') 选款需求详情 : {{ $productRequire->name }} {{ $productRequire->url }} @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('productRequire.index') }}">选款需求</a></li>
        <li class="active"><strong>选款需求详情 : {{ $productRequire->name }} {{ $productRequire->address }}</strong></li>
    </ol>
@stop
@section('content')
    <div class='table-responsive'>
        <table class='table table-bordered'>
        @if($productRequire->img1)
        <tr class='info'>
            <td>图片1</td>
            <td><img src="{{ $productRequire->img1 }}" alt='' class='img-rounded' width='170px' height='100px'/></td>
        </tr>
        @endif
        @if($productRequire->img2)
        <tr class='info'>
            <td>图片2</td>
            <td><img src="{{ $productRequire->img2 }}" alt='' class='img-rounded' width='170px' height='100px'/></td> 
        </tr> 
        @endif       
        @if($productRequire->img3)
        <tr class='info'>
            <td>图片3</td>
            <td><img src="{{ $productRequire->img3 }}" alt='' class='img-rounded' width='170px' height='100px'/></td> 
        </tr> 
        @endif
        @if($productRequire->img4)
        <tr class='info'>
            <td>图片4</td>
            <td><img src="{{ $productRequire->img4 }}" alt='' class='img-rounded' width='170px' height='100px'/></td> 
        </tr> 
        @endif 
        @if($productRequire->img5)
        <tr class='info'>
            <td>图片5</td>
            <td><img src="{{ $productRequire->img5 }}" alt='' class='img-rounded' width='170px' height='100px'/></td> 
        </tr>
        @endif
        @if($productRequire->img6)
        <tr class='info'>
            <td>图片6</td>
            <td><img src="{{ $productRequire->img6 }}" alt='' class='img-rounded' width='170px' height='100px'/></td> 
        </tr>  
        @endif        
        <tr ><td>选款需求id</td><td>{{ $productRequire->id }}</td></tr>    
        <tr ><td>选款需求名</td><td>{{ $productRequire->name }}</td></tr>
        <tr ><td>货物源省</td><td>{{ $productRequire->province }}</td></tr>
        <tr ><td>货物源市</td><td>{{ $productRequire->city }}</td></tr>
        <tr ><td>类似款sku</td><td>{{ $productRequire->similar_sku }}</td></tr>
        <tr ><td>竞争产品url</td><td>{{ $productRequire->competition_url }}</td></tr>
        <tr ><td>选款备注</td><td>{{ $productRequire->remark }}</td></tr>
        <tr ><td>期待上传日期</td><td>{{ $productRequire->expected_date }}</td></tr>
        <tr ><td>需求人id</td><td>{{ $productRequire->needer_id }}</td></tr>
        <tr ><td>需求店铺id</td><td>{{ $productRequire->needer_shop_id }}</td></tr>
        <tr ><td>创建人</td><td>{{ $productRequire->created_by }}</td></tr>
        <tr ><td>创建时间</td><td>{{ $productRequire->created_at }}</td>
        <tr ><td>状态</td><td>{{ $productRequire->status }}</td></tr>
        <tr ><td>处理者id</td><td>{{ $productRequire->user_id }}</td></tr>
        <tr ><td>处理时间</td><td>{{ $productRequire->handle_time }}</td></tr>  
        </table> 
    </div>  
@stop