@extends('common.form')
@section('formAction') {{ route('item.update', ['id' => $model->id]) }} @stop
@section('formBody')
    <input type='hidden' value='PUT' name="_method">
    <div class="row">
        <div class="form-group col-md-3">
            <label for="size">item</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" disabled="disabled" id="name" placeholder="sku" name='sku' value="{{ old('sku') ?  old('sku') : $model->sku }}">
        </div>

        <div class="form-group col-md-3">
            <label for="size">item英文</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="name" placeholder="item英文" disabled="disabled" name='name' value="{{ old('name') ?  old('name') : $model->name }}">
        </div>
        <div class="form-group col-md-3">
            <label for="color">item中文</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="c_name" placeholder="item中文" disabled="disabled" name='c_name' value="{{ old('c_name') ?  old('c_name') : $model->c_name }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-3">
            <label for="size">主供应商</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select id="supplier_id" class="form-control" name="supplier_id">
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id}}" {{ $supplier->id == $model->supplier_id ? 'selected' : '' }}>{{$supplier->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="color">主供应商sku</label>
            <input class="form-control" id="supplier_sku" placeholder="主供应商sku" name='supplier_sku' value="{{ old('supplier_sku') ?  old('supplier_sku') : $model->supplier_sku }}">
        </div>
        <div class="form-group col-md-3"><label for="color">辅供应商</label>
            <select class="form-control" name="second_supplier_id">
                <option value="0"></option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id}}" {{ $supplier->id == $model->second_supplier_id ? 'selected' : '' }} >{{$supplier->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="color">辅供应商sku</label>
            <input class="form-control" id="second_supplier_sku" placeholder="辅供应商sku" name='second_supplier_sku' value="{{ old('second_supplier_sku') ?  old('second_supplier_sku') : $model->second_supplier_sku }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-4">
            <label for="color">采购链接</label>
            <input class="form-control" id="purchase_url" placeholder="采购链接" name='purchase_url' value="{{ old('purchase_url') ?  old('purchase_url') : $model->purchase_url }}">
        </div>
        <div class="form-group col-md-2">
            <label for="size">采购价（RMB）</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="purchase_price" placeholder="采购价" name='purchase_price' value="{{ old('purchase_price') ?  old('purchase_price') : $model->purchase_price }}">
        </div>
        <div class="form-group col-md-2">
            <label for="color">采购物流费（RMB）</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="purchase_carriage" placeholder="采购物流费" name='purchase_carriage' value="{{ old('purchase_carriage') ?  old('purchase_carriage') : $model->purchase_carriage }}">
        </div>
        
    </div>

    <div class="row">
        <div class="form-group col-md-3">
            <label for="size">尺寸类型</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select id="product_size" class="form-control" name="product_size">     
                <option value="大" {{ $model->product_size == '大' ? 'selected' : '' }}>大</option>
                <option value="中" {{ $model->product_size == '中' ? 'selected' : '' }}>中</option>
                <option value="小" {{ $model->product_size == '小' ? 'selected' : '' }}>小</option>
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="color">item包装尺寸（cm）(长*宽*高)</label>
            <input class="form-control" id="package_size" placeholder="产品包装尺寸" name='package_size' value="{{ old('package_size') ?  old('package_size') : $model->package_size }}">
        </div>
        <div class="form-group col-md-3">
            <label for="size">item重量（kg）</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="weight" placeholder="产品重量" name='weight' value="{{ old('weight') ?  old('weight') : $model->weight }}">
        </div>        
    </div>


    <div class="row">
        <div class="form-group col-md-1">
            <label for="color">库存</label>
            
            <input disabled="disabled" class="form-control" id="inventory" placeholder="库存" name='inventory' value="{{ old('inventory') ?  old('inventory') :$model->all_quantity['all_quantity'] }}">
        </div>

        <div class="form-group col-md-1">
            <label for="size">库存总金额</label>
            <input disabled="disabled" class="form-control" id="amount" placeholder="库存总金额" name='amount' value="{{ old('amount') ?  old('amount') :$model->all_quantity['all_amount'] }}">
        </div>

    </div>


    <div class="row">
        <div class="form-group col-md-12" style="padding-top:26px">
            <label for="color">物流限制</label>
            @foreach($logisticsLimit as $carriage_limit)
                    <label>
                        <input type='checkbox' disabled="disabled" name='carriage_limit_arr[]' value='{{$carriage_limit->id}}' {{ in_array($carriage_limit->id, explode(',',$model->product->carriage_limit))? 'checked' : '' }} >{{$carriage_limit->name}}
                    </label>
            @endforeach
        </div>
        <div class="form-group col-md-12" style="padding-top:26px">
            <label for="color">包装限制</label>
            @foreach($wrapLimit as $wrap_limit)
                    <label>
                        <input type='checkbox' disabled="disabled" name='package_limit_arr[]' value='{{$wrap_limit->id}}' {{ in_array($wrap_limit->id, explode(',',$model->product->package_limit))? 'checked' : '' }} >{{$wrap_limit->name}}
                    </label>
            @endforeach
        </div>
        <div class="form-group col-md-3">
            <label for="size">仓库</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select  class="form-control" name="warehouse_id">
                <option value="0"></option>
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}" {{ $model->warehouse_id == $warehouse->id ? 'selected' : '' }}>{{$warehouse->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="color">库位</label>
            <input class="form-control" id="warehouse_position" placeholder="备注" name='warehouse_position' value="{{ old('warehouse_position') ?  old('warehouse_position') : $model->warehouse_position }}">
        </div>
        <div class="form-group col-md-3">
            <label for="color">备注</label>
            <input class="form-control" id="remark" placeholder="备注" name='remark' value="{{ old('remark') ?  old('remark') : $model->remark }}">
        </div>
        <div class="form-group col-md-3">
            <label for="color">在售状态</label>
            <select  class="form-control" name="status">
                <option value="selling" {{ $model->status == 'selling' ? 'selected' : '' }}>在售</option>
                <option value="sellWaiting" {{ $model->status == 'sellWaiting' ? 'selected' : '' }}>待售</option>
                <option value="saleOutStopping" {{ $model->status == 'saleOutStopping' ? 'selected' : '' }}>卖完下架</option>
                <option value="stopping" {{ $model->status == 'stopping' ? 'selected' : '' }}>停产</option>
                <option value="trySale" {{ $model->status == 'trySale' ? 'selected' : '' }}>试销</option>
                <option value="unSellTemp" {{ $model->status == 'unSellTemp' ? 'selected' : '' }}>货源待定</option>
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="color">是否激活</label>
            <select  class="form-control" name="is_available">
                <option value="1" {{ $model->is_available == 1 ? 'selected' : '' }}>激活</option>
                <option value="0" {{ $model->is_available == 0 ? 'selected' : '' }}>非激活</option>
            </select>
        </div>
    </div>
@stop