@extends('common.form')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('checkformupdate', ['id' => $allotment->id]) }} @stop
@section('formBody') 
    <div class='row'>
        <div class="form-group col-lg-4">
            <label for="allotment_id" class='control-label'>调拨单号</label>
            <input type='text' class="form-control" id="allotment_id" placeholder="调拨单号" name='allotment_id' value="{{ $allotment->allotment_id }}" readonly>
        </div>
        <div class="form-group col-lg-2">
            <label for="allotment_man_id" class='control-label'>调拨人</label> 
            <input type='text' class="form-control" id="allotment_man_id" placeholder="调拨人" name='allotment_man_id' value="{{ $allotment->allotment_man_id}}" readonly>
        </div>
        <div class="form-group col-lg-2">
            <label for="allotment_time" class='control-label'>调拨时间</label> 
            <input type='text' class="form-control" id="allotment_time" placeholder="调拨时间" name='allotment_time' value="{{ $allotment->allotment_time}}" readonly>
        </div>
        <div class="form-group col-lg-2">
            <label for="out_warehouses_id" class='control-label'>调出仓库</label> 
            <input type='text' class="form-control" id="out_warehouses_id" placeholder="调出仓库" name='out_warehouses_id' value="{{ $allotment->outwarehouse->name}}" readonly>
        </div>
        <div class="form-group col-lg-2">
            <label for="in_warehouses_id" class='control-label'>调入仓库</label> 
            <input type='text' class="form-control" id="in_warehouses_id" placeholder="调入仓库" name='in_warehouses_id' value="{{ $allotment->inwarehouse->name}}" readonly>
        </div>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading">
            列表
        </div>  

        <div class='panel-body'>
            @foreach($allotmentforms as $key => $allotmentform)
                <div class='row'>
                    <div class='form-group col-sm-1'>
                        <label for='item_id' class='control-label'>item号</label> 
                        <input type='text' class='form-control item_id' id='arr[item_id][{{$key}}]' placeholder='item号' name='arr[item_id][{{$key}}]' value={{ $allotmentform->item_id }} readonly>
                    </div>
                    <div class='form-group col-sm-1'>
                        <label for='sku' class='control-label'>sku</label><small class='text-danger glyphicon glyphicon-asterisk'></small>
                        <input type='text' class='form-control sku' id='arr[sku][{{$key}}]' placeholder='sku' name='arr[sku][{{$key}}]' value='{{ $allotmentform->sku }}' readonly>
                    </div>
                    <div class='form-group col-sm-2'>
                        <label for='amount' class='control-label'>数量</label><small class='text-danger glyphicon glyphicon-asterisk'></small>
                        <input type='text' class='form-control amount' id='arr[amount][{{$key}}]' placeholder='amount' name='arr[amount][{{$key}}]' value={{ $allotmentform->amount }} readonly>
                    </div>
                    <div class='form-group col-sm-2'>
                        <label for='total_amount' class='control-label'>总金额(￥)</label><small class='text-danger glyphicon glyphicon-asterisk'></small>
                        <input type='text' class='form-control total_amount' id='arr[total_amount][{{$key}}]' placeholder='总金额(￥)' name='arr[total_amount][{{$key}}]' value='{{ $allotmentform->total_amount }}' readonly>
                    </div>
                    <div class='form-group col-sm-2'>
                        <label for='receive_amount' class='control-label'>之前收到数量</label><small class='text-danger glyphicon glyphicon-asterisk'></small>
                        <input type='text' class='form-control old_receive_amount' id='arr[old_receive_amount][{{$key}}]' name='arr[old_receive_amount][{{$key}}]' value='{{ $allotmentform->receive_amount }}' readonly>
                    </div>
                    <div class='form-group col-sm-2'>
                        <label for='receive_amount' class='control-label'>新收到数量</label><small class='text-danger glyphicon glyphicon-asterisk'></small>
                        <input type='text' class='form-control receive_amount' id='arr[receive_amount][{{$key}}]' placeholder='新收到数量' name='arr[receive_amount][{{$key}}]'>
                    </div>
                    <div class='form-group col-sm-2'>
                        <label for='warehouse_positions_id'>库位</label> <small class='text-danger glyphicon glyphicon-asterisk'></small>
                        <select name='arr[warehouse_positions_id][{{$key}}]' id='arr[warehouse_positions_id][{{$key}}]' class='form-control warehouse_positions_id'>
                        <option>请输入库位</option>
                        @foreach($positions as $position)
                            <option value="{{$position->id}}" {{$allotmentform->in_warehouse_positions_id == $position->id ? 'selected' : ''}}>{{$position->name}}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="form-group">
        <label for="remark" class='control-label'>备注</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <textarea name='remark' class='form-control'>{{$allotment->remark}}</textarea>
    </div>
@stop
<script type='text/javascript'>
$(document).ready(function(){
    $('.receive_amount').blur(function(){
        obj = $(this);
        var reg=/-(\d)+/gi;
        if(reg.test(obj.val())) {
            alert('fuck,数量负的了!');
            obj.val('0');
        }
        if(parseInt(obj.val()) > parseInt(obj.parent().prev().prev().children('.amount').val())) {
            alert('超出数量');
            obj.val(obj.parent().prev().prev().children('.amount').val());
        }
        all_amount = parseInt(obj.parent().parent().find('.amount').val());
        old_amount = parseInt(obj.parent().parent().find('.old_receive_amount').val());
        amount = parseInt(obj.val());
        if(all_amount < (old_amount + amount)) {
            alert('fuck,超出数量了');
            $(this).val('');
        }
    });

    $('.warehouse_positions_id').blur(function(){
        obj = $(this);
        val = obj.val();
        sku = obj.parent().parent().find('.sku').val();
        $.ajax({
            url:"{{ route('stockposition') }}",
            data:{warehouse:{{$allotment->in_warehouses_id}}, position:val},
            dataType:'json',
            type:'get',
            success:function(result) {
                if(result != 'none')
                    if(result['sku'] != sku) {
                        alert('库位与sku不匹配');
                        obj.empty();
                        arr = {!! $positions !!};
                        str = '<option>请选择仓库</option>';
                        for(i=0;i<arr.length;i++)
                            str +="<option value="+arr[i].id+">"+arr[i].name+"</option>";
                        $(str).appendTo(obj);
                    }
            }
        });
    });
});
</script>