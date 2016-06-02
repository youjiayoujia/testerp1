@extends('common.table')
@section('tableToolButtons')
<a class="btn btn-info" id="batchedit">
    <i class="glyphicon glyphicon-ok-circle"></i> 批量修改属性
</a>
@stop{{-- 工具按钮 --}}
@section('tableHeader')
    <th><input type="checkbox" isCheck="true" id="checkall" onclick="quanxuan()"> 全选</th>
    <th class="sort" data-field="id">ID</th>
    <th class="sort" data-field="sku">sku名称</th>
    <th>图片</th>
    <th>分类</th>
    <th class="sort" data-field="name">名称</th>
    <th class="sort" data-field="c_name">中文名称</th>
    <th>供应商</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>更新时间</th>
    <th>操作</th>
@stop

@section('tableBody')

    @foreach($data as $item)
        <tr>
            
            <td><input type="checkbox" name="tribute_id"  value="{{$item->id}}" ></td>
            <td>{{ $item->id }}</td>
            <td>{{ $item->sku }}</td>
            <td>@if($item->product->default_image>0)<img src="{{ asset($item->product->image->path) }}/{{$item->product->image->name}}" width="100px" >@else无图片@endif</td>
            <td>{{ $item->product->catalog->name }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->c_name }}</td>
            <td>{{ $item->supplier->name }}</td>
            <td>{{ $item->updated_at }}</td>
            <td>{{ $item->created_at }}</td>
            <td>
                <a href="{{ route('item.show', ['id'=>$item->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 查看
                </a>
                <a href="{{ route('item.edit', ['id'=>$item->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
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
    $('#batchedit').click(function () {
        var checkbox = document.getElementsByName("tribute_id");
        var item_ids = "";
        for (var i = 0; i < checkbox.length; i++) {
            if(!checkbox[i].checked)continue;
            item_ids += checkbox[i].value+",";
        }
        item_ids = item_ids.substr(0,(item_ids.length)-1);
        //alert(item_ids);return;
        var url = "{{ route('batchEdit') }}";
        window.location.href=url+"?item_ids="+item_ids;
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