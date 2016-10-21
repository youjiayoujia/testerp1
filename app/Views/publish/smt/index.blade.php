@extends('common.table')
@section('tableHeader')
    <th><input type='checkbox' name='select_all' class='select_all'></th>
    <th class="sort" data-field="id">产品ID</th>
    <th>图片</th>
    <th>账号</th>   
    <th>SKU</th>
    <th>单价</th> 
    <th>状态</th> 
    <th>标题</th>
    <th>关键词</th>
    <th>操作</th>
@stop
@section('tableBody')
     @foreach($data as $smtProductList)
        <tr>
            <td><input type='checkbox' name='single[]' class='single' value="{{$smtProductList->productId}}"></td>
            <td>{{ $smtProductList->productId }}</td>
            <td>
                <?php
                    if(!empty($smtProductList->details->imageURLs)){
                        $imagesUrlArr = explode(';', $smtProductList->details->imageURLs);
                        $firstImageURL = array_shift($imagesUrlArr);
                    }
                ?>
                 @if(!empty($firstImageURL))
                  <a target="_blank" href="{{ $firstImageURL}}"><img style="width:50px;height:50px;" src="{{ $firstImageURL}}"></a>
                 @endif
            </td>
            <td>{{ $smtProductList->accounts ? $smtProductList->accounts->account : ''}}</td>
            <td>
                <?php 
                    $skuCodeArr = array();
                    
                    foreach ($smtProductList->productSku as $productSkuItem){
                        echo $productSkuItem->skuCode.'<br/>';
                       
                    }                                             
                ?>            
            </td>
            <td>{{ $smtProductList->productPrice}}</td>
            <td>{{ $smtProductList->productStatusType == 'waitPost' ? '待发布' : '草稿'}}</td>
            <td>{{ $smtProductList->subject }}</td>
      
            
            <td>{{ $smtProductList->details ? $smtProductList->details->keyword : ''}} </td>
            <!-- 
            <td>{{ $smtProductList->details ? $smtProductList->details->productMoreKeywords1 : ''}}</td>
            <td>{{ $smtProductList->details ? $smtProductList->details->productMoreKeywords2 : ''}}</td>
            
            <td>{{ $smtProductList->userInfo ? $smtProductList->userInfo->name : ''}}</td>
            <td>{{ $smtProductList->updated_at }}</td>
             -->
            <td>                                      
               <a href="{{ route('smt.edit', ['id'=>$smtProductList->productId]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
               </a>
               <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $smtProductList->productId }}"
                   data-url="{{ route('smt.destroy', ['id' => $smtProductList->productId]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除                    
               </a>                                        
            </td>
        </tr>
     @endforeach
     <!-- 
     <form name="batchModify" action="{{route('smtProduct.batchModifyProduct',['_token'=>csrf_token()])}}" method="post" target="_blank" onsubmit="openNewSpecifiedWindow('newWindow2')">
		<input type="hidden" name="operateProductIds" value="" id="operateProductIds"/>
		<input type="hidden" name="from" value="draft"/>
	</form>
	-->
@stop
@section('tableToolButtons')
    @if($type == 'waitPost')
        <div class="btn-group">
            <a class="btn btn-success export" href="{{route('smt.index')}}">
                查看草稿列表
            </a>
        </div>              
         <div class="btn-group">
            <a class="btn btn-success export" href="javascript:">
                批量生产草稿
            </a>
        </div>
      
     @else
        <div class="btn-group">
            <a class="btn btn-success export" href="{{route('smt.waitPost')}}">
                查看待发布产品列表
            </a>
        </div>
        <div class="btn-group">
            <a class="btn btn-success export" href="javascript:" id="batch_wait">
             批量保存为待发布
            </a>
        </div>
        
        
     @endif
         <div class="btn-group">
                <a class="btn btn-success export" href="javascript:" id="batch_modify">
                    批量修改
                </a>
           </div>
     
        <div class="btn-group">
            <a class="btn btn-success export" href="javascript:" id="batch_del">
                批量删除
            </a>
        </div>
        <div class="btn-group">
            <a class="btn btn-success export" href="javascript:" id="batch_post">
                批量发布
            </a>
        </div>
        <div class="btn-group">
            <a class="btn btn-success export" href="{{ route('smt.create') }}">
               新增
            </a>
        </div>
       

