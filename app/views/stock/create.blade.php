@extends('common.form')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('stock.store') }} @stop
@section('formBody')
    <div class="form-group">
        <label for="item_id" class='control-label'>item号</label> 
        <input type='text' class="form-control" id="item_id" placeholder="item号" name='item_id' value="{{ old('item_id') }}" readonly>
    </div>
    <div class="form-group">
        <label for="sku" class='control-label'>sku</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="sku" placeholder="sku" name='sku' value="{{ old('sku') }}">
    </div>
    <div class='row'>
        <div class="form-group col-sm-6">
            <label for="warehouses_id">仓库</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name='warehouses_id' id='warehouses_id' class='form-control'>
                <option>请选择仓库</option>
                @foreach($warehouses as $warehouse)
                    <option value={{ $warehouse->id }} {{ old('warehouses_id') ? old('warehouses_id') == $warehouse->id ? 'selected' : '' : ''}}>{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-6">
            <label for="warehouse_positions_id">库位</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name='warehouse_positions_id' id='warehouse_positions_id' class='form-control'><option>请选择库位</option></select>
        </div>
    </div>
    <div class='row'>
        <div class="form-group col-sm-3">
            <label for="all_amount" class='control-label'>总数量</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control all_amount" id="all_amount" placeholder="总数量" name='all_amount' value="{{ old('all_amount') }}">
        </div>
        <div class="form-group col-sm-3">
            <label for="available_amount" class='control-label'>可用数量</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control available_amount" id="available_amount" placeholder="可用数量" name='available_amount' value="{{ old('available_amount') }}">
        </div>
        <div class="form-group col-sm-3">
            <label for="hold_amount" class='control-label'>hold数量</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control hold_amount" id="hold_amount" placeholder="hold数量" name='hold_amount' value="{{ old('hold_amount') }}">
        </div>
        <div class="form-group col-sm-3">
            <label for="total_amount" class='control-label'>总金额(￥)</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control total_amount" id="total_amount" placeholder="总金额" name='total_amount' value="{{ old('total_amount') }}">
        </div>
    </div>
@stop
<script type='text/javascript'>
    $(document).ready(function(){

        $('.all_amount,.available_amount,.hold_amount').blur(function(){
            if(parseInt($('.all_amount').val()) != parseInt($('.available_amount').val()) + parseInt($('.hold_amount').val())) {
                $(':button[type=submit]').attr('disabled', true);
            } else {
                $(':button[type=submit]').attr('disabled', false);
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