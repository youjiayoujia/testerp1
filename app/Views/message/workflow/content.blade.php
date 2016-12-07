<div class="panel panel-info">
    <div class="panel-heading">
        <strong>描述：{{ str_limit($message->subject,150) }}</strong><br/>
        <small>
            {{ $message->date }} by <i>{{ $message->from_name }}</i> from {{ '<'.$message->from.'>' }}
        </small>
        To:{{$message->MessageAccountName}}
        <a href="javascript:" class="close" data-toggle="modal" data-target="#myModal">
            <small class="glyphicon glyphicon-list"></small>
        </a>
    </div>
	
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                   {!! $message->MessageInfo !!}
            </div>
        </div>
        @if(count($message->message_attanchments) > 0)
            <hr>
            @foreach($message->message_attanchments as $attanchment)
                <div class="row">
                    <div class="col-lg-12">
                        <strong>附件</strong>:
                        <a href="{{ $attanchment['filepath'] }}" target="_blank">{{ $attanchment['filename'] }}</a>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

