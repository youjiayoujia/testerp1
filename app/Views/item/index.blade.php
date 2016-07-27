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
        </ul>
    </div>
@stop{{-- 工具按钮 --}}
@section('tableHeader')
    <th><input type="checkbox" isCheck="true" id="checkall" onclick="quanxuan()"> 全选</th>
    <th class="sort" data-field="id">ID</th>
    <th>图片</th>
    <th class="sort" data-field="sku">sku名称</th>
    <th class="sort" data-field="c_name">中文名称</th>
    <th>分类</th>
    <th>重量</th>
    <th>仓位</th>
    <th>申报价值</th>
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
            <td>{{ $item->sku }}</td>
            <td>{{ $item->c_name }}</td>
            <td>{{ $item->product and $item->product->catalog ? $item->product->catalog->name : ''}}</td>
            <td>{{ $item->weight }}</td>
            <td>{{ $item->warehousePosition?$item->warehousePosition->name:'' }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
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

    </script>
@stop