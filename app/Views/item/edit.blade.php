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
            <label for="size">item名英文</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="name" placeholder="产品name" name='name' value="{{ old('name') ?  old('name') : $model->name }}">
        </div>
        <div class="form-group col-md-3">
            <label for="color">item名中文</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="c_name" placeholder="产品中文名" name='c_name' value="{{ old('c_name') ?  old('c_name') : $model->c_name }}">
        </div>
        <div class="form-group col-md-3">
            <label for="color">item别名中文</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="c_name" placeholder="产品中文名" name='c_name' value="{{ old('c_name') ?  old('c_name') : $model->c_name }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-3">
            <label for="color">item别名英文</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="c_name" placeholder="产品中文名" name='c_name' value="{{ old('c_name') ?  old('c_name') : $model->c_name }}">
        </div>
        <div class="form-group col-md-3">
            <label for="size">主供应商</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select id="supplier_id" class="form-control" name="supplier_id">
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id}}" {{ $supplier->id == $model->supplier_id ? 'selected' : '' }}>{{$supplier->name}}</option>
                @endforeach
            </select>
        </div>
        <!--<div class="form-group col-md-3">
            <label for="size">供应商信息</label>
            <input class="form-control" id="supplier_info" placeholder="供应商信息" name='supplier_info' value="{{ old('supplier_info') ?  old('supplier_info') : $model->supplier_info }}">
        </div>-->
        <div class="form-group col-md-3"><label for="color">辅供应商</label>
            <select class="form-control" name="second_supplier_id">
                <option value="0"></option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id}}" {{ $supplier->id == $model->second_supplier_id ? 'selected' : '' }} >{{$supplier->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-3">
            <label for="color">供应商sku</label>
            <input class="form-control" id="purchase_url" placeholder="采购链接" name='purchase_url' value="{{ old('purchase_url') ?  old('purchase_url') : $model->purchase_url }}">
        </div>
        <div class="form-group col-md-3">
            <label for="color">采购链接</label>
            <input class="form-control" id="purchase_url" placeholder="采购链接" name='purchase_url' value="{{ old('purchase_url') ?  old('purchase_url') : $model->purchase_url }}">
        </div>
        <div class="form-group col-md-1">
            <label for="size">采购价</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="purchase_price" placeholder="采购价" name='purchase_price' value="{{ old('purchase_price') ?  old('purchase_price') : $model->purchase_price }}">
        </div>
        <div class="form-group col-md-1">
            <label for="color">采购物流费</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="purchase_carriage" placeholder="采购物流费" name='purchase_carriage' value="{{ old('purchase_carriage') ?  old('purchase_carriage') : $model->purchase_carriage }}">
        </div>
        <div class="form-group col-md-1">
            <label for="size">item尺寸</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="product_size" placeholder="产品尺寸" name='product_size' value="{{ old('product_size') ?  old('product_size') : $model->product_size }}">
        </div>
        <div class="form-group col-md-1">
            <label for="color">item包装尺寸</label>
            <input class="form-control" id="package_size" placeholder="产品包装尺寸" name='package_size' value="{{ old('package_size') ?  old('package_size') : $model->package_size }}">
        </div>
        <div class="form-group col-md-1">
            <label for="size">item重量</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="weight" placeholder="产品重量" name='weight' value="{{ old('weight') ?  old('weight') : $model->weight }}">
        </div>
    </div>


    <div class="row">
        <div class="form-group col-md-1">
            <label for="color">库存</label>
            <?php 
                $inventory = 0;
                $amount = 0;
                foreach($model->stocks as $stock){
                    $inventory += $stock->all_quantity;
                    $amount += $stock->amount;
                } 
            ?>
            <input disabled="disabled" class="form-control" id="inventory" placeholder="库存" name='inventory' value="{{ old('inventory') ?  old('inventory') : $inventory }}">
        </div>

        <div class="form-group col-md-1">
            <label for="size">库存总金额</label>
            <input disabled="disabled" class="form-control" id="amount" placeholder="库存总金额" name='amount' value="{{ old('amount') ?  old('amount') : $amount }}">
        </div>

    </div>


    <div class="row">
        <div class="form-group col-md-3">
            <label for="color">物流限制</label>
            @foreach(config('product.carriage_limit') as $carriage_key=>$carriage_limit)
                <label>
                    <input type='checkbox' name='carriage_limit_arr[]' value='{{$carriage_key}}' {{ in_array($carriage_key, explode(',',$model->carriage_limit))? 'checked' : '' }} >{{$carriage_limit}}
                </label>
            @endforeach
        </div>
        <div class="form-group col-md-3">
            <label for="color">包装限制</label>
            @foreach(config('product.package_limit') as $package_key=>$package_limit)
                <label>
                    <input type='checkbox' name='package_limit_arr[]' value='{{$package_key}}' {{ in_array($package_key, explode(',',$model->package_limit))? 'checked' : '' }} >{{$package_limit}}
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
            <label for="color">备注</label>
            <input class="form-control" id="remark" placeholder="备注" name='remark' value="{{ old('remark') ?  old('remark') : $model->remark }}">
        </div>
        <div class="form-group col-md-3">
            <label for="color">是否可售</label>
            <select  class="form-control" name="is_sale">
                <option value="1" >可售</option>
                <option value="0" {{ $model->is_sale == 0 ? 'selected' : '' }}>不可售</option>
            </select>
        </div>
    </div>
@stop