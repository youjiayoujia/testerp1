@extends('common.table')
@section('tableHeader')
    <th><input type="checkbox" isCheck="true" id="checkall" onclick="quanxuan()">全选</th>
    <th class="sort" data-field="id">ID</th>
    <th>收货人姓名</th>
    <th>邮箱</th>
    <th>收货人邮编</th>
    <th class="sort" data-field="whitelist">纳入白名单</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $blacklist)
        <tr>
            <td>
                <input type="checkbox" name="tribute_id" value="{{$blacklist->id}}">
            </td>
            <td>{{ $blacklist->id }}</td>
            <td>{{ $blacklist->name }}</td>
            <td>{{ $blacklist->email }}</td>
            <td>{{ $blacklist->zipcode }}</td>
            <td>{{ $blacklist->whitelist == '1' ? '是' : '否' }}</td>
            <td>{{ $blacklist->updated_at }}</td>
            <td>{{ $blacklist->created_at }}</td>
            <td>
                <a href="{{ route('orderBlacklist.show', ['id'=>$blacklist->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('orderBlacklist.edit', ['id'=>$blacklist->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $blacklist->id }}"
                   data-url="{{ route('orderBlacklist.destroy', ['id' => $blacklist->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            批量审核
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="javascript:" class="shenhe" data-status="0" data-name="黑名单">黑名单</a></li>
            <li><a href="javascript:" class="shenhe" data-status="1" data-name="白名单">白名单</a></li>
        </ul>
    </div>
@parent
@stop
@section('childJs')
    <script type="text/javascript">
        //批量审核
        $('.shenhe').click(function () {
            if (confirm("确认")) {
                var url = "{{route('listAll')}}";
                var checkbox = document.getElementsByName("tribute_id");
                var blacklist_ids = "";
                var blacklist_status = $(this).data('status');

                for (var i = 0; i < checkbox.length; i++) {
                    if(!checkbox[i].checked)continue;
                    blacklist_ids += checkbox[i].value+",";
                }
                blacklist_ids = blacklist_ids.substr(0,(blacklist_ids.length)-1);
                $.ajax({
                    url : url,
                    data : {blacklist_ids:blacklist_ids,blacklist_status:blacklist_status},
                    dataType : 'json',
                    type : 'get',
                    success:function(result){
                        window.location.reload();
                    }
                })
            }
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