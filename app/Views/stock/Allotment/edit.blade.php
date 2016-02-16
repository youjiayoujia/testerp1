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
        <div class="form-group col-lg-2">
            <label for="allotment_time" class='control-label'>调拨时间</label> 
            <input type='text' class="form-control" id="allotment_time" placeholder="调拨时间" name='allotment_time' value="{{ old('allotment_time') ? old('allotment_time') : $allotment->allotment_time}}" >
        </div>
        <div class="form-group col-lg-2">
            <label for="out_warehouses_id" class='control-label'>调出仓库</label> 
            <select id='out_warehouses_id' name='out_warehouses_id' class='form-control'>
            @foreach($warehouses as $warehouse)
                <option value='{{ $warehouse->id }}' {{ $allotment->out_warehouses_id == $warehouse->id ? 'selected' : ''}}>{{ $warehouse->name }}</option>
            @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label for="in_warehouses_id" class='control-label'>调入仓库</label> 
            <select name='in_warehouses_id' class='form-control'>
            @foreach($warehouses as $warehouse)
                <option value='{{ $warehouse->id }}' {{ $allotment->in_warehouses_id == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
            @endforeach
            </select> 
        </div>
    </div>
    <div class="form-group">
        <label for="remark" class='control-label'>备注</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <textarea name='remark' class='form-control'>{{ old('remark') ? old('remark') : $allotment->remark }}</textarea>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading">
            列表
        </div>  
        <div class='panel-body'>
            @foreach($allotmentforms as $key => $allotmentform)
                <div class='row'>
                    <div class='form-group col-sm-2'>
                        <label for='warehouse_positions_id'>库位</label> <small class='text-danger glyphicon glyphicon-asterisk'></small>
                        <select name='arr[warehouse_positions_id][{{$key}}]' id='arr[warehouse_positions_id][{{$key}}]' class='form-control warehouse_positions_id'>
                        @foreach($positions as $position)
                            <option value="{{$position->id}}" {{ $position->id == $allotmentform->warehouse_positions_id ? 'selected' : ''}}>{{$position->name}}</option>
                        @endforeach
                        </select>
                    </div>
                    <div class='form-group col-sm-2'>
                        <label for='sku'>sku</label> <small class='text-danger glyphicon glyphicon-asterisk'></small>
                        <select name='arr[sku][{{$key}}]' id='arr[sku][{{$key}}]' class='form-control sku'>
                        @foreach($skus[$key] as $sku)
                            <option value="{{$sku['sku']}}" {{ $sku['sku'] == $allotmentform->sku ? 'selected' : ''}}>{{$sku['sku']}}</option>
                        @endforeach
                        </select>
                    </div>
                    <div class='form-group col-sm-2'>
                        <label for='item_id' class='control-label'>item号</label> 
                        <input type='text' class='form-control item_id' id='arr[item_id][{{$key}}]' placeholder='item号' name='arr[item_id][{{$key}}]' value={{ $allotmentform->item_id }} readonly>
                    </div>
                    <div class='form-group col-sm-2'>
                        <label for='quantity' class='control-label'>数量</label><small class='text-danger glyphicon glyphicon-asterisk'></small>
                        <input type='text' class='form-control quantity' id='arr[quantity][{{$key}}]' placeholder='quantity' name='arr[quantity][{{$key}}]' value='{{ $allotmentform->quantity }}'>
                    </div>
                    <div class='form-group col-sm-2'>
                        <label for='amount' class='control-label'>总金额(￥)</label><small class='text-danger glyphicon glyphicon-asterisk'></small>
                        <input type='text' class='form-control amount' id='arr[amount][{{$key}}]' placeholder='总金额(￥)' name='arr[amount][{{$key}}]' value='{{ $allotmentform->amount }}' readonly>
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

        $(document).on('change', '#out_warehouses_id', function(){
            val = $('#out_warehouses_id').val();
            obj = $(this).parent();
            position = $('.warehouse_positions_id');
            sku = $('.sku');
            $.ajax({
                url: "{{ route('allotoutwarehouse') }}",
                data: {warehouse:val},
                dataType:'json',
                type:'get',
                success:function(result){
                    allotoutwarehouse = result;
                    position.empty();
                    if(result != 'none') {
                        str = '';
                        str1 = '';
                        for(var i=0;i<result[0].length;i++) 
                        {
                            str += '<option value='+result[0][i]['id']+'>'+result[0][i]['name']+'</option>';
                        }
                        for(var i=0;i<result[1].length;i++)
                        {
                            str1 += '<option value='+result[1][i]['sku']+'>'+result[1][i]['sku']+'</option>';
                        }
                        if(result[1][0]) {
                            $('.item_id').val(result[1][0]['item_id']);
                            $('.access_quantity').val(result[1][0]['available_quantity']); 
                        } else {
                            $('.item_id').val('');
                            $('.access_quantity').val('');
                            sku.empty();
                        }
                        $(str).appendTo(position);
                        $(str1).appendTo(sku);
                    }
                }
            });
        });

        $(document).on('change', '.warehouse_positions_id', function(){
            obj = $(this).parent().parent();
            warehouse = $('#out_warehouses_id').val();
            position = $(this).val();
            sku = obj.find('.sku');
            $.ajax({
                url:"{{ route('allotposition' )}}",
                data: {position:position, warehouse:warehouse},
                dataType:'json',
                type:'get',
                success:function(result) {
                    sku.empty();
                    if(result != 'none') {
                        str = '';
                        for(var i=0;i<result[0].length;i++) 
                        {
                            str +="<option value="+result[0][i]['sku']+">"+result[0][i]['sku']+"</option>";
                        }
                        obj.find('.access_quantity').val(result[0][0]['available_quantity']);
                        obj.find('.item_id').val(result[0][0]['item_id']);
                        if(obj.find('.quantity').val()) 
                        {
                            obj.find('.amount').val((result[1]*obj.find('.quantity').val()).toFixed('3'));
                        }
                    } else {
                        obj.find('.sku').empty();
                        obj.find('.access_quantity').val('');
                        obj.find('.item_id').val('');
                    }
                    $(str).appendTo(sku);
                }
            });
        });

        $(document).on('change', '.sku', function(){
            obj = $(this).parent().parent();
            warehouse = $('#out_warehouses_id').val();
            position = obj.find('.warehouse_positions_id').val();
            sku = $(this).val();
            $.ajax({
                url:"{{ route('allotsku' )}}",
                data: {position:position, warehouse:warehouse, sku:sku},
                dataType:'json',
                type:'get',
                success:function(result) {
                    if(result != 'none') {
                        obj.find('.item_id').val(result[0]['item_id']);
                        obj.find('.access_quantity').val(result[0]['available_quantity']);
                        if(obj.find('.quantity').val())
                        {
                            obj.find('.amount').val((result[1]*obj.find('.quantity').val()).toFixed('3'));
                        }
                    } else {
                        alert('sku对应没有库存');
                        obj.find('.item_id').val('');
                        obj.find('access_quantity').val('');
                        obj.find('.amount').val('');
                    }
                }
            });
        });

        $(document).on('blur', '.quantity', function(){
            var reg = /^(\d)+$/gi;
            if(!reg.test($(this).val())) {
                alert('fuck,数量有问题啊');
                $(this).val('');
                return;
            }
            obj = $(this).parent().parent();
            warehouse =  $('#out_warehouses_id').val();
            position = obj.find('.warehouse_positions_id').val();
            sku = obj.find('.sku').val();
            if($(this).val() > parseFloat(obj.find('.access_quantity').val())) {
                alert('超出可用数量');
                $(this).val('');
                obj.find('.amount').val('');
                return;
            }
            $.ajax({
                url:"{{ route('allotsku') }}",
                data:{warehouse:warehouse, position:position, sku:sku},
                dataType:'json',
                'type':'get',
                success:function(result){
                    if(result != 'none') {
                        obj.find('.amount').val((result[1]*obj.find('.quantity').val()).toFixed('3'));
                    }
                }
            });
        });

        $('#allotment_time').cxCalendar();
    });
</script>