@extends('common.form')
<link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('purchaseList.update', ['id' => $model->id]) }}  @stop
@section('formAttributes') name='creator' enctype="multipart/form-data" @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="form-group col-lg-4">
        <label for="sku_id">sku_id:</label>
        {{$model->sku_id}}
    </div>
     <div class="form-group col-lg-4">
        <label >图片:</label>
        <img src="{{asset($model->purchaseItem->product->image->src)}}" height='100px'/>
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
            <select name='status'>
            @foreach(config('purchase.purchaseItem.status') as $k=>$v)
            	<option value="{{$k}}">{{ $v }}</option>
            @endforeach
            </select>
    </div>
    <!-- <div class="form-group col-lg-4">
            <label >异常状态:</label>
           <select  class="form-control" name="active" id='type' onChange="reportwait(this.id)">
            @foreach(config('purchase.purchaseItem.active') as $k=>$v)
            	<option value="{{$k}}" >{{ $v }}</option>
            @endforeach
            </select>
    </div>
   <div class="form-group col-lg-4" id='reportwaitingshow' style="display:none">
        <label for="URL">预计到货日期：</label>
        <input id="waiting_date" class='form-control' name='arrival_time' type="text" placeholder='预计到货时间' value="{{ old('expected_date') }}">
    </div>
    <div class="form-group col-lg-4" id='reportshow' style="display:none">
        <label for="URL">异常备注：</label>
        	<textarea name="remark" cols="20" rows="5" style="width:590;hight:140">           
        	</textarea>
    </div>-->
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
            <select name='costExamineStatus'>
            @foreach(config('purchase.purchaseItem.costExamineStatus') as $k=>$v)
            	<option value="{{$k}}" @if($model->costExamineStatus == $k ) selected @endif>{{ $v }}</option>      
            @endforeach
            </select>
    </div>
    <div class="form-group col-lg-4">
        <label class='control-label'>已到货数量:</label>
        <input class="form-control" type="text" name='arrival_num' value='{{$model->arrival_num}}'/>
    </div>
    <script type="text/javascript">
	 $(document).ready(function(){
        $('#waiting_date').cxCalendar();
    });
	function reportwait(x){
		var a=$('#type').val(); 
		if(a=='2'){			
            $('#reportwaitingshow').show();
			$('#reportshow').hide();
		}else if(a=='3'){
			$('#reportwaitingshow').hide();
			$('#reportshow').show();
			}else{
			$('#reportwaitingshow').hide();
			$('#reportshow').hide();
				}
		}
    </script>
@stop
 
 
 
 