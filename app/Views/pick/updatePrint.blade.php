@extends('common.detail')
@section('detailBody')
<div class='row'>
    <div class='form-group col-lg-2'>
        <label for='remark'>更新物流面单重新打印:</label>
    </div>
    <div class='form-group col-lg-2'>
        <input type='text' class='form-control change_package_id' placeholder='package_id'>
    </div>
    <div class='form-group col-lg-2'>
        <input type='text' class='form-control change_trackno' placeholder='trackno'>
    </div>
    <div class='form-group col-lg-2'>
        <select class='form-control' name='new_logistic'>
        @foreach($logistics as $logistic)
            <option value="{{ $logistic->id }}">{{ $logistic->short_code }}</option>
        @endforeach
        </select>
    </div>
    <div class='form-group'>
        <button type='button' class='btn btn-info change_print'>重新打印</button>
    </div>
</div>
@stop