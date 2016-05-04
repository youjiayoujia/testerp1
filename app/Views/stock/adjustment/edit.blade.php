@extends('common.form')
<link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
@section('formAction') {{ route('stockAdjustment.update', ['id' => $model->id] ) }} @stop
@section('formBody')
    <input type='hidden' name='_method' value='PUT'/>
    <div class='row'>
        <div class='form-group col-sm-4'>
            <label for='adjust_form_id'>调整单号</label>
            <input type='text' class='form-control' name='adjust_form_id' id='adjust_form_id' value="{{ old('adjust_form_id') ? old('adjust_form_id') : $model->adjust_form_id }}" readonly>
        </div>
        <div class="form-group col-sm-4">
            <label for="warehouse_id">仓库</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name='warehouse_id' id='warehouse_id' class='form-control warehouse_id'>
                <option>请选择仓库</option>
                @foreach($warehouses as $warehouse)
                    <option value={{ $warehouse->id }} {{ old('warehouse_id') ? old('warehouse_id') == $warehouse->id ? 'selected' : '' : $model->warehouse_id == $warehouse->id ? 'selected' : ''}}>{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-4">
            <label for="adjust_by">调整人</label>
            <input type='text' class="form-control adjust_by" id="adjust_by" placeholder="调整人" name='adjust_by' value="{{ old('adjust_by') ? old('adjust_by') : $model->adjust_by }}" readonly>
        </div>
    </div>
    <div class='form-group'>
        <label for='label'>备注(原因)</label>
        <textarea class='form-control remark' name='remark' id='remark'>{{ old('remark') ? old('remark') : $model->remark }}</textarea>
    </div>
    <div class="panel panel-info">
        <div class="panel-heading">sku</div>
        <div class="panel-body add_row">
            <div class='row'>
                <div class='form-group col-sm-2'>
                    <label>出入库类型</label>
                </div>
                <div class="form-group col-sm-2">
                    <label for="sku" class='control-label'>sku</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-2">
                    <label for="warehouse_position_id">库位</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-1">
                    <label for="quantity" class='control-label'>可用数量</label>
                </div>
                <div class="form-group col-sm-2">
                    <label for="quantity" class='control-label'>数量</label>
                </div>
                <div class="form-group col-sm-2">
                    <label for="unit_cost" class='control-label'>单价(￥)</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
            </div>
            @foreach($adjustments as $key => $adjustment)
                <div class='row'>
                    <div class='form-group col-sm-2'>
                        <select name='arr[type][{{$key}}]' class='form-control type'>
                            <option value='IN' {{ old('arr[type][$key]')? old('arr[type][$key]') == 'IN' ? 'selected' : '' :$adjustment->type == 'IN' ? 'selected' : '' }}>入库</option>
                            <option value='OUT' {{ old('arr[type][$key]')? old('arr[type][$key]') == 'OUT' ? 'selected' : '' :$adjustment->type == 'OUT' ? 'selected' : '' }}>出库</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-2">
                        <input type='text' class="form-control sku" id="arr[sku][{{$key}}]" placeholder="sku" name='arr[sku][{{$key}}]' value="{{ old('arr[sku][$key]') ? old('arr[sku][$key]') : $adjustment->item->sku }}">
                    </div>
                    <div class="form-group col-sm-2">
                        <input type='text' name='arr[warehouse_position_id][{{$key}}]' class='form-control warehouse_position_id' placeholder='库位' value="{{ old('arr[warehouse_position_id][$key]') ? old('arr[warehouse_position_id][$key]') : $adjustment->position->name }}">
                    </div>
                    <div class="form-group col-sm-1">
                        <input type='text' class="form-control access_quantity" id="arr[access_quantity][{{$key}}]" placeholder="可用数量" name='arr[access_quantity][{{$key}}]' value="{{ $access_quantity[$key] }}" readonly>
                    </div>
                    <div class="form-group col-sm-2">
                        <input type='text' class="form-control quantity" id="arr[quantity][{{$key}}]" placeholder="数量" name='arr[quantity][{{$key}}]' value="{{ old('arr[quantity][$key]') ? old('arr[quantity][$key]') : $adjustment->quantity }}">
                    </div>
                    <div class="form-group col-sm-2">
                        <input type='text' class="form-control unit_cost" id="arr[unit_cost][{{$key}}]" placeholder="单价" name='arr[unit_cost][{{$key}}]' value="{{ old('arr[unit_cost][$key]') ? old('arr[unit_cost][$key]') : round($adjustment->amount/$adjustment->quantity, 3) }}" {{ $adjustment->type == 'OUT' ? 'readonly' : ''}}>
                    </div>
                    <button type='button' class='btn btn-danger bt_right'><i class='glyphicon glyphicon-trash'></i></button>
                </div>
            @endforeach
        </div>
    </div>
@stop
@section('pageJs')
<script type='text/javascript'>
    $(document).ready(function(){
        $(document).on('click', '.bt_right', function(){
            $(this).parent().remove();
        });

        $(document).on('blur', '.unit_cost', function(){
            tmp = $(this);
            block = tmp.parent().parent();
            type = block.find('.type').val();
            unit_cost = block.find('.unit_cost').val();
            sku = block.find('.sku').val();
            warehouse_id = $('#warehouse_id').val();
            if(sku && warehouse_id){
                $.ajax({
                    url: "{{route('stock.getMessage')}}",
                    data: {sku:sku, warehouse_id:warehouse_id},
                    dataType: 'json',
                    type: 'get',
                    success: function(result){
                        if(type == 'IN' && unit_cost) {
                            if(unit_cost > result[1]*1.3 || unit_cost < result[1]*0.7) {
                                alert('调整单价超出范围0.7-1.3,库存单价为'+result[1]);
                                block.find('.unit_cost').val('');
                            }
                        }
                    }
                })
            }
        });

        $(document).on('blur', '.warehouse_position_id', function(){
            tmp = $(this);
            warehouse_id = $('#warehous_id').val();
            block = tmp.parent().parent();
            sku = block.find('.sku').val();
            position = tmp.val();
            type = block.find('.type').val();
            if(position) {
                $.ajax({
                    url:"{{route('stock.getByPosition')}}",
                    data:{position:position, sku:sku, type:type, warehouse_id:warehouse_id},
                    dataType:'json',
                    type:'get',
                    success:function(result){
                        if(result == false) {
                            alert('库位超过两个||库位或库存不存在');
                            tmp.val('');
                            return;
                        }
                        block.find('.access_quantity').val(result);
                    }
                });
            }
        });

        $(document).on('blur', '.quantity', function(){
            tmp = $(this);
            block = tmp.parent().parent();
            access_quantity = block.find('.access_quantity').val();
            quantity = block.find('.quantity').val();
            if(quantity) {
                if(parseInt(quantity) > parseInt(access_quantity)) {
                    alert('数量超出可用数量');
                    tmp.val('');
                }
            }
        });

        $(document).on('change', '.type', function(){
            block = $(this).parent().parent();
            if($(this).val() == 'IN')
                $(this).parent().parent().find('.unit_cost').attr('readonly', false);
            else {
                $(this).parent().parent().find('.unit_cost').attr('readonly', true);
            }
            block.find('.sku').val('');
            block.find('.quantity').val('');
            block.find('.unit_cost').val('');
        });

        $(document).on('change', '.sku', function(){
            var tmp = $(this);
            var block = $(this).parent().parent();
            var type = block.find('.type').val();
            var sku = $(this).val();
            var warehouse_id = $('#warehouse_id').val();
            var position_name = block.find('.warehouse_position_id').prop('name');
            if(sku && warehouse_id){
                $.ajax({
                    url: "{{route('stock.getMessage')}}",
                    data: {sku:sku, warehouse_id:warehouse_id},
                    dataType: 'json',
                    type: 'get',
                    success: function(result){
                        if(result == 'false' || result == 'sku_none') {
                            alert('sku有误');
                            tmp.val('');
                            return;
                        }
                        if(result == 'stock_none') {
                            if(block.find('.type').val() == 'OUT') {
                                alert('该sku没有对应的库存了');
                                tmp.val('');
                                return;
                            }
                        }
                        if(type == 'IN') {
                            block.find('.position_html').html("<input type='text' name='"+position_name+"' class='form-control warehouse_position_id' placeholder='库位'>");
                            block.find('.access_quantity').val('');
                            block.find('.quantity').val('');
                            block.find('.unit_cost').val('');
                        } else {
                            str = "<select name='"+position_name+"' class='form-control warehouse_position_id'>";
                            str += "</select>";
                            block.find('.position_html').html(str);
                            block.find('.access_quantity').val('');
                            block.find('.unit_cost').val('');
                            str = "<select name='"+position_name+"' class='form-control warehouse_position_id'>";
                            for(i=0; i<result[0].length; i++)
                            {
                                str += "<option value='"+result[0][i]['position']['name']+"'>"+result[0][i]['position']['name']+"</option>";
                            }
                            str += "</select>";
                            block.find('.position_html').html(str);
                            block.find('.access_quantity').val(result[0][0]['available_quantity']);
                            block.find('.unit_cost').val(result[1]);
                        }
                    } 
                });
            }
        });

        $(document).on('change', '#warehouse_id', function(){
            $('.warehouse_position_id').val('');
            $('.type').val('IN');
            $('.unit_price').attr('readonly', false);
            $('.quantity').val('');
            $('.unit_price').val('');
            $('.sku').val('');
        });
    });
</script>
@stop