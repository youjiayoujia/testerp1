@extends('common.form')
@section('formAction') {{ route('purchaseItem.store') }} @stop
@section('formBody')
    <input type="hidden" name="user_id" value="1">
    <div class="row">
    <div class="form-group col-lg-4">
        <label for="color">仓库：</label>
        <select class="form-control" name="warehouse_id" >
            @foreach($warehouse as $warehouse)
                <option value="{{ $warehouse->id}}">{{ $warehouse->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-lg-4">
        <label for="color">采购类型：</label>
        <select class="form-control" name="type" >
            @foreach(config('purchase.purchaseItem.type') as $k=>$type)
                <option value="{{ $k }}">{{ $type }}</option>
            @endforeach
        </select>
    </div>    
    <div class="form-group col-lg-4">
        <label class='control-label'>订单itemID</label>
        <input class="form-control" type="text" name='order_item_id' value='1'/>
    </div>
    </div>
    <div class="row">
    <div class="form-group col-lg-4">
        <label class='control-label'>SKU</label>
        <input class="form-control" type="text" name='sku' value='1'/>
    </div>
   
    <div class="form-group col-lg-4">
        <label class='control-label'>采购数数量</label>
        <input class="form-control" type="text" name='purchase_num' value='1'/>
    </div>    
    </div> 
@stop
 
 