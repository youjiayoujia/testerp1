@extends('layouts.default')
@section('content')
    <!--编辑工具插件-->
    <link href="{{ asset('plugins/ueditor/themes/default/css/umeditor.css') }}" rel="stylesheet">{{-- OUR CSS --}}

    <script src="{{ asset('plugins/ueditor/umeditor.config.js') }}"></script>
    <script src="{{ asset('plugins/ueditor/umeditor.min.js') }}"></script>
    <script src="{{ asset('plugins/ueditor/lang/zh-cn/zh-cn.js') }}"></script>
    <div class="row">
        <div class="col-lg-8">
            @include('message.process.content')

            @if($driver == 'wish')
                @include('message.process.wish_order_detail')
            @endif

            @include('message.process.reply')
        </div>
        <div class="col-lg-4">
            @include('message.process.operate')
            @if($message->related)
                @include('message.process.orders')
            @else
                @include('message.process.relate')
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">日志信息</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <strong>创建时间</strong>: {{ $message->created_at }}
                        </div>
                        <div class="col-lg-6">
                            <strong>更新时间</strong>: {{ $message->updated_at }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('pageJs')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.btn-translation').click(function(){
                text =changeSome($(this).attr('need-translation-content'),1);

                $.ajax({
                    url: "{{route('ajaxGetTranInfo')}}",
                    data: 'content=' + text,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function (data) {

                        if(data.content){
                            $(this).prev().show().addClass('alert-success');
                            $(this).prev().children('.content').text(data.content);
                            $(this).hide();
                        }else{
                            console.log(data.content);

                            $(this).prev().show().addClass('alert-danger');
                            $(this).prev().children('.content').text('翻译失败');
                            $(this).hide();

                        }
                    }
                });
            });
        });

        function changeSome(text,type){
            if(type==1){

                text=text.replace(/\?/g, "^");
            }
            if(type==2){
                text=text.replace(/\^/g, "?");
            }

            return text;
        }
        var tran_info = false;

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

        function changeChildren(parent) {
            $('#loadingDiv').show();
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
            $('#loadingDiv').hide();
        }

        function changeTemplateType(type) {
            $('#loadingDiv').show();
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
            $('#loadingDiv').hide();
        }

        /**
         * type mail邮件 或者 text文本
         */
        function changeTemplate(template,type) {
            $('#loadingDiv').show();
            $.post(
                    '{{ route('messageTemplate.ajaxGetTemplate') }}',
                    {id: template.val()},
                    function (response) {
                        var messgae_id="{{ $message->id }}";
                        var assign_name="{{ $message->assigner->name_en }}";
                        if (response != 'error') {
                            //替换字符串
                            response['content']=response['content'].replace("署名", assign_name);

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
                        $('#loadingDiv').hide();
                    }, 'json'
            );
        }
    </script>


@stop