@extends('common.form')
<link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('stockAdjustment.update', ['id' => $model->id] ) }} @stop
@section('formBody')
    <input type='hidden' name='_method' value='PUT'/>
    <div class='row'>
        <div class='form-group col-sm-2'>
            <label for='adjust_form_id'>调整单号</label>
            <input type='text' class='form-control' name='adjust_form_id' id='adjust_form_id' value="{{ old('adjust_form_id') ? old('adjust_form_id') : $model->adjust_form_id }}" readonly>
        </div>
        <div class="form-group col-sm-2">
            <label for="warehouses_id">仓库</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name='warehouses_id' id='warehouses_id' class='form-control warehouses_id'>
                <option>请选择仓库</option>
                @foreach($warehouses as $warehouse)
                    <option value={{ $warehouse->id }} {{ old('warehouses_id') ? old('warehouses_id') == $warehouse->id ? 'selected' : '' : $model->warehouses_id == $warehouse->id ? 'selected' : ''}}>{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-2">
            <label for="adjust_man_id">调整人</label>
            <input type='text' class="form-control adjust_man_id" id="adjust_man_id" placeholder="调整人" name='adjust_man_id' value="{{ old('adjust_man_id') ? old('adjust_man_id') : $model->adjust_man_id }}" readonly>
        </div>
        <div class="form-group col-sm-2">
            <label for="adjust_time">调整时间</label>
            <input type='text' class="form-control adjust_time" id="adjust_time" placeholder="调整时间" name='adjust_time' value="{{ old('adjust_time') ? old('adjust_time') : $model->adjust_time}}">
        </div>
        <div class='form-group col-sm-4'>
            <label for='label'>备注(原因)</label>
            <textarea class='form-control remark' name='remark' id='remark'>{{ old('remark') ? old('remark') : $model->remark }}</textarea>
        </div>
    </div>
    @foreach($adjustments as $key => $adjustment)
    <div class="panel panel-primary">
        <div class="panel-heading">
            sku{{($key+1)}}
            <button type='button' class='btn btn-primary div_del bt_right'><span class='glyphicon glyphicon-remove'></span></button>
        </div>
        <div class='panel-body'>
            <div class='row'>
                <div class="form-group col-sm-6">
                    <label for="item_id" class='control-label'>item号</label> 
                    <input type='text' class="form-control item_id" placeholder="item号" name='arr[item_id][{{$key}}]' value="{{ old('arr[item_id][$key]') ? old('arr[item_id][$key]') : $adjustment->item_id }}" readonly>
                </div>
                <div class="form-group col-sm-6">
                    <label for="sku" class='control-label'>sku</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <input type='text' class="form-control sku" placeholder="sku" name='arr[sku][{{$key}}]' value="{{ old('arr[sku][$key]') ? old('arr[sku][$key]') : $adjustment->sku }}">
                </div>
            </div>
            <div class='row'>
                <div class='form-group col-sm-3'>
                    <label>出入库类型</label>
                    <div class='radio type'>
                        <label>
                            <input type='radio' name='arr[type][{{$key}}]' value='入库' {{ old('arr[type][$key]') ? old('arr[type][$key]') == '入库' ? 'checked' : '' : $adjustment->type == '入库' ? 'checked' : ''}}>入库
                        </label>
                    </div>
                    <div class='radio type'>
                        <label>
                            <input type='radio' name='arr[type][{{$key}}]' value='出库' {{ old('arr[type][$key]') ? old('arr[type][$key]') == '出库' ? 'checked' : '' : $adjustment->type == '出库' ? 'checked' : ''}}>出库
                        </label>
                    </div>
                </div>
                <div class="form-group col-sm-3">
                    <label for="warehouse_positions_id">库位</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <select name='arr[warehouse_positions_id][{{$key}}]' id="warehouse_positions_id[{{$key}}]" class='form-control warehouse_positions_id'>
                    @foreach($positions as $position)
                        <option value={{$position['id']}} {{ $position['id'] == $adjustment->warehouse_positions_id ? 'selected' : ''}}>{{ $position['name'] }}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-3">
                    <label for="amount" class='control-label'>数量</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <input type='text' class="form-control amount" placeholder="数量" name='arr[amount][{{$key}}]' value="{{ old('arr[amount][$key]') ? old('arr[amount][$key]') : $adjustment->amount }}">
                </div>
                <div class="form-group col-sm-3">
                    <label for="total_amount" class='control-label'>总金额(￥)</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <input type='text' class="form-control total_amount" placeholder="总金额" name='arr[total_amount][{{$key}}]' value="{{ old('arr[total_amount][$key]') ? old('arr[total_amount][$key]') : $adjustment->total_amount }}" {{$adjustment->type == '出库' ? 'readonly' : ''}}>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@stop

<script type='text/javascript'>
    $(document).ready(function(){
        $(document).on('blur', '.type,.warehouse_positions_id,.sku', function(){
            tmp = $(this).parent().parent().parent();
            val_sku = tmp.find('.sku').val();
            val_type = tmp.find('.type :radio:checked').val();
            val_position = tmp.find('.warehouse_positions_id').val();
            if(val_sku && val_type && val_position) {
                $.ajax({
                    url:"{{ route('getsku') }}",
                    data:{val_position:val_position},
                    dataType:'json',
                    type:'get',
                    success:function(result) {
                        if(val_type == '入库') {
                            if(result != 'none') {
                                if(result[0] != val_sku) {
                                    alert('sku 和 库位不匹配');
                                    tmp.find('.sku').val('');
                                } else {
                                    $.ajax({
                                        url:"{{ route('getunitcost') }}",
                                        data:{sku:val_sku},
                                        dataType:'json',
                                        'type':'get',
                                        success:function(result){
                                            total_amount = tmp.find('.total_amount').val();
                                            amount = tmp.find('.amount').val();
                                            if(total_amount && amount) {
                                                form_cost = total_amount/amount;
                                                if((form_cost > result*1.3) || (form_cost < result*0.6)) {
                                                    alert('调整单价有误不在单价*0.6-1.3变动范围内');
                                                    tmp.find('.amount').val('');
                                                    tmp.find('.total_amount').val('');
                                                }
                                            }
                                        }
                                    });
                                }
                            }
                        } else {
                            if(result == 'none') {
                                alert('无此库位，不可出库');
                                tmp.find('.sku').val('');
                            } else {
                                if(result[0] != val_sku) {
                                    alert('sku 和 库位不匹配');
                                    tmp.find('.sku').val('');
                                } else {
                                    buf = tmp.find('.amount');
                                    if(buf.val()) {
                                        if(parseInt(buf.val()) > result[1]) {
                                            alert('数量超出可用库存，最大可用数量'+result[1]);
                                            buf.val('');
                                        } else {
                                            $.ajax({
                                                url:"{{ route('getunitcost') }}",
                                                data:{sku:val_sku},
                                                dataType:'json',
                                                'type':'get',
                                                success:function(result){
                                                    buf.parent().next().children('.total_amount').val(result*buf.val());
                                                }
                                            });
                                        }
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });

        $(document).on('blur', '.type', function(){
            if($(this).parent().find(':radio:checked').val() == '入库')
                $(this).parent().parent().find('.total_amount').attr('readonly', false);
            else
                $(this).parent().parent().find('.total_amount').attr('readonly', true);
        });

        $(document).on('blur', '.amount,.total_amount', function(){
            var rowline = $(this).parent().parent();
            var sku = rowline.prev().find('.sku').val();
            var position = rowline.find('.warehouse_positions_id').val();
            var tmp = $(this);
            if(tmp.val()) {
                $.ajax({
                    url:"{{ route('getunitcost') }}",
                    data:{sku:sku},
                    dataType:'json',
                    'type':'get',
                    success:function(result){
                        if(rowline.find(':radio:checked').val() == '出库') {
                            $.ajax({
                                url:"{{ route('getavailableamount') }}",
                                data:{position:position},
                                dataType:'json',
                                type:'get',
                                success:function(result){
                                    if(sku && position) {
                                        if(result[0] < tmp.val()) {
                                            alert('超出可用库存，最大可用量'+result);
                                            tmp.val('');
                                        } else {
                                            tmp.parent().next().children('.total_amount').val(result[1]*tmp.val());
                                        }
                                    }
                                }
                            });
                        } else {
                            total_amount = rowline.find('.total_amount').val();
                            amount = rowline.find('.amount').val();
                            if(total_amount && amount) {
                                form_cost = total_amount/amount;
                                if((form_cost > result*1.3) || (form_cost < result*0.6)) {
                                    alert('调整单价有误不在单价*0.6-1.3变动范围内');
                                    rowline.find('.amount').val('');
                                    rowline.find('.total_amount').val('');
                                }
                            }
                        }
                    }
                });
            }
        });

        $(document).on('click','.div_del',function(){
            $(this).parent().parent().remove(); 
        });

        $(document).on('blur', '.sku', function(){
            var tmp = $(this);
            var sku_val = $(this).val();
            if(sku_val){
            $.ajax({
                url: "{{route('getitemid')}}",
                data: {sku_val:sku_val},
                dataType: 'json',
                type: 'get',
                success: function(result){
                    tmp.parent().prev().children(':text').val(result);
                    if(!result) {
                        tmp.val('');
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