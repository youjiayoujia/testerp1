@extends('common.table')
@section('tableHeader')
    <th><input type='checkbox' name='select_all' class='select_all'></th>
    <th class="sort" data-field="id">ID</th>
    <th>图片</th>
    <th>账号</th>
    <th>SKU</th>
    <th>单价</th>
    <th>状态</th>
    <th>标题</th>
    <th>关键词1</th>
    <th>关键词2</th>
    <th>关键词3</th>
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
            <td>
                <?php 
                    $skuCodeArr = array();
                    
                    foreach ($smtProductList->productSku as $productSkuItem){
                    
                        if($productSkuItem->skuCode){                                           
                            array_push($skuCodeArr, $productSkuItem->skuCode);
                        }
                    }                    
                    $skuCode = implode(',', $skuCodeArr);
                    echo $skuCode;                    
                ?>            
            </td>
            <td>{{ $smtProductList->productPrice}}</td>
            <td>{{ $smtProductList->productStatusType ? '待发布' : ''}}</td>
            <td>{{ $smtProductList->subject }}</td>
            <td>{{ $smtProductList->details ? $smtProductList->details->keyword : ''}} </td>
            <td>{{ $smtProductList->details ? $smtProductList->details->productMoreKeywords1 : ''}}</td>
            <td>{{ $smtProductList->details ? $smtProductList->details->productMoreKeywords2 : ''}}</td>
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
@stop
@section('tableToolButtons')
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
@stop
@section('childJs')
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
				if(typeof(data) == "string"){
					data = JSON.prase(data);
				}
				if(data.status){
					alert(data.info);
				}
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
</script>
@stop
