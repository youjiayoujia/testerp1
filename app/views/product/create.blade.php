@extends('layouts.default')
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="#">产品</a></li>
        <li class="active"><strong>添加产品</strong></li>
    </ol>
@stop
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">添加产品</div>
        <div class="panel-body">
            <form action="{{ route('product.store') }}" method="post">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                <div class="form-group">
                    <label for="brand_id">品牌</label>
                    <select id="brand_id" class="form-control" name="brand_id">
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="size">型号</label>
                    <input class="form-control" id="size" placeholder="型号">
                </div>
                <div class="form-group">
                    <label for="color">颜色</label>
                    <input class="form-control" id="color" placeholder="颜色">
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
            </form>
        </div>
    </div>
@stop