@extends('common.form')
@section('title') 修改出库信息 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('stockOut.index') }}">出库</a></li>
        <li class="active"><strong>修改出库信息</strong></li>
    </ol>
@stop
    <script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}

@section('formTitle') 修改出库信息 @stop
@section('formAction') {{ route('stockOut.update', ['id' => $out->id]) }} @stop
@section('formBody')
    <input type='hidden' name='_method' value='PUT'/>
    <div class="form-group">
        <label for="item_id" class='control-label'>item号</label>
        <input type='text' class="form-control" id="item_id" placeholder="item_id" name='item_id' value="{{ old('item_id') ? old('item_id') : $out->item_id }}" readonly>
    </div>
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
        <textarea name='remark' id='remark' class='form-control'>{{ old('remark') ? old('remark') : $out->remark }}</textarea>
    </div>
    <div class='row'>
        <div class="form-group col-sm-6">
            <label for="warehouses_id">仓库</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name='warehouses_id' id='warehouses_id' class='form-control'>
                @foreach($warehouses as $warehouse)
                    <option value={{ $warehouse->id }} {{ old('warehouses_id') ? (old('warehouses_id') == $warehouse->id ? 'selected' : '') : $out->warehouses_id == $warehouse->id ? 'selected' : ''}}>{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-6">
            <label for="warehouse_positions_id">库位</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name='warehouse_positions_id' id='warehouse_positions_id' class='form-control'></select>
        </div>
    </div>
    <div class="form-group">
        <label for="type">出库类型</label>
        <select name='type' class='form-control'>
            @foreach($data as $stockout_name)
                <option value="{{ $stockout_name }}" {{ old('type') ? (old('type') == $stockout_name ? 'selected' : '') : ($out->
                type == $stockout_name ? 'selected' : '') }}> {{ $stockout_name }}</option>   
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="relation_id">出库类型id</label>
        <input type='text' class="form-control" id="relation_id" placeholder="出库来源id" name='relation_id' value="{{ old('relation_id') ? old('relation_id') : $out->relation_id }}">
    </div>
@stop
<script type='text/javascript'>
    $(document).ready(function(){
        var position = {!! $out->warehouse_positions_id !!};
        var warehouse = {!! $out->warehouses_id !!};
        var buf = {!! $position !!};
        for(var i in buf)
            if(buf[i]['warehouses_id'] == warehouse)
                if(buf[i]['id'] == position)
                    $('<option value='+position+' selected>'+buf[i]['name']+'</option>').appendTo($('#warehouse_positions_id'));
                else
                    $('<option value='+position+'>'+buf[i]['name']+'</option>').appendTo($('#warehouse_positions_id'));

        $('#sku').blur(function(){
            var sku_val = $('#sku').val();
            var flag = 0;
            var buf = new Array();
            buf = {!! $item !!};
            for(var test in buf)
                if(buf[test]['sku'] == sku_val) {
                    $('#item_id').val(buf[test]['id']);
                    flag = 1;
                }
            if(flag == 0) {
                $('#sku').val('');
                $('#item_id').val('');
                alert('sku不存在');
            }
        });

        $('#warehouses_id').change(function(){
            $('#warehouse_positions_id').empty();
            var warehouse_value = $('#warehouses_id').val();
            for(var current in buf)
                if(buf[current]['warehouses_id'] == warehouse_value)
                    $('<option value='+buf[current]['id']+'>'+buf[current]['name']+'</option>').appendTo($('#warehouse_positions_id'));
        });
    });
</script>