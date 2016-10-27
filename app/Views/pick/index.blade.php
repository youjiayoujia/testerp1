@extends('common.table')
@section('beforeTable')
<font size='15px' color='green'>拣货单列表&nbsp;&nbsp;&nbsp;&nbsp;今日打印拣货单:{{$today_print}}张&nbsp;&nbsp;&nbsp;&nbsp;已分配:{{$allocate}}张</font>
@stop
@section('tableHeader')
    <th><input type='checkbox' name='select_all' class='select_all'></th>
    <th class='sort' data-field='id'>ID</th>
    <th>拣货单号</th>
    <th>类型</th>
    <th>物流</th>
    <th>未包装(包裹数)</th>
    <th>已包装(包裹数)</th>
    <th>状态</th>
    <th>拣货人</th>
    <th>拣货时间</th>
    <th class='sort' data-field='created_at'>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $pickList)
        <tr>
            <td><input type='checkbox' name='single[]' class='single'></td>
            <td>{{ $pickList->id }}</td>
            <td>{{ $pickList->picknum }}</td>
            <td>{{ $pickList->type == 'SINGLE' ? '单单' : ($pickList->type == 'SINGLEMULTI' ? '单多' : '多多')}}
            <td>{{ $pickList->logistic ? $pickList->logistic->name : '混合物流'}}</td>
            <td>{{ $pickList->package->where('status', 'PICKING')->count() }}</td>
            <td>{{ $pickList->package->where('status', 'PACKED')->count() }}</td>
            <td>{{ $pickList->status_name }}</td>
            <td>{{ $pickList->pickByName ? $pickList->pickByName->name : ''}}</td>
            <td>{{ $pickList->pick_at }}</td>
            <td>{{ $pickList->created_at }}</td>
            <td>
                <a href="{{ route('pickList.show', ['id'=>$pickList->id]) }}" class="btn btn-info btn-xs" title='查看'>
                    <span class="glyphicon glyphicon-eye-open"></span> 
                </a>
                @if($pickList->status == 'NONE')
                <a href="javascript:" class="btn btn-warning btn-xs print" title='打印拣货单'>
                    <span class="glyphicon glyphicon-pencil"></span> 
                </a>
                @endif
                @if($pickList->type == 'MULTI' && $pickList->status == 'PICKING')
                <a href="{{ route('pickList.inbox', ['id'=>$pickList->id])}}" class="btn btn-warning btn-xs" title='分拣'>
                    <span class="glyphicon glyphicon-pencil"></span> 
                </a>
                @endif
                @if($pickList->status == 'PRINTED')
                <button class="btn btn-info btn-xs pickBy"
                        data-toggle="modal"
                        data-target="#pickBy" title='绑定拣货人员'>
                        <span class="glyphicon glyphicon-pencil"></span> 
                </button>
                @endif
                @if(($pickList->status == 'PICKING' && $pickList->type != 'MULTI') || $pickList->status == 'PACKAGEING' || ($pickList->status == 'INBOXED' && $pickList->type == 'MULTI'))
                <a href="{{ route('pickList.package', ['id'=>$pickList->id]) }}" class="btn btn-warning btn-xs" title='包装'>
                    <span class="glyphicon glyphicon-pencil"></span> 
                </a>
                @endif
                @if($pickList->status == 'NONE')
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $pickList->id }}"
                   data-url="{{ route('pickList.destroy', ['id' => $pickList->id]) }}" title='删除'>
                    <span class="glyphicon glyphicon-trash"></span> 
                </a>
                @endif
            </td>
        </tr>
    @endforeach
    <div class="modal fade" id="pickBy" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="panel panel-default">
                    <div class="panel-heading">拣货人</div>
                    <div class="panel-body">
                    <div class='row'>
                    <form action="{{ route('pickList.confirmPickBy') }}" method='POST'>
                        {!! csrf_field() !!}
                        <div class='form-group col-lg-4'>
                            <input type='text' class='form-control col-lg-2' name='pickBy' placeholder='拣货人id'>
                            <input type='hidden' name='pickId' class='pickId' value="">
                        </div>
                        <div class='form-group col-lg-2'>
                            <button type='submit' class='btn btn-info'>确认</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <iframe src='' id='iframe_print' style='display:none'></iframe>
@stop
@section('tableToolButtons')
<div class="btn-group">
    <a href="javascript:" class="btn btn-success multiPrint" >
        批量打印拣货单
    </a>
</div>
<div class="btn-group" role="group">
    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="glyphicon glyphicon-filter"></i> 类型
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
        <li><a href="{{ DataList::filtersEncode(['type','=','SINGLE']) }}">单单</a></li>
        <li><a href="{{ DataList::filtersEncode(['type','=','SINGLEMULTI']) }}">单多</a></li>
        <li><a href="{{ DataList::filtersEncode(['type','=','MULTI']) }}">多多</a></li>
    </ul>
</div>
<div class="btn-group">
    <a href="{{ route('pickList.createPick') }}" class="btn btn-success" >
        生成拣货单
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

    $('.pickBy').click(function(){
        id = $(this).parent().parent().find('td:eq(1)').text();
        $('.pickId').val(id);
    })

    $('.multiPrint').click(function(){
        arr = new Array();
        i = 0;
        $.each($('.single:checked'), function () {
            tmp = $(this).parent().next().text();
            arr[i] = tmp;
            i++;
        })
        if (arr.length) {
            src = "{{ route('pickList.print', ['id'=>'']) }}/" + arr;
            $('#iframe_print').attr('src', src);
            $('#iframe_print').load(function(){
                $('#iframe_print')[0].contentWindow.focus();
                $('#iframe_print')[0].contentWindow.print();
            });
        } else {
            alert('未选择拣货单信息信息');
        }
    });

    $(document).on('click', '.print', function () {
        id = $(this).parent().parent().find('td:eq(1)').text();
        src = "{{ route('pickList.print', ['id'=>'']) }}/" + id;
        $('#iframe_print').attr('src', src);
        $('#iframe_print').load(function () {
            $('#iframe_print')[0].contentWindow.focus();
            $('#iframe_print')[0].contentWindow.print();
        });
    });

    $(document).on('click', '.code', function () {
        id = $(this).parent().parent().find('td:eq(1)').text();
        src = "{{ route('pickList.pickCode', ['id'=>'']) }}/" + id;
        $('#iframe_print').attr('src', src);
        $('#iframe_print').load(function () {
            $('#iframe_print')[0].contentWindow.focus();
            $('#iframe_print')[0].contentWindow.print();
        });
    });
});
</script>
@stop