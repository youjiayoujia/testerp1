@extends('common.form')
@section('formAction') {{ route('stockCarryOver.createCarryOverResult') }} @stop
@section('formBody')
    <div class='row'>
        <div class='form-group col-lg-3'>
            <label for='stockTime'>时间</label>
            <input type='text' placeholder='xxxx-xx' name='stockTime' class='form-control stockTime'>
        </div>
    </div>
@stop
@section('formButton')
    <button type="submit" class="btn btn-success">生成月结记录</button>
@stop
