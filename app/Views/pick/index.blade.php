@extends('common.table')
@section('tableHeader')
    <th class='sort' data-field='id'>ID</th>
    <th>拣货单号</th>
    <th>类型</th>
    <th>物流</th>
    <th>状态</th>
    <th>拣货人</th>
    <th class='sort' data-field='created_at'>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $pickList)
        <tr>
            <td>{{ $pickList->id }}</td>
            <td>{{ $pickList->picknum }}</td>
            <td>{{ $pickList->type == 'SINGLE' ? '单单' : ($pickList->type == 'SINGLEMULTI' ? '单多' : '多多')}}
            <td>{{ $pickList->logistic ? $pickList->logistic->logistics_type : '混合物流'}}</td>
            <td>{{ $pickList->status_name }}</td>
            <td>{{ $pickList->pickByName ? $pickList->pickByName->name : ''}}</td>
            <td>{{ $pickList->created_at }}</td>
            <td>
                <a href="{{ route('pickList.show', ['id'=>$pickList->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('pickList.print', ['id'=>$pickList->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 打印拣货单
                </a>
                @if($pickList->type == 'MULTI' && $pickList->status == 'PICKING')
                <a href="{{ route('pickList.inbox', ['id'=>$pickList->id])}}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 分拣
                </a>
                @endif
                @if(($pickList->status == 'PICKING' && $pickList->type != 'MULTI') || $pickList->status == 'PACKAGEING' || ($pickList->status == 'INBOXED' && $pickList->type == 'MULTI'))
                <a href="{{ route('pickList.package', ['id'=>$pickList->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 包装
                </a>
                @endif
                @if($pickList->status == 'NONE')
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $pickList->id }}"
                   data-url="{{ route('pickList.destroy', ['id' => $pickList->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
                @endif
            </td>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')
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
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@stop