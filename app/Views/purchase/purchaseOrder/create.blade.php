@extends('common.form')
@section('formAction') {{ route('purchaseOrder.store') }} @stop
@section('formBody')
    <input type="hidden" name="user_id" value="1">
      <div class="form-group col-lg-3">
        <label for="color">仓库：</label>
        <select class="form-control" name="warehouse_id" id="warehouse" onChange="getWarehouse()">
        	<option value="0" selected:selected>选择仓库</option>
            @foreach($warehouse as $warehouse)
                <option value="{{ $warehouse->id}}">{{ $warehouse->name }}</option>
            @endforeach
        </select>
   	 </div>
     <div class="form-group col-lg-3">
        <label for="color">供应商：</label>
        <select class="form-control" name="supplier_id" >
        <option value="0">0</option>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
        </select>
   	 </div>
  <script type="text/javascript"/>
  	function getWarehouse(){
		var warehouse=$("#warehouse option:selected").val();
		 $.ajax({
                url: "purchaseOrderSupplier",
                data:{warehouse_id:warehouse},
                dataType: "html",
                type:'get',
                success:function(result){
                    if(result==0){
                        $(".ajaxinsert").html('');
                    }else{
                        $(".ajaxinsert").html(result);  
                    }
                    
                }
            });     
		}
  </script>
@stop
 
 