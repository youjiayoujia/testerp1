<div class='row'>
<div class='form-group col-sm-2'>
    <label for='sku'>sku</label> <small class='text-danger glyphicon glyphicon-asterisk'></small>
    <select name='arr[sku][{{$current}}]' id='arr[sku][{{$current}}]' class='form-control sku'>
    @foreach($skus as $sku)
        <option value={{$sku['sku']}}>{{$sku['sku']}}</option>
    @endforeach
    </select>
</div>
<div class='form-group col-sm-2'>
    <label for='warehouse_positions_id'>库位</label> <small class='text-danger glyphicon glyphicon-asterisk'></small>
    <select name='arr[warehouse_positions_id][{{$current}}]' id='arr[warehouse_positions_id][{{$current}}]' class='form-control warehouse_positions_id'>
    @foreach($positions as $position)
        <option value={{$position['id']}}>{{$position['name']}}</option>
    @endforeach
    </select>
</div>
<div class='form-group col-sm-1'>
    <label for='item_id' class='control-label'>item号</label> 
    <input type='text' class='form-control item_id' id='arr[item_id][{{$current}}]' placeholder='item号' name='arr[item_id][{{$current}}]' value="{{ $model->item_id }}" readonly>
</div>
<div class='form-group col-sm-2'>
<label for='access_quantity' class='control-label'>可用数量</label> <small class='text-danger glyphicon glyphicon-asterisk'></small>
<input type='text' class='form-control access_quantity' placeholder='可用数量' name='arr[access_quantity][{{$current}}]' value="{{ $model->available_quantity }}" readonly>
</div>
<div class='form-group col-sm-2'>
    <label for='quantity' class='control-label'>数量</label> <small class='text-danger glyphicon glyphicon-asterisk'></small>
    <input type='text' class='form-control quantity' id='arr[quantity][{{$current}}]' placeholder='数量' name='arr[quantity][{{$current}}]' value={{ old('arr[quantity][$current]') }}>
</div>
<div class='form-group col-sm-2'>
    <label for='amount' class='control-label'>总金额(￥)</label> <small class='text-danger glyphicon glyphicon-asterisk'></small>
    <input type='text' class='form-control amount' id='arr[amount][{{$current}}]' placeholder='总金额' name='arr[amount][{{$current}}]' value="{{ old('arr[amount][$current]') }}" readonly>
</div>
<button type='button' class='btn btn-danger btn-outline bt_right'><i class='glyphicon glyphicon-trash'></i></button>
</div>