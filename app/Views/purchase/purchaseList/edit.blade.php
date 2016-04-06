@extends('common.form')
<link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('purchaseList.update', ['id' => $model->id]) }}  @stop
@section('formAttributes') name='creator' enctype="multipart/form-data" @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="row">
      <div class="form-group col-lg-3">
        <label for="type">采购类型：</label>
        @foreach(config('purchase.purchaseItem.type') as $k=>$type)
            	@if($model->type == $k)
            	<td>{{ $type }}</td>
                @endif
            @endforeach
    </div>
    
    
     <div class="form-group col-lg-3">
            <label for="warehouse">仓库:</label>
            {{$model->warehouse->name}}
    </div>
    <div class="form-group col-lg-3">
        <label for="sku_id">sku:</label>
        {{$model->sku}}
    </div>
  <div class="form-group col-lg-3">
        <label >图片:</label>
        <img src="{{asset($model->item->product->image->src)}}" height='50px'/>
    </div>
  
    </div>
    <div class="row">
    <div class="form-group col-lg-3">
            <label >报残:</label>
           <select  class="form-control" name="active_status" id='type' onChange="reportwait(this.id)">
            @foreach(config('purchase.purchaseItem.active_status.3') as $k=>$v)
            	<option value="{{$k}}" >{{ $v }}</option>
            @endforeach
            </select>
    </div>
    <div class="form-group col-lg-3" >
        <label for="URL">报残备注：</label>
        <input type="text" class="form-control"  name="remark" value=""/>
    </div>
    </div>
    <div class="row">
    <div class="form-group col-lg-3">
            <label >参考价格:</label>
            {{$model->item->product->purchase_price}}
    </div>
    <div class="form-group col-lg-3">
            <label >成本价格:</label>
            {{$model->purchase_cost}}
    </div>
        <div class="form-group col-lg-4">
            <label >审核成本:</label>
            <select name='costExamineStatus'>
            @foreach(config('purchase.purchaseItem.costExamineStatus') as $k=>$v)
            	<option value="{{$k}}" @if($model->costExamineStatus == $k ) selected @endif>{{ $v }}</option>      
            @endforeach
            </select>
    </div>
    </div>
    <div class="row">
    <div class="form-group col-lg-4">
            <label >需要采购数量:</label>
            {{$model->purchase_num}}
    </div>
    <div class="form-group col-lg-4">
        <label class='control-label'>已到货数量:</label>
        <input class="form-control" type="text" name='arrival_num' value='{{$model->arrival_num}}'/>
    </div>
    </div>
@stop
 
 
 
 