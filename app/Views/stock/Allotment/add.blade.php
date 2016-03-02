<div class='row'>
    <div class='form-group col-sm-2'>
        <select name='arr[item_id][{{$current}}]' id='arr[sku][{{$current}}]' class='form-control sku'>
        @foreach($skus as $sku)
            <option value={{$sku['items']['id']}}>{{$sku['items']['sku']}}</option>
        @endforeach
        </select>
    </div>
    <div class='form-group col-sm-2'>
        <select name='arr[warehouse_position_id][{{$current}}]' id='arr[warehouse_position_id][{{$current}}]' class='form-control warehouse_position_id'>
        @foreach($positions as $position)
            <option value={{$position['id']}}>{{$position['name']}}</option>
        @endforeach
        </select>
    </div>
    <div class='form-group col-sm-2'>
        <input type='text' class='form-control access_quantity' placeholder='可用数量' name='arr[access_quantity][{{$current}}]' value="{{ $model->available_quantity }}" readonly>
    </div>
    <div class='form-group col-sm-2'>
        <input type='text' class='form-control quantity' id='arr[quantity][{{$current}}]' placeholder='数量' name='arr[quantity][{{$current}}]' value={{ old('arr[quantity][$current]') }}>
    </div>
    <div class='form-group col-sm-2'>
        <input type='text' class='form-control amount' id='arr[amount][{{$current}}]' placeholder='总金额' name='arr[amount][{{$current}}]' value="{{ old('arr[amount][$current]') }}" readonly>
    </div>
    <button type='button' class='btn btn-danger btn-outline bt_right'><i class='glyphicon glyphicon-trash'></i></button>
</div>