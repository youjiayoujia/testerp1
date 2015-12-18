@extends('common.form')
@section('title') 编辑物流方式 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('logisticsType.index') }}">物流方式</a></li>
        <li class="active"><strong>编辑物流方式</strong></li>
    </ol>
@stop
<script type="text/javascript" src="{{ asset('js/pro_city.js') }}}"></script>

@section('formTitle') 编辑物流方式 @stop
@section('formAction') {{ route('logisticsType.update', ['id' => $logisticsType->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="form-group">
        <label for="type" class="control-label">物流商物流方式</label>
        <input class="form-control" id="type" placeholder="物流商物流方式" name='type' value="{{ old('type') ?  old('type') : $logisticsType->type }}">
    </div>
    <div class="form-group">
        <label for="logistics_id">物流商</label>
        <select name="logistics_id" class="form-control">
            @foreach($logisticsType as $type)
                <option value="{{$type->id}}" {{$type->id == $logisticsType->logistics_id ? 'selected' : ''}}>
                    {{$type->name}}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="remark" class="control-label">备注</label>
        <input class="form-control" id="remark" placeholder="备注" name='remark' value="{{ old('remark') ?  old('remark') : $logisticsType->remark }}">
    </div>
@stop