<div class="row message-template">
    <div class="col-lg-8">
        @include('message.workflow.content')
        @include('message.workflow.reply')

    </div>
    <div class="col-lg-4">
        @include('message.workflow.operate')
        @if($message->related)
            @include('message.workflow.orders')
        @else
            <p>ERP系统中没找到此消息关联的订单 /(ㄒoㄒ)/~~</p>
        @endif
    </div>
</div>