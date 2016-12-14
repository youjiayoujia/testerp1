<script type="text/javascript">

    var message = {
        entry : 5, //配置初始化消息数量
        smt_order_operate : false, //速卖通订单操作
        has_workflow_message : true, //
        @if(request()->session()->get('workflow')=='keeping')
            is_workflow : true,
        @else
            is_workflow : false,
        @endif
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
                        if(message.is_workflow == false){
                            window.location.href = document.referrer;
                            return;
                        }
                        message.showNextMessage();
                        message.loadingNext();
                        message.showTip('删一条操作成功');

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
                        if(message.is_workflow == false){
                            window.location.href = document.referrer;
                            return;
                        }
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
                    if(message.is_workflow == false){
                        window.location.href = document.referrer;
                        return;
                    }
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
                        if(message.is_workflow == false){
                            window.location.href = document.referrer;
                            return;
                        }
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
