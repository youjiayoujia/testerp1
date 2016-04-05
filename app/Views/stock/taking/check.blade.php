@extends('common.form')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('stockTaking.takingCheckResult', ['id' => $model->id]) }} @stop
@section('formBody')
<div class="row">
    <div class="form-group col-sm-3">
        <label for="盘点表id" class='control-label'>盘点表id</label>
        <input type='text' class="form-control " placeholder="盘点表id" value="{{ $model->taking_id }}" readonly>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">盘点表信息</div>
    <div class="panel-body">
            <div class='row'>
                <div class="form-group col-lg-1">
                    <label for="ID" class='control-label'>ID</label>
                </div>
                <div class="form-group col-lg-1">
                    <label for="sku" class='control-label'>sku</label>
                </div>
                <div class="form-group col-lg-2">
                    <label for="仓库" class='control-label'>仓库</label>
                </div>
                <div class="form-group col-lg-2">
                    <label for="库位" class='control-label'>库位</label>
                </div>
                <div class="form-group col-lg-1">
                    <label for="可用数量" class='control-label'>可用数量</label>
                </div>
                <div class="form-group col-lg-1">
                    <label for="hold数量" class='control-label'>hold数量</label>
                </div>
                <div class="form-group col-lg-2">
                    <label for="总数量" class='control-label'>总数量</label>
                </div>
                <div class="form-group col-lg-2">
                    <label for="实盘数量" class='control-label'>实盘数量</label>
                </div>
            </div>
        @foreach($stockTakingForms as $stockTakingForm)
            @if($stockTakingForm->stock_taking_status != 'equal')
            <div class='row'>
                <div class="form-group col-lg-1">
                    <input type='text' name='arr[id][]' class='form-control' value="{{ $stockTakingForm->id }}" readonly>
                </div>
                <div class="form-group col-lg-1">
                    <input type='text' name='arr[sku][]' class='form-control' value="{{ $stockTakingForm->stock ? $stockTakingForm->stock->items ? $stockTakingForm->stock->items->sku : '' : '' }}" readonly>
                </div>
                <div class="form-group col-lg-2">
                    <input type='text' name='arr[warehouse_id][]' class='form-control' value="{{ $stockTakingForm->stock ? $stockTakingForm->stock->warehouse ? $stockTakingForm->stock->warehouse->name : '' : '' }}" readonly>
                </div>
                <div class="form-group col-lg-2">
                    <input type='text' name='arr[warehouse_position_id][]' class='form-control' value="{{ $stockTakingForm->stock ? $stockTakingForm->stock->position ? $stockTakingForm->stock->position->name : '' : '' }}" readonly>
                </div>
                <div class="form-group col-lg-1">
                    <input type='text' name='arr[available_quantity][]' class='form-control' value="{{ $stockTakingForm->stock ? $stockTakingForm->stock->available_quantity : '' }}" readonly>
                </div>
                <div class="form-group col-lg-1">
                    <input type='text' name='arr[hold_quantity][]' class='form-control' value="{{ $stockTakingForm->stock ? $stockTakingForm->stock->hold_quantity : '' }}" readonly>
                </div>
                <div class="form-group col-lg-2">
                    <input type='text' name='arr[all_quantity][]' class='form-control all_quantity' value="{{ $stockTakingForm->stock ? $stockTakingForm->stock->all_quantity : '' }}" readonly>
                </div>
                <div class="form-group col-lg-2">
                    <input type='text' name='arr[quantity][]' class='form-control quantity' placeholder='实盘数量' value="{{$stockTakingForm->quantity}}" readonly>
                </div>
            </div>
            @endif
        @endforeach
    </div>
</div>
<div class='form-group row'>
    <div class='col-lg-4'>
        <label for='checkout'>审核结果</label>
        <select name='result' class='form-control'>
            <option value='1'>审核通过</option>
            <option value='0'>拒绝</option>
        </select>
    </div>
</div>
@stop
