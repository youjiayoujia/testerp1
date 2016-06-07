@extends('common.form')
@section('formAction')  {{ route('selectPurchaseOrder') }}  @stop
@section('formBody')  
     <div class="panel panel-default">
        
        <div class="panel-body">
           <div class="form-group col-lg-2">
                <strong>运单号</strong>
               
                <input class="form-control" id="" placeholder="订单号" name='post_coding' value="">
            </div>
            </div> 
            </div>
       
@stop