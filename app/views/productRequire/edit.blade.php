@extends('common.form')
@section('title') 编辑选款需求 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('productRequire.index') }}">选款需求</a></li>
        <li class="active"><strong>编辑选款需求</strong></li>
    </ol>
@stop
    <link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
    <script type='text/javascript' src="{{ asset('js/pro_city.js') }}"></script>

@section('formTitle') 编辑选款需求 @stop
@section('formAction') {{ route('productRequire.update', ['id' => $productRequire->id]) }} @stop
@section('formAttributes') name='creator' enctype="multipart/form-data" @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    @if($productRequire->img1)
        <img src="{{ $productRequire->img1 }}" width='170px' height='100px'/> 
    @endif
    <div class='form-group'>
        <input id="img1" name='img1' class="file" type="file" multiple>
    </div><hr>
    @if($productRequire->img2)
        <img src="{{ $productRequire->img2 }}" width='170px' height='100px'/>
    @endif  
    <div class='form-group'>
        <input id="img2" name='img2' class="file" type="file" multiple>
    </div><hr>
    
    @if($productRequire->img3)
        <img src="{{ $productRequire->img3 }}" width='170px' height='100px'/>
    @endif
    <div class='form-group'>
        <input id="img3" name='img3' class="file" type="file" multiple>
    </div><hr>
    
    @if($productRequire->img4)
        <img src="{{ $productRequire->img4 }}" width='170px' height='100px'/>
    @endif
    <div class='form-group'>
        <input id="img4" name='img4' class="file" type="file" multiple>
    </div><hr>
    
    @if($productRequire->img5)
        <img src="{{ $productRequire->img5 }}" width='170px' height='100px'/>
    @endif
    <div class='form-group'>
        <input id="img5" name='img5' class="file" type="file" multiple>
    </div><hr>

     @if($productRequire->img6)
        <img src="{{ $productRequire->img6 }}" width='170px' height='100px'/>
    @endif
    <div class='form-group'>
        <input id="img6" name='img6' class="file" type="file" multiple>
    </div><hr>

    <div class="form-group">
        <label for="name" class='control-label'>选款需求名</label>
        <input type='text' class="form-control" id="name" placeholder="选款需求名" name='name' value="{{ old('name') ? old('name') : $productRequire->name}}" readonly>
    </div>
    <div class="form-group">
        <label for="detail_address" class='control-label'>详细地址</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <p for='province'>省份</p> <select name="province" onChange = "select()" class='form-control'></select>　
        <p for='city'>城市</p> <select name="city" onChange = "select()" class='form-control'></select>
    </div>
     <div class="form-group">
        <label for="sku" class='control-label'>类似款sku</label>
        <input type='text' class="form-control" id="sku" placeholder="类似款sku" name='sku' value="{{ old('sku') ? old('sku') : $productRequire->similar_sku}}">
    </div>
     <div class="form-group">
        <label for="url" class='control-label'>竞争产品url</label>
        <input type='text' class="form-control" id="url" placeholder="竞争产品url" name='url' value="{{ old('url') ? old('url') : $productRequire->competition_url }}">
    </div>
     <div class="form-group">
        <label for="name" class='control-label'>需求备注</label>
        <input type='text' class="form-control" id="remark" placeholder="需求备注" name='remark' value="{{ old('remark') ? old('url') : $productRequire->remark }}">
    </div>
    <div class='form-group'>
        <label for="expdate">期待上传日期</label>
        <input id="expdate" name='expdate' type="text" value=" {{ $productRequire->expected_date }}">
    </div>
    <div class="form-group">
        <label for="url">需求者id</label>
        <input type='text' class="form-control" id="needer_id" placeholder="需求者id" name='needer_id' value="{{ old('needer_id') ? old('needer_id') : $productRequire->needer_id }}">
    </div>
    <div class="form-group">
        <label for="telephone">需求店铺id</label>
        <input type='text' class="form-control" id="needer_shop_id" placeholder="需求店铺id" name='needer_shop_id' value="{{ old('needer_shop_id') ? old('needer_shop_id') : $productRequire->needer_shop_id}}">
    </div>
    <div class='form-group'>
        <label for='created_by'>创建人</label>
        <input type='text' class='form-control' id='created_by' placeholder='创建人' name='created_by' value="{{ old('created_by') ? old('craeted_by') : $productRequire->created_by }}">
    </div>
@stop
<script type='text/javascript'>
    window.onload = function(){
        var buf = new Array();
        buf = "{{ $productRequire->address }}".split(' ');
        init(buf[0],buf[1]);
        $('#expdate').cxCalendar();
    };
</script>