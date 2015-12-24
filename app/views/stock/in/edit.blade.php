@extends('common.form')
@section('title') 修改入库信息 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('in.index') }}">入库</a></li>
        <li class="active"><strong>修改入库信息</strong></li>
    </ol>
@stop
@section('formTitle') 修改入库信息 @stop
@section('formAction') {{ route('in.update', ['id' => $in->id] ) }} @stop
@section('formBody')
    <input type='hidden' name='_method' value='PUT'/>
    <div class="form-group">
        <label for="sku" class='control-label'>sku</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="sku" placeholder="sku" name='sku' value="{{ old('sku') ? old('sku') : $in->sku}}">
    </div>
    <div class='row'>
        <div class="form-group col-sm-6">
            <label for="amount" class='control-label'>数量</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="amount" placeholder="数量" name='amount' value="{{ old('amount') ? old('amount') : $in->amount }}">
        </div>
        <div class="form-group col-sm-6">
            <label for="total_amount" class='control-label'>总金额(￥)</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="total_amount" placeholder="总金额" name='total_amount' value="{{ old('total_amount') ? old('total_amount') : $in->total_amount }}">
        </div>
    </div>
    <div class="form-group">
        <label for="remark">备注</label>
        <input type='text' class="form-control" id="remark" placeholder="备注" name='remark' value="{{ old('remark') ? old('remark') : $in->remark }}">
    </div>
    <div class="form-group">
        <label for="warehouses_id">仓库</label>
        <input type='text' class="form-control" id="warehouses_id" placeholder="仓库" name='warehouses_id' value="{{ old('warehouses_id') ? old('warehouses_id') : $in->warehouses_id }}">
    </div>
    <div class="form-group">
        <label for="warehouse_positions_id">库位</label>
        <input type='text' class="form-control" id="warehouse_positions_id" placeholder="库位" name='warehouse_positions_id' value="{{ old('warehouse_positions_id') ? old('warehouse_positions_id') : $in->warehouse_positions_id }}">
    </div>
    <div class="form-group">
        <label for="typeof_stockin">入库类型</label>
        <select name='typeof_stockin' class='form-control'>
            @foreach($data as $stockin_name)
                <option value="{{ $stockin_name }}" {{ old('typeof_stockin') ? (old('typeof_stockin') == $stockin_name ? 'selected' : '') : ($in->
                typeof_stockin == $stockin_name ? 'selected' : '') }}> {{ $stockin_name }}</option>   
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="typeof_stockin_id">入库来源id</label>
        <input type='text' class="form-control" id="typeof_stockin_id" placeholder="入库来源id" name='typeof_stockin_id' value="{{ old('typeof_stockin_id') ? old('typeof_stockin_id') : $in->typeof_stockin_id }}">
    </div>
@stop