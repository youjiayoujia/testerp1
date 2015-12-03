@extends('common.form')
@section('title') 编辑选款需求 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('fashion.index') }}">选款需求</a></li>
        <li class="active"><strong>编辑选款需求</strong></li>
    </ol>
@stop
@section('formTitle') 编辑选款需求 @stop
@section('formAction') {{ route('fashion.update', ['id' => $fashion->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class='form-group'>
        <div class='form-group'>
            <input id="file-0a" name='img1' class="file" type="file" multiple>
        </div>
        <div class='form-group'>
            <input id="file-0b" name='img2' class="file" type="file" multiple>
        </div>
        <div class='form-group'>
            <input id="file-0c" name='img3' class="file" type="file" multiple>
        </div>
        <div class='form-group'>
            <input id="file-0d" name='img4' class="file" type="file" multiple>
        </div>
        <div class='form-group'>
            <input id="file-0e" name='img5' class="file" type="file" multiple>
        </div>
        <div class='form-group'>
            <input id="file-0f" name='img6' class="file" type="file" multiple>
        </div>
    </div>

    <div class="form-group">
        <label for="name" class='control-label'>选款需求名</label>
        <input type='text' class="form-control" id="name" placeholder="选款需求名" name='name' value="{{ old('name') ? old('name') : $fashion->name}}">
    </div>
     <div class="form-group">
        <label for="address" class='control-label'>产品货源地</label>
        <input type='text' class="form-control" id="address" placeholder="产品货源地" name='address' value="{{ old('address') ? old('address') : $fashion->address }}">
    </div>
     <div class="form-group">
        <label for="sku" class='control-label'>类似款sku</label>
        <input type='text' class="form-control" id="sku" placeholder="类似款sku" name='sku' value="{{ old('sku') ? old('sku') : $fashion->similar_sku}}">
    </div>
     <div class="form-group">
        <label for="url" class='control-label'>竞争产品url</label>
        <input type='text' class="form-control" id="url" placeholder="竞争产品url" name='url' value="{{ old('url') ? old('url') : $fashion->competition_url }}">
    </div>
     <div class="form-group">
        <label for="name" class='control-label'>需求备注</label>
        <input type='text' class="form-control" id="remark" placeholder="需求备注" name='remark' value="{{ old('remark') ? old('url') : $fashion->remark }}">
    </div>
    <div class='form-group'>
        <label for='expdate'>
            期望上传时间
        </label>
        <input type='date' name='expdate' id='expdate'>
    </div>
    
    <div class="form-group">
        <label for="url">需求者id</label>
        <input type='text' class="form-control" id="neederid" placeholder="需求者id" name='neederid' value="{{ old('neederid') ? old('neederid') : $fashion->needer_id }}">
    </div>
    <div class="form-group">
        <label for="telephone">需求店铺id</label>
        <input class="form-control" id="needershopid" placeholder="需求店铺id" name='needershopid' value="{{ old('needershopid') ? old('needershopid') : $fashion->needer_shopid}}">
    </div>
    <div class='form-group'>
        <label for='status'>
            处理状态
        </label>
        <select name='status'>
            <option value='未处理'>未处理</option>
            <option value='未找到'>未找到</option>
            <option value='已找到'>已找到</option>
        </select>
    </div>
    <div class="form-group">
        <label for="purchaseid">处理者id</label>
        <input class="form-control" id="userid" placeholder="处理者id" name='userid' value="{{ old('userid') ? old('userid') : $fashion->userid }}">
    </div>
    <div class='form-group'>
        <label for='handletime'>
            处理时间
        </label>
        <input type='date' name='handletime' id='handletime'>
    </div>
@stop