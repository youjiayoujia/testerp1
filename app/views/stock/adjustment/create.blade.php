@extends('common.form')
@section('title') 添加库存调整信息 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('stockAdjustment.index') }}">库存调整</a></li>
        <li class="active"><strong>添加库存调整信息</strong></li>
    </ol>
@stop
    <link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
    <script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formTitle') 添加库存调整信息 @stop
@section('formAction') {{ route('stockAdjustment.store') }} @stop
@section('formBody')
    <div class='form-group'>
        <label 
    </div>
    <div class="form-group">
        <label for="item_id" class='control-label'>item号</label> 
        <input type='text' class="form-control" id="item_id" placeholder="item号" name='item_id' value="{{ old('item_id') }}" readonly>
    </div>
    <div class="form-group">
        <label for="sku" class='control-label'>sku</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="sku" placeholder="sku" name='sku' value="{{ old('sku') }}">
    </div>
    <div class='form-group'>
        <label>出入库类型</label>
        <div class='radio'>
            <label>
                <input type='radio' name='type' value='入库' {{ old('type') ? old('type') == '入库' ? 'checked' : '' : 'checked'}}>入库
            </label>
        </div>
        <div class='radio'>
            <label>
                <input type='radio' name='type' value='出库' {{ old('type') ? old('type') == '入库' ? 'checked' : '' : ''}}>出库
            </label>
        </div>
    </div>
    <div class='row'>
        <div class="form-group col-sm-6">
            <label for="warehouses_id">仓库</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name='warehouses_id' id='warehouses_id' class='form-control'>
                @foreach($warehouses as $warehouse)
                    <option value={{ $warehouse->id }} {{ old('warehouses_id') ? old('warehouses_id') == $warehouse->id ? 'selected' : '' : ''}}>{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-6">
            <label for="warehouse_positions_id">库位</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name='warehouse_positions_id' id='warehouse_positions_id' class='form-control'></select>
        </div>
    </div>
    <div class='row'>
        <div class="form-group col-sm-6">
            <label for="amount" class='control-label'>数量</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="amount" placeholder="数量" name='amount' value="{{ old('amount') }}">
        </div>
        <div class="form-group col-sm-6">
            <label for="total_amount" class='control-label'>总金额(￥)</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="total_amount" placeholder="总金额" name='total_amount' value="{{ old('total_amount') }}">
        </div>
    </div>
    <div class='form-group'>
        <label for='label'>备注(原因)</label>
        <textarea class='form-control' name='remark' id='remark'>{{ old('remark') }}</textarea>
    </div>
    <div class="form-group">
        <label for="adjust_man_id">调整人</label>
        <input type='text' class="form-control" id="adjust_man_id" placeholder="调整人" name='adjust_man_id' value="{{ old('adjust_man_id') }}" readonly>
    </div>
    <div class="form-group">
        <label for="adjust_time">调整时间</label>
        <input type='text' class="form-control" id="adjust_time" placeholder="调整时间" name='adjust_time' value="{{ old('adjust_time') }}">
    </div>
@stop
<script type='text/javascript'>
    $(document).ready(function(){
        // var buf = new XMLHttpRequest();
        // if(buf)
        //     alert('ajax对象创建成功');

        var warehouse = $('#warehouses_id').val();
        var buf = {!! $position !!};
        for(var i in buf)
            if(buf[i]['warehouses_id'] == warehouse)
                $('<option value='+buf[i]['id']+'>'+buf[i]['name']+'</option>').appendTo($('#warehouse_positions_id'));

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
            var buf = {!! $position !!};
            for(var current in buf)
                if(buf[current]['warehouses_id'] == warehouse_value)
                    $('<option value='+buf[current]['id']+'>'+buf[current]['name']+'</option>').appendTo($('#warehouse_positions_id'));
        });
        $('#adjust_time').cxCalendar();
        $('#check_time').cxCalendar();
    });
</script>