@extends('common.form')
@section('title') 修改入库信息 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('stockAdjustment.index') }}">入库</a></li>
        <li class="active"><strong>修改入库信息</strong></li>
    </ol>
@stop
    <link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
    <script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formTitle') 修改入库信息 @stop
@section('formAction') {{ route('stockAdjustment.update', ['id' => $adjustment->id] ) }} @stop
@section('formBody')
    <input type='hidden' name='_method' value='PUT'/>
    <div class='row'>
        <div class='form-group col-sm-2'>
            <label for='adjust_form_id'>调整单号</label>
            <input type='text' class='form-control' name='adjust_form_id' id='adjust_form_id' value="{{ old('adjust_form_id') ? old('adjust_form_id') : $adjustment->adjust_form_id }}" readonly>
        </div>
        <div class="form-group col-sm-2">
            <label for="warehouses_id">仓库</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name='warehouses_id' id='warehouses_id' class='form-control'>
                <option>请选择仓库</option>
                @foreach($warehouses as $warehouse)
                    <option value={{ $warehouse->id }} {{ old('warehouses_id') ? old('warehouses_id') == $warehouse->id ? 'selected' : '' : $adjustment->warehouses_id == $warehouse->id ? 'selected' : ''}}>{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-2">
            <label for="adjust_man_id">调整人</label>
            <input type='text' class="form-control" id="adjust_man_id" placeholder="调整人" name='adjust_man_id' value="{{ old('adjust_man_id') ? old('adjust_man_id') : $adjustment->adjust_man_id }}" readonly>
        </div>
        <div class="form-group col-sm-2">
            <label for="adjust_time">调整时间</label>
            <input type='text' class="form-control" id="adjust_time" placeholder="调整时间" name='adjust_time' value="{{ old('adjust_time') ? old('adjust_time') : $adjustment->adjust_time}}">
        </div>
        <div class='form-group col-sm-4'>
            <label for='label'>备注(原因)</label>
            <textarea class='form-control remark' name='remark' id='remark'>{{ old('remark') ? old('remark') : $adjustment->remark }}</textarea>
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">
            sku1
        </div>
        <div class='panel-body'>
            <div class='row'>
                <div class="form-group col-sm-6">
                    <label for="item_id" class='control-label'>item号</label> 
                    <input type='text' class="form-control item_id" id="item_id" placeholder="item号" name='item_id' value="{{ old('item_id') ? old('item_id') : $adjustment->item_id }}" readonly>
                </div>
                <div class="form-group col-sm-6">
                    <label for="sku" class='control-label'>sku</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <input type='text' class="form-control sku" id="sku" placeholder="sku" name='sku' value="{{ old('sku') ? old('sku') : $adjustment->sku }}">
                </div>
            </div>
            <div class='row'>
                <div class='form-group col-sm-3'>
                    <label>出入库类型</label>
                    <div class='radio type'>
                        <label>
                            <input type='radio' name='type' value='入库' {{ old('type') ? old('type') == '入库' ? 'checked' : '' : $adjustment->type == '入库' ? 'checked' : ''}}>入库
                        </label>
                    </div>
                    <div class='radio type'>
                        <label>
                            <input type='radio' name='type' value='出库' {{ old('type') ? old('type') == '出库' ? 'checked' : '' : $adjustment->type == '出库' ? 'checked' : ''}}>出库
                        </label>
                    </div>
                </div>
                <div class="form-group col-sm-3">
                    <label for="warehouse_positions_id">库位</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <select name='warehouse_positions_id' id='warehouse_positions_id' class='form-control warehouse_positions_id'>
                        <option>请选择库位</option>
                    </select>
                </div>
                <div class="form-group col-sm-3">
                    <label for="amount" class='control-label'>数量</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <input type='text' class="form-control amount" id="amount" placeholder="数量" name='amount' value="{{ old('amount') ? old('amount') : $adjustment->amount }}">
                </div>
                <div class="form-group col-sm-3">
                    <label for="total_amount" class='control-label'>总金额(￥)</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <input type='text' class="form-control total_amount" id="total_amount" placeholder="总金额" name='total_amount' value="{{ old('total_amount') ? old('total_amount') : $adjustment->total_amount }}" readonly>
                </div>
            </div>
        </div>
    </div>
@stop
<script type='text/javascript'>
    $(document).ready(function(){
        warehouse = $('#warehouses_id').val();
        $.ajax({
                url: "{{ route('getposition') }}",
                data: {val:warehouse},
                dataType:'json',
                type:'get',
                success:function(result){
                    $('#warehouse_positions_id').empty();
                    for(var i=0;i<result.length;i++)
                        if(result[i]['id'] == {{ old('warehouse_positions_id') ? old('warehouse_positions_id') : $adjustment->warehouse_positions_id }})
                            $('<option value='+result[i]['id']+' selected>'+result[i]['name']+'</option>').appendTo($('#warehouse_positions_id'));
                        else
                            $('<option value='+result[i]['id']+'>'+result[i]['name']+'</option>').appendTo($('#warehouse_positions_id'));
                }
            });

        $(document).on('blur', '.amount', function(){
            var sku = $(this).parent().parent().prev().find('.sku').val();
            var tmp = $(this);
            if(tmp.val()) {
                $.ajax({
                    url:"{{ route('getunitcost') }}",
                    data:{sku:sku},
                    dataType:'json',
                    'type':'get',
                    success:function(result){
                        tmp.parent().next().children('.total_amount').val(result*tmp.val());
                    }
                });
            }
        });

        $(document).on('blur', '.sku', function(){
            var tmp = $(this);
            var sku_val = $(this).val();
            var test = $(this).val();
            if(sku_val){
            $.ajax({
                url: "{{route('getitemid')}}",
                data: {sku_val:sku_val},
                dataType: 'json',
                type: 'get',
                success: function(result){
                    tmp.parent().prev().children(':text').val(result);
                    if(!result) {
                        $('#sku').val('');
                        alert('sku不存在');
                    }
                } 
            });
            }
            
        });

        $(document).on('change', '#warehouses_id', function(){
            val = $(this).val();
            $.ajax({
                url: "{{ route('getposition') }}",
                data: {val:val},
                dataType:'json',
                type:'get',
                success:function(result){
                    $('.warehouse_positions_id').empty();
                    for(var i=0;i<result.length;i++)
                        $('<option value='+result[i]['id']+'>'+result[i]['name']+'</option>').appendTo($('.warehouse_positions_id'));
                }
            });
        });
        
        $('#adjust_time').cxCalendar();
        $('#check_time').cxCalendar();
    });
</script>