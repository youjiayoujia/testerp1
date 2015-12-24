@extends('common.form')
@section('title') 修改入库信息 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('adjustment.index') }}">入库</a></li>
        <li class="active"><strong>修改入库信息</strong></li>
    </ol>
@stop
    <link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
    <script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formTitle') 修改入库信息 @stop
@section('formAction') {{ route('adjustment.update', ['id' => $adjustment->id] ) }} @stop
@section('formBody')
    <input type='hidden' name='_method' value='PUT'/>
    <div class="form-group">
        <label for="sku" class='control-label'>sku</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="sku" placeholder="sku" name='sku' value="{{ old('sku') ? old('sku') : $adjustment->sku}}">
    </div>
    <div class='form-group'>
        <label>出入库类型</label>
        <div class='radio'>
            <label>
                <input type='radio' name='type' value='入库' {{ old('type') ? old('type') == '入库' ? 'checked' : '' : $adjustment->type == '入库' ? 'checked' : ''}} >入库
            </label>
        </div>
        <div class='radio'>
            <label>
                <input type='radio' name='type' value='出库' {{ old('type') ? old('type') == '出库' ? 'checked' : '' : $adjustment->type == '出库' ? 'checked' : ''}}>出库
            </label>
        </div>
    </div>
    <div class='row'>
        <div class="form-group col-sm-6">
            <label for="warehouses_id">仓库</label>
            <input type='text' class="form-control" id="warehouses_id" placeholder="仓库" name='warehouses_id' value="{{ old('warehouses_id') ? old('warehouses_id') : $adjustment->warehouses_id}}">
        </div>
        <div class="form-group col-sm-6">
            <label for="warehouse_positions_id">库位</label>
            <input type='text' class="form-control" id="warehouse_positions_id" placeholder="库位" name='warehouse_positions_id' value="{{ old('warehouse_positions_id') ? old('warehouse_positions_id') : $adjustment->warehouse_positions_id }}">
        </div>
    </div>
    <div class='row'>
        <div class="form-group col-sm-6">
            <label for="amount" class='control-label'>数量</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="amount" placeholder="数量" name='amount' value="{{ old('amount') ? old('amount') : $adjustment->amount }}">
        </div>
        <div class="form-group col-sm-6">
            <label for="total_amount" class='control-label'>总金额(￥)</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="total_amount" placeholder="总金额" name='total_amount' value="{{ $adjustment->total_amount }}" readonly>
        </div>
    </div>
    <div class='row'>
        <div class="form-group col-sm-6">
            <label for="adjust_man_id">调整人</label>
            <input type='text' class="form-control" id="adjust_man_id" placeholder="调整人" name='adjust_man_id' value="{{ old('adjust_man_id') ? old('adjust_man_id') : $adjustment->adjust_man_id }}">
        </div>
        <div class="form-group col-sm-6">
            <label for="adjust_time">调整时间</label>
            <input type='text' class="form-control" id="adjust_time" placeholder="调整时间" name='adjust_time' value="{{ old('adjust_time') ? old('adjust_time') : $adjustment->adjust_time }}">
        </div>
    </div>
@stop
<script type='text/javascript'>
    $(document).ready(function(){
        $('#adjust_time').cxCalendar();
        $('#check_time').cxCalendar();
    });
</script>