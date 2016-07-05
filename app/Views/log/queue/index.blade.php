@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>关联ID</th>
    <th>队列</th>
    <th>描述</th>
    <th>执行时间</th>
    <th>执行结果</th>
    <th>备注</th>
    <th class="sort" data-field="created_at">创建时间</th>
@stop
@section('tableBody')
    @foreach($data as $log)
        <tr>
            <td>{{ $log->id }}</td>
            <td>{{ $log->relation_id }}</td>
            <td>{{ $log->queue }}</td>
            <td>{{ $log->description }}</td>
            <td>{{ $log->lasting }}秒</td>
            <td class="bg-{{ $log->color }}">{{ $log->result }}</td>
            <td>{{ $log->remark }}</td>
            <td>{{ $log->created_at }}</td>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="glyphicon glyphicon-filter"></i> 队列
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            @foreach(config('queue.queues') as $queue => $text)
                <li>
                    <a href="{{ DataList::filtersEncode(['queue','=',$queue]) }}">{{ $text }}</a>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="glyphicon glyphicon-filter"></i> 结果
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li>
                <a href="{{ DataList::filtersEncode(['result','=','init']) }}">初始化</a>
                <a href="{{ DataList::filtersEncode(['result','=','success']) }}">成功</a>
                <a href="{{ DataList::filtersEncode(['result','=','fail']) }}">失败</a>
            </li>
        </ul>
    </div>
@stop