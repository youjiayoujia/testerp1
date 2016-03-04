<div class='row'>
    <div class="form-group col-sm-2">
        <input type='text' class="form-control sku" id="arr[sku][{{$current}}]" placeholder="sku" name='arr[sku][{{$current}}]' value="{{ old('arr[sku][$current]') }}">
    </div>
    <div class="form-group col-sm-1">
        <input type='text' class="form-control qty" id="arr[qty][{{$current}}]" placeholder="数量" name='arr[qty][{{$current}}]' value="{{ old('arr[qty][$current]') }}">
    </div>
    <div class="form-group col-sm-1">
        <input type='text' class="form-control price" id="arr[price][{{$current}}]" placeholder="金额" name='arr[price][{{$current}}]' value="{{ old('arr[price][$current]') }}">
    </div>
    <div class="form-group col-sm-2">
        <input type='text' class="form-control status" id="arr[status][{{$current}}]" placeholder="订单状态" name='arr[status][{{$current}}]' value="{{ old('arr[status][$current]') }}">
    </div>
    <div class="form-group col-sm-2">
        <input type='text' class="form-control ship_status" id="arr[ship_status][{{$current}}]" placeholder="发货状态" name='arr[ship_status][{{$current}}]' value="{{ old('arr[ship_status][$current]') }}">
    </div>
    <div class="form-group col-sm-1">
        <input type='text' class="form-control is_gift" id="arr[is_gift][{{$current}}]" placeholder="是否赠品" name='arr[is_gift][{{$current}}]' value="{{ old('arr[is_gift][$current]') }}">
    </div>
    <div class="form-group col-sm-2">
        <input type='text' class="form-control remark" id="arr[remark][{{$current}}]" placeholder="备注" name='arr[remark][{{$current}}]' value="{{ old('arr[remark][$current]') }}">
    </div>
    <button type='button' class='btn btn-danger bt_right'><i class='glyphicon glyphicon-trash'></i></button>
</div>