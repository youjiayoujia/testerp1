@extends('common.form')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('stock.store') }} @stop
@section('formBody')
    <div class='row'>
        <div class="form-group col-lg-4">
            <label for="item" class='control-label'>item</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control sku" placeholder="sku" name='sku' value="{{ old('sku') }}">
        </div>
        <div class="form-group col-sm-4">
            <label for="warehouse_id">仓库</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name='warehouse_id' id='warehouse_id' class='form-control'>
                <option>请选择仓库</option>
                @foreach($warehouses as $warehouse)
                    <option value={{ $warehouse->id }} {{ old('warehouse_id') ? old('warehouse_id') == $warehouse->id ? 'selected' : '' : ''}}>{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-4">
            <label for="warehouse_position_id">库位</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control warehouse_position_id" placeholder="库位" name='warehouse_position_id' value="{{ old('warehouse_position_id') }}">
        </div>
    </div>
    <div class='row'>
        <div class="form-group col-sm-3">
            <label for="all_quantity" class='control-label'>数量</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control all_quantity" id="all_quantity" placeholder="总数量" name='all_quantity' value="{{ old('all_quantity') }}">
        </div>
        <div class="form-group col-sm-3">
            <label for="unit_price" class='control-label'>单价(￥)</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control unit_price" id="unit_price" placeholder="单价" name='unit_price' value="{{ old('unit_price') }}">
        </div>
    </div>
@stop
<script type='text/javascript'>
    $(document).ready(function(){
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