@extends('common.form')
@section('formAction') {{ route('item.update', ['id' => $model->id]) }} @stop
@section('formBody')
<div class="form-group col-md-3">
        <label for="size">产品name</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="name" placeholder="产品name" name='name' value="{{ old('name') ?  old('name') : $model->name }}">
    </div>
    <div class="form-group col-md-3">
        <label for="color">产品中文名</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="c_name" placeholder="产品中文名" name='c_name' value="{{ old('c_name') ?  old('c_name') : $model->c_name }}">
    </div>
    <div class="form-group col-md-3">
        <label for="size">主供应商</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select id="supplier_id" class="form-control" name="supplier_id">
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id}}" {{ $supplier->id == $model->supplier_id ? 'selected' : '' }}>{{$supplier->name}}</option>
            @endforeach
        </select>
    </div>   
    <div class="form-group col-md-3">
        <label for="size">供应商信息</label>
        <input class="form-control" id="supplier_info" placeholder="供应商信息" name='supplier_info' value="{{ old('supplier_info') ?  old('supplier_info') : $model->supplier_info }}">
    </div>        
    <div class="form-group col-md-3"><label for="color">辅供应商</label>
        <select  class="form-control" name="second_supplier_id_arr[]">
            <option value="0"></option>
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id}}" {{ $supplier->id == $second_supplier_id[0] ? 'selected' : '' }} >{{$supplier->name}}</option>
            @endforeach
        </select>
    </div>
        <div class="form-group col-md-3"><label for="color">辅供应商</label>
            <select  class="form-control" name="second_supplier_id_arr[]">
                <option value="0"></option>
                @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id}}" {{ $supplier->id == $second_supplier_id[1] ? 'selected' : '' }} >{{$supplier->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-3"><label for="color">辅供应商</label>
            <select  class="form-control" name="second_supplier_id_arr[]">
                <option value="0"></option>
                @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id}}" {{ $supplier->id == $second_supplier_id[2] ? 'selected' : '' }} >{{$supplier->name}}</option>
                @endforeach
                </select>
        </div>
        <div class="form-group col-md-3"><label for="color">辅供应商</label>
            <select  class="form-control" name="second_supplier_id_arr[]">
                <option value="0"></option>
                @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id}}" {{ $supplier->id == $second_supplier_id[3] ? 'selected' : '' }} >{{$supplier->name}}</option>
                @endforeach
            </select>          
        </div>
    <div class="form-group col-md-3">
        <label for="color">采购链接</label>
        <input class="form-control" id="purchase_url" placeholder="采购链接" name='purchase_url' value="{{ old('purchase_url') ?  old('purchase_url') : $model->purchase_url }}">
    </div>
    <div class="form-group col-md-1">
        <label for="size">采购价</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="purchase_price" placeholder="采购价" name='purchase_price' value="{{ old('purchase_price') ?  old('purchase_price') : $model->purchase_price }}">
    </div>
    <div class="form-group col-md-1">
        <label for="color">采购物流费</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="purchase_carriage" placeholder="采购物流费" name='purchase_carriage' value="{{ old('purchase_carriage') ?  old('purchase_carriage') : $model->purchase_carriage }}">
    </div>
    <div class="form-group col-md-1">
        <label for="size">产品尺寸</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="product_size" placeholder="产品尺寸" name='product_size' value="{{ old('product_size') ?  old('product_size') : $model->product_size }}">
    </div>
    <div class="form-group col-md-1">
        <label for="color">产品包装尺寸</label>
        <input class="form-control" id="package_size" placeholder="产品包装尺寸" name='package_size' value="{{ old('package_size') ?  old('package_size') : $model->package_size }}">
    </div>
    <div class="form-group col-md-1">
        <label for="size">产品重量</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="weight" placeholder="产品重量" name='weight' value="{{ old('weight') ?  old('weight') : $model->weight }}">
    </div>
    <div class="form-group col-md-1">
        <label for="color">更新人</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="upload_user" placeholder="上传人" name='upload_user' value="{{ old('upload_user') ?  old('upload_user') : $model->upload_user }}">
    </div>
    <div class="form-group col-md-3">
        <label for="color">物流限制</label>
        <input class="form-control" id="carriage_limit" placeholder="物流限制" name='carriage_limit' value="{{ old('carriage_limit') ?  old('carriage_limit') : $model->carriage_limit }}">
    </div>
    <div class="form-group col-md-3">
        <label for="size">物流限制1</label>
        <input class="form-control" id="carriage_limit_1" placeholder="物流限制1" name='carriage_limit_1' value="{{ old('carriage_limit_1') ?  old('carriage_limit_1') : $model->carriage_limit_1 }}">
    </div>
    <div class="form-group col-md-3">
        <label for="color">包装限制</label>
        <input class="form-control" id="package_limit" placeholder="包装限制" name='package_limit' value="{{ old('package_limit') ?  old('package_limit') : $model->package_limit }}">
    </div>
    <div class="form-group col-md-3">
        <label for="size">包装限制1</label>
        <input class="form-control" id="package_limit_1" placeholder="包装限制1" name='package_limit_1' value="{{ old('package_limit_1') ?  old('package_limit_1') : $model->package_limit_1 }}">
    </div>
    <div class="form-group col-md-3">
        <label for="color">备注</label>
        <input class="form-control" id="remark" placeholder="备注" name='remark' value="{{ old('remark') ?  old('remark') : $model->remark }}">
    </div>  
    <input type='hidden' value='PUT' name="_method">

@stop