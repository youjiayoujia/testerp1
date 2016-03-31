@extends('common.form')
@section('formAction') {{ route('package.feeStore') }} @stop
@section('formBody')
<input type='hidden' name='id' value={{ $model->id}}>
<div class='row'>
    <div class='form-group col-lg-3'>
        <label for='logistic_id'>物流方式</label>
        <select class='form-control' name='logistic_id'>
        @foreach($logistics as $logistic)
            <option value={{ $logistic->id }}>{{ $logistic->logistics_type }}</option>
        @endforeach
        </select>
    </div>
    <div class='form-group col-lg-3'>
        <label for='logistic_code'>物流跟踪号</label>
        <input type='text' name='logistic_code' class='form-control' placeholder="物流跟踪号">
    </div>
    <div class='form-group col-lg-3'>
        <label for='fee'>物流费</label>
        <input type='text' class='form-control' name='fee' placeholder="物流费">
    </div>
</div>
<div class='form-group'>
    <label for='logistic_code'>备注</label>
    <textarea name='remark' class='form-control'></textarea>
</div>
@stop