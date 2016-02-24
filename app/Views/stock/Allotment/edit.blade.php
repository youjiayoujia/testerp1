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
            <label for="out_warehouses_id" class='control-label'>调出仓库</label> 
            <select id='out_warehouses_id' name='out_warehouses_id' class='form-control'>
            @foreach($warehouses as $warehouse)
                <option value='{{ $warehouse->id }}' {{ $allotment->out_warehouses_id == $warehouse->id ? 'selected' : ''}}>{{ $warehouse->name }}</option>
            @endforeach
            </select>
        </div>
        <div class="form-group col-lg-3">
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
                        <label for='sku'>sku</label> <small class='text-danger glyphicon glyphicon-asterisk'></small>
                        <select name='arr[items_id][{{$key}}]' id='arr[sku][{{$key}}]' class='form-control sku'>
                        @foreach($skus as $sku)
                            <option value="{{$sku['items_id']}}" {{ $sku['items_id'] == $allotmentform->items_id ? 'selected' : ''}}>{{$sku['items']['sku']}}</option>
                        @endforeach
                        </select>
                    </div>
                    <div class='form-group col-sm-2'>
                        <label for='warehouse_positions_id'>库位</label> <small class='text-danger glyphicon glyphicon-asterisk'></small>
                        <select name='arr[warehouse_positions_id][{{$key}}]' id='arr[warehouse_positions_id][{{$key}}]' class='form-control warehouse_positions_id'>
                        @foreach($positions[$key] as $position)
                            <option value="{{$position['id']}}" {{ $position['id'] == $allotmentform->warehouse_positions_id ? 'selected' : ''}}>{{$position['name']}}</option>
                        @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-2">
                        <label for="access_quantity" class='control-label'>可用数量</label>
                        <input type='text' class="form-control access_quantity" placeholder="可用数量" name='arr[access_quantity][{{$key}}]' value="{{ $availquantity[$key] }}" readonly>
                    </div>
                    <div class='form-group col-sm-2'>
                        <label for='quantity' class='control-label'>数量</label><small class='text-danger glyphicon glyphicon-asterisk'></small>
                        <input type='text' class='form-control quantity' id='arr[quantity][{{$key}}]' placeholder='数量' name='arr[quantity][{{$key}}]' value='{{ $allotmentform->quantity }}'>
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
            quantity = $('.quantity');
            amount = $('.amount');
            sku = $('.sku');
            $.ajax({
                url: "{{ route('allotoutwarehouse') }}",
                data: {warehouse:val},
                dataType:'json',
                type:'get',
                success:function(result){
                    allotoutwarehouse = result;
                    sku.empty();
                    position.empty();
                    quantity.val('');
                    amount.val('');
                    if(result != 'none') {
                        str = '';
                        str1 = '';
                        for(var i=0;i<result[2].length;i++) 
                        {
                            str += '<option value='+result[2][i]['id']+'>'+result[2][i]['name']+'</option>';
                        }
                        for(var i=0;i<result[0].length;i++)
                        {
                            str1 += '<option value='+result[0][i]['items']['id']+'>'+result[0][i]['items']['sku']+'</option>';
                        }
                        if(result[1]) {
                            $('.access_quantity').val(result[1]['available_quantity']); 
                        } else {
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
            sku = obj.find('.sku').val();
            $.ajax({
                url:"{{ route('allotposition' )}}",
                data: {position:position, items_id:sku},
                dataType:'json',
                type:'get',
                success:function(result) {
                    if(result != 'none') {
                        obj.find('.access_quantity').val(result[0]['available_quantity']);
                        if(obj.find('.quantity').val() && obj.find('.quantity').val() > result[0]['available_quantity']) 
                        {
                            alert('数量超过了可用数量');
                            obj.find('.quantity').val('');
                            obj.find('.amount').val('');
                        }
                    } else {
                        obj.find('.access_quantity').val('');
                        obj.find('.item_id').val('');
                        obj.find('.quantity').val('');
                        obj.find('.amount').val('');
                    }
                }
            });
        });

        $(document).on('change', '.sku', function(){
            obj = $(this).parent().parent();
            warehouse = $('#out_warehouses_id').val();
            position = obj.find('.warehouse_positions_id');
            sku = $(this).val();
            $.ajax({
                url:"{{ route('allotsku' )}}",
                data: {warehouse:warehouse, items_id:sku},
                dataType:'json',
                type:'get',
                success:function(result) {
                    if(result != 'none') {
                        str = '';
                        position.empty();
                        obj.find('.access_quantity').val(result[0]['available_quantity']);
                        for(var i=0;i<result[1].length;i++) 
                        {
                            str +="<option value="+result[1][i]['id']+">"+result[1][i]['name']+"</option>";
                        }
                        if(obj.find('.quantity').val() && obj.find('.quantity').val() > obj.find('.access_quantity').val())
                        {
                            alert('超出库存数量');
                            obj.find('.quantity').val('');
                            obj.find('.amount').val('');
                            $(str).appendTo(position);
                            return;
                        }
                        if(obj.find('.quantity').val() && obj.find('.quantity').val() < obj.find('.access_quantity').val())
                        {
                            obj.find('.amount').val((result[2]*obj.find('.quantity').val()).toFixed('3'));
                        }
                        $(str).appendTo(position);
                    } else {
                        alert('sku对应没有库存');
                        position.empty();
                        obj.find('access_quantity').val('');
                        obj.find('.amount').val('');
                    }
                }
            });
        });

        $(document).on('blur', '.quantity', function(){
            if($(this).val()) {
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
                    data:{warehouse:warehouse, items_id:sku},
                    dataType:'json',
                    'type':'get',
                    success:function(result){
                        if(result != 'none') {
                            obj.find('.amount').val((result[2]*obj.find('.quantity').val()).toFixed('3'));
                        }
                    }
                });
            }
        });

        $('#allotment_time').cxCalendar();
    });
</script>