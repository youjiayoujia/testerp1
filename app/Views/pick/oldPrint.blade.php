@extends('common.detail')
@section('detailBody')
<div class='row'>
    <div class='form-group col-lg-1'>
        <label for='remark'>原面单重新打印:</label>
    </div>
    <div class='form-group col-lg-2'>
        <input type='text' class='form-control re_package_id' placeholder='package_id'>
    </div>
    <div class='form-group col-lg-2'>
        <input type='text' class='form-control re_trackno' placeholder='trackno'>
    </div>
    <div class='form-group'>
        <button type='button' class='btn btn-info re_print'>重新打印</button>
    </div>
</div>
@stop