@extends('common.form')
@section('formAction') {{ route('report.checkResult', ['id' => $model->id]) }} @stop
@section('formBody')
<div class='row'>
    <div class="form-group col-lg-4">
        <label for="fba_address" class='control-label'>fba地址</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" placeholder="fba地址" name='fba_address' value="{{ old('fba_address') ? old('fba_address') : $model->fba_address }}">
    </div>
    <div class="form-group col-lg-4">
        <label for='from_address'>发货地址</label>
        <input type='text' class="form-control" placeholder="发货地址" name='from_address' value="{{ old('from_address') ? old('from_address') : $model->from_address }}">
    </div>
    <div class='form-group col-lg-4'> 
        <label for='渠道帐号'>渠道帐号</label> 
        <input type='text' class="form-control" value="{{ $model->account ? $model->account->account : '' }}">
    </div>
</div>
<div class='row'>
    <div class="form-group col-lg-3">
        <label for="fba_address" class='control-label'>plan Id</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" placeholder="plan Id" name='plan_id' value="{{ old('plan_id') ? old('plan_id') : $model->plan_id }}">
    </div>
    <div class="form-group col-lg-3">
        <label for='from_address'>shipment Id</label>
        <input type='text' class="form-control" placeholder="shipment Id" name='shipment_id' value="{{ old('shipment_id') ? old('shipment_id') : $model->shipment_id }}">
    </div>
    <div class='form-group col-lg-3'> 
        <label for='渠道帐号'>reference Id</label> 
        <input type='text' class="form-control" placeholder="reference Id" name='reference_id' value="{{ old('reference_id') ? old('reference_id') : $model->reference_id }}">
    </div>
    <div class='form-group col-lg-3'> 
        <label for='渠道帐号'>shipment名称</label> 
        <input type='text' class="form-control" placeholder="shipment 名称" name='shipment_name' value="{{ old('shipment_name') ? old('shipment_name') : $model->shipment_name }}">
    </div>
</div>
<div class="panel panel-info">
    <div class="panel-heading">列表</div>
    <div class="panel-body add_row">
        <div class='row'>
            <div class="form-group col-sm-2">
                <label for="sku">sku</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            </div>
            <div class="form-group col-sm-2">
                <label for="warehouse_position_id">库位</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            </div>
            <div class="form-group col-sm-2">
                <label for="quantity" class='control-label'>数量</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            </div>
        </div>
        @foreach($forms as $form)
        <div class='row'>
            <div class="form-group col-sm-2">
                <input type='text' class="form-control" value="{{ $form->item ? $form->item->sku : '' }}">
            </div>
            <div class="form-group col-sm-2 position_html">
                <input type='text' class="form-control" value="{{ $form->position ? $form->position->name : '' }}">
            </div>
            <div class="form-group col-sm-2">
                <input type='text' class="form-control" value="{{ $form->report_quantity }}">
            </div>
        </div>
        @endforeach
    </div>
</div> 
@stop
@section('formButton')
    <button type="submit" name='result' value='1' class="btn btn-success">审核通过</button>
    <button type="submit" name='result' value='0' class="btn btn-default">审核未通过</button>
@stop