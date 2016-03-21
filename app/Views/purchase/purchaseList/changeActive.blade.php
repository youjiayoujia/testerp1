@extends('common.form')
<link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') /purchaseList/updateActive/{{$abnormal->id}}  @stop
@section('formAttributes') name='creator' enctype="multipart/form-data" @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="form-group col-lg-4">
        <label for="sku_id">sku_id:</label>
        {{$abnormal->sku_id}}
    </div>
    <div class="form-group col-lg-4">
        <label for="">产品默认图片:</label>
        <img src="{{asset($abnormal->purchaseItem->product->image->src)}}" height="50px"/>
    </div>
     <div class="form-group col-lg-4">
        <label >异常状态:</label>
        @foreach(config('purchase.purchaseItem.active') as $key => $v)
               @if($abnormal->active == $key) {{$v}} @endif
        @endforeach    
    </div>
    <div class="form-group col-lg-4">
        <label >处理状态:</label>
        <select name="status"  >
        @if($abnormal->active==1)
        @foreach(config('purchase.productAbnormal.status.1') as $key => $v)
               <option value="{{$key}}" @if($abnormal->active_status==$key) selected @endif>{{$v}}</option>
        @endforeach
        @elseif($abnormal->active==2)
        @foreach(config('purchase.productAbnormal.status.2') as $key => $v)
               <option value="{{$key}}" @if($abnormal->active_status==$key) selected @endif>{{$v}}</option>
        @endforeach
        @elseif($abnormal->active==3)
        @foreach(config('purchase.productAbnormal.status.3') as $key => $v)
               <option value="{{$key}}" @if($abnormal->active_status==$key) selected @endif>{{$v}}</option>
        @endforeach
        @elseif($abnormal->active==4)
        @foreach(config('purchase.productAbnormal.status.4') as $key => $v)
               <option value="{{$key}}" @if($abnormal->active_status==$key) selected @endif>{{$v}}</option>
        @endforeach
        @endif
        </select>      
    </div>
     @if($abnormal->active ==1)
     <div class="form-group col-lg-4" >
      主供应商：{{$abnormal->supplier->name}}&nbsp;电话：{{$abnormal->supplier->telephone}}&nbsp;地址：{{$abnormal->supplier->province}}{{$abnormal->supplier->city}}{{$abnormal->supplier->address}}</br>
      <input type="radio" name="newSupplier" value="{{$second_supplier->id}}">
      辅应商：{{$second_supplier->name}}&nbsp;电话：{{$second_supplier->telephone}}&nbsp;地址：{{$second_supplier->province}}{{$second_supplier->city}}{{$second_supplier->address}}
    </div>
    @endif
    @if($abnormal->active ==2)
     <div class="form-group col-lg-4" >
        <label for="URL">预计到货日期：</label>
        <input id="waiting_date" class='form-control' name='arrival_time' type="text" placeholder='预计到货时间' value="{{ $abnormal->arrival_time }}">
    </div>
    @endif
    @if($abnormal->active ==3)
    <div class="form-group col-lg-4" >
        <label for="URL">异常备注：</label>
        	<textarea name="remark" cols="20" rows="5" style="width:590;hight:140">  
            	{{$abnormal->remark}}         
        	</textarea>
    </div>
    @endif
    @if($abnormal->active == 4)
    <div class="form-group col-lg-4">
        <label for="URL">上传新图：</label>
        <input type="file" name='newImage' value=""/>
    </div>
    @endif
    <script type="text/javascript">
	 $(document).ready(function(){
        $('#waiting_date').cxCalendar();
    });
	 </script> 
@stop
 
 
 
 