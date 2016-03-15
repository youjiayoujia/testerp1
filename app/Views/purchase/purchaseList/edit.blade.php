@extends('common.form')
@section('formAction')  {{ route('purchaseList.update', ['id' => $model->id]) }}  @stop
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
            <label >处理状态:</label>
            <select name='active'>
            @foreach(config('purchase.purchaseItem.status') as $k=>$v)
            	<option value="{{$k}}">{{ $v }}</option>
            @endforeach
            </select>
    </div>
    <div class="form-group col-lg-4">
            <label >异常状态:</label>
            <select name='active'>
            @foreach(config('purchase.purchaseItem.active') as $k=>$v)
            	<option value="{{$k}}">{{ $v }}</option>
            @endforeach
            </select>
    </div>
    <div class="form-group col-lg-4">
            <label >参考价格:</label>
            {{$model->cost}}
    </div>
    <div class="form-group col-lg-4">
            <label >成本价格:</label>
            {{$model->purchase_cost}}
    </div>
        <div class="form-group col-lg-4">
            <label >审核成本:</label>
            <select name='active'>
            @foreach(config('purchase.purchaseItem.costExamineStatus') as $k=>$v)
            	<option value="{{$k}}">{{ $v }}</option>
            @endforeach
            </select>
    </div>
    <div class="form-group col-lg-4">
        <label class='control-label'>已到货数量:</label>
        <input class="form-control" type="text" name='arrival_num' value='{{$model->arrival_num}}'/>
    </div>
@stop
 
 
 
 