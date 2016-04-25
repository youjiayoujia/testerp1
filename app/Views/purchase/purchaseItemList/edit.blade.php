@extends('common.form')
<link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('purchaseItemList.update', ['id' => $model->id]) }}  @stop
@section('formAttributes') name='creator' enctype="multipart/form-data" @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="row">
    <div class="form-group col-lg-4">
       <strong>ID</strong>: {{ $model->id }}
      </div>
      <div class="form-group col-lg-4">
        <label for="type">采购类型：</label>
        @foreach(config('purchase.purchaseItem.type') as $k=>$type)
            	@if($model->type == $k)
            	<td>{{ $type }}</td>
                @endif
            @endforeach
    </div>
    
    
     <div class="form-group col-lg-4">
            <label for="warehouse">仓库:</label>
            {{$model->warehouse->name}}
    </div>
    </div>
     <div class="row">
    <div class="form-group col-lg-4">
        <label for="sku_id">sku:</label>
        {{$model->sku}}
    </div>
  <div class="form-group col-lg-4">
        <label >图片:</label>
        <img src="{{asset($model->item->product->image->src)}}" height='50px'/>
    </div>
    <div class="form-group col-lg-4">
                <strong>采购状态</strong>:
               @foreach(config('purchase.purchaseItem.status') as $k=>$val)
            	@if($model->status == $k)
            		{{$val}}
                @endif
            	@endforeach
            </div> 
     </div>
    <div class="row">
    <div class="form-group col-lg-4">
        <strong>供应商信息</strong>:
        名：{{$model->supplier->name}}&nbsp;电话：{{$model->supplier->telephone}} &nbsp;地址：{{$model->supplier->province}}{{$model->supplier->city}}{{$model->supplier->address}}
    </div>
    <div class="form-group col-lg-4">
            <label >参考价格:</label>
            {{$model->item->product->purchase_price}}
    </div>
    <div class="form-group col-lg-4">
            <label >成本价格:</label>
            {{$model->purchase_cost}}
    </div>
    </div>
    <div class="row">
     @if($model->purchase_cost >0)
        <div class="form-group col-lg-4">
            <label >审核成本:</label>
            <select name='costExamineStatus'>
            @foreach(config('purchase.purchaseItem.costExamineStatus') as $k=>$v)
            	<option value="{{$k}}" @if($model->costExamineStatus == $k ) selected @endif>{{ $v }}</option>      
            @endforeach
            </select>
    </div>
    @endif
    @if($model->status < 2)
     <div class="form-group col-lg-4" >
        <label for="URL">采购数量：</label>
        <input type="text" class="form-control"  name="purchase_num" value="{{$model->purchase_num}}"/>
    </div>
    @endif
    </div>
@stop
 
 
 
 