@extends('common.table')
@section('tableToolButtons')
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            批量操作
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="javascript:" data-toggle="modal" data-target="#myModal-refuse">拒绝处理</a></li>
            <li><a href="javascript:" id="do_edit" data-status="notpass" >标记平台已处理</a></li>
            <li><a href="javascript:" id="do_edit" data-status="notpass" >平台延迟发货界面</a></li>
            <li><a href="javascript:" id="do_edit" data-status="notpass" >平台处理界面</a></li>
        </ul>
    </div>
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="glyphicon glyphicon-filter"></i> ====账号====
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            @foreach($accounts as $account)
                <li><a href="{{ DataList::filtersEncode(['account_id','=',$account->id]) }}">{{$account->account}}</a></li>
            @endforeach
        </ul>
    </div>
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="glyphicon glyphicon-filter"></i> =====纠纷原因=====
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            @foreach($reasonFilter as $reason)
                <li><a href="{{ DataList::filtersEncode(['reasonChinese','=',$reason->reasonChinese]) }}">{{$reason->reasonChinese}}</a></li>
            @endforeach
        </ul>
    </div>

@stop
@section('tableHeader')
    <th><input type="checkbox" name="select_all" class="select_all"></th>
    <th class="sort" data-field="id">序号</th>
    <th>订单号</th>
    <th>ERP 信息</th>
    <th>纠纷类型</th>
    <th>账号</th>
    <th>纠纷原因</th>
    <th>纠纷创建时间</th>
    <th>纠纷修改时间</th>

    <th>抓取时间</th>


    <th>操作</th>

@stop
@section('tableBody')

    @foreach($data as $issue)
        <tr>
            <td>
                <input type="checkbox" name="issue_ids[]" value="{{$issue->id}}" class="issue_ids">
            </td>
            <td>{{$issue->id}}</td>
            <td>{{$issue->orderId}}</td>
            <td>ERP订单物流 </td>
            <td>{{$issue->IssueTypeName}}</td>
            <td>{{$issue->accountName}}</td>
            <td>{{$issue->reasonChinese}}</td>

            <td>{{$issue->gmtCreate}}</td>
            <td>{{$issue->gmtModified}}</td>

            <td>{{$issue->created_at}}</td>



            <td>
                <a href="AliexpressIssue/{{$issue->id}}/edit" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 详情
                </a>
            </td>
        </tr>
    @endforeach
    <!--模态框-->
    <div class="modal fade" id="myModal-refuse" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">拒绝处理界面</h4>
                </div>
                <div class="modal-body">
                    <form  action="{{route('aliexpress.doRefuseIssues')}}" method="post" id="refuse">
                        {!! csrf_field() !!}
                        <input type="hidden" name="checked-ids" value="">
                        <div class="form-group">
                            <label for="remark" class="control-label">拒绝原因：(<font color="red">会以订单留言方式发送，长度不能超过200字符</font>)</label>
                            <textarea class="form-control" rows="3" id="remark" name="remark"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭
                    </button>
                    <button type="button" class="btn btn-primary form-submit" next-do="refuse">
                        提交
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!--模态框-->
@stop
@section('childJs')
<script>
$(document).ready(function(){
    $('.form-submit').click(function(){
        var next = $(this).attr('next-do');
        var ids = new Array();
        var i = 0;
        switch(next){
            case 'refuse':
                $(".issue_ids:checked").each(function(){
                    ids[i] = $(this).val();
                    i++;
                });
                break;
            default:
                return;
        }

        if(ids.length != 0){
            $("input[name='checked-ids']").val(ids);
            $("#refuse").submit();
        }else{
            alert('请先选中要处理的纠纷');
            return;
        }

    });
});
</script>
@stop
