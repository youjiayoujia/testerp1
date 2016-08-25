@extends('common.table')
@section('tableToolButtons')
    <div class="btn-group btn-info" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="glyphicon glyphicon-filter"></i> 批量修改属性
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="javascript:" class="batchedit" data-name="weight">重量</a></li>
            <li><a href="javascript:" class="batchedit" data-name="purchase_price">参考成本</a></li>
            <li><a href="javascript:" class="batchedit" data-name="status">SKU状态</a></li>
            <li><a href="javascript:" class="batchedit" data-name="package_size">体积</a></li>
            <li><a href="javascript:" class="batchedit" data-name="name">中英文资料</a></li>
            <li><a href="javascript:" class="batchedit" data-name="wrap_limit">包装方式</a></li>
            <li><a href="javascript:" class="batchedit" data-name="catalog">分类</a></li>
            <li><a href="javascript:" class="batchdelete" data-name="catalog">批量删除</a></li>
            <li><a href="javascript:" class="" data-toggle="modal" data-target="#myModal">上传表格修改状态</a></li>
        </ul>
    </div>
@stop{{-- 工具按钮 --}}
@section('tableHeader')
    <th><input type="checkbox" isCheck="true" id="checkall" onclick="quanxuan()"> 全选</th>
    <th class="sort" data-field="id">ID</th>
    <th>图片</th>
    <th class="sort" data-field="sku">产品名称</th>
    <th class="sort" data-field="c_name">sku</th>
    <th>重量</th>
    <th>仓位</th>
    <th>申报资料</th>
    <th>注意事项</th>
    <th>小计</th>
    <th>状态</th>
    <th>采购负责人</th>
    <th>开发负责人</th>
    <th>供应商</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>更新时间</th>
    <th>操作</th>
@stop

@section('tableBody')

    @foreach($data as $item)
        <tr>
            <td><input type="checkbox" name="tribute_id" value="{{$item->id}}"></td>
            <td>{{ $item->id }}</td>
            <td><img src="{{ asset($item->product->dimage) }}" width="100px"></td>
            <td>{{ $item->c_name }}<br>物品分类：{{ $item->product->catalog?$item->product->catalog->all_name:'' }}<br>
                                    开发时间：{{ $item->created_at }}<br>
                                    【包装方式：<br>
                                    @foreach($item->product->wrapLimit as $wrap)
                                        {{$wrap->name}}<br>
                                    @endforeach
                                    】
                                </td>
            <td>{{ $item->sku }}</td>
            <td>{{ $item->weight }}kg</td>
            <td>{{ $item->warehouse?$item->warehouse->name:'' }}<br>{{ $item->warehousePosition?$item->warehousePosition->name:'' }}</td>
            <td>{{ $item->product?$item->product->declared_en:'' }}<br>{{ $item->product?$item->product->declared_cn:'' }}<br>
                    $<?php 
                        if($item->product){
                                if($item->product->declared_value>0){
                                    echo $item->product->declared_value;
                                }elseif(($item->purchase_price/6)<1){echo 1;}elseif(($item->purchase_price/6)>25){echo 25;}else{echo round($item->purchase_price/6);} 
                        }
                    ?>
            </td>
            <td>{{$item->product?$item->product->notify:''}}</td>
            <td>
                <div>虚：{{$item->available_quantity}}</div>
                <div>实：{{$item->all_quantity}}</div>
                <div>途：{{$item->normal_transit_quantity}}</div>
                <div>特：{{$item->special_transit_quantity}}</div>
                <div>7天销量：{{$item->getsales('-7 day')}}</div>
                <div>14天销量：{{$item->getsales('-14 day')}}</div>
                <div>28天销量：{{$item->getsales('-28 day')}}</div>
                <div>建议采购值：{{$item->getNeedPurchase()}}</div>
                <div>库存周数：{{$item->getsales('-7 day')==0?0:($item->available_quantity+$item->normal_transit_quantity)/$item->getsales('-7 day')}}</div>
            </td>
            <td>{{ config('item.status')[$item->status]}}</td>
            <td>{{ $item->product->purchaseAdminer?$item->product->purchaseAdminer->name:''}}</td>
            <td>{{ $item->product->spu->Developer?$item->product->spu->Developer->name:''}}</td>
            <td>{{ $item->supplier ? $item->supplier->name :''}}</td>
            <td>{{ $item->updated_at }}</td>
            <td>{{ $item->created_at }}</td>
            <td>
                <a href="{{ route('item.show', ['id'=>$item->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 查看
                </a>
                <a href="{{ route('item.edit', ['id'=>$item->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="{{ route('item.print', ['id'=>$item->id]) }}" class="btn btn-warning btn-xs" data-id="{{ $item->id }}">
                    <span class="glyphicon glyphicon-pencil"></span> 打印
                </a> 
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $item->id }}"
                   data-url="{{ route('item.destroy', ['id' => $item->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach

        <!-- 模态框（Modal） -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               上传表格修改sku状态
            </h4>
         </div>
             <form action="{{ route('item.uploadSku') }}" method="post" enctype="multipart/form-data">
                 <input type="hidden" name="_token" value="{{ csrf_token() }}">
                 <input type="file" name="upload" >  
                 <div class="modal-footer">
                    <button type="button" class="btn btn-default" 
                       data-dismiss="modal">关闭
                    </button>
                    <button type="submit" class="btn btn-primary" >
                       提交
                    </button>
                 </div>
             </form>
        </div>
    </div>
</div>
    

@stop

@section('childJs')
    <script type="text/javascript">
        $('.batchedit').click(function () {
            var checkbox = document.getElementsByName("tribute_id");
            var item_ids = "";
            var param = $(this).data("name");
            for (var i = 0; i < checkbox.length; i++) {
                if (!checkbox[i].checked)continue;
                item_ids += checkbox[i].value + ",";
            }
            item_ids = item_ids.substr(0, (item_ids.length) - 1);

            var url = "{{ route('batchEdit') }}";
            window.location.href = url + "?item_ids=" + item_ids + "&param=" + param;
        });

        //全选
        function quanxuan() {
            var collid = document.getElementById("checkall");
            var coll = document.getElementsByName("tribute_id");
            if (collid.checked) {
                for (var i = 0; i < coll.length; i++)
                    coll[i].checked = true;
            } else {
                for (var i = 0; i < coll.length; i++)
                    coll[i].checked = false;
            }
        }

        $('.batchdelete').click(function () {
            
            var url = "{{route('item.batchDelete')}}";

            var checkbox = document.getElementsByName("tribute_id");
            var item_ids = "";
            for (var i = 0; i < checkbox.length; i++) {
                if (!checkbox[i].checked)continue;
                item_ids += checkbox[i].value + ",";
            }
            item_ids = item_ids.substr(0, (item_ids.length) - 1);

            $.ajax({
                url: url,
                data: {item_ids:item_ids},
                dataType: 'json',
                type: 'get',
                success: function (result) {
                    window.location.reload();
                }
            })
        });

    </script>
@stop