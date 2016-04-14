@extends('common.form')
@section('formAction') /purchaseStockIn/updateStorage @stop
@section('formAttributes') name='creator' enctype="multipart/form-data" @stop
@section('formBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="form-group col-lg-4">
                <strong>输入SKU</strong>: 
                <input type="text"class="form-control" name="sku" value="">
            </div>
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
