@extends('common.form')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('stockIn.update', ['id' => $in->id] ) }} @stop
@section('formBody')
    <input type='hidden' name='_method' value='PUT'/>
    <div class='row'>
        <div class="form-group col-lg-6">
            <label for="item_id" class='control-label'>item号</label> 
            <input type='text' class="form-control" id="item_id" placeholder="item号" name='item_id' value="{{ old('item_id') ? old('item_id') : $in->item_id }}" readonly>
        </div>
        <div class="form-group  col-lg-6">
            <label for="sku" class='control-label'>sku</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="sku" placeholder="sku" name='sku' value="{{ old('sku') ? old('sku') : $in->sku}}">
        </div>
    </div>
    <div class='row'>
        <div class="form-group col-sm-3">
            <label for="amount" class='control-label'>数量</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="amount" placeholder="数量" name='amount' value="{{ old('amount') ? old('amount') : $in->amount }}">
        </div>
        <div class="form-group col-sm-3">
            <label for="total_amount" class='control-label'>总金额(￥)</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="total_amount" placeholder="总金额" name='total_amount' value="{{ old('total_amount') ? old('total_amount') : $in->total_amount }}">
        </div>
        <div class="form-group col-sm-3">
            <label for="warehouses_id">仓库</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name='warehouses_id' id='warehouses_id' class='form-control'>
                @foreach($warehouses as $warehouse)
                    <option value={{ $warehouse->id }} {{ old('warehouses_id') ? (old('warehouses_id') == $warehouse->id ? 'selected' : '') : $in->warehouses_id == $warehouse->id ? 'selected' : ''}}>{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-3">
            <label for="warehouse_positions_id">库位</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name='warehouse_positions_id' id='warehouse_positions_id' class='form-control'></select>
        </div>
    </div>
    <div class='row'>
        <div class="form-group col-lg-6">
            <label for="type">入库类型</label>
            <select name='type' class='form-control'>
                @foreach($data as $stockin_key => $stockin_val)
                    <option value="{{ $stockin_key }}" {{ old('type') ? (old('type') == $stockin_key ? 'selected' : '') : ($in->
                    type == $stockin_key ? 'selected' : '') }}> {{ $stockin_val }}</option>   
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-6"> 
            <label for="relation_id">入库来源id</label>
            <input name='relation_id' id="relation_id" type='text' class="form-control" placeholder="入库来源id" value="{{ old('relation_id') ? old('relation_id') : $in->relation_id }}">
        </div>
    </div>
    <div class="form-group">
        <label for="remark">备注</label>
        <textarea name='remark' id='remark' class='form-control'>{{ old('remark') ? old('remark') : $in->remark }}</textarea>
    </div>
@stop
<script type='text/javascript'>
    $(document).ready(function(){
        var warehouses_id = $('#warehouses_id').val();
        $.ajax({
                url: "{{ route('getposition') }}",
                data: {val:warehouses_id},
                dataType:'json',
                type:'get',
                success:function(result){
                    $('#warehouse_positions_id').empty();
                    for(var i=0;i<result.length;i++)
                        if(result[i]['id'] == {{ old('warehouse_positions_id') ? old('warehouse_positions_id') : $in->warehouse_positions_id }})
                            $('<option value='+result[i]['id']+' selected>'+result[i]['name']+'</option>').appendTo($('#warehouse_positions_id'));
                        else
                            $('<option value='+result[i]['id']+'>'+result[i]['name']+'</option>').appendTo($('#warehouse_positions_id'));
                }
            });

       $('#sku').blur(function(){
            var sku_val = $('#sku').val();
            if(sku_val){
            $.ajax({
                url: "{{route('getitemid')}}",
                data: {sku_val:sku_val},
                dataType: 'json',
                type: 'get',
                success: function(result){
                    $('#item_id').val(result);
                    if(!result) {
                        $('#sku').val('');
                        alert('sku不存在');
                    }
                } 
            });
            }  
        });

        $('#warehouses_id').change(function(){
            val = $('#warehouses_id').val();
            $.ajax({
                url: "{{ route('getposition') }}",
                data: {val:val},
                dataType:'json',
                type:'get',
                success:function(result){
                    $('#warehouse_positions_id').empty();
                    for(var i=0;i<result.length;i++)
                        $('<option value='+result[i]['id']+'>'+result[i]['name']+'</option>').appendTo($('#warehouse_positions_id'));
                }
            });
        });
    });
</script>