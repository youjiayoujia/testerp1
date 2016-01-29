@extends('common.form')

<link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}

@section('formAction') {{ route('logisticsCode.update', ['id' => $model->id]) }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="form-group col-lg-4">
        <label for="logistics_id">物流方式</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select name="logistics_id" class="form-control">
            @foreach($logisticses as $logistics)
                <option value="{{ $logistics->id }}" {{ $logistics->id == old('$logisticses->logistics->id') ? 'selected' : '' }}>
                    {{ $logistics->logistics_type }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-lg-4">
        <label for="code" class="control-label">跟踪号</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="code" placeholder="跟踪号" name='code' value="{{ old('code') ? old('code') : $model->code }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="package_id" class="control-label">包裹ID</label>
        <input class="form-control" id="package_id" placeholder="包裹ID" name='package_id' value="{{ old('package_id') ? old('package_id') : $model->package_id }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="status" class="control-label">状态</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <div class="radio">
            <label>
                <input type="radio" name="status" value="Y" {{ $model->status == 'Y' ? 'checked' : '' }}>启用
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="status" value="N" {{ $model->status == 'N' ? 'checked' : '' }}>未启用
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