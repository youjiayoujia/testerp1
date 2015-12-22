@extends('common.form')
@section('title') 修改入库信息 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('itemin.index') }}">入库</a></li>
        <li class="active"><strong>修改入库信息</strong></li>
    </ol>
@stop
@section('formTitle') 修改入库信息 @stop
@section('formAction') {{ route('itemin.store') }} @stop
@section('formBody')
    <div class="form-group">
        <label for="sku" class='control-label'>sku</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="sku" placeholder="sku" name='sku' value="{{ old('sku') ? old('sku') : $itemin->sku}}">
    </div>
    <div class="form-group col-sm-6">
        <label for="amount" class='control-label'>数量</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="amount" placeholder="数量" name='amount' value="{{ old('amount') ? old('amount') : $itemin->amount }}">
    </div>
    <div class="form-group col-sm-6">
        <label for="total_amount" class='control-label'>总金额(￥)</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="total_amount" placeholder="总金额" name='total_amount' value="{{ old('total_amount') ? old('total_amount') : $itemin->total_amount }}">
    </div>
    <div class="form-group">
        <label for="remark">备注</label>
        <input type='text' class="form-control" id="remark" placeholder="备注" name='remark' value="{{ old('remark') ? old('remark') : $itemin->remark }}">
    </div>
    <div class="form-group">
        <label for="typeof_itemin">入库类型</label>
        <select name='typeof_itemin' class='form-control'>
            @foreach($data as $iteminname)
                <option value="{{ $iteminname->id }}" {{ old('typeof_itemin') ? (old('typeof_itemnin') == $iteminname->id ? 'selected' : '') : ($itemin->
                typeof_itemin == $iteminname->id ? 'selected' : '') }}>{{ $iteminname->name }}</option>   
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="typeof_itemin_id">入库来源id</label>
        <input type='text' class="form-control" id="typeof_itemin_id" placeholder="入库来源id" name='typeof_itemin_id' value="{{ old('typeof_itemin_id') ? old('typeof_itemin_id') : $itemin->typeof_itemin_id }}">
    </div>
@stop