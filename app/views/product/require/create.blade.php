@extends('common.form')
@section('title') 添加选款需求 @stop

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('Require.index') }}">选款需求</a></li>
        <li class="active"><strong>添加选款需求</strong></li>
    </ol>
@stop
    <link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
    <script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
    <script src="{{ asset('js/pro_city.js') }}"></script>

@section('formTitle') 添加选款需求 @stop
@section('formAction') {{ route('Require.store') }} @stop
@section('formAttributes') name='creator' enctype="multipart/form-data" @stop
@section('formBody')
    <div class='form-group'>
        <input id="img1" name='img1' class="file" type="file">
    </div>
    <div class='form-group'>
        <input id="img2" name='img2' class="file" type="file">
    </div>
    <div class='form-group'>
        <input id="img3" name='img3' class="file" type="file">
    </div>
    <div class='form-group'>
        <input id="img4" name='img4' class="file" type="file">
    </div>
    <div class='form-group'>
        <input id="img5" name='img5' class="file" type="file">
    </div>
    <div class='form-group'>
        <input id="img6" name='img6' class="file" type="file">
    </div>    
    <div class="form-group">
        <label for="name" class='control-label'>需求名</label>
        <input type='text' class="form-control" id="name" placeholder="选款需求名" name='name' value="{{ old('name') }}">
    </div>
    <div class="form-group">
        <label for="detail_address" class='control-label'>(省/市)地址</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <div class='row'>
            <div class='col-sm-6'>
                <label for='province'>省份</label> <select name="province" onChange = "select()" class='form-control'></select>　
            </div>
            <div class='col-sm-6'> 
                <label for='city'>城市</label> <select name="city" onChange = "select()" class='form-control'></select>
            </div>
        </div>
    </div>
        <div class="form-group"> 
        <label for="similar_sku" class='control-label'>类似款sku</label>
        <input type='text' class="form-control" id="similar_sku" placeholder="类似款sku" name='similar_sku' value="{{ old('similar_sku') }}">
    </div>
     <div class="form-group">
        <label for="competition_url" class='control-label'>竞争产品url</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="competition_url" placeholder="竞争产品url" name='competition_url' value="{{ old('competition_url') }}">
    </div>
     <div class="form-group">
        <label for="remark" class='control-label'>需求备注</label>
        <input type='text' class="form-control" id="remark" placeholder="需求备注" name='remark' value="{{ old('remark') }}">
    </div>
    <div class='form-group'>
        <label for="expected_date">期望上传日期</label>
        <input id="expected_date" name='expected_date' type="text" placeholder='期望上传日期' value="{{ old('expected_date') }}">
    </div>
    
    <div class="form-group">
        <label for="needer_id">需求人</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="needer_id" placeholder="需求者id" name='needer_id' value="{{ old('needer_id') }}">
    </div>
    <div class="form-group">
        <label for="needer_shop_id">需求店铺</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="needer_shop_id" placeholder="需求店铺id" name='needer_shop_id' value="{{ old('needer_shop_id') }}">
    </div>
    <div class="form-group">
        <label for="created_by">创建人</label>
        <input class="form-control" id="created_by" placeholder="创建人" name='created_by' value="{{ old('created_by') }}" readonly>
    </div>
@stop

<script type='text/javascript'>
    $(document).ready(function(){
        var buf = new Array();
        buf[0] = "{{ old('province') }}";
        buf[1] = "{{ old('city') }}";
        init(buf[0],buf[1]);
        $('#expected_date').cxCalendar();
    });
</script>