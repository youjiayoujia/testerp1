@extends('common.table')
@section('tableHeader')
    <th><input type="checkbox" isCheck="true" id="checkall" onclick="quanxuan()">全选</th>
    <th class="sort" data-field="id">ID</th>
    <th>平台</th>
    <th>订单号</th>
    <th>姓名</th>
    <th>邮箱</th>
    <th>邮编</th>
    <th>类型</th>
    <th>订单总数</th>
    <th>退款订单数</th>
    <th>退款率</th>
    <th>备注</th>
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
            <td>{{ $blacklist->channel->name }}</td>
            <td>{{ $blacklist->ordernum }}</td>
            <td>{{ $blacklist->name }}</td>
            <td>{{ $blacklist->email }}</td>
            <td>{{ $blacklist->zipcode }}</td>
            <td>{{ $blacklist->type_name }}</td>
            <td>{{ $blacklist->total_order }}</td>
            <td>{{ $blacklist->refund_order }}</td>
            <td>{{ $blacklist->refund_rate }}</td>
            <td>{{ $blacklist->remark }}</td>
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
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="glyphicon glyphicon-filter"></i> 查询类型
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="{{ DataList::filtersEncode(['type', '=', 'CONFIRMED']) }}">确认黑名单</a></li>
            <li><a href="{{ DataList::filtersEncode(['type', '=', 'SUSPECTED']) }}">疑似黑名单</a></li>
            <li><a href="{{ DataList::filtersEncode(['type', '=', 'WHITE']) }}">白名单</a></li>
        </ul>
    </div>
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            批量审核
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="javascript:" class="shenhe" data-status="CONFIRMED" data-name="确认黑名单">确认黑名单</a></li>
            <li><a href="javascript:" class="shenhe" data-status="SUSPECTED" data-name="疑似黑名单">疑似黑名单</a></li>
            <li><a href="javascript:" class="shenhe" data-status="WHITE" data-name="白名单">白名单</a></li>
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
