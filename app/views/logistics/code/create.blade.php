@extends('common.form')
@section('formAction') {{ route('logisticsCode.store') }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <div class="form-group">
        <label for="logistics_id">物流方式</label>
        <select name="logistics_id" class="form-control" id="logistics_id">
            @foreach($logistics as $logisticses)
                <option value="{{ $logisticses->id }}" {{ $logisticses->id == old('$logisticses->logistics->id') ? 'selected' : '' }}>
                    {{ $logisticses->logistics_type }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="code" class="control-label">跟踪号</label>
        <input class="form-control" id="code" placeholder="跟踪号" name='code' value="{{ old('code') }}">
    </div>
    <div class="form-group">
        <label for="package_id" class="control-label">包裹ID</label>
        <input class="form-control" id="package_id" placeholder="包裹ID" name='package_id' value="{{ old('package_id') }}">
    </div>
    <div class="form-group">
        <label for="status" class="control-label">状态</label>
        <div class="radio">
            <label>
                <input type="radio" name="status" value="Y">启用
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="status" value="N" checked>未启用
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="used_at" class="control-label">使用时间( 如'2008-08-08' )</label>
        <input class="form-control" id="used_at" placeholder="使用时间" name='used_at' value="{{ old('used_at') }}">
    </div>
@stop