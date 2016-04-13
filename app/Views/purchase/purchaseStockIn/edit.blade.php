@extends('common.form')
@section('formAction') {{ route('purchaseStockIn.update', ['id' => $model->id]) }}  @stop
@section('formAttributes') name='creator' enctype="multipart/form-data" @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="row">
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
    <div class="form-group col-lg-4">
        <label for="sku_id">sku:</label>
        {{$model->sku}}
    </div>
    </div>
  
    <div class="row">
    <div class="form-group col-lg-4">
            <label >入库状态:</label>
            @foreach(config('purchase.purchaseItem.storageStatus') as $k=>$v)
            	@if($model->storageStatus == $k)
            	<td>{{ $v }}</td>
                @endif
            @endforeach
    </div>
    <div class="form-group col-lg-4">
        <label class='control-label'>条码号:</label>
        {{$model->bar_code}}
    </div>
    <div class="form-group col-lg-4">
         <strong>采购人</strong>:
         {{$model->update_userid}}
    </div>
    </div>
    <div class="row">
    <div class="form-group col-lg-4">
            <label >需要采购数量:</label>
            {{$model->purchase_num}}
    </div>
    <div class="form-group col-lg-4">
        <label class='control-label'>已到货数量:</label>{{$model->arrival_num}}
    </div>
    <div class="form-group col-lg-4">
         <strong>已入库数量</strong>:
         {{$model->storage_qty}}
    </div>
    </div>
     <div class="row">
    <div class="form-group col-lg-4" id='checkType'>
            <label >入库方式:</label>
            <input type="radio" name="storageInType" checked onClick="checkType()" value="1">单件入库
             <input type="radio" name="storageInType" onClick="checkType()" value="2">多件入库
    </div>
    
    <div id="more" style="display:none">
     <div class="form-group col-lg-4">
         <strong>入库数量</strong>:
         <input type="text"class="form-control" name="storage_qty" value="">
    </div>
    </div> 
    </div>
    <script type="text/javascript">
        function checkType() {
            var uploadType = $("#checkType [name='storageInType']:checked").val();
            if (uploadType == '1') {
                $('#more').hide();
            } else {
                $('#more').show();
            }
        }
	</script>
@stop
 
 
 
 