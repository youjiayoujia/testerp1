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
            <label for="allotment_man_id" class='control-label'>调拨人</label> 
            <input type='text' class="form-control" id="allotment_man_id" placeholder="调拨人" name='allotment_man_id' value="{{ old('allotment_man_id') ? old('allotment_man_id') : $allotment->allotment_man_id}}" readonly>
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
                        <label for='item_id' class='control-label'>item号</label> 
                        <input type='text' class='form-control item_id' id='arr[item_id][{{$key}}]' placeholder='item号' name='arr[item_id][{{$key}}]' value={{ $allotmentform->item_id }} readonly>
                    </div>
                    <div class='form-group col-sm-2'>
                        <label for='sku' class='control-label'>sku</label><small class='text-danger glyphicon glyphicon-asterisk'></small>
                        <input type='text' class='form-control sku' id='arr[sku][{{$key}}]' placeholder='sku' name='arr[sku][{{$key}}]' value='{{ $allotmentform->sku }}' readonly>
                    </div>
                    <div class='form-group col-sm-2'>
                        <label for='amount' class='control-label'>数量</label><small class='text-danger glyphicon glyphicon-asterisk'></small>
                        <input type='text' class='form-control amount' id='arr[amount][{{$key}}]' placeholder='amount' name='arr[amount][{{$key}}]' value='{{ $allotmentform->amount }}'>
                    </div>
                    <div class='form-group col-sm-2'>
                        <label for='total_amount' class='control-label'>总金额(￥)</label><small class='text-danger glyphicon glyphicon-asterisk'></small>
                        <input type='text' class='form-control total_amount' id='arr[total_amount][{{$key}}]' placeholder='总金额(￥)' name='arr[total_amount][{{$key}}]' value='{{ $allotmentform->total_amount }}' readonly>
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
            $.ajax({
                url: "{{ route('getpsi') }}",
                data: {warehouse:val},
                dataType:'json',
                type:'get',
                success:function(result){
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
                        } else {
                            $('.item_id').val('');
                            $('.sku').val('');
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
                        obj.find('.item_id').val(result[2]);
                    } else {
                        obj.find('.sku').val('');
                        obj.find('.item_id').val('');
                    }
                }
            });
        });

        $(document).on('blur', '.amount', function(){
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
                        obj.find('.total_amount').val(result[1]*obj.find('.amount').val());
                    }
                }
            });
        });

        $('#allotment_time').cxCalendar();
    });
</script>