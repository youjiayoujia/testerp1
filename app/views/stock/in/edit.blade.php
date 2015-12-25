@extends('common.form')
@section('title') 修改入库信息 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('in.index') }}">入库</a></li>
        <li class="active"><strong>修改入库信息</strong></li>
    </ol>
@stop
    <script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formTitle') 修改入库信息 @stop
@section('formAction') {{ route('in.update', ['id' => $in->id] ) }} @stop
@section('formBody')
    <input type='hidden' name='_method' value='PUT'/>
    <div class="form-group">
        <label for="item_id" class='control-label'>item号</label> 
        <input type='text' class="form-control" id="item_id" placeholder="item号" name='item_id' value="{{ old('item_id') ? old('item_id') : $in->item_id }}" readonly>
    </div>
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
        <textarea name='remark' id='remark' class='form-control'>{{ old('remark') ? old('remark') : $in->remark }}</textarea>
    </div>
    <div class='row'>
        <div class="form-group col-sm-6">
            <label for="warehouses_id">仓库</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name='warehouses_id' id='warehouses_id' class='form-control'>
                @foreach($warehouses as $warehouse)
                    <option value={{ $warehouse->id }} {{ old('warehouses_id') ? (old('warehouses_id') == $warehouse->id ? 'selected' : '') : $in->warehouses_id == $warehouse->id ? 'selected' : ''}}>{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-6">
            <label for="warehouse_positions_id">库位</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name='warehouse_positions_id' id='warehouse_positions_id' class='form-control'></select>
        </div>
    </div>
    <div class="form-group">
        <label for="type">入库类型</label>
        <select name='type' class='form-control'>
            @foreach($data as $stockin_name)
                <option value="{{ $stockin_name }}" {{ old('type') ? (old('type') == $stockin_name ? 'selected' : '') : ($in->
                type == $stockin_name ? 'selected' : '') }}> {{ $stockin_name }}</option>   
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="relation_id">入库来源id</label>
        <input type='text' class="form-control" id="relation_id" placeholder="入库来源id" name='relation_id' value="{{ old('relation_id') ? old('relation_id') : $in->relation_id }}">
    </div>
@stop
<script type='text/javascript'>
    $(document).ready(function(){
        var position = {!! $in->warehouse_positions_id !!};
        var buf = {!! $position !!};
        for(var i in buf)
            if(buf[i]['id'] == position)
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