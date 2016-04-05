@extends('common.form')
<link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('stockAllotment.update', ['id' => $allotment->id] ) }} @stop
@section('formBody')
    <input type='hidden' name='_method' value='PUT'/>
    <div class='row'>
        <div class="form-group col-lg-4">
            <label for="allotment_id" class='control-label'>调拨单号</label>
            <input type='text' class="form-control" id="allotment_id" placeholder="调拨单号" name='allotment_id' value="{{ old('allotment_id') ? old('allotment_id') : $allotment->allotment_id }}" readonly>
        </div>
        <div class="form-group col-lg-2">
            <label for="allotment_by" class='control-label'>调拨人</label> 
            <input type='text' class="form-control" id="allotment_by" placeholder="调拨人" name='allotment_by' value="{{ old('allotment_by') ? old('allotment_by') : $allotment->allotment_by}}" readonly>
        </div>
        <div class="form-group col-lg-3">
            <label for="out_warehouse_id" class='control-label'>调出仓库</label> 
            <select id='out_warehouse_id' name='out_warehouse_id' class='form-control'>
            @foreach($warehouses as $warehouse)
                <option value='{{ $warehouse->id }}' {{ $allotment->out_warehouse_id == $warehouse->id ? 'selected' : ''}}>{{ $warehouse->name }}</option>
            @endforeach
            </select>
        </div>
        <div class="form-group col-lg-3">
            <label for="in_warehouse_id" class='control-label'>调入仓库</label> 
            <select name='in_warehouse_id' class='form-control'>
            @foreach($warehouses as $warehouse)
                <option value='{{ $warehouse->id }}' {{ $allotment->in_warehouse_id == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
            @endforeach
            </select> 
        </div>
    </div>
    <div class="form-group">
        <label for="remark" class='control-label'>备注</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <textarea name='remark' class='form-control'>{{ old('remark') ? old('remark') : $allotment->remark }}</textarea>
    </div>
    
    <div class="panel panel-info">
        <div class="panel-heading">列表</div>
        <div class="panel-body add_row">
            <div class='row'>
                <div class='form-group col-sm-2'>
                    <label for='sku'>sku</label> <small class='text-danger glyphicon glyphicon-asterisk'></small>
                </div>
                <div class='form-group col-sm-2'>
                    <label for='warehouse_position_id'>库位</label> <small class='text-danger glyphicon glyphicon-asterisk'></small>
                </div>
                <div class="form-group col-sm-2">
                    <label for="access_quantity" class='control-label'>可用数量</label>
                </div>
                <div class='form-group col-sm-2'>
                    <label for='quantity' class='control-label'>数量</label><small class='text-danger glyphicon glyphicon-asterisk'></small>
                </div>
                <div class='form-group col-sm-2'>
                    <label for='unit_cost' class='control-label'>单价(￥)</label><small class='text-danger glyphicon glyphicon-asterisk'></small>
                </div>
            </div>
            @foreach($allotmentforms as $key => $allotmentform)
                <div class='row'>
                    <div class="form-group col-sm-2">
                        <input type='text' class="form-control sku" placeholder="sku" name='arr[sku][{{$key}}]' value="{{ old('arr[sku][$key]') ? old('arr[sku][$key]') : ($allotmentform->items ? $allotmentform->items->sku : '') }}">
                    </div>
                    <div class="form-group col-sm-2">
                        <input type='text' class="form-control warehouse_position_id" placeholder="库位" name='arr[warehouse_position_id][{{$key}}]' value="{{ old('arr[warehouse_position_id][$key]') ? old('arr[warehouse_position_id][$key]') : ($allotmentform->position ? $allotmentform->position->name : '') }}">
                    </div>
                    <div class="form-group col-sm-2">
                        <input type='text' class="form-control access_quantity" placeholder="可用数量" name='arr[access_quantity][{{$key}}]' value="{{ $availquantity[$key] }}" readonly>
                    </div>
                    <div class='form-group col-sm-2'>
                        <input type='text' class='form-control quantity' id='arr[quantity][{{$key}}]' placeholder='数量' name='arr[quantity][{{$key}}]' value='{{ $allotmentform->quantity }}'>
                    </div>
                    <div class='form-group col-sm-2'>
                        <input type='text' class='form-control unit_cost' id='arr[unit_cost][{{$key}}]' placeholder='单价(￥)' name='arr[unit_cost][{{$key}}]' value='{{ round($allotmentform->amount/$allotmentform->quantity, 3) }}' readonly>
                    </div>
                    <button type='button' class='btn btn-danger bt_right'><i class='glyphicon glyphicon-trash'></i></button>
                </div>
            @endforeach
        </div>
    </div>
@stop

<script type='text/javascript'>
    $(document).ready(function(){
        $(document).on('click', '.bt_right', function(){
            $(this).parent().remove();
        });

        $(document).on('change', '#out_warehouse_id, #in_warehouse_id', function(){
            out_warehouse = $('#out_warehouse_id').val();
            in_warehouse = $('#in_warehouse_id').val();
            if(out_warehouse && in_warehouse && out_warehouse == in_warehouse)
            {
                alert('调入调出仓库不能相同');
            }
        });
        
        $(document).on('change', '#out_warehouse_id', function(){
            $('.sku').val('');
            $('.warehouse_position_id').val('');
            $('.access_quantity').val('');
            $('.quantity').val('');
            $('.unit_cost').val('');
        });

        $(document).on('blur', '.warehouse_position_id,.sku', function(){
            block = $(this).parent().parent();
            sku = block.find('.sku').val();
            position = block.find('.warehouse_position_id').val();
            if(sku && position) {
                $.ajax({
                    url:"{{ route('stock.allotPosition' )}}",
                    data: {position:position, sku:sku},
                    dataType:'json',
                    type:'get',
                    success:function(result) {
                        if(result != 'none') {
                            obj.find('.access_quantity').val(result[0]['available_quantity']);
                            if(obj.find('.quantity').val() && obj.find('.quantity').val() > result[0]['available_quantity']) 
                            {
                                alert('数量超过了可用数量');
                                obj.find('.quantity').val('');
                            }
                        } else {
                            obj.find('.access_quantity').val('');
                            obj.find('.quantity').val('');
                        }
                    }
                });
            }
        });

        $(document).on('blur', '.warehouse_position_id', function(){
            obj = $(this).parent().parent();
            tmp = $(this);
            warehouse = $('#out_warehouse_id').val();
            position = $(this).val();
            sku = obj.find('.sku').val();
            if(position) {
                $.ajax({
                    url:"{{ route('stock.ajaxPosition') }}",
                    data:{position:position},
                    dataType:'json',
                    type:'get',
                    success:function(result) {
                        if(result == 'false') {
                            alert('库位不存在');
                            tmp.val('');
                            block.find('.access_quantity').val('');
                            block.find('.quantity').val('');
                            return;
                        }
                    }
                })
            }
        });

        $(document).on('change', '.sku', function(){
            tmp = $(this);
            block = $(this).parent().parent();
            warehouse = $('#out_warehouse_id').val();
            position = block.find('.warehouse_position_id');
            sku = $(this).val();
            $.ajax({
                url:"{{ route('stock.allotSku' )}}",
                data: {warehouse:warehouse, sku:sku},
                dataType:'json',
                type:'get',
                success:function(result) {
                    if(result == 'none') {
                        alert('sku有误或对应没有库存');
                        tmp.val('');
                        return;
                    }
                    if(result != false) {
                        block.find('.warehouse_position_id').val(result[0]);
                        block.find('.access_quantity').val(result[1]);
                        block.find('.unit_cost').val(result[2]);
                    }
                }
            });
        });

        $(document).on('blur', '.quantity', function(){
            if($(this).val()) {
                var reg = /^(\d)+$/gi;
                if(!reg.test($(this).val())) {
                    alert('输入数量有问题');
                    $(this).val('');
                    return;
                }
                obj = $(this).parent().parent();
                if($(this).val() > parseInt(obj.find('.access_quantity').val())) {
                    alert('超出可用数量');
                    $(this).val('');
                    return;
                }
            }
        });
    });
</script>