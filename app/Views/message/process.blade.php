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

            $('#save').click(function () {
              /*  alert(1231);
                return false;*/
/*                if ($('#do-chaeck').val() == 'true'){
                    var status = $('#order-operate').val();
                    if (status) {
                        $('#reply-content').submit();
                    } else {
                        alert('请先选择订单操作');
                    }
                }*/
                $('#reply-content').submit();

            });

            $('#do-review-order').click( function () {
                if(confirm('确定审核？')){
                    var order_id =  $('#order-id').val();
                    $.ajax({
                        url: "{{route('updateStatus')}}",
                        data: 'order_id=' + order_id,
                        type: 'GET',
                        success: function (data) {
                            console.log(data);
                            if(data == '1'){
                                alert('审核成功');
                                $('#do-review-order').attr('disabled',true);
                            }else{
                                alert('审核失败');
                            }
                        }
                    });
                }
            });
            $('#do-withdraw-order').click(function () {
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
                                alert('撤单成功');
                                $('#withdrawOrder').modal('hide')
                            }else{
                                alert('撤单失败');
                            }
                        }
                    });
                }
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