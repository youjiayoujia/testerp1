@extends('layouts.default')
@section('content')
    <!--编辑工具插件-->
    <link href="{{ asset('plugins/ueditor/themes/default/css/umeditor.css') }}" rel="stylesheet">{{-- OUR CSS --}}

    <script src="{{ asset('plugins/ueditor/umeditor.config.js') }}"></script>
    <script src="{{ asset('plugins/ueditor/umeditor.min.js') }}"></script>
    <script src="{{ asset('plugins/ueditor/lang/zh-cn/zh-cn.js') }}"></script>

    <script src="{{ asset('plugins/pace/pace.min.js') }}"></script>
    <link href="{{ asset('plugins/pace/dataurl.css') }}" rel="stylesheet" />
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
                                        <option>请选择</option>
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
                            {{--                    @if($driver == 'wish')
                                                    <a class="btn btn-primary " href="{{route('message.WishSupportReplay',['id'=>$message->id]) }}">Apeal To Wish Support</a>
                                                @endif--}}
                            <button class="btn btn-warning option-group" do="no-reply" type="button">
                                <span class="glyphicon glyphicon-minus-sign"></span> 无需回复
                            </button>
                            <button class="btn btn-warning option-group" do="next-time" type="button">
                                <span class="glyphicon glyphicon-minus-sign"></span> 稍后处理
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
            entry : 3, //配置初始化消息数量
        }
        message.workflowStop = function (){
            if(confirm('确认终止工作流？')){
                location.href='{{ route('message.endWorkflow') }}'
            }
        }
        message.noReply = function (id){
            if(confirm('确定无需回复？')){
                $.ajax({
                    url: '{{route('message.endWorkflow',['id' => '＋id＋'])}}',
                    data:{},
                    type: 'POST',
                    success:function (data) {
                        console.log(data);
                    }

                });
            }

        }
        message.nextTime = function (id){
            if(confirm('确定稍后回复？')){

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
            //验证回复内容不能为空
            if(!$('textarea').val()){
                alert('请先回复的内容，再提交！');
                return;
            }
            　var param =  $('.reply-content').first().serialize();

            //删除邮件dom
            $('.message-template').first().remove();
            //显示第二封
            $('.message-template').first().show();
            //回到顶部
            $('html,body').animate({scrollTop:0},'slow');

            //异步发送
            $.ajax({
                url:'{{route('workflow.reply')}}',
                data: param,
                type: 'POST',
                success: function (data) {
                    console.log(data);
                }

            });

            //继续加载需要回复的邮件池
            $.ajax({
                url: "{{route('ajaxGetMsgInfo')}}",
                data: 'total=' + message.entry,
                type: 'POST',
                success: function (data) {
                    console.log('新邮件肥来了');
                    $('.message-group').append(data);
                    // $('.message-template').first().show();
                }
            });
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
                        /*
                         $('#templateContent').html('<div class="form-group"><textarea rows="16" name="content" style="width:100%;height:400px;">'+response['content']+'</textarea></div>');
                         */
                        //记录回复邮件的类型
                        $('#tem_type').val(response.type_id);
                    }
                }, 'json'
            );
        }



    </script>
@stop