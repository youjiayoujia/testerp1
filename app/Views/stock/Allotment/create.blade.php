@extends('common.form')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
<link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
@section('formAction') {{ route('stockAllotment.store') }} @stop
@section('formBody')
    <div class='row'>
        <div class="form-group col-lg-4">
            <label for="allotment_id" class='control-label'>调拨单号</label> 
            <input type='text' class="form-control" id="allotment_id" placeholder="调拨单号" name='allotment_id' value="{{ old('allotment_id') ? old('allotment_id') : 'DB'.time()}}" readonly>
        </div>
        <div class="form-group col-lg-2">
            <label for="allotment_man_id" class='control-label'>调拨人</label> 
            <input type='text' class="form-control" id="allotment_man_id" placeholder="调拨人" name='allotment_man_id' value="{{ old('allotment_man_id') }}" readonly>
        </div>
        <div class="form-group col-lg-2">
            <label for="allotment_time" class='control-label'>调拨时间</label> 
            <input type='text' class="form-control" id="allotment_time" placeholder="调拨时间" name='allotment_time' value="{{ old('allotment_time') }}" >
        </div>
        <div class="form-group col-lg-2">
            <label for="out_warehouses_id" class='control-label'>调出仓库</label> 
            <select id='out_warehouses_id' name='out_warehouses_id' class='form-control'>
            <option>请选择仓库</option>
            @foreach($warehouses as $warehouse)
                <option value='{{ $warehouse->id }}' {{old('out_warehouses_id') == $warehouse->id ? 'selected' : ''}}>{{ $warehouse->name }}</option>
            @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="in_warehouses_id" class='control-label'>调入仓库</label> 
            <select id='in_warehouses_id' name='in_warehouses_id' class='form-control'>
            <option>请选择仓库</option>
            @foreach($warehouses as $warehouse)
                <option value='{{ $warehouse->id }}' {{old('in_warehouses_id') == $warehouse->id ? 'selected' : ''}}>{{ $warehouse->name }}</option>
            @endforeach
            </select> 
        </div>
    </div>
    <div class="form-group">
        <label for="remark" class='control-label'>备注</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <textarea name='remark' class='form-control'>{{ old('remark') }}</textarea>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading">
            列表
        </div>
        <div class='panel-body'>
            <div class='row'>
                <div class="form-group col-sm-2">
                    <label for="warehouse_positions_id">库位</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <select name='arr[warehouse_positions_id][0]' id='arr[warehouse_positions_id][0]' class='form-control warehouse_positions_id'>
                    </select>
                </div>
                <div class="form-group col-sm-1">
                    <label for="item_id" class='control-label'>item号</label> 
                    <input type='text' class="form-control item_id" id="arr[item_id][0]" placeholder="item号" name='arr[item_id][0]' value="{{ old('arr[item_id][0]') }}" readonly>
                </div>
                <div class="form-group col-sm-1">
                    <label for="sku" class='control-label'>sku</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <input type='text' class="form-control sku" id="arr[sku][0]" placeholder="sku" name='arr[sku][0]' value="{{ old('arr[sku][0]') }}" readonly>
                </div>
                <div class="form-group col-sm-2">
                    <label for="access_amount" class='control-label'>可用数量</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <input type='text' class="form-control access_amount" placeholder="可用数量" name='arr[access_amount][0]' value="{{ old('arr[access_amount][0]') }}" readonly>
                </div>
                <div class="form-group col-sm-2">
                    <label for="amount" class='control-label'>数量</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <input type='text' class="form-control amount" id="arr[amount][0]" placeholder="数量" name='arr[amount][0]' value="{{ old('arr[amount][0]') }}">
                </div>
                <div class="form-group col-sm-2">
                    <label for="total_amount" class='control-label'>总金额(￥)</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                    <input type='text' class="form-control total_amount" id="arr[total_amount][0]" placeholder="总金额" name='arr[total_amount][0]' value="{{ old('arr[total_amount][0]') }}" readonly>
                </div>
                <button type='button' class='btn btn-danger bt_right'><i class='glyphicon glyphicon-trash'></i></button>
            </div>
            <div class='row addpanel'>
                <a href='javascript:' class='btn btn-primary col-sm-12' id='create_form'>
                    <span class='glyphicon glyphicon-plus'>新增</span>
                </a>
            </div>
        </div>
    </div>  
