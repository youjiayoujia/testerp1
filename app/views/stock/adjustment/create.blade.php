@extends('common.form')
<link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('stockAdjustment.store') }} @stop
@section('formBody')
    <div class='row'>
        <div class='form-group col-sm-2'>
            <label for='adjust_form_id'>调整单号</label>
            <input type='text' class='form-control' name='adjust_form_id' id='adjust_form_id' value="{{ old('adjust_form_id') ? old('adjust_form_id') : 'CD'.time() }}" readonly>
        </div>
        <div class="form-group col-sm-2">
            <label for="warehouses_id">仓库</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name='warehouses_id' id='warehouses_id' class='form-control'>
                <option>请选择仓库</option>
                @foreach($warehouses as $warehouse)
                    <option value={{ $warehouse->id }} {{ old('warehouses_id') ? old('warehouses_id') == $warehouse->id ? 'selected' : '' : ''}}>{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-2">
            <label for="adjust_man_id">调整人</label>
            <input type='text' class="form-control" id="adjust_man_id" placeholder="调整人" name='adjust_man_id' value="{{ old('adjust_man_id') }}" readonly>
        </div>
        <div class="form-group col-sm-2">
            <label for="adjust_time">调整时间</label>
            <input type='text' class="form-control" id="adjust_time" placeholder="调整时间" name='adjust_time' value="{{ old('adjust_time') }}">
        </div>
        <div class='form-group col-sm-4'>
            <label for='label'>备注(原因)</label>
            <textarea class='form-control remark' name='remark' id='remark'>{{ old('remark') }}</textarea>
        </div>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading">
            sku1
            <button type='button' class='btn btn-primary div_del bt_right'><span class='glyphicon glyphicon-remove'></span></button>
        </div>
        <div class='panel-body'>
            <div class='row'>
                <div class="form-group col-sm-6">
                    <label for="item_id" class='control-label'>item号</label> 
                    <input type='text' class="form-control item_id" id="arr[item_id][0]" placeholder="item号" name='arr[item_id][0]' value="{{ old('arr[item_id][0]') }}" readonly>
                </div>
                <div class="form-group col-sm-6">
                    <label for="sku" class='control-label'>sku</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <input type='text' class="form-control sku" id="arr[sku][0]" placeholder="sku" name='arr[sku][0]' value="{{ old('arr[sku][0]') }}">
                </div>
            </div>
            <div class='row'>
                <div class='form-group col-sm-3'>
                    <label>出入库类型</label>
                    <div class='radio type'>
                        <label>
                            <input type='radio' name='arr[type][0]' value='入库' {{ old('arr[type][0]') ? old('arr[type][0]') == '入库' ? 'checked' : '' : 'checked'}}>入库
                        </label>
                    </div>
                    <div class='radio type'>
                        <label>
                            <input type='radio' name='arr[type][0]' value='出库' {{ old('arr[type][0]') ? old('arr[type][0]') == '出库' ? 'checked' : '' : ''}}>出库
                        </label>
                    </div>
                </div>
                <div class="form-group col-sm-3">
                    <label for="warehouse_positions_id">库位</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <select name='arr[warehouse_positions_id][0]' id='arr[warehouse_positions_id][0]' class='form-control warehouse_positions_id'>
                    </select>
                </div>
                <div class="form-group col-sm-3">
                    <label for="amount" class='control-label'>数量</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <input type='text' class="form-control amount" id="arr[amount][0]" placeholder="数量" name='arr[amount][0]' value="{{ old('arr[amount][0]') }}">
                </div>
                <div class="form-group col-sm-3">
                    <label for="total_amount" class='control-label'>总金额(￥)</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <input type='text' class="form-control total_amount" id="arr[total_amount][0]" placeholder="总金额" name='arr[total_amount][0]' value="{{ old('arr[total_amount][0]') }}">
                </div>
            </div>
        </div>
    </div>
    <div class='form-group addpanel'>
        <a href='javascript:' class='btn btn-primary col-sm-12' id='create_form'>
            <span class='glyphicon glyphicon-plus'>新增</span>
        </a>
    </div>
@stop
<script type='text/javascript'>
    $(document).ready(function(){
        var current = 1;
        position_buf = '';
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
                                    alert('不匹配,库位对应的sku是'+result[0]);
                                    tmp.find('.sku').val('');
                                } else {
                                    total_amount = tmp.find('.total_amount').val();
                                    amount = tmp.find('.amount').val();
                                    if(total_amount && amount) {
                                        form_cost = total_amount/amount;
                                        if((form_cost > result[3]*1.3) || (form_cost < result[3]*0.6)) {
                                            alert('商品单价'+result[3]+',调整单价不在单价*0.6-1.3变动范围内');
                                            tmp.find('.amount').val('');
                                            tmp.find('.total_amount').val('');
                                        }
                                    }
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
                                            buf.parent().next().children('.total_amount').val(result[3]*buf.val());
                                        }
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });

        $('#create_form').click(function(){
              var appendhtml = "\
        <div class='panel panel-primary'>\
            <div class='panel-heading'> sku"+(current+1)+"\
                <button type='button' class='btn btn-primary div_del bt_right'><span class='glyphicon glyphicon-remove'></span></button>\
            </div>\
            <div class='panel-body'>\
                <div class='row'>\
                    <div class='form-group col-sm-6'>\
                        <label for='item_id' class='control-label'>item号</label> \
                        <input type='text' class='form-control item_id' id='arr[item_id]["+current+"]' placeholder='item号' name='arr[item_id]["+current+"]' value='{{ old('arr[item_id]["+current+"]') }}' readonly>\
                    </div>\
                    <div class='form-group col-sm-6'>\
                        <label for='sku' class='control-label'>sku</label> <small class='text-danger glyphicon glyphicon-asterisk'></small>\
                        <input type='text' class='form-control sku' id='arr[sku]["+current+"]' placeholder='sku' name='arr[sku]["+current+"]' value='{{ old('arr[sku]["+current+"]') }}'>\
                    </div>\
                </div>\
                <div class='row'>\
                    <div class='form-group col-sm-3'>\
                        <label>出入库类型</label>\
                        <div class='radio type'>\
                            <label>\
                                <input type='radio' name='arr[type]["+current+"]' value='入库' {{ old('arr[type]["+current+"]') ? old('arr[type]["+current+"]') == '入库' ? 'checked' : '' : 'checked'}}>入库\
                            </label>\
                        </div>\
                        <div class='radio type'>\
                            <label>\
                                <input type='radio' name='arr[type]["+current+"]' value='出库' {{ old('arr[type]["+current+"]') ? old('arr[type]["+current+"]') == '入库' ? 'checked' : '' : ''}}>出库\
                            </label>\
                        </div>\
                    </div>\
                    <div class='form-group col-sm-3'>\
                        <label for='warehouse_positions_id'>库位</label> <small class='text-danger glyphicon glyphicon-asterisk'></small>\
                        <select name='arr[warehouse_positions_id]["+current+"]' id='arr[warehouse_positions_id]["+current+"]' class='form-control warehouse_positions_id'></select>\
                    </div>\
                    <div class='form-group col-sm-3'>\
                        <label for='amount' class='control-label'>数量</label> <small class='text-danger glyphicon glyphicon-asterisk'></small>\
                        <input type='text' class='form-control amount' id='arr[amount]["+current+"]' placeholder='数量' name='arr[amount]["+current+"]' value='{{ old('arr[amount]["+current+"]') }}'>\
                    </div>\
                    <div class='form-group col-sm-3'>\
                        <label for='total_amount' class='control-label'>总金额(￥)</label> <small class='text-danger glyphicon glyphicon-asterisk'></small>\
                        <input type='text' class='form-control total_amount' id='arr[total_amount]["+current+"]' placeholder='总金额' name='arr[total_amount]["+current+"]' value='{{ old('arr[total_amount]["+current+"]') }}'>\
                    </div>\
                </div>\
            </div>\
        </div>";
            $('.addpanel').before(appendhtml);
            if(position_buf) {
                $('.addpanel').prev().find('warehouse_positions_id').empty();
                for(var i=0;i<position_buf.length;i++)
                    $('<option value='+position_buf[i]['id']+'>'+position_buf[i]['name']+'</option>').appendTo($('.addpanel').prev().find('.warehouse_positions_id'));
            }

            current++;
        });

        $(document).on('click', '.type', function(){
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
                    url:"{{ route('getavailableamount') }}",
                    data:{position:position},
                    dataType:'json',
                    type:'get',
                    success:function(result) {
                        if(rowline.find(':radio:checked').val() == '出库') {
                            if(sku && position) {
                                if(result[0] < tmp.val()) {
                                    alert('超出可用库存，最大可用量'+result);
                                    tmp.val('');
                                } else {
                                    tmp.parent().next().children('.total_amount').val(result[1]*tmp.val());
                                }
                            }
                        } else {
                            total_amount = rowline.find('.total_amount').val();
                            amount = rowline.find('.amount').val();
                            if(total_amount && amount) {
                                form_cost = total_amount/amount;
                                if((form_cost > result[1]*1.3) || (form_cost < result[1]*0.6)) {
                                    alert('商品单价'+result[1]+',调整单价不在单价*0.6-1.3变动范围内');
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
                    position_buf = result;
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