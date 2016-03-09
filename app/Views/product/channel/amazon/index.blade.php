@extends('common.table')
@section('tableToolButtons')
    
@stop{{-- 工具按钮 --}}
@section('tableHeader')
    
    <th>ID</th>
    <th>产品信息</th>
    <th>状态</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $amazonProduct)
        <tr>
            <td>{{ $amazonProduct->product_id }}</td>
            <td>{{ $amazonProduct->choies_info }}</td>
            <td>{{ $amazonProduct->created_at }}</td>
            <td>
                <a href="{{ route('amazonProduct.show', ['id'=>$amazonProduct->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('amazonProduct.edit', ['id'=>$amazonProduct->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑资料
                </a>
                <a href="{{ route('amazonProductEditImage', ['id'=>$amazonProduct->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑图片
                </a>
                <?php if($amazonProduct->status==0){ ?>
                    <a href="javascript:" class="btn btn-info btn-xs examine_model"
                           data-id="{{ $amazonProduct->id }}"
                           data-url="{{route('examineAmazonProduct')}}">
                            <span class="glyphicon glyphicon-check"></span> <span class='examine_{{$amazonProduct->id}}'>审核</span>
                    </a>
                <?php }elseif($amazonProduct->status==1){ ?>
                    <a href="javascript:" class="btn btn-info btn-xs cancel_examine_model"
                           data-id="{{ $amazonProduct->id }}"
                           data-url="{{route('cancelExamineAmazonProduct')}}">
                            <span class="glyphicon glyphicon-check"></span> <span class='examine_{{$amazonProduct->id}}'>撤销审核</span>
                    </a>
                <?php } ?>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $amazonProduct->id }}"
                   data-url="{{ route('amazonProduct.destroy', ['id' => $amazonProduct->id]) }}">
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
                var url = "{{route('examineAmazonProduct')}}";
                $.ajax({
                    url:url,
                    data:{product_ids:product_id},
                    dataType:'json',
                    type:'get',
                    success:function(result){
                        if(result==1){
                            //$(".examine_"+product_id).text("已审核");
                            //$(".examine_"+product_id).addClass("hasexamine_"+product_id);
                       }else{
                            //alert("审核失败");
                       }                     
                    }                  
                })
            }
        });


        $('.cancel_examine_model').click(function () {
            var product_id = $(this).data('id');
            if($(".examine_"+product_id).hasClass("hasexamine_"+product_id)){
                alert("该产品已审核");return;
            }
            if (confirm("确认审核?")) {
                var url = "{{route('amazonProduct/cancelExamineAmazonProduct')}}";
                $.ajax({
                    url:url,
                    data:{product_ids:product_id},
                    dataType:'json',
                    type:'get',
                    success:function(result){
                        if(result==1){
                            //$(".examine_"+product_id).text("已审核");
                            //$(".examine_"+product_id).addClass("hasexamine_"+product_id);
                       }else{
                            //alert("审核失败");
                       }                     
                    }                  
                })
            }
        });
        
        $('.has_check').click(function () {
            alert("该产品已审核");
        });
        </script>
@stop