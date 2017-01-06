@extends('common.form')
@section('formAction') {{ route('firstLeg.store') }} @stop
@section('formBody')
<div class='row'>
    <div class="form-group col-lg-2">
        <label class='control-label'>仓库</label>
        <select name='warehouse_id' class='form-control'>
        @foreach($warehouses as $warehouse)
            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
        @endforeach
        </select>
    </div>
    <div class="form-group col-lg-2">
        <label class='control-label'>物流方式</label>
        <input type='text' name='name' class='form-control' value="{{ old('name') }}">
    </div>
    <div class="form-group col-lg-2">
        <label class='control-label'>单价</label>
        <input type='text' name='cost' class='form-control' value="{{ old('cost') }}">
    </div>
    <div class="form-group col-lg-2">
        <label class='control-label'>运输方式</label>
        <select name='transport' class='form-control'>
            <option value='0' {{ old('transport') ? (old('transport') == '0' ? 'selected' : '') : ''}}>海运</option>
            <option value='1' {{ old('transport') ? (old('transport') == '1' ? 'selected' : '') : ''}}>空运</option>
        </select>
    </div>
    <div class="form-group col-lg-2">
        <label class='control-label'>公式</label>
        <input type='text' name='formula' class='form-control' value="{{ old('formula') }}">
    </div>
</div>
@stop