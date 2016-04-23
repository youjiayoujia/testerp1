@extends('common.form')
<link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('stockAdjustment.store') }} @stop
@section('formBody')
    <div class='row'>
        <div class='form-group col-sm-4'>
            <label for='adjust_form_id'>调整单号</label>
            <input type='text' class='form-control' name='adjust_form_id' id='adjust_form_id' value="{{ old('adjust_form_id') ? old('adjust_form_id') : 'CD'.time() }}" readonly>
        </div>
        <div class="form-group col-sm-4">
            <label for="warehouse_id">仓库</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name='warehouse_id' id='warehouse_id' class='form-control'>
                <option value=''>请选择仓库</option>
                @foreach($warehouses as $warehouse)
                    <option value={{ $warehouse->id }} {{ old('warehouse_id') ? old('warehouse_id') == $warehouse->id ? 'selected' : '' : ''}}>{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-4">
            <label for="adjust_by">调整人</label>
            <input type='text' class="form-control" id="adjust_by" placeholder="调整人" name='adjust_by' value="{{ old('adjust_by') ? old('adjust_by') : '1'}}" readonly>
        </div>
        <div class='form-group col-sm-12'>
            <label for='label'>备注(原因)</label>
            <textarea class='form-control remark' name='remark' id='remark'>{{ old('remark') }}</textarea>
        </div>
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
                    <label for="access_quantity" class='control-label'>可用数量</label>
                </div>
                <div class="form-group col-sm-2">
                    <label for="quantity" class='control-label'>数量</label>
                </div>
                <div class="form-group col-sm-2">
                    <label for="amount" class='control-label'>单价(￥)</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
            </div>
            <div class='row'>
                <div class='form-group col-sm-2'>
                    <select name='arr[type][0]' class='form-control type'>
                        <option value='IN' {{ old('arr[type][0]') == 'IN' ? 'selected' : '' }}>入库</option>
                        <option value='OUT' {{ old('arr[type][0]') == 'OUT' ? 'selected' : '' }}>出库</option>
                    </select>
                </div>
                <div class="form-group col-sm-2">
                    <input type='text' class="form-control sku" id="arr[sku][0]" placeholder="sku" name='arr[sku][0]' value="{{ old('arr[sku][0]') }}">
                </div>
                <div class="form-group col-sm-2 position_html">
                    <input type='text' name='arr[warehouse_position_id][0]' class='form-control warehouse_position_id' placeholder='库位' value="{{ old('arr[warehouse_position_id][0]') }}">
                </div>
                <div class="form-group col-sm-1">
                    <input type='text' class="form-control access_quantity" id="arr[access_quantity][0]" placeholder="可用数量" name='arr[access_quantity][0]' value="{{ old('arr[access_quantity][0]') }}" readonly>
                </div>
                <div class="form-group col-sm-2">
                    <input type='text' class="form-control quantity" id="arr[quantity][0]" placeholder="数量" name='arr[quantity][0]' value="{{ old('arr[quantity][0]') }}">
                </div>
                <div class="form-group col-sm-2">
                    <input type='text' class="form-control unit_cost" id="arr[unit_cost][0]" placeholder="单价" name='arr[unit_cost][0]' value="{{ old('arr[unit_cost][0]') }}">
                </div>
                <button type='button' class='btn btn-danger bt_right'><i class='glyphicon glyphicon-trash'></i></button>
            </div>
        </div>
        <div class="panel-footer create_form">
            <div class="create"><i class="glyphicon glyphicon-plus"></i></div>
        </div>
    </div>
@stop
<script type='text/javascript'>
    $(document).ready(function(){
        var current = 1;    
        $(document).on('click', '.create_form', function(){
            warehouse = $('#warehouse_id').val();
            $.ajax({
                url:"{{ route('stockAdjustment.adjustAdd') }}",
                data:{current:current, warehouse:warehouse},
                dataType:'html',
                type:'get',
                success:function(result) {
                    $('.add_row').children('div:last').after(result);
                }
            });
            current++;
        });

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
                            alert('库位超过两个或库存不存在');
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
                            block.find('.position_html').html("<input type='text' name='"+position_name+"' class='form-control' placeholder='库位'>");
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
        
        $('#check_time').cxCalendar();
    });
</script>