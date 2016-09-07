@extends('common.table')
@section('tableHeader')
    <th><input type='checkbox' name='select_all' class='select_all'></th>
    <th>图片</th>
    <th>账号</th>   
    <th>产品ID</th>
    <th>SKU</th>
    <th>标题</th>
    <th>近30天销售</th>
    <th>刊登人员</th>
    <th>刊登时间</th>
    <th>操作</th>
@stop
@section('tableBody')
     @foreach($data as $smtProductList)
        <tr>
            <td><input type='checkbox' name='single[]' class='single' value="{{$smtProductList->productId}}"></td>
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
            <td>{{ $smtProductList->productId }}</td>
            <td>
                <?php 
                    $skuCodeArr = array();                    
                    foreach ($smtProductList->productSku as $productSkuItem){
                        echo $productSkuItem->skuCode.'<br/>';                       
                    }                                             
                ?>            
            </td>
            <td>{{ $smtProductList->subject }}</td>    
            <td>{{ $smtProductList->quantitySold1 }}</td>       
            <td>{{ $smtProductList->userInfo ? $smtProductList->userInfo->name : ''}}</td>
            <td>{{ $smtProductList->gmtCreate}}</td>
            <td>   
                  
                    <a href="javascript:void(0)"
                       class="btn btn-warning btn-xs" id="editOnline" data-id = "{{$smtProductList->productId}}">
                        <span class="glyphicon glyphicon-pencil"></span> 编辑在线信息
                    </a>
                     @if( $smtProductList->productStatusType == 'onSelling' || $smtProductList->productStatusType == 'offline')
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
         <div class="btn-group">
            <a class="btn btn-success export" href="javascript:void(0)" id="batch_copy">
              另存为草稿
            </a>
        </div>
        <div class="btn-group">
            <a class="btn btn-success export" href="{{ route('smt.index')}}">
            查看草稿列表
            </a>
        </div>
        <div class="btn-group">
            <a class="btn btn-success export" href="{{ route('smt.create') }}">
              批量修改
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

$(document).on('click','#editOnline',function(){
	var product_id = $(this).data('id');
	if(confirm('是否要先同步广告，本次同步不计算利润率')){
		$.ajax({
			url: "{{route('smtProduct.synchronizationProduct')}}",
			data: 'product_id='+product_id,
			type: 'POST',
			dataType: 'JSON',
			beforeSend: function(){
				showtips('产品:'+product_id+'同步中...', 'alert-success');
			},
			success: function(data){
				if (data.status){
					showtips('同步成功', 'alert-success');
					targetBtn.trigger('click');
				}else {
					showtips('同步失败'+data.info);
					targetBtn.trigger('click');
				}
			}
		});
	}else{		
		var url = "{{route('smt.editOnlineProduct')}}";
		window.location.href = url + '?id=' + product_id;
	}	
	
})

$(document).on('click', '#batch_copy', function(event) {
		event.preventDefault();
		/* Act on the event */
		var productIds = $('input[name="single[]"]:checked').map(function() {
			return $(this).val();
		}).get().join(',');
		if (productIds == ''){
			alert('请先选择数据');
			return false;
		}

		if (confirm('确定要将选中的广告另存为草稿吗？')) {
			//弹出层选择账号
			$.layer({
				type   : 2,
				shade  : [0.8 , '' , true],
				title  : ['选择账号',true],
				iframe : {src : '{{route('smtProduct.showAccountToCopyProduct')}}'},
				area   : ['900px' , '550px'],
				success : function(){
					layer.shift('top', 400)
				},
				btns : 2,
				btn : ['确定', '取消'],
				yes : function(index){ //确定按钮的操作
					var account_list = layer.getChildFrame('.account_list :checked', index).map(function(){
						return $(this).val();
					}).get().join(',');
					console.log(account_list);
					if (account_list != ''){

						$.ajax({
							url: '{{route('smtProduct.copyToDraft')}}',
							data: 'productIds='+productIds+'&tokenIds='+account_list,
							type: 'POST',
							dataType: 'json',
							beforeSend: function(){
								$('#batch_copy').addClass('disabled');
							},
							success: function(data){
								var str='';
								if (data.data){
									$.each(data.data, function(index, el){
										str += el+';';
									});
								}
								if (data.status) { //成功
									showxbtips(data.info+str);
								}else {
									showxbtips(data.info+str, 'alert-warning');
								}
							},
							complete: function(){
								$('#batch_copy').removeClass('disabled');
							}
						});
					}else {
						showtips('请先选择账号', 'alert-warning');
					}
					layer.close(index);
				},
				no: function(index){
					layer.close(index);
				}
			});
		}else{
			return false;
		}
	});
</script>
@stop