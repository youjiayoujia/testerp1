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

    <div class="panel panel-primary">
        <div class="panel-heading"><strong>操作</strong></div>
        <div class="panel-body">
            <div class="row form-group">
                <div class="col-lg-6">
                    <form action="" method="POST">
                        {!! csrf_field() !!}
                        <div class="input-group">
                            <select class="form-control customer-id" name="assign_id" >
                                <option>请选择</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">
                                    </option>
                                @endforeach
                            </select>
                            <span class="input-group-btn">
                            <button class="btn btn-success" type="submit">
                                <span class="glyphicon glyphicon-random"></span> 转交
                            </button>
                        </span>
                        </div>
                    </form>
                </div>
                <div class="col-lg-6 text-right">
                    @if($driver == 'wish')
                        <a class="btn btn-primary " href="{{route('message.WishSupportReplay',['id'=>$message->id]) }}">Apeal To Wish Support</a>
                    @endif
                    <button class="btn btn-warning" type="button"
                            onclick="if(confirm('确认无需回复?')){location.href='{{ route('message.notRequireReply', ['id'=>$message->id]) }}'}">
                        <span class="glyphicon glyphicon-minus-sign"></span> 无需回复
                    </button>
                    <button class="btn btn-warning" type="button"
                            onclick="if(confirm('确认稍后处理?')){location.href='{{ route('message.dontRequireReply', ['id'=>$message->id]) }}'}">
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
                        <button class="btn btn-danger" type="button"
                                onclick="if(confirm('确认终止工作流?')){location.href='{{ route('message.endWorkflow', ['id'=>$message->id]) }}'}">
                            <span class="glyphicon glyphicon-minus-sign"></span> 终止工作流
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
@stop
@section('pageJs')
    <script type="text/javascript">
        var message = {
            entry : 3, //配置初始化消息数量
        }

        $(document).ready(function () {


            $.ajax({
                url: "{{route('ajaxGetMsgInfo')}}",
                data: 'total=' + message.entry,
                type: 'POST',
                success: function (data) {
                    $('.message-group').append(data);
                    $('.message-template').first().show();
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
            //验证

            //删除邮件dom
            $('.message-template').first().remove();
            //显示第二封
            $('.message-template').first().show();
            //回到顶部
            $('html,body').animate({scrollTop:0},'slow');
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


    </script>
@stop