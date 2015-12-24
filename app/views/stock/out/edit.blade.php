@extends('common.form')
@section('title') 修改出库信息 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('out.index') }}">出库</a></li>
        <li class="active"><strong>修改出库信息</strong></li>
    </ol>
@stop
@section('formTitle') 修改出库信息 @stop
@section('formAction') {{ route('out.update', ['id' => $out->id]) }} @stop
@section('formBody')
    <input type='hidden' name='_method' value='PUT'/>
    <div class="form-group">
        <label for="sku" class='control-label'>sku</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="sku" placeholder="sku" name='sku' value="{{ old('sku') ? old('sku') : $out->sku}}">
    </div>
    <div class='row'>
        <div class="form-group col-sm-6">
            <label for="amount" class='control-label'>数量</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="amount" placeholder="数量" name='amount' value="{{ old('amount') ? old('amount') : $out->amount }}">
        </div>
        <div class="form-group col-sm-6">
            <label for="total_amount" class='control-label'>总金额(￥)</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="total_amount" placeholder="总金额" name='total_amount' value="{{ old('total_amount') ? old('total_amount') : $out->total_amount }}">
        </div>
    </div>
    <div class="form-group">
        <label for="remark">备注</label>
        <input type='text' class="form-control" id="remark" placeholder="备注" name='remark' value="{{ old('remark') ? old('remark') : $out->remark }}">
    </div>
    <div class="form-group">
        <label for="warehouses_id">仓库</label>
        <input type='text' class="form-control" id="warehouses_id" placeholder="仓库" name='warehouses_id' value="{{ old('warehouses_id') ? old('warehouses_id') : $out->warehouses_id }}">
    </div>
    <div class="form-group">
        <label for="warehouse_positions_id">库位</label>
        <input type='text' class="form-control" id="warehouse_positions_id" placeholder="库位" name='warehouse_positions_id' value="{{ old('warehouse_positions_id') ? old('warehouse_positions_id') : $out->warehouse_positions_id }}">
    </div>
    <div class="form-group">
        <label for="typeof_stockout">出库类型</label>
        <select name='typeof_stockout' class='form-control'>
            @foreach($data as $stockout_name)
                <option value="{{ $stockout_name }}" {{ old('typeof_stockout') ? (old('typeof_stockout') == $stockout_name ? 'selected' : '') : ($out->
                typeof_stockout == $stockout_name ? 'selected' : '') }}> {{ $stockout_name }}</option>   
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="typeof_stockout_id">出库来源id</label>
        <input type='text' class="form-control" id="typeof_stockout_id" placeholder="出库来源id" name='typeof_stockout_id' value="{{ old('typeof_stockout_id') ? old('typeof_stockout_id') : $out->typeof_stockout_id }}">
    </div>
@stop