@extends('common.table')
@section('tableToolButtons')
    <div class="btn-group">
        <a class="btn btn-success" href="{{ route('message.startWorkflow') }}">
            <i class="glyphicon glyphicon-play"></i> 开始工作流
        </a>

        <div class="btn-group" role="group">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="glyphicon glyphicon-pushpin"></i>
                我的
                @if(request()->user()->process_messages > 0)
                    <span class="badge">{{ request()->user()->process_messages }}</span>
                @endif
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a href="{{ DataList::filtersEncode([['assign_id','=',request()->user()->id],['status','=','PROCESS']], true) }}">
                        待处理
                        @if(request()->user()->process_messages > 0)
                            <span class="badge">{{ request()->user()->process_messages }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ DataList::filtersEncode([['assign_id','=',request()->user()->id],['status','=','COMPLETE']], true) }}">
                        已处理
                    </a>
                </li>
            </ul>
        </div>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="glyphicon glyphicon-filter"></i> 过滤
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="{{ DataList::filtersEncode(['status','=','UNREAD']) }}">未读</a></li>
                <li><a href="{{ DataList::filtersEncode(['status','=','PROCESS']) }}">待处理</a></li>
                <li><a href="{{ DataList::filtersEncode(['status','=','COMPLETE']) }}">已处理</a></li>
            </ul>
        </div>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="glyphicon glyphicon-filter"></i> 客服
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                @foreach($users as $user)
                    <li><a href="{{ DataList::filtersEncode(['assign_id','=',$user->id]) }}">{{$user->name}}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
@stop
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>账号</th>
    <th>主题</th>
    <th>状态</th>
    <th class="sort" data-field="from_name">发信人</th>
    <th class="sort" data-field="from">发信邮箱</th>
    <th class="sort" data-field="date">发信日期</th>
    <th class="sort">客服</th>
    <th class="sort" data-field="created_at">创建日期</th>
    <th class="sort" data-field="updated_at">更新日期</th>
    <th>延时</th>
    <th>操作</th>
    <th>无需回复</th>
@stop
@section('tableBody')
    @foreach($data as $message)
        <tr>
            <td>{{ $message->id }}</td>
            <td>{{ $message->account->account }}</td>
            <td>
                {!! $message->label_text !!}
                @if($message->status=='UNREAD')
                    <strong>{{ $message->subject }}</strong>
                @else
                    {{ $message->subject }}
                @endif
            </td>
            <td>{{ $message->status_text }}</td>
            <td>{{ $message->from_name }}</td>
            <td>{{ $message->from }}</td>
            <td>{{ date('Y-m-d H:i:s',strtotime($message->date)) }}</td>
            <td>{{ $message->assign_id ? $message->assigner->name : '未分配' }}</td>
            <td>{{ $message->created_at }}</td>
            <td>{{ $message->updated_at }}</td>
            <td>
                <?php
                if($message->status == 'COMPLETE'){
                ?>
                {{ ceil((strtotime($message->updated_at)-strtotime($message->created_at))/60) }}
                <?php
                }else{
                ?>
                {{ $message->delay }}
                <?php
                }
                ?>
                分</td>
            <td>
                @if($message->status == 'UNREAD')
                    <a href="{{ route('message.process', ['id'=>$message->id]) }}" class="btn btn-primary btn-xs">
                        <span class="glyphicon glyphicon-play"></span> 开始处理
                    </a>
                @endif
                @if($message->status == 'PROCESS' and $message->assign_id == request()->user()->id)
                    <a href="{{ route('message.process', ['id'=>$message->id]) }}" class="btn btn-warning btn-xs">
                        <span class="glyphicon glyphicon-pause"></span> 继续处理
                    </a>
                @endif
                @if($message->status == 'COMPLETE' or request()->user()->group == 'super')
                    <a href="{{ route('message.show', ['id'=>$message->id]) }}" class="btn btn-info btn-xs">
                        <span class="glyphicon glyphicon-eye-open"></span> 查看
                    </a>
                @endif
            </td>
            <td>
                @if($message->status == 'COMPLETE' or request()->user()->group == 'super' )

                @else
                    @if($message->status == 'PROCESS' and $message->assign_id == request()->user()->id)
                        <button class="btn btn-warning btn-xs" style="background-color: #88775A;border-color: #FFFFFF;" type="button" onclick="if(confirm('确认无需回复?')){location.href='{{ route('message.notRequireReply_1', ['id'=>$message->id]) }}'}">
                            <span class="glyphicon glyphicon-minus-sign"></span> 无需回复
                        </button>
                    @endif
                    @if($message->status == 'UNREAD')
                        <button class="btn btn-warning btn-xs" style="background-color: #88775A;border-color: #FFFFFF;" type="button" onclick="if(confirm('确认无需回复?')){location.href='{{ route('message.notRequireReply_1', ['id'=>$message->id]) }}'}">
                            <span class="glyphicon glyphicon-minus-sign"></span> 无需回复
                        </button>
                    @endif

                @endif

            </td>
        </tr>

    @endforeach
@stop