@stop
@section('childJs')
<link href="{{ asset('plugins/layer/skin/layer.css')}}" type="text/css" rel="stylesheet">
<script src="{{ asset('plugins/layer/layer.js') }}"></script>
<script type='text/javascript'>
$(document).ready(function(){
    $('.select_all').click(function () {
        if ($(this).prop('checked') == true) {
            $('.single').prop('checked', true);
        } else {
            $('.single').prop('checked', false);
        }
    });
})

$("#batch_wait").on('click',function(){
	var product_ids = $('input[name="single[]"]:checked').map(function(){
		return $(this).val();
	}).get().join(',');

	if (!product_ids){
		alert('请先选择行', 'alert-warning');
		return false;
	}

	if (confirm('确定要批量保存为待发布?')){
		$.ajax({
			url: "{{route('smt.changeStatusToWait')}}",
			data: {product_ids : product_ids},
			dataType : 'json',
			type : 'get',
			success : function(result){
				window.location.reload();
			}
		})
	}

	
	
})

$(document).on('click', '#batch_post', function(){
		var productIds = $('input[name="single[]"]:checked').map(function(){
			return $(this).val();
		}).get().join(',');
		if (!productIds){
			alert('请先选择行', 'alert-warning');
			return false;
		}

		if (!confirm('确定要批量发布吗，发布过程中不能操作?')){
			return false;
		}
	
		 $.ajax({
			url: "{{route('smt.batchPost')}}",
			data: 'productIds='+productIds,
			type: 'post',
			dataType: 'json',
			async: true,
			success:function(data){			
				var str = '';
				$.each(data,function(name, value){
					if(value.status){
						str = str + value.info + " ";
					}else{
						str =str + value.info + "  ";
					}
				});
				layer.alert(str);
				window.location.reload();
			}
		});	 

	});

//批量删除
$(document).on('click', '#batch_del', function(){
	var productIds = $('input[name="single[]"]:checked').map(function(){
		return $(this).val();
	}).get().join(',');
	if (!productIds){
		alert('请先选择行', 'alert-warning');
		return false;
	}

	if (!confirm('确定要删除吗？')){
		return false;
	}

	$.ajax({
		url: "{{route('smt.batchDel')}}",
		data: 'productIds='+productIds,
		type: 'post',
		dataType: 'json',
		async: true,
		success:function(result){
			alert(result.msg);
			window.location.reload();
		}
	});	 	
});

function operator(id,type,e){
	layer.confirm("您确定要进行此操作？",function(){
		 $.ajax({
		        url : "{{ route('smt.ajaxOperateOnlineProduct') }}",
		        data : {id:id,type:type},
		        dataType : 'json',
		        type : 'get',
		        success : function(result) {
			        if(typeof(result) == 'string'){
				        result = JSON.parse();
			        }
		            if(result.status==1){
		                if(type=='online'){
		                    $(e).next().removeClass('hidden');
		                    $(e).addClass('hidden');
		                }
		                if(type=='offline'){
		                    $(e).prev().removeClass('hidden');
		                    $(e).addClass('hidden');
		                }
		                
		                layer.alert(result.info);
		                parent.location.reload();
		            }else{
		            	layer.alert(result.info);		            	
		            }
		        }
		    });
	});   
}

//批量修改
$('#batch_modify').on('click', function(e){
	var productIds = $('input[name="single[]"]:checked').map(function() {
		return $(this).val();
	}).get().join(',');
	if (productIds == ''){
		layer.msg('请先选择产品');
		return false;
	}

	var url = "{{route('smtProduct.batchModifyProduct')}}";
    window.location.href = url + '?ids=' + productIds + '&type=' + "{{$type}}";
	//赋值下 --选择的产品就是需要批量修改的
	
});

function openNewSpecifiedWindow( windowName )
{
	window.open('',windowName,'width=700,height=400,menubar=no,scrollbars=no');
}

</script>
@stop