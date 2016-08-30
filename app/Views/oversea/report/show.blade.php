@extends('common.detail')
@section('detailBody')
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
            <div class="form-group col-lg-2">
                <label for="sku">sku</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            </div>
            <div class="form-group col-lg-2">
                <label for="warehouse_position_id">库位</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            </div>
            <div class="form-group col-lg-2">
                <label for="quantity" class='control-label'>数量</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
            </div>
        </div>
        @foreach($forms as $form)
        <div class='row'>
            <div class="form-group col-lg-2">
                <input type='text' class="form-control" value="{{ $form->item ? $form->item->sku : '' }}">
            </div>
            <div class="form-group col-lg-2 position_html">
                <input type='text' class="form-control" value="{{ $form->position ? $form->position->name : '' }}">
            </div>
            <div class="form-group col-lg-2">
                <input type='text' class="form-control" value="{{ $form->report_quantity }}">
            </div>
        </div>
        @endforeach
    </div>
</div> 
<div class="panel panel-info">
    <div class="panel-heading">装箱信息</div>
    <div class="panel-body">
    @foreach($boxes as $box)
    <div class='row'>
        <div class="form-group col-lg-2">
            <label>箱号</label>
            <input type='text' class="form-control" value="{{ $box->boxNum }}">
        </div>
        <div class="form-group col-lg-2">
            <label>物流方式</label>
            <input type='text' class="form-control" value="{{ $box->logistics ? $box->logistics->code : '' }}">
        </div>
        <div class="form-group col-lg-2">
            <label>长(m)</label>
            <input type='text' class="form-control" value="{{ $box->length }}">
        </div>
        <div class="form-group col-lg-2">
            <label>宽(m)</label>
            <input type='text' class="form-control" value="{{ $box->width }}">
        </div>
        <div class="form-group col-lg-2">
            <label>高(m)</label>
            <input type='text' class="form-control" value="{{ $box->height }}">
        </div>
        <div class="form-group col-lg-2">
            <label>重量(kg)</label>
            <input type='text' class="form-control" value="{{ $box->weight }}">
        </div>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
            <th>sku</th>
            <th>fnsku</th>
            <th>数量</th>
            </tr>
        </thead>
        <tbody>
        @foreach($box->forms as $form)
        <tr class='success'>
            <td>{{ $form->sku }}</td>
            <td>{{ $form->fnsku }}</td>
            <td>{{ $form->quantity }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    <hr>
    @endforeach
    </div>
</div>
@stop