@extends('common.table')
@section('tableHeader')
    <th class='sort' data-field='id'>ID</th>
    <th class='sort' data-field='allotment_id'>调拨单号</th>
    <th>调出仓库</th>
    <th>调入仓库</th>
    <th>备注</th>  
    <th>调拨人</th>
    <th>调拨时间</th>
    <th>调拨状态</th>
    <th class='sort' data-field='check_man_id'>审核人</th>
    <th>审核状态</th>
    <th class='sort' data-field='check_time'>审核时间</th>
    <th>物流方式</th>
    <th>物流号</th>
    <th>物流方式</th>
    <th>对单人</th>
    <th>对单时间</th>
    <th>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $allotment)
        <tr>
            <td>{{ $allotment->id }}</td>
            <td>{{ $allotment->allotment_id }}</td>
            <td>{{ $allotment->outwarehouse->name }}</td>
            <td>{{ $allotment->inwarehouse->name }}</td>
            <td>{{ $allotment->remark }}</td>
            <td>{{ $allotment->allotment_man_id }}</td>
            <td>{{ $allotment->allotment_time }}</td>
            <td>{{ $allotment->status_name }}</td>
            <td>{{ $allotment->check_man_id }}</td>
            <td>{{ $allotment->check_status == 'N' ? '未审核' : '已审核' }}</td>
            <td>{{ $allotment->check_time }}</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>{{ $allotment->checkform_man_id }}</td>
            <td>{{ $allotment->checkform_time }}</td>
            <td>{{ $allotment->created_at }}</td>
            <td>
                <a href="{{ route('stockAllotment.show', ['id'=>$allotment->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                @if($allotment->check_status == 'N')
                <a href="{{ route('stockAllotment.edit', ['id'=>$allotment->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                @endif
                @if($allotment->allotment_status == 'new' && $allotment->check_status == 'N')
                <a href="javascript:" class="btn btn-success btn-xs check_time" data-id="{{ $allotment->id }}">
                    <span class="glyphicon glyphicon-pencil"></span>
                    审核调拨单
                </a>
                @endif
                @if($allotment->check_status == 'Y' && $allotment->allotment_status == 'new')
                <a href="javascript:" class="btn btn-success btn-xs pick" data-id="{{ $allotment->id }}">
                    <span class="glyphicon glyphicon-pencil"></span>生成拣货单
                </a>
                @endif
                @if($allotment->allotment_status == 'pick')
                <a href="javascript:" class="btn btn-success btn-xs new" data-id="{{ $allotment->id }}">
                    <span class="glyphicon glyphicon-pencil"></span>
                    new
                </a>
                <a href="javascript:" class="btn btn-success btn-xs check_out" data-id="{{ $allotment->id }}">
                    <span class="glyphicon glyphicon-pencil"></span>
                    确认出库
                </a>
                @endif
                @if($allotment->check_status == 'Y' && ($allotment->allotment_status == 'out' || $allotment->allotment_status == 'check'))
                    @if($allotment->allotment_status != 'over')
                    <a href="{{ route('checkform', ['id'=>$allotment->id]) }}" class="btn btn-success btn-xs">
                        <span class="glyphicon glyphicon-eye-open"></span> 对单
                    </a>
                    @endif
                @endif
                @if($allotment->check_status == 'N')
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $allotment->id }}"
                   data-url="{{ route('stockAllotment.destroy', ['id' => $allotment->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
                @endif
            </td>
        </tr>
    @endforeach
@stop
@section('childJs')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
<script type='text/javascript'>
$(document).ready(function(){   
    $(document).on('click', '.check_time', function(){
        obj = $(this).parent().parent();
        tmp = $(this);
        if(obj.find('td:eq(9)').text() == '未审核') {
            if(confirm('确认审核?')) {
                tmp.prev().hide();
                id = $(this).data('id');
                str = "data-id="+id;
                tmp.after(" <a href='javascript:' class='btn btn-success btn-xs pick' "+str+">\
                <span class='glyphicon glyphicon-eye-open'></span>生成拣货单</a>");
                tmp.hide();
                obj.find('.delete_item').hide();
                $.ajax({
                    url:"{{ route('allotmentcheck') }}",
                    data:{id:id},
                    dataType:'json',
                    type:'get',
                    success:function(result){
                        tmp.html('<span class="glyphicon glyphicon-pencil"></span>已审核');
                        obj.find('td:eq(10)').text(result);
                        obj.find('td:eq(9)').text('已审核');
                    }
                });
            }
        } else {
            alert('已审核');
        }
    });

    $(document).on('click', '.pick', function(){
        obj = $(this).parent().parent();
        tmp = $(this);
        id = $(this).data('id');
        $.ajax({
                url:"{{ route('allotmentpick') }}",
                data:{id:id},
                dataType:'json',
                type:'get',
                success:function(result){
                    obj.find('td:eq(7)').text('拣货中');
                    tmp.after("<a href='javascript:' class='btn btn-success btn-xs new' data-id="+id+"><span class='glyphicon glyphicon-pencil'></span>new</a> <a href='javascript:' class='btn btn-success btn-xs check_out' data-id="+id+"><span class='glyphicon glyphicon-pencil'></span>确认出库</a>");
                    tmp.hide();
                }
            });
    });

    $(document).on('click', '.new', function(){
        id = $(this).data('id');
        obj = $(this);
        td = obj.parent();
        $.ajax({
            url:"{{ route('allotmentnew') }}",
            data:{id:id},
            dataType:'json',
            type:'get',
            success:function(result) {
                str = "{{route('stockAllotment.show', ['id'=>''])}}/"+id;
                str1 = "{{ route('stockAllotment.edit', ['id'=>'']) }}/"+id;
                str2 = "{{route('stockAllotment.destroy', ['id'=>''])}}/"+id;
                td.html("<a href='"+str+"' class='btn btn-info btn-xs'>\
                    <span class='glyphicon glyphicon-eye-open'></span> 查看\
                </a> <a href='"+str1+"' class='btn btn-warning btn-xs'>\
                    <span class='glyphicon glyphicon-pencil'></span> 编辑\
                </a> <a href='javascript:' class='btn btn-success btn-xs check_time' data-id='"+id+"'>\
                    <span class='glyphicon glyphicon-pencil'></span>\
                    审核调拨单\
                </a> <a href='javascript:' class='btn btn-danger btn-xs delete_item'\
                   data-id='"+id+"'\
                   data-url='"+str2+"'>\
                    <span class='glyphicon glyphicon-trash'></span> 删除\
                </a>");
                td.parent().find('td:eq(7)').text('new');
                td.parent().find('td:eq(9)').text('未审核');
            }
        });
    });

    $(document).on('click', '.check_out', function(){
        id = $(this).data('id');
        obj = $(this);
        td = obj.parent();
        block = obj.parent().parent();
        $.ajax({
            url:"{{ route('allotmentcheckout') }}",
            data:{id:id},
            dataType:'json',
            type:'get',
            success:function(result) {
                block.find('td:eq(7)').text('出库');
                td.empty();
                str = "{{route('stockAllotment.show', ['id'=>''])}}/"+id;
                str1 = "{{ route('checkform', ['id'=>'']) }}/"+id;
                td.html("<a href='"+str+"' class='btn btn-info btn-xs'>\
                    <span class='glyphicon glyphicon-eye-open'></span> 查看\
                </a> <a href='"+str1+"' class='btn btn-success btn-xs'>\
                        <span class='glyphicon glyphicon-eye-open'></span> 对单\
                    </a>")
            }
        });
    });
});
</script>
@stop