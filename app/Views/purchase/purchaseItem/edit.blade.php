@extends('common.form')
@section('formAction')  {{ route('purchaseItem.update', ['id' => $model->id]) }}  @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="form-group col-lg-4">
        <label for="sku_id">sku_id:</label>
        {{$model->sku_id}}
    </div>
    <div class="form-group col-lg-4">
        <label for="type">采购类型：</label>
        @foreach(config('purchase.purchaseItem.type') as $k=>$type)
            	@if($model->type == $k)
            	<td>{{ $type }}</td>
                @endif
            @endforeach
    </div>
    @if($model->type == 0)
        <div class="form-group col-lg-4">
            <label for="order_id">订单号:</label>
            {{$model->order_id}}
        </div>
    @endif
    <div class="form-group col-lg-4">
            <label for="warehouse">仓库:</label>
            {{$model->warehouse->name}}
    </div>
    <div class="form-group col-lg-4">
        <label class='control-label'>需要购买数量:</label>
        <input class="form-control" type="text" name='purchase_num' value='{{$model->purchase_num}}'/>
    </div>
@stop
 
 
 
 