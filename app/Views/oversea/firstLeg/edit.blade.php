@extends('common.form')
@section('formAction') {{ route('firstLeg.update', ['id' => $model->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class='row'>
        <div class="form-group col-lg-2">
            <label class='control-label'>仓库</label>
            <select name='warehouse_id' class='form-control'>
            @foreach($warehouses as $warehouse)
                <option value="{{ $warehouse->id }}" {{ $warehouse->id == $model->warehouse_id ? 'selected' : ''}}>{{ $warehouse->name }}</option>
            @endforeach
            </select>
        </div>
        <div class="form-group col-lg-2">
            <label class='control-label'>物流方式</label>
            <input type='text' name='name' class='form-control' value="{{ old('name') ? old('name') : $model->name }}">
        </div>
        <div class="form-group col-lg-2">
            <label class='control-label'>单价</label>
            <input type='text' name='cost' class='form-control' value="{{ old('cost') ? old('cost') : $model->cost }}">
        </div>
        <div class="form-group col-lg-2">
            <label class='control-label'>运输方式</label>
            <select name='transport' class='form-control'>
            <option value='0' {{ $model->transport ? ($model->transport == '0' ? 'selected' : '') : ''}}>海运</option>
            <option value='1' {{ $model->transport ? ($model->transport == '1' ? 'selected' : '') : ''}}>空运</option>
        </select>
        </div>
        <div class="form-group col-lg-2">
            <label class='control-label'>公式</label>
            <input type='text' name='formula' class='form-control' value="{{ old('formula') ? old('formula') : $model->formula }}">
        </div>
    </div>
@stop