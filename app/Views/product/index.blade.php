@extends('common.table')
@section('tableToolButtons')
    <div class="btn-group">
        <a class="btn btn-info" id="batchexamine">
            <i class="glyphicon glyphicon-ok-circle"></i> 批量审核
        </a>
    </div>
    <div class="btn-group">
        <a class="btn btn-success" href="{{ route(request()->segment(1).'.create') }}">
            <i class="glyphicon glyphicon-plus"></i> 新增
        </a>
    </div>
@stop{{-- 工具按钮 --}}
@section('tableHeader')
    <th><input type="checkbox" isCheck="true" id="checkall" onclick="quanxuan()"> 全选</th>
    <th>ID</th>
    <th class="sort" data-field="model">MODEL</th>
    <th>图片</th>
    <th>分类</th>
    <th class="sort" data-field="name">名称</th>
    <th class="sort" data-field="c_name">中文名称</th>
    <th>供应商</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $product)
        <tr>
            <td>
                @if($product->status)
                <input type="checkbox" name="tribute_id"  value="{{$product->id}}" isexamine="1" >
                @else
                <input type="checkbox" name="tribute_id"  value="{{$product->id}}" isexamine="0" >
                @endif
            </td>
            <td>{{ $product->id }}</td>
            <td>{{ $product->model }}</td>
            <td>@if($product->default_image>0)<img src="{{ asset($product->image->path) }}/{{$product->image->name}}" width="100px" >@else无图片@endif</td>
            <td>{{ $product->catalog->name }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->c_name }}</td>
            <td>{{ $product->supplier->name }}</td>
            <td>{{ $product->created_at }}</td>
            <td>
                <a href="{{ route('product.show', ['id'=>$product->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('product.edit', ['id'=>$product->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                @if(!$product->status)
                    <a href="javascript:" class="btn btn-info btn-xs examine_model"
                       data-id="{{ $product->id }}"
                       data-url="{{route('examine')}}">
                        <span class="glyphicon glyphicon-check"></span> <span class='examine_{{$product->id}}'>审核</span>
                    </a>
                @else
                    <a href="javascript:" class="btn btn-info btn-xs has_check">
                        <span class="glyphicon glyphicon-check"></span> <span>已审核</span>
                    </a>
                @endif
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $product->id }}"
                   data-url="{{ route('product.destroy', ['id' => $product->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
@section('childJs')
    <script type="text/javascript">
        //单个审核
        $('.examine_model').click(function () {
            var product_id = $(this).data('id');
            if($(".examine_"+product_id).hasClass("hasexamine_"+product_id)){
                alert("该产品已审核");return;
            }
            if (confirm("确认审核?")) {
                var url = "{{route('examine')}}";
                $.ajax({
                    url:url,
                    data:{product_ids:product_id},
                    dataType:'json',
                    type:'get',
                    success:function(result){
                        if(result==1){
                            $(".examine_"+product_id).text("已审核");
                            $(".examine_"+product_id).addClass("hasexamine_"+product_id);
                       }else{
                            alert("审核失败");
                       }                     
                    }                  
                })
            }
        });
        $('.has_check').click(function () {
            alert("该产品已审核");
        });
        //批量审核
        $('#batchexamine').click(function () {
            if (confirm("确认审核?")) {
                var url = "{{route('examine')}}";
                var checkbox = document.getElementsByName("tribute_id");
                var product_ids = "";
                for (var i = 0; i < checkbox.length; i++) {
                    if(!checkbox[i].checked)continue;
                    if(checkbox[i].getAttribute('isexamine')==1){
                        alert("产品id为"+checkbox[i].value+"的model已经审核了");
                        return;
                    }
                    product_ids += checkbox[i].value+",";
                }
                product_ids = product_ids.substr(0,(product_ids.length)-1);
                $.ajax({
                    url:url,
                    data:{product_ids:product_ids},
                    dataType:'json',
                    type:'get',
                    success:function(result){
                        window.location.reload();
                    }                    
                })
            }
        });

        //批量审核
        /*$('#choseShop').click(function () {
            var url = "{{route('amazonProduct.index')}}";
            var checkbox = document.getElementsByName("tribute_id");
            var product_ids = "";
            for (var i = 0; i < checkbox.length; i++) {
                if(!checkbox[i].checked)continue;
                product_ids += checkbox[i].value+",";
            }
            product_ids = product_ids.substr(0,(product_ids.length)-1);
            window.location.href=url+"?product_ids="+product_ids;      
        });*/

        //批量审核
        $('#choseShop').click(function () {
            var url = "{{route('choseShop')}}";
            var checkbox = document.getElementsByName("tribute_id");
            var product_ids = "";
            for (var i = 0; i < checkbox.length; i++) {
                if(!checkbox[i].checked)continue;
                product_ids += checkbox[i].value+",";
            }
            product_ids = product_ids.substr(0,(product_ids.length)-1);
            window.location.href=url+"?product_ids="+product_ids;      
        });

        //全选
        function quanxuan()
        {
          var collid = document.getElementById("checkall");
          var coll = document.getElementsByName("tribute_id");
          if (collid.checked){
             for(var i = 0; i < coll.length; i++)
               coll[i].checked = true;
          }else{
             for(var i = 0; i < coll.length; i++)
               coll[i].checked = false;
          }
        }
    </script>
@stop