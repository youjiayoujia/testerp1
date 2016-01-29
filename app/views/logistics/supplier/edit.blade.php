@extends('common.form')
@section('formAction') {{ route('logisticsSupplier.update', ['id' => $model->id]) }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="form-group col-lg-4">
        <label for="name" class="control-label">物流商名称</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type="text" class="form-control" id="name" placeholder="物流商名称" name='name' value="{{ old('name') ?  old('name') : $model->name }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="customer_id" class="control-label">客户ID</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="customer_id" placeholder="客户ID" name='customer_id' value="{{ old('customer_id') ?  old('customer_id') : $model->customer_id }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="secret_key" class="control-label">密钥</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="secret_key" placeholder="密钥" name='secret_key' value="{{ old('secret_key') ?  old('secret_key') : $model->secret_key }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="client_manager" class="control-label">客户经理</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="client_manager" placeholder="客户经理" name='client_manager' value="{{ old('client_manager') ?  old('client_manager') : $model->client_manager }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="manager_tel" class="control-label">客户经理联系方式</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="manager_tel" placeholder="客户经理联系方式" name='manager_tel' value="{{ old('manager_tel') ?  old('manager_tel') : $model->manager_tel }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="is_api">是否有API</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <div class="radio">
            <label>
                <input type="radio" name="is_api" value="Y" {{old('is_api') ? (old('is_api') == 'Y' ? 'checked' : '') : ($model->is_api == 'Y' ? 'checked' : '')}}>有
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="is_api" value="N" {{old('is_api') ? (old('is_api') == 'N' ? 'checked' : '') : ($model->is_api == 'N' ? 'checked' : '')}}>没有
            </label>
        </div>
    </div>
    <div class="form-group col-lg-4">
        <label for="technician" class="control-label">技术人员</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="technician" placeholder="技术人员" name='technician' value="{{ old('technician') ?  old('technician') : $model->technician }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="technician_tel" class="control-label">技术联系方式</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="technician_tel" placeholder="技术联系方式" name='technician_tel' value="{{ old('technician_tel') ?  old('technician_tel') : $model->technician_tel }}">
    </div>
    <div class="form-group col-lg-4
    ">
        <label for="remark" class="control-label">备注</label>
        <input class="form-control" id="remark" placeholder="备注" name='remark' value="{{ old('remark') ?  old('remark') : $model->remark }}">
    </div>
@stop