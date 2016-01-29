@extends('common.form')
@section('formAction') {{ route('item.update', ['id' => $model->id]) }} @stop
@section('formBody')
<div class="form-group">
    <div class="form-group col-md-3">
        <label for="color">item</label>
        <input readonly="readonly" class="form-control" id="remark" placeholder="货品" name='sku' value="{{ $model->sku }}">
    </div>

    <div class="col-lg-3">
        <label for="weight">重量</label>
        <input class="form-control" id="weight" placeholder="重量" name='weight' value="{{ old('weight') ?  old('weight') : $model->weight }}">
    </div>
</div>  
<input type='hidden' value='PUT' name="_method">

@stop