@stop
<script type='text/javascript'>
    $(document).ready(function(){
        psi_tmp = '';
        current = 1;
        $(document).on('click', '#create_form', function(){
              var appendhtml = "\
                <div class='row'>\
                    <div class='form-group col-sm-2'>\
                        <label for='warehouse_positions_id'>库位</label> <small class='text-danger glyphicon glyphicon-asterisk'></small>\
                        <select name='arr[warehouse_positions_id]["+current+"]' id='arr[warehouse_positions_id]["+current+"]' class='form-control warehouse_positions_id'>\
                        </select>\
                    </div>\
                    <div class='form-group col-sm-1'>\
                        <label for='item_id' class='control-label'>item号</label> \
                        <input type='text' class='form-control item_id' id='arr[item_id]["+current+"]' placeholder='item号' name='arr[item_id]["+current+"]' value='{{ old('arr[item_id]["+current+"]') }}' readonly>\
                    </div>\
                    <div class='form-group col-sm-1'>\
                        <label for='sku' class='control-label'>sku</label><small class='text-danger glyphicon glyphicon-asterisk'></small>\
                        <input type='text' class='form-control sku' id='arr[sku]["+current+"]' placeholder='sku' name='arr[sku]["+current+"]' value='{{ old('arr[sku]["+current+"]') }}' readonly>\
                    </div>\
                    <div class='form-group col-sm-2'>\
                    <label for='access_amount' class='control-label'>可用数量</label> <small class='text-danger glyphicon glyphicon-asterisk'></small>\
                    <input type='text' class='form-control access_amount' placeholder='可用数量' name='arr[access_amount]["+current+"]' value='{{ old('arr[access_amount]["+current+"]') }}' readonly>\
                </div>\
                    <div class='form-group col-sm-2'>\
                        <label for='amount' class='control-label'>数量</label> <small class='text-danger glyphicon glyphicon-asterisk'></small>\
                        <input type='text' class='form-control amount' id='arr[amount]["+current+"]' placeholder='数量' name='arr[amount]["+current+"]' value='{{ old('arr[amount]["+current+"]') }}'>\
                    </div>\
                    <div class='form-group col-sm-2'>\
                        <label for='total_amount' class='control-label'>总金额(￥)</label> <small class='text-danger glyphicon glyphicon-asterisk'></small>\
                        <input type='text' class='form-control total_amount' id='arr[total_amount]["+current+"]' placeholder='总金额' name='arr[total_amount]["+current+"]' value='{{ old('arr[total_amount]["+current+"]') }}' readonly>\
                    </div>\
                    <button type='button' class='btn btn-danger btn-outline bt_right'><i class='glyphicon glyphicon-trash'></i></button>\
                </div>";
            $('.addpanel').before(appendhtml);

            val = $('#out_warehouses_id').val();
            obj = $('.addpanel').prev();
            position = obj.find('.warehouse_positions_id');
            if(psi_tmp) {
                obj.find('.warehouse_positions_id').empty();
                if(psi_tmp != 'none') {
                    str = '';
                    for(var i=0;i<psi_tmp[0].length;i++) 
                    {
                        str += '<option value='+psi_tmp[0][i]['id']+'>'+psi_tmp[0][i]['name']+'</option>';
                    }
                    if(psi_tmp[1][0]) {
                        obj.find('.item_id').val(psi_tmp[1][0]['item_id']);
                        obj.find('.sku').val(psi_tmp[1][0]['sku']);  
                        obj.find('.access_amount').val(psi_tmp[1][0]['available_amount']); 
                    } else {
                        obj.find('.item_id').val('');
                        obj.find('.sku').val('');
                        obj.find('.access_amount').val('');
                    }
                    $(str).appendTo(obj.find('.warehouse_positions_id'));
                }
            }
            current++;
        });

        $('#in_warehouses_id, #out_warehouses_id').change(function(){
            inwarehouse = $('#in_warehouses_id').val();
            outwarehouse = $('#out_warehouses_id').val();
            obj = $('#in_warehouses_id');
            obj1 = $('#out_warehouses_id');
            if(inwarehouse && outwarehouse && inwarehouse == outwarehouse)
            {
                alert('两仓库不可同名');
                obj.empty();
                obj1.empty();
                arr = {!! $warehouses !!};
                str = '<option>请选择仓库</option>';
                for(i=0;i<arr.length;i++)
                    str +="<option value="+arr[i].id+">"+arr[i].name+"</option>";
                $(str).appendTo(obj);
                $(str).appendTo(obj1);
            }
        });

        $(document).on('click', '.bt_right', function(){
            $(this).parent().remove();
        });

        $(document).on('change', '#out_warehouses_id', function(){
            val = $('#out_warehouses_id').val();
            obj = $(this).parent();
            position = $('.warehouse_positions_id');
            $.ajax({
                url: "{{ route('getpsi') }}",
                data: {warehouse:val},
                dataType:'json',
                type:'get',
                success:function(result){
                    psi_tmp = result;
                    position.empty();
                    if(result != 'none') {
                        str = '';
                        for(var i=0;i<result[0].length;i++) 
                        {
                            str += '<option value='+result[0][i]['id']+'>'+result[0][i]['name']+'</option>';
                        }
                        if(result[1][0]) {
                            $('.item_id').val(result[1][0]['item_id']);
                            $('.sku').val(result[1][0]['sku']);   
                            $('.access_amount').val(result[1][0]['available_amount']); 
                        } else {
                            $('.item_id').val('');
                            $('.sku').val('');
                            $('.access_amount').val('');
                        }
                        $(str).appendTo(position);
                    }
                }
            });
        });

        $(document).on('change', '.warehouse_positions_id', function(){
            obj = $(this).parent().parent();
            val_position = obj.find('.warehouse_positions_id').val();
            $.ajax({
                url:"{{ route('getsku' )}}",
                data: {val_position:val_position},
                dataType:'json',
                type:'get',
                success:function(result) {
                    if(result != 'none') {
                        obj.find('.sku').val(result[0]);
                        obj.find('.access_amount').val(result[1]);
                        obj.find('.item_id').val(result[2]);
                        if(obj.find('.amount').val()) {
                            obj.find('.total_amount').val(result[1]*obj.find('.amount').val());
                        }
                    } else {
                        obj.find('.sku').val('');
                        obj.find('.access_amount').val('');
                        obj.find('.item_id').val('');
                    }
                }
            });
        });

        $(document).on('blur', '.amount', function(){
            var reg = /-(\d)+/gi;
            if(reg.test($(this).val())) {
                alert('fuck,数量是负的了');
                $(this).val('');
                return;
            }
            obj = $(this).parent().parent();
            position = obj.find('.warehouse_positions_id').val();
            $.ajax({
                url:"{{ route('getavailableamount') }}",
                data:{position:position},
                dataType:'json',
                'type':'get',
                success:function(result){
                    if(result[0] < obj.find('.amount').val()) {
                        alert('超出可用数量');
                        obj.find('.amount').val('');
                    } else {
                        obj.find('.total_amount').val((result[1]*obj.find('.amount').val()).toFixed('3'));
                    }
                }
            });
        });
        $('#allotment_time').cxCalendar();
    });
</script>