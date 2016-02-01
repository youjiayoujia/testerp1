@extends('common.form')
@section('formAction') {{ route('supplierShipping.store') }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <div class="form-group col-lg-4">
        <label for="name" class="control-label">物流商物流方式</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type="text" class="form-control" id="logistics_type" placeholder="物流商名称" name='logistics_type' value="{{ old('logistics_type') }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="logistics_supplier_id">物流商</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select name="supplier_id" class="form-control" id="supplier_id">
            @foreach($suppliers as $supplier)
                <option value="{{$supplier->id}}" {{$supplier->id == old('$suppliers->supplier->id') ? 'selected' : ''}}>
                    {{$supplier->name}}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-lg-4">
        <label for="remark" class="control-label">备注</label>
        <input class="form-control" id="remark" placeholder="备注" name='remark' value="{{ old('remark') }}">
    </div>
@stop