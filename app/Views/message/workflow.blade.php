@extends('layouts.default')
@section('content')
    <!--编辑工具插件-->
    <link href="{{ asset('plugins/ueditor/themes/default/css/umeditor.css') }}" rel="stylesheet">{{-- OUR CSS --}}

    <script src="{{ asset('plugins/ueditor/umeditor.config.js') }}"></script>
    <script src="{{ asset('plugins/ueditor/umeditor.min.js') }}"></script>
    <script src="{{ asset('plugins/ueditor/lang/zh-cn/zh-cn.js') }}"></script>

    <script src="{{ asset('plugins/pace/pace.min.js') }}"></script>
    <link href="{{ asset('plugins/pace/dataurl.css') }}" rel="stylesheet" />
    <div class="tips-content row">
    </div>
    <message class="row message-group">
    </message>

    <div class="row">
        <div class="col-lg-8">
            <div class="panel panel-primary">
                <div class="panel-heading"><strong>操作</strong></div>
                <div class="panel-body">
                    <div class="row form-group">
                        <div class="col-lg-6">
                            <form action="" method="POST">
                                {!! csrf_field() !!}
                                <div class="input-group">
                                    <select class="form-control customer-id" name="assign_id">
                                        <option value="">请选择</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="input-group-btn">
                            <button class="btn btn-success option-group" do="other-customer" type="button">
                                <span class="glyphicon glyphicon-random"></span> 转交
                            </button>
                        </span>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-6 text-right">
                            <button class="btn btn-warning option-group" do="no-reply" type="button">
                                <span class="glyphicon glyphicon-minus-sign"></span> 无需回复
                            </button>
                            <button class="btn btn-warning option-group" do="next-time" type="button">
                                <span class="glyphicon glyphicon-minus-sign"></span> 跳到下一封
                            </button>
                        </div>
                    </div>
                    <script type="text/javascript">
                        function setImg(id) {
                            var value = $('#textcontent').val();
                            $('#textcontent').val(value + " /:" + id.replace('ali_', ''));
                        }
                    </script>
                    @if(request()->session()->get('workflow')=='keeping')
                        <div class="row">
                            <div class="col-lg-12">
                                <button class="btn btn-danger option-group" do="workflow-stop" type="button">
                                    <span class="glyphicon glyphicon-minus-sign"></span> 终止工作流
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
@section('pageJs')
    <script type="text/javascript">

        var message = {
            entry : 2, //配置初始化消息数量
            smt_order_operate : false, //速卖通订单操作
            has_workflow_message : true, //
        }

        message.showNextMessage = function () {
            //删除邮件dom
            $('.message-template').first().remove();
            //显示第二封
            $('.message-template').first().show();
            //回到顶部
            $('html,body').animate({scrollTop:0},'slow');
        }

        message.workflowStop = function () {
            if(confirm('确认终止工作流？')){
                location.href='{{ route('message.endWorkflow') }}'
            }
        }

        message.noReply = function (id) {
            if(confirm('确定无需回复？')){
                $.ajax({
                    url: '{{route('message.workflowNoReply')}}',
                    data:'id='+id,
                    type: 'POST',
                    success:function (data) {
                        if(data == 1){
                            message.showNextMessage();
                            message.loadingNext();
                        }else{
                            alert('操作失败');
                        }
                    }
                });
            }
        }
        //暂时不处理，此消息跳到下一封
        message.nextTime = function (id) {
            if(confirm('确定跳到下一封？')){
               $.ajax({
                   url : '{{route('message.workflowDontRequireReply')}}',
                   data : 'id='+id,
                   type : 'POST',
                   success:function (data){
                       console.log(data);
                       if(data == 1){
                           message.showNextMessage();
                           message.loadingNext();
                           message.showTip('已经跳转到下一封');
                       }

                   }
               });
            }
        }

        message.otherCustomer = function (id,assign_id){
            if(confirm('确定转交给他人？')){
                $.ajax({
                    url: '{{route('message.workflowAssignToOther')}}',
                    data: 'id='+id+'&assign_id='+assign_id,
                    type:'POST',
                    success: function (data) {
                        if(data == 1){
                            message.showNextMessage();
                            message.loadingNext();
                            message.showTip('上一封消息转交成功！');
                        }else{
                            alert('转交失败');
                        }
                    }
                });
            }
        }

        message.showTip =  function (tip){
            $('.show-tip').remove();
            var html = '<div class="row alert alert-success show-tip" role="alert" width="1000px"> <a  class="alert-link">'+tip+'</a> </div>';
            $('.tips-content').show();
            $('.tips-content').append(html).hide(2000);
        }

        message.loadingNext = function (){
            //继续加载需要回复的邮件池
            if(message.has_workflow_message == true){
                $.ajax({
                    url: "{{route('ajaxGetMsgInfo')}}",
                    data: 'total=1',
                    type: 'POST',
                    success: function (data) {
                        if(data == -1){
                            message.has_workflow_message = false;
                            console.log('没有更多消息了');
                        }else{
                            $('.message-group').append(data);
                        }
                    }
                });

            }else{
                console.log('没有更多消息了');
            }

        }


        $(document).ready(function () {
            $('.customer-id').select2();

            //初始化工作流数据
            $.ajax({
                url: "{{route('ajaxGetMsgInfo')}}",
                data: 'total=' + message.entry,
                type: 'POST',
                success: function (data) {
                    if(data == {{config('status.ajax')['fail']}} ){
                        alert('没有发现需要处理的消息，请点击按钮，结束工作流。');
                        return;
                    }
                    $('.message-group').append(data);
                    $('.message-template').first().show();
                }
            });

            //信息处理选项
            $('.option-group').click(function () {
                var option = $(this).attr('do');
                var id = $('input[name="id"]').val();
                switch (option) {
                    case 'workflow-stop':
                        message.workflowStop();
                        break;
                    case 'no-reply':
                        message.noReply(id);
                        break;
                    case 'next-time':
                        message.nextTime(id);
                        break;
                    case 'other-customer':
                       var assign_id = $('.customer-id').val();
                       if(!assign_id){
                           alert('请选择用户');
                       }else{
                           message.otherCustomer(id,assign_id);

                       }
                        break;
                    default:
                        return false;

                }
            });
        });

        $(document).on("click", '.btn-translation', function () {
            text        = changeSome($(this).attr('need-translation-content'),1);
            content_key = $(this).attr('content-key');

            $.ajax({
                url: "{{route('ajaxGetTranInfo')}}",
                data: 'content=' + text,
                type: 'POST',
                dataType: 'JSON',
                success: function (data) {

                    if(data.content){
                        $('#content-'+content_key).text(data.content);
                    }else{
                        $('#content-'+content_key).text('翻译失败');
                    }
                }
            });
        });

        $(document).on("click", ".from-submit", function (){
            if($('.is-need-operate-order').first().val() == 'true' &&  message.smt_order_operate == false){
                alert('请先进行操作订单，再提交！');
                return;
            }
            //验证回复内容不能为空
            if(!$('textarea').val()){
                alert('请先回复的内容，再提交！');
                return;
            }
            　var param =  $('.reply-content').first().serialize();
            //异步发送
            $.ajax({
                url:'{{route('workflow.reply')}}',
                data: param,
                type: 'POST',
                success: function (data) {
                    if(data == 1){
                        //显示下一封
                        message.showNextMessage();
                        //继续加载一封的邮件池
                        message.loadingNext();
                        message.showTip('上一封消息已经回复');
                    }
                }
            });
        });

        $(document).on("click", "#do-review-order", function (){
            if(confirm('确定审核？')){
                var order_id =  $('#order-id').val();
                $.ajax({
                    url: "{{route('updateStatus')}}",
                    data: 'order_id=' + order_id,
                    type: 'GET',
                    success: function (data) {
                        if(data == '1'){
                            message.smt_order_operate = true;
                            alert('审核成功');
                            $('#do-review-order').attr('disabled',true);
                        }else{
                            alert('审核失败');
                        }
                    }
                });
            }
        });

        $(document).on("click", "#do-withdraw-order", function (){
            if(confirm('确定撤单？')){
                var order_id = $('#order-id').val();
                var withdraw = $('#withdraw').val();
                var withdraw_reason = $('#withdraw_reason').val();

                if(withdraw == 'NULL' || withdraw_reason == ''){
                    alert('请编辑撤单原因，选择撤单类型');
                    return false;
                }

                $.ajax({
                    url: "{{route('ajaxWithdraw')}}",
                    data: 'id=' + order_id+'&withdraw='+withdraw+'&withdraw_reason='+withdraw_reason,
                    type: 'POST',
                    success: function (data) {
                        if(data == '1'){
                            message.smt_order_operate = true;
                            alert('撤单成功');
                            $('#withdrawOrder').modal('hide')
                        }else{
                            alert('撤单失败');
                        }
                    }
                });
            }
        });

        function getTransInfo(content) {
            $.ajax({
                url: "{{route('ajaxGetTranInfo')}}",
                data: 'content=' + content,
                type: 'POST',
                dataType: 'JSON',
                success: function (data) {
                    return data.content ? data.content : false;
                }
            });
        }

        function changeSome(text,type){
            if(type==1){

                text=text.replace(/\?/g, "^");
            }
            if(type==2){
                text=text.replace(/\^/g, "?");
            }

            return text;
        }

        function changeChildren(parent) {
            $('#children').html('');
            $('#children').append('<option>请选择类型</option>');
            $('#templates').html('');
            $('#templates').append('<option>请选择</option>');
            if (parent.val() != "") {
                $.post(
                    '{{ route('messageTemplateType.ajaxGetChildren') }}',
                    {id: parent.val()},
                    function (response) {
                        if (response != 'error') {
                            $.each(response, function (n, child) {
                                $('#children').append('<option value="' + child.id + '">' + child.name + '</option>');
                            });
                        }
                    }, 'json'
                );
            }
        }


        function changeTemplateType(type) {
            $('#templates').html('');
            $('#templates').append('<option>请选择</option>');
            $.post(
                '{{ route('messageTemplateType.ajaxGetTemplates') }}',
                {id: type.val()},
                function (response) {
                    if (response != 'error') {
                        $.each(response, function (n, template) {
                            $('#templates').append('<option value="' + template.id + '">' + template.name + '</option>');
                        });
                    }
                }, 'json'
            );
        }

        /**
         * type mail邮件 或者 text文本
         */
        function changeTemplate(template,type) {
            $.post(
                '{{ route('messageTemplate.ajaxGetTemplate') }}',
                {id: template.val()},
                function (response) {
                    if (response != 'error') {
                        //替换字符串
                        //response['content']=response['content'].replace("署名", assign_name);

                        if(type == 'email'){
                            editor.setContent(response['content']);
                        }else if(type == 'text'){
                            $('#textcontent').val(response['content']);
                        }
                        //记录回复邮件的类型
                        $('#tem_type').val(response.type_id);
                    }
                }, 'json'
            );
        }
        /**
         * wish support
         * @param id
         */
        function wishSupportReplay(id){
            if(confirm('确定要进行操作？')){
                $.ajax({
                    url: "{{route('message.WishSupportReplay')}}",
                    data: 'id=' + id,
                    type: 'POST',
                    success: function (data) {
                        if(data == 1){
                            message.showNextMessage();
                            message.showTip('请求wish,回复成功');
                            message.loadingNext();

                        }else if(data == -1){
                            alert('请求wish,回复失败');

                        }
                    }
                });

            }

        }



    </script>
@stop