@extends('common.form')
@section('title') 编辑物流 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('logistics.index') }}">物流</a></li>
        <li class="active"><strong>编辑物流</strong></li>
    </ol>
@stop
<script type="text/javascript" src="{{ asset('js/pro_city.js') }}}"></script>

@section('formTitle') 编辑物流 @stop
@section('formAction') {{ route('logistics.update', ['id' => $logistics->id]) }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="form-group">
        <label for="name" class="control-label">物流商名称</label>
        <input class="form-control" id="name" placeholder="物流商名称" name='name' value="{{ old('name') ?  old('name') : $logistics->name }}">
    </div>
    <div class="form-group">
        <label for="customer_id" class="control-label">客户ID</label>
        <input class="form-control" id="customer_id" placeholder="客户ID" name='customer_id' value="{{ old('customer_id') ?  old('customer_id') : $logistics->customer_id }}">
    </div>
    <div class="form-group">
        <label for="secret_key" class="control-label">密钥</label>
        <input class="form-control" id="secret_key" placeholder="密钥" name='secret_key' value="{{ old('secret_key') ?  old('secret_key') : $logistics->secret_key }}">
    </div>
    <div class="form-group">
        <label for="is_api">是否有API</label>
        <div class="radio">
            <label>
                <input type="radio" name="is_api" value="Y" {{ $logistics->is_api == 'Y' ? 'checked' : '' }}>有
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="is_api" value="N" {{ $logistics->is_api == 'N' ? 'checked' : '' }}>没有
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="client_manager" class="control-label">客户经理</label>
        <input class="form-control" id="client_manager" placeholder="客户经理" name='client_manager' value="{{ old('client_manager') ?  old('client_manager') : $logistics->client_manager }}">
    </div>
    <div class="form-group">
        <label for="manager_tel" class="control-label">客户经理联系方式</label>
        <input class="form-control" id="manager_tel" placeholder="客户经理联系方式" name='manager_tel' value="{{ old('manager_tel') ?  old('manager_tel') : $logistics->manager_tel }}">
    </div>
    <div class="form-group">
        <label for="technician" class="control-label">技术人员</label>
        <input class="form-control" id="technician" placeholder="技术人员" name='technician' value="{{ old('technician') ?  old('technician') : $logistics->technician }}">
    </div>
    <div class="form-group">
        <label for="technician_tel" class="control-label">技术联系方式</label>
        <input class="form-control" id="technician_tel" placeholder="技术联系方式" name='technician_tel' value="{{ old('technician_tel') ?  old('technician_tel') : $logistics->technician_tel }}">
    </div>
    <div class="form-group">
        <label for="remark" class="control-label">备注</label>
        <input class="form-control" id="remark" placeholder="备注" name='remark' value="{{ old('remark') ?  old('remark') : $logistics->remark }}">
    </div>
@stop