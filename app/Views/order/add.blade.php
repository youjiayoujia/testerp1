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
        <select class="form-control status" name="arr[status][{{$current}}]" id="arr[status][{{$current}}]">
            @foreach(config('order.product_status') as $product_status_key => $status)
                <option value="{{ $product_status_key }}" {{ old('arr[status][$current]') == $product_status_key ? 'selected' : '' }}>
                    {{ $status }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-sm-2">
        <select class="form-control ship_status" name="arr[ship_status][{{$current}}]" id="arr[ship_status][{{$current}}]">
            @foreach(config('order.ship_status') as $ship_status_key => $ship_status)
                <option value="{{ $ship_status_key }}" {{ old('arr[ship_status][$current]') == $ship_status_key ? 'selected' : '' }}>
                    {{ $ship_status }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-sm-1">
        <select class="form-control is_gift" name="arr[is_gift][{{$current}}]" id="arr[is_gift][{{$current}}]">
            @foreach(config('order.whether') as $is_gift_key => $is_gift)
                <option value="{{ $is_gift_key }}" {{ old('arr[is_gift][$current]') == $is_gift_key ? 'selected' : '' }}>
                    {{ $is_gift }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-sm-2">
        <input type='text' class="form-control remark" id="arr[remark][{{$current}}]" placeholder="备注" name='arr[remark][{{$current}}]' value="{{ old('arr[remark][$current]') }}">
    </div>
    <button type='button' class='btn btn-danger bt_right'><i class='glyphicon glyphicon-trash'></i></button>
</div>