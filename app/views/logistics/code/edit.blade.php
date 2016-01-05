@extends('common.form')

<link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}

@section('formAction') {{ route('logisticsCode.update', ['id' => $code->id]) }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="form-group col-lg-4">
        <label for="logistics_id" class="control-label">物流方式</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="logistics_id" placeholder="物流方式" name='logistics_id' value="{{ old('logistics_id') ? old('logistics_id') : $code->logistics_id }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="code" class="control-label">跟踪号</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="code" placeholder="跟踪号" name='code' value="{{ old('code') ? old('code') : $code->code }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="package_id" class="control-label">包裹ID</label>
        <input class="form-control" id="package_id" placeholder="包裹ID" name='package_id' value="{{ old('package_id') ? old('package_id') : $code->package_id }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="status" class="control-label">状态</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <div class="radio">
            <label>
                <input type="radio" name="status" value="Y" {{ $code->status == 'Y' ? 'checked' : '' }}>启用
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="status" value="N" {{ $code->status == 'N' ? 'checked' : '' }}>未启用
            </label>
        </div>
    </div>
    <div class="form-group col-lg-4">
        <label for="used_at" class="control-label">使用时间</label>
        <input class="form-control" id="used_at" placeholder="使用时间" name='used_at' value="{{ old('used_at') ? old('used_at') : $code->used_at }}">
    </div>
@stop
<script type='text/javascript'>
    $(document).ready(function(){
        $('#used_at').cxCalendar();
    });
</script>