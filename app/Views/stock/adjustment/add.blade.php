<div class='row'>
    <div class='form-group col-sm-2'>
        <input type='text' class='form-control sku' id='arr[sku][{{$current}}]' placeholder='sku' name='arr[sku][{{$current}}]' value='{{ old('arr[sku][$current]') }}'>
    </div>
    <div class='form-group col-sm-2'>
        <select name='arr[type][{{$current}}]' class='form-control type'>
            <option value='IN' {{ old('arr[type][$current]') == 'IN' ? 'selected' : '' }}>入库</option>
            <option value='OUT' {{ old('arr[type][$current]') == 'OUT' ? 'selected' : '' }}>出库</option>
        </select>
    </div>
    <div class='form-group col-sm-2'>
        <input type='text' name='arr[warehouse_position_id][{{$current}}]' class='form-control warehouse_position_id' placeholder='库位' value="{{ old('arr[warehouse_position_id][$current]') }}">
    </div>
    <div class='form-group col-sm-2'>
        <input type='text' class='form-control quantity' id='arr[quantity][{{$current}}]' placeholder='数量' name='arr[quantity][{{$current}}]' value='{{ old('arr[quantity][$current]') }}'>
    </div>
    <div class="form-group col-sm-2">
        <input type='text' class="form-control unit_cost" id="arr[unit_cost][{{$current}}]" placeholder="单价" name='arr[unit_cost][{{$current}}]' value="{{ old('arr[unit_cost][$current]') }}">
    </div>
    <button type='button' class='btn btn-danger bt_right'><i class='glyphicon glyphicon-trash'></i></button>
</div>
