@extends('common.form')
<link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
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
    <div class="panel panel-primary">
        <div class="panel-heading">
            sku
            <button type='button' class='btn btn-primary div_del bt_right'><span class='glyphicon glyphicon-remove'></span></button>
        </div>
        <div class='panel-body'>
            @foreach($adjustments as $key => $adjustment)
                <div class='row'>
                    <div class="form-group col-sm-2">
                        <label for="sku" class='control-label'>sku</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                        <input type='text' class="form-control sku" id="arr[sku][{{$key}}]" placeholder="sku" name='arr[sku][{{$key}}]' value="{{ old('arr[sku][$key]') ? old('arr[sku][$key]') : $adjustment->items->sku }}">
                    </div>
                    <div class='form-group col-sm-2'>
                        <label>出入库类型</label>
                        <select name='arr[type][{{$key}}]' class='form-control type'>
                            <option value='IN' {{ old('arr[type][$key]')? old('arr[type][$key]') == 'IN' ? 'selected' : '' :$adjustment->type == 'IN' ? 'selected' : '' }}>入库</option>
                            <option value='OUT' {{ old('arr[type][$key]')? old('arr[type][$key]') == 'OUT' ? 'selected' : '' :$adjustment->type == 'OUT' ? 'selected' : '' }}>出库</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-2">
                        <label for="warehouse_position_id">库位</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                        <select name='arr[warehouse_position_id][{{$key}}]' id='arr[warehouse_position_id][{{$key}}]' class='form-control warehouse_position_id'>
                        @foreach($positions as $position)
                            <option value={{$position['id']}} {{ $position['id'] == $adjustment->warehouse_position_id ? 'selected' : ''}}>{{ $position['name'] }}</option>
                        @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-2">
                        <label for="quantity" class='control-label'>数量</label>
                        <input type='text' class="form-control quantity" id="arr[quantity][{{$key}}]" placeholder="数量" name='arr[quantity][{{$key}}]' value="{{ old('arr[quantity][$key]') ? old('arr[quantity][$key]') : $adjustment->quantity }}">
                    </div>
                    <div class="form-group col-sm-2">
                        <label for="amount" class='control-label'>总金额(￥)</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                        <input type='text' class="form-control amount" id="arr[amount][{{$key}}]" placeholder="总金额" name='arr[amount][{{$key}}]' value="{{ old('arr[amount][$key]') ? old('arr[amount][$key]') : $adjustment->amount }}" {{ $adjustment->type == 'OUT' ? 'readonly' : ''}}>
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

        $(document).on('blur', '.quantity,.amount', function(){
            tmp = $(this);
            block = tmp.parent().parent();
            type = block.find('.type').val();
            quantity = block.find('.quantity').val();
            amount = block.find('.amount').val();
            sku = block.find('.sku').val();
            warehouse_id = $('#warehouse_id').val();
            if(sku && warehouse_id){
                $.ajax({
                    url: "{{route('getmessage')}}",
                    data: {sku:sku, warehouse_id:warehouse_id},
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

        $(document).on('blur', '.warehouse_position_id', function(){
            tmp = $(this);
            block = tmp.parent().parent();
            sku = block.find('.sku').val();
            position = tmp.val();
            quantity = block.find('.quantity').val();
            warehouses_id = $('#warehouse_id').val();
            $.ajax({
                url:"{{route('getbyposition')}}",
                data:{position:position},
                dataType:'json',
                type:'get',
                success:function(result){
                    if(block.find('.type').val() == 'OUT' && sku) {
                        flag = 0;
                        available_quantity = '';
                        for(var i=0;i<result.length;i++) {
                            if(result[i].items.sku == sku) {
                                available_quantity = result[i].available_quantity;
                                flag = 1;
                            }
                        }
                        if(flag == 0) {
                            alert('sku和库位不匹配');
                            block.find('.sku').val('');
                            return;
                        }
                        if(quantity && available_quantity < quantity) {
                            alert('数量超出了库存数量');
                            block.find('.quantity').val('');
                            block.find('.amount').val(''); 
                            return;                       
                        }
                    }
                }
            })
        });

        $(document).on('change', '.type', function(){
            if($(this).val() == 'IN')
                $(this).parent().parent().find('.amount').attr('readonly', false);
            else
                $(this).parent().parent().find('.amount').attr('readonly', true);
        });

        $(document).on('blur', '.sku', function(){
            var tmp = $(this);
            var block = $(this).parent().parent();
            var sku = $(this).val();
            var warehouse_id = $('#warehouse_id').val();
            if(sku && warehouse_id){
                $.ajax({
                    url: "{{route('getmessage')}}",
                    data: {sku:sku, warehouse_id:warehouse_id},
                    dataType: 'json',
                    type: 'get',
                    success: function(result){
                        if(result == 'false' || result == 'sku_none') {
                            alert('sku有误');
                            tmp.val('');
                            return;
                        }
                        if(result == 'stock_none'  && block.find('.type').val() == 'OUT') {
                            alert('该sku没有对应的库存了');
                            tmp.val('');
                            return;
                        }
                        var str = '';
                        var position = block.find('.warehouse_position_id').val();
                        var flag = 0;
                        for(var i=0;i<result[2].length;i++) {
                            str +="<option value="+result[2][i].id+">"+result[2][i].name+"</option>";
                            if(result[2][i].id == position)
                                flag = 1;
                        }
                        if(flag == 0 && result != 'stock_none' && block.find('.type').val() == 'OUT') {
                            block.find('.warehouse_position_id').empty();
                            block.find('.warehouse_position_id').html(str);
                            block.find('.quantity').val('');
                            block.find('.amount').val('');
                            return;
                        }
                        available_amount = '';
                        for(var i=0;i<result[1].length;i++) {
                            if(position == result[1][i].warehouse_position_id && warehouse_id == result[1][i].warehouse_id) {
                                available_amount = result[1][i].available_quantity;
                            }
                        }
                        if(block.find('.type').val() == 'OUT' && block.find('.quantity').val()) {
                            if(parseFloat(block.find('.quantity').val()) > available_amount) {
                                alert('fuck，该库位数量不足啊，'+available_amount);
                                block.find('.quantity').val('');
                                block.find('.amount').val('');
                                return;
                            }
                            block.find('.amount').val((block.find('.quantity').val()*result[3]).toFixed('3'));
                        }
                        if(block.find('.type').val() == 'IN') {
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

        $(document).on('change', '#warehouse_id', function(){
            val = $(this).val();
            $.ajax({
                url: "{{ route('getposition') }}",
                data: {val:val},
                dataType:'json',
                type:'get',
                success:function(result){
                    $('.warehouse_position_id').empty();
                    for(var i=0;i<result.length;i++)
                        $('<option value='+result[i]['id']+'>'+result[i]['name']+'</option>').appendTo($('.warehouse_position_id'));
                    $('.type').val('IN');
                    $('.amount').attr('readonly', false);
                    $('.quantity').val('');
                    $('.amount').val('');
                    $('.sku').val('');
                }
            });
        });
        
        $('#check_time').cxCalendar();
    });
</script>