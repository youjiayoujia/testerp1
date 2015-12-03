@extends('common.form')
@section('title') 添加供货商 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('product.index') }}">供货商</a></li>
        <li class="active"><strong>添加供货商</strong></li>
    </ol>
@stop
@section('formTitle') 添加供货商 @stop
@section('formAction') {{ route('provider.store') }} @stop
@section('formBody')
    <div class="form-group">
        <label for="name" class='control-label'>供货商名字</label>
        <input type='text' class="form-control" id="name" placeholder="供货商名字" name='name' value="{{ old('name') }}">
    </div>
    <div class="form-group">
        <label for="address">供货商地址</label>
        <input type='text' class="form-control" id="address" placeholder="供货商地址" name='address' value="{{ old('address') }}">
    </div>
    <div class="form-group">
        <label for="online">是否是线上供货商0/1</label>
        <div class='radio'>
            <label>
                <input type='radio' name='online' value='0'>0
            </label>   
        </div>
        <div class='radio'>
            <label>
                <input type='radio' name='online' value='1'>1
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="url">供货商url</label>
        <input type='text' class="form-control" id="url" placeholder="供货商url" name='url' value="{{ old('url') }}">
    </div>
    <div class="form-group">
        <label for="telephone">供货商电话</label>
        <input class="form-control" id="telephone" placeholder="供货商电话" name='telephone' value="{{ old('telephone') }}">
    </div>
    <div class="form-group">
        <label for="purchaseid">采购者id</label>
        <input class="form-control" id="purchaseid" placeholder="采购者id" name='purchaseid' value="{{ old('purchase') }}">
    </div>
    <div class="form-group">
        <label for="level">供货商评级</label>
        <select name='level' class='form-control'>
        <option value='1'>1</option>
        <option value='2'>2</option>
        <option value='3'>3</option>
        <option value='4'>4</option>
        <option value='5'>5</option>
        </select>
   </div>
@stop