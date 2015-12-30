@extends('common.form')
@section('title') 编辑跟踪号号码池 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('logisticsCode.index') }}">跟踪号号码池</a></li>
        <li class="active"><strong>编辑跟踪号号码池</strong></li>
    </ol>
@stop

@section('formTitle') 编辑跟踪号号码池 @stop
@section('formAction') {{ route('logisticsCode.update', ['id' => $code->id]) }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="form-group">
        <label for="logistics_id" class="control-label">物流方式</label>
        <input class="form-control" id="logistics_id" placeholder="物流方式" name='logistics_id' value="{{ old('logistics_id') ? old('logistics_id') : $code->logistics_id }}">
    </div>
    <div class="form-group">
        <label for="code" class="control-label">跟踪号</label>
        <input class="form-control" id="code" placeholder="跟踪号" name='code' value="{{ old('code') ? old('code') : $code->code }}">
    </div>
    <div class="form-group">
        <label for="package_id" class="control-label">包裹ID</label>
        <input class="form-control" id="package_id" placeholder="包裹ID" name='package_id' value="{{ old('package_id') ? old('package_id') : $code->package_id }}">
    </div>
    <div class="form-group">
        <label for="status" class="control-label">状态</label>
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
    <div class="form-group">
        <label for="used_at" class="control-label">使用时间( 如'2008-08-08' )</label>
        <input class="form-control" id="used_at" placeholder="使用时间" name='used_at' value="{{ old('used_at') ? old('used_at') : $code->used_at }}">
    </div>
@stop