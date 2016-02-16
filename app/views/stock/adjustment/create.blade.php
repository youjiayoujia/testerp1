@extends('common.form')
<link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('stockAdjustment.store') }} @stop
@section('formBody')
    <div class='row'>
        <div class='form-group col-sm-3'>
            <label for='adjust_form_id'>调整单号</label>
            <input type='text' class='form-control' name='adjust_form_id' id='adjust_form_id' value="{{ old('adjust_form_id') ? old('adjust_form_id') : 'CD'.time() }}" readonly>
        </div>
        <div class="form-group col-sm-3">
            <label for="warehouses_id">仓库</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name='warehouses_id' id='warehouses_id' class='form-control'>
                <option value=''>请选择仓库</option>
                @foreach($warehouses as $warehouse)
                    <option value={{ $warehouse->id }} {{ old('warehouses_id') ? old('warehouses_id') == $warehouse->id ? 'selected' : '' : ''}}>{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-3">
            <label for="adjust_man_id">调整人</label>
            <input type='text' class="form-control" id="adjust_man_id" placeholder="调整人" name='adjust_man_id' value="{{ old('adjust_man_id') }}" readonly>
        </div>
        <div class="form-group col-sm-3">
            <label for="adjust_time">调整时间</label>
            <input type='text' class="form-control" id="adjust_time" placeholder="调整时间" name='adjust_time' value="{{ old('adjust_time') }}">
        </div>
        <div class='form-group col-sm-12'>
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
                            <input type='radio' name='arr[type][0]' value='IN' {{ old('arr[type][0]') ? old('arr[type][0]') == 'IN' ? 'checked' : '' : 'checked'}}>入库
                        </label>
                    </div>
                    <div class='radio type'>
                        <label>
                            <input type='radio' name='arr[type][0]' value='OUT' {{ old('arr[type][0]') ? old('arr[type][0]') == 'OUT' ? 'checked' : '' : ''}}>出库
                        </label>
                    </div>
                </div>
                <div class="form-group col-sm-3">
                    <label for="warehouse_positions_id">库位</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <select name='arr[warehouse_positions_id][0]' id='arr[warehouse_positions_id][0]' class='form-control warehouse_positions_id'>
                    </select>
                </div>
                <div class="form-group col-sm-3">
                    <label for="quantity" class='control-label'>数量</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <input type='text' class="form-control quantity" id="arr[quantity][0]" placeholder="数量" name='arr[quantity][0]' value="{{ old('arr[quantity][0]') }}">
                </div>
                <div class="form-group col-sm-3">
                    <label for="amount" class='control-label'>总金额(￥)</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <input type='text' class="form-control amount" id="arr[amount][0]" placeholder="总金额" name='arr[amount][0]' value="{{ old('arr[amount][0]') }}">
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
                                <input type='radio' name='arr[type]["+current+"]' value='IN' {{ old('arr[type]["+current+"]') ? old('arr[type]["+current+"]') == 'IN' ? 'checked' : '' : 'checked'}}>入库\
                            </label>\
                        </div>\
                        <div class='radio type'>\
                            <label>\
                                <input type='radio' name='arr[type]["+current+"]' value='OUT' {{ old('arr[type]["+current+"]') ? old('arr[type]["+current+"]') == 'OUT' ? 'checked' : '' : ''}}>出库\
                            </label>\
                        </div>\
                    </div>\
                    <div class='form-group col-sm-3'>\
                        <label for='warehouse_positions_id'>库位</label> <small class='text-danger glyphicon glyphicon-asterisk'></small>\
                        <select name='arr[warehouse_positions_id]["+current+"]' id='arr[warehouse_positions_id]["+current+"]' class='form-control warehouse_positions_id'></select>\
                    </div>\
                    <div class='form-group col-sm-3'>\
                        <label for='quantity' class='control-label'>数量</label> <small class='text-danger glyphicon glyphicon-asterisk'></small>\
                        <input type='text' class='form-control quantity' id='arr[quantity]["+current+"]' placeholder='数量' name='arr[quantity]["+current+"]' value='{{ old('arr[quantity]["+current+"]') }}'>\
                    </div>\
                    <div class='form-group col-sm-3'>\
                        <label for='amount' class='control-label'>总金额(￥)</label> <small class='text-danger glyphicon glyphicon-asterisk'></small>\
                        <input type='text' class='form-control amount' id='arr[amount]["+current+"]' placeholder='总金额' name='arr[amount]["+current+"]' value='{{ old('arr[amount]["+current+"]') }}'>\
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

        $(document).on('blur', '.quantity,.amount', function(){
            tmp = $(this);
            block = tmp.parent().parent().parent();
            type = block.find(':radio:checked').val();
            quantity = block.find('.quantity').val();
            amount = block.find('.amount').val();
            sku = block.find('.sku').val();
            warehouses_id = $('#warehouses_id').val();
            if(sku && warehouses_id){
                $.ajax({
                    url: "{{route('getmessage')}}",
                    data: {sku:sku, warehouses_id:warehouses_id},
                    dataType: 'json',
                    type: 'get',
                    success: function(result){
                        if(type == 'OUT' && quantity) {
                            block.find('.amount').val((parseFloat(quantity)*result[3]).toFixed('3'));
                            return;
                        }
                        if(type == 'IN' && quantity && amount) {
                            if(amount/quantity > result[3]*1.3 || amount/quantity < result[3]*0.7) {
                                alert('fuck,调整单价超出范围,库存单价'+result[3]);
                                block.find('.quantity').val('');
                                block.find('.amount').val('');
                            }
                        }
                    }
                })
            }
        });

        $(document).on('blur', '.warehouse_positions_id', function(){
            tmp = $(this);
            block = tmp.parent().parent().parent();
            sku = block.find('.sku').val();
            warehouse_positions_id = tmp.val();
            quantity = block.find('.quantity').val();
            warehouses_id = $('#warehouses_id').val();
            $.ajax({
                url:"{{route('getbyposition')}}",
                data:{warehouse_positions_id:warehouse_positions_id,warehouses_id:warehouses_id},
                dataType:'json',
                type:'get',
                success:function(result){
                    if(block.find('.type').find(':radio:checked').val() == 'OUT' && sku) {
                        flag = 0;
                        available_quantity = '';
                        for(var i=0;i<result.length;i++) {
                            if(result[i].sku == sku) {
                                available_quantity = result[i].available_quantity;
                                flag = 1;
                            }
                        }
                        if(flag == 0) {
                            alert('sku和库位不匹配');
                            block.find('.sku').val('');
                            block.find('.item_id').val('');
                            return;
                        }
                        if(available_quantity < quantity) {
                            alert('数量超出了库存数量');
                            block.find('.quantity').val('');
                            block.find('.amount').val(''); 
                            return;                       
                        }
                    }
                }
            })
        });

        $(document).on('click', '.type', function(){
            if($(this).parent().find(':radio:checked').val() == 'IN')
                $(this).parent().parent().find('.amount').attr('readonly', false);
            else
                $(this).parent().parent().find('.amount').attr('readonly', true);
        });

        $(document).on('click','.div_del',function(){
            $(this).parent().parent().remove(); 
        });

        $(document).on('blur', '.sku', function(){
            var tmp = $(this);
            var block = $(this).parent().parent().parent();
            var sku = $(this).val();
            var warehouses_id = $('#warehouses_id').val();
            if(sku && warehouses_id){
                $.ajax({
                    url: "{{route('getmessage')}}",
                    data: {sku:sku, warehouses_id:warehouses_id},
                    dataType: 'json',
                    type: 'get',
                    success: function(result){
                        if(result == 'false' || result == 'sku_none') {
                            alert('sku有误');
                            tmp.val('');
                            return;
                        }
                        if(result == 'stock_none'  && block.find('.type').find(':radio:checked').val() == 'OUT') {
                            alert('该sku没有对应的库存了');
                            tmp.val('');
                            return;
                        }
                        block.find('.item_id').val(result[0].id);
                        var str = '';
                        var position = block.find('.warehouse_positions_id').val();
                        var flag = 0;
                        for(var i=0;i<result[2].length;i++) {
                            str +="<option value="+result[2][i].id+">"+result[2][i].name+"</option>";
                            if(result[2][i].id == position)
                                flag = 1;
                        }
                        if(flag == 0 && result != 'stock_none') {
                            block.find('.warehouse_positions_id').empty();
                            block.find('.warehouse_positions_id').html(str);
                            block.find('.quantity').val('');
                            block.find('.amount').val('');
                            return;
                        }
                        available_amount = '';
                        for(var i=0;i<result[1].length;i++) {
                            if(position == result[1][i].warehouse_positions_id && warehouses_id == result[1][i].warehouses_id) {
                                available_amount = result[1][i].available_quantity;
                            }
                        }
                        if(block.find('.type').find(':radio:checked').val() == 'OUT' && block.find('.quantity').val()) {
                            if(parseFloat(block.find('.quantity').val()) > available_amount) {
                                alert('fuck，该库位数量不足啊，'+available_amount);
                                block.find('.quantity').val('');
                                block.find('.amount').val('');
                                return;
                            }
                            block.find('.amount').val((block.find('.quantity').val()*result[3]).toFixed('3'));
                        }
                        if(block.find('.type').find(':radio:checked').val() == '入库') {
                            quantity = block.find('.quantity').val();
                            amount = block.find('.amount').val();
                            if(quantity && amount) {
                                if(amount/quantity > result[3]*1.3 || amount/quantity < result[3]*0.6) {
                                    alert('单价变动超出范围,库存单价'+result[3]);
                                    block.find('.quantity').val('');
                                    block.find('.amount').val('');
                                    return;
                                }
                            }
                        }
                    } 
                });
            }
        });

        $(document).on('change', '#warehouses_id', function(){
            val = $(this).val();
            tmp = 
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
                    $('.quantity').val('');
                    $('.amount').val('');
                }
            });
        });
        
        $('#adjust_time').cxCalendar();
        $('#check_time').cxCalendar();
    });
</script>