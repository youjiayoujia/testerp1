@extends('common.form')
<link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
<script type='text/javascript' src="{{ asset('js/pro_city.js') }}"></script>
@section('formAction') {{ route('productRequire.update', ['id' => $model->id]) }} @stop
@section('formAttributes') name='creator' enctype="multipart/form-data" @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class='row'>
    <div class='form-group col-lg-4'>
        @if($model->img1)
        <img src="{{ $model->img1 }}" width='170px' height='100px'/> 
        @endif
        <input id="img1" name='img1' class="file" type="file" multiple>
    </div>
    <div class='form-group col-lg-4'>
        @if($model->img2)
        <img src="{{ $model->img2 }}" width='170px' height='100px'/>
        @endif  
        <input id="img2" name='img2' class="file" type="file" multiple>
    </div>
    <div class='form-group col-lg-4'>
        @if($model->img3)
        <img src="{{ $model->img3 }}" width='170px' height='100px'/>
        @endif
        <input id="img3" name='img3' class="file" type="file" multiple>
    </div><hr>
    </div>
    <div class='row'>

    <div class='form-group col-lg-4'>
        @if($model->img4)
        <img src="{{ $model->img4 }}" width='170px' height='100px'/>
        @endif
        <input id="img4" name='img4' class="file" type="file" multiple>
    </div>
    <div class='form-group col-lg-4'>
        @if($model->img5)
        <img src="{{ $model->img5 }}" width='170px' height='100px'/>
        @endif
        <input id="img5" name='img5' class="file" type="file" multiple>
    </div>
    <div class='form-group col-lg-4'>
         @if($model->img6)
        <img src="{{ $model->img6 }}" width='170px' height='100px'/>
        @endif
        <input id="img6" name='img6' class="file" type="file" multiple>
    </div><hr>
    </div>
    <div class='row'>
    <div class="form-group col-lg-3">
        <label for="name" class='control-label'>选款需求名</label>
        <input type='text' class="form-control" id="name" placeholder="选款需求名" name='name' value="{{ old('name') ? old('name') : $model->name}}">
    </div>
    <div class="form-group col-lg-3">
        <label for='province'>省份</label> <select name="province" onChange = "select()" class='form-control'></select>　
    </div>
    <div class='form-group col-lg-3'> 
        <label for='city'>城市</label> <select name="city" onChange = "select()" class='form-control'></select>
    </div>
     <div class="form-group col-lg-3">
        <label for="sku" class='control-label'>类似款sku</label>
        <input type='text' class="form-control" id="sku" placeholder="类似款sku" name='sku' value="{{ old('sku') ? old('sku') : $model->similar_sku}}">
    </div>
    </div>
    <div class='row'>
     <div class="form-group col-lg-3">
        <label for="competition_url" class='control-label'>竞争产品url</label>
        <input type='text' class="form-control" id="competition_url" placeholder="竞争产品url" name='competition_url' value="{{ old('competition_url') ? old('competition_url') : $model->competition_url }}">
    </div>
    <div class="form-group col-lg-3">
        <label for="remark" class='control-label'>需求备注</label>
        <input type='text' class="form-control" id="remark" placeholder="需求备注" name='remark' value="{{ old('remark') ? old('remark') : $model->remark }}">
    </div>
    <div class='form-group col-lg-3'>
        <label for="expected_date">期待上传日期</label>
        <input id="expected_date" class='form-control' name='expected_date' type="text" value=" {{ old('expected_date') ? old('expected_date') : $model->expected_date }}">
    </div>
    <div class="form-group col-lg-3">
        <label for="needer_id">需求者id</label>
        <input type='text' class="form-control" id="needer_id" placeholder="需求者id" name='needer_id' value="{{ old('needer_id') ? old('needer_id') : $model->needer_id }}">
    </div>
    </div>
    <div class='row'>
    <div class="form-group col-lg-6">
        <label for="needer_shop_id">需求店铺id</label>
        <input type='text' class="form-control" id="needer_shop_id" placeholder="需求店铺id" name='needer_shop_id' value="{{ old('needer_shop_id') ? old('needer_shop_id') : $model->needer_shop_id}}">
    </div>
    <div class='form-group col-lg-6'>
        <label for='created_by'>创建人</label>
        <input type='text' class='form-control' id='created_by' placeholder='创建人' name='created_by' value="{{ old('created_by') ? old('craeted_by') : $model->created_by }}" readonly>
    </div>
    </div>
@stop
<script type='text/javascript'>
    window.onload = function(){
        var buf = new Array();
        buf[0] = "{{ old('province') ? old('province') : $model->province }}";
        buf[1] = "{{ old('city') ? old('city') : $model->city }}";
        init(buf[0],buf[1]);
        $('#expected_date').cxCalendar();
    };
</script>