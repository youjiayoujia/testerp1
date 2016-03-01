<div class='row'>
    <div class='form-group col-sm-2'>
        <label for='sku' class='control-label'>sku</label> <small class='text-danger glyphicon glyphicon-asterisk'></small>
        <input type='text' class='form-control sku' id='arr[sku][{{$current}}]' placeholder='sku' name='arr[sku][{{$current}}]' value='{{ old('arr[sku][$current]') }}'>
    </div>
    <div class='form-group col-sm-2'>
        <label>出入库类型</label>
        <select name='arr[type][{{$current}}]' class='form-control type'>
            <option value='IN' {{ old('arr[type][$current]') == 'IN' ? 'selected' : '' }}>入库</option>
            <option value='OUT' {{ old('arr[type][$current]') == 'OUT' ? 'selected' : '' }}>出库</option>
        </select>
    </div>
    <div class='form-group col-sm-2'>
        <label for='warehouse_position_id'>库位</label> <small class='text-danger glyphicon glyphicon-asterisk'></small>
        <select name='arr[warehouse_position_id][{{$current}}]' id='arr[warehouse_position_id][{{$current}}]' class='form-control warehouse_position_id'>
            @foreach($positions as $position)
                <option value={{$position->id}}>{{$position->name}}</option>
            @endforeach
        </select>
    </div>
    <div class='form-group col-sm-2'>
        <label for='quantity' class='control-label'>数量</label> <small class='text-danger glyphicon glyphicon-asterisk'></small>
        <input type='text' class='form-control quantity' id='arr[quantity][{{$current}}]' placeholder='数量' name='arr[quantity][{{$current}}]' value='{{ old('arr[quantity][$current]') }}'>
    </div>
    <div class='form-group col-sm-2'>
        <label for='amount' class='control-label'>总金额(￥)</label> <small class='text-danger glyphicon glyphicon-asterisk'></small>
        <input type='text' class='form-control amount' id='arr[amount][{{$current}}]' placeholder='总金额' name='arr[amount][{{$current}}]' value='{{ old('arr[amount][$current]') }}'>
    </div>
    <button type='button' class='btn btn-danger'><i class='glyphicon glyphicon-trash'></i></button>
</div>
