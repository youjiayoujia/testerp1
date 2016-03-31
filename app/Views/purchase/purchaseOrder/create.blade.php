@extends('common.form')
@section('formAction') {{ route('purchaseOrder.store') }} @stop
@section('formBody')
    <input type="hidden" name="user_id" value="1">
    <input type="hidden" name="checkedPurchaseItems" id='checkedPurchaseItem_ids' value="">
      <div class="form-group col-lg-3">
        <label for="color">仓库：</label>
        <select class="form-control" name="warehouse_id" id="warehouse" onChange="getWarehouse()">
        	<option value="0" selected:selected>选择仓库</option>
            @foreach($warehouse as $warehouse)
                <option value="{{ $warehouse->id}}">{{ $warehouse->name }}</option>
            @endforeach
        </select>
   	 </div>
     <div class="form-group col-lg-12">
        <label for="color">选择入单条目：</label>
       <div id="checkProductItem"></div>
   	 </div>
     <div class="form-group col-lg-12" >
       <label for="color">已选取条目：</label>
       <div id="checkedProductItem"></div>
   	 </div>
	
  <script type="text/javascript"/>
  //根据仓库筛选供应商
  	function getWarehouse(){
		var warehouse=$("#warehouse option:selected").val();
		$.ajax({
                url: "checkProductItems",
                data:{warehouse_id:warehouse},
                dataType: "html",
                type:'get',
                success:function(result){ 
				$("#checkProductItem").show();
				if(result==0){
                        $("#checkProductItem").html('');
                    }else{
                        $("#checkProductItem").html(result);  
                }
				} 
				 
            }); ;   
		 
		}
	 //全选
        function quanxuan()
        {
          var collid = document.getElementById("checkall");
          var coll = document.getElementsByName("purchaseItem_id");
          if (collid.checked){
             for(var i = 0; i < coll.length; i++)
               coll[i].checked = true;
          }else{
             for(var i = 0; i < coll.length; i++)
               coll[i].checked = false;
          }
        }
	   //选取需要生成订单的采购条目
        function addPurchaseOrder() {
            if (confirm("确认选取这些条目添加进此采购单?")) {
                var checkbox = document.getElementsByName("purchaseItem_id");
                var purchaseItemIds = "";
                for (var i = 0; i < checkbox.length; i++) {
                    if(!checkbox[i].checked)continue;
                    purchaseItemIds += checkbox[i].value+",";
                }
				}
                purchaseItemIds = purchaseItemIds.substr(0,(purchaseItemIds.length)-1);
				$("#checkedPurchaseItem_ids").val(purchaseItemIds);
				$.ajax({
                    url:'checkedPurchaseItem',
                    data:{purchaseItemIds:purchaseItemIds},
                    dataType:'html',
                    type:'get',
                    success:function(result){
						if(result==0){
								$("#checkedProductItem").html('');
							}else{
								$("#checkedProductItem").html(result);  
						}   
                    }                    
                })
               
            
        }	
  </script>
@stop
 
 