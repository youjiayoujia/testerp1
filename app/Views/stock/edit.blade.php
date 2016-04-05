@extends('common.form')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('stock.update', ['id' => $model->id] ) }} @stop
@section('formBody')
    <input type='hidden' name='_method' value='PUT'>
    <div class='row'>
        <div class="form-group col-lg-4">
            <label for="sku" class='control-label'>sku</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control sku" placeholder="sku" name='sku' value="{{ old('sku') ? old('sku') : ($model->items ? $model->items->sku : '') }}">
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
            <input type='text' class="form-control warehouse_position_id" placeholder="库位" name='warehouse_position_id' value="{{ old('warehouse_position_id') ? old('warehouse_position_id') : ($model->position ? $model->position->name : '') }}">
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

        $('.sku').blur(function(){
            tmp = $(this);
            sku = $(this).val();
            if(sku) {
                $.ajax({
                    url:"{{ route('stock.ajaxSku') }}",
                    data:{sku:sku},
                    dataType:'json',
                    type:'get',
                    success:function(result) {
                        if(result == 'false') {
                            alert('sku不存在');
                            tmp.val('');
                        }
                    }
                })
            }
        });

        $('.warehouse_position_id').blur(function(){
            tmp = $(this);
            position = $(this).val();
            if(position) {
                $.ajax({
                    url:"{{ route('stock.ajaxPosition') }}",
                    data:{position:position},
                    dataType:'json',
                    type:'get',
                    success:function(result) {
                        if(result == 'false') {
                            alert('库位不存在');
                            tmp.val('');
                        }
                    }
                })
            }
        });
    });
</script>