@extends('common.form')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('stock.update', ['id' => $model->id] ) }} @stop
@section('formBody')
    <input type='hidden' name='_method' value='PUT'>
    <div class='row'>
        <div class="form-group col-lg-4">
            <label for="item" class='control-label'>item</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name='item_id' class='form-control'>
                @foreach($items as $item)
                <option value={{$item->id}} {{ old('item_id') ? old('item_id') == $item->id ? 'selected' : '' : $item->id == $model->item_id ? 'selected' : '' }}>{{$item->sku}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-4">
            <label for="warehouse_id">仓库</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name='warehouse_id' id='warehouse_id' class='form-control'>
                <option>请选择仓库</option>
                @foreach($warehouses as $warehouse)
                    <option value={{ $warehouse->id }} {{ old('warehouse_id') ? old('warehouse_id') == $warehouse->id ? 'selected' : '' : $model->warehouse_id == $warehouse->id ? 'selected' : ''}}>{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-4">
            <label for="warehouse_position_id">库位</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name='warehouse_position_id' id='warehouse_position_id' class='form-control'>
                @foreach($positions as $position)
                    <option value={{ $position->id }} {{ old('warehouse_position_id') ? old('warehouse_position_id') == $position->id ? 'selected' : '' : $model->warehouse_position_id == $position->id ? 'selected' : ''}}>{{$position->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class='row'>
        <div class="form-group col-sm-3">
            <label for="all_quantity" class='control-label'>总数量</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control all_quantity" id="all_quantity" placeholder="总数量" name='all_quantity' value="{{ old('all_quantity') ? old('all_quantity') : $model->all_quantity }}">
        </div>
        <div class="form-group col-sm-3">
            <label for="available_quantity" class='control-label'>可用数量</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control available_quantity" id="available_quantity" placeholder="可用数量" name='available_quantity' value="{{ old('available_quantity') ? old('available_quantity') : $model->available_quantity }}">
        </div>
        <div class="form-group col-sm-3">
            <label for="hold_quantity" class='control-label'>hold数量</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control hold_quantity" id="hold_quantity" placeholder="hold数量" name='hold_quantity' value="{{ old('hold_quantity') ? old('hold_quantity') : $model->hold_quantity }}">
        </div>
        <div class="form-group col-sm-3">
            <label for="amount" class='control-label'>总金额(￥)</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="amount" placeholder="总金额" name='amount' value="{{ old('amount') ? old('amount') : $model->amount }}">
        </div>
    </div>
@stop
<script type='text/javascript'>
    $(document).ready(function(){
        $('.all_quantity,.available_quantity,.hold_quantity').blur(function(){
            if(parseInt($('.all_quantity').val()) != parseInt($('.available_quantity').val()) + parseInt($('.hold_quantity').val())) {
                $(':button[type=submit]').attr('disabled', true);
            } else {
                $(':button[type=submit]').attr('disabled', false);
            }
        });

        $('#warehouse_id').change(function(){
            val = $('#warehouse_id').val();
            $.ajax({
                url: "{{ route('getposition') }}",
                data: {val:val},
                dataType:'json',
                type:'get',
                success:function(result){
                    $('#warehouse_position_id').empty();
                    for(var i=0;i<result.length;i++)
                        $('<option value='+result[i].id+'>'+result[i].name+'</option>').appendTo($('#warehouse_position_id'));
                }
            });
        });
    });
</script>