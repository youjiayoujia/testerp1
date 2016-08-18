@extends('common.table')
@section('tableHeader')
    <th><input type='checkbox' name='select_all' class='select_all'></th>
    <th class="sort" data-field="id">ID</th>
    <th>图片</th>
    <th>账号</th>
    <th>产品ID</th>
    <th>SKU</th>
    <th>标题</th>
    <th>刊登人员</th>
    <th>刊登时间</th>

    <th>操作</th>
@stop
@section('tableBody')
     @foreach($data as $smtProductList)
        <tr>
            <td><input type='checkbox' name='single[]' class='single' value="{{$smtProductList->productId}}"></td>
            <td>{{ $smtProductList->id }}</td>
            <td>
                <?php
                    if(!empty($smtProductList->details->imageURLs)){
                        $imagesUrlArr = explode(';', $smtProductList->details->imageURLs);
                        $firstImageURL = array_shift($imagesUrlArr);
                    }

                ?>
                  <a target="_blank" href="{{ $firstImageURL}}"><img style="width:50px;height:50px;" src="{{ $firstImageURL}}"></a>
            </td>
            <td>{{ $smtProductList->accounts ? $smtProductList->accounts->account : ''}}</td>
            <td>{{ $smtProductList->productId}}</td>
            <td>
                <?php 
                    $skuCodeArr = array();
                    
                    foreach ($smtProductList->productSku as $productSkuItem){
                        echo $productSkuItem->skuCode.'<br/>';
                       
                    }                    
                            
                ?>            
            </td>
           
            <td>{{ $smtProductList->subject }}</td>
            <!-- 
            <td>{{ $smtProductList->productPrice}}</td>
            <td>{{ $smtProductList->details ? $smtProductList->details->keyword : ''}} </td>
            <td>{{ $smtProductList->details ? $smtProductList->details->productMoreKeywords1 : ''}}</td>
            <td>{{ $smtProductList->details ? $smtProductList->details->productMoreKeywords2 : ''}}</td>
             -->
            <td>{{ $smtProductList->userInfo ? $smtProductList->userInfo->name : ''}}</td>
            <td>{{ $smtProductList->updated_at }}</td>
            <td>   
                  @if($smtProductList->productStatusType == 'waitPost')
                  
                   <a href="{{ route('smt.edit', ['id'=>$smtProductList->productId]) }}" class="btn btn-warning btn-xs">
                        <span class="glyphicon glyphicon-pencil"></span> 编辑
                   </a>
                   <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                       data-id="{{ $smtProductList->productId }}"
                       data-url="{{ route('smt.destroy', ['id' => $smtProductList->productId]) }}">
                        <span class="glyphicon glyphicon-trash"></span> 删除                    
                   </a>

                  @else
                       @if($smtProductList->productStatusType=='onSelling')
                        <a href="{{ route('smt.editOnlineProduct', ['id'=>$smtProductList->productId]) }}"
                           class="btn btn-warning btn-xs">
                            <span class="glyphicon glyphicon-pencil"></span> 编辑在线信息
                        </a>
                        @endif
                    <a onclick="operator('<?php echo $smtProductList->productId;  ?>' ,'online',this)" class="btn btn-danger btn-xs  <?php   if($smtProductList->productStatusType=='offline'){echo "hidden"; }      ?>">
                        <span class="glyphicon glyphicon-pencil "></span> 下架
                    </a>

                    <a onclick="operator('<?php echo $smtProductList->productId;  ?>' ,'offline',this)"  class="btn btn-success btn-xs <?php   if($smtProductList->productStatusType=='onSelling'){echo "hidden"; }      ?>">
                        <span class="glyphicon glyphicon-pencil  "></span> 上架
                    </a>
                @endif                               
            </td>
        </tr>
     @endforeach
@stop
@section('tableToolButtons')
    @if(!isset($type))
        <div class="btn-group">
            <a class="btn btn-success export" href="javascript:">
                查看草稿列表
            </a>
        </div>
        <div class="btn-group">
            <a class="btn btn-success export" href="javascript:" id="batch_del">
                批量删除
            </a>
        </div>
        <div class="btn-group">
            <a class="btn btn-success export" href="javascript:">
                批量修改
            </a>
        </div>
         <div class="btn-group">
            <a class="btn btn-success export" href="javascript:">
                批量生产草稿
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
        @endif

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
				/*if(typeof(data) == "object"){
					data = JSON.stringify(data);					
				}*/
				
				$.each(data,function(name, value){
					if(value.status){
						alert(value.info);
					}else{
						alert(value.info);
					}
				});
				
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
		success:function(data){
			if(typeof(data) == "string"){
				data = JSON.prase(data);
			}
			if(data.status){
				alert(data.info);
			}
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
</script>
@stop