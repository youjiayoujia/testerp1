@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <div class="panel panel-primary">
        <div class="panel-heading"><strong>回复:</strong></div>
        <div class="panel-body">
            <form action="{{ route('message.reply', ['id'=>$message->id]) }}" method="POST" onsubmit="return check()" ;>
                {!! csrf_field() !!}
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <select class="form-control" onchange="changeChildren($(this));">
                                <option>请选择一级类型</option>
                                @foreach($parents as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <select class="form-control" id="children" onchange="changeTemplateType($(this));">
                                <option>请选择类型</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            @if($message->drives == 'amazon')
                                <select class="form-control" id="templates" onchange="changeTemplate($(this),'email');">
                            @else
                                <select class="form-control" id="templates" onchange="changeTemplate($(this),'text');">
                             @endif
                                <option>请选择模版</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2" id="loadingDiv">
                        <img src="{{ asset('loading.gif') }}" width="30" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <input type="text" class="form-control" name="to" value="{{ $message->from_name }}"/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <input type="text" id="to_email" class="form-control" name="to_email"
                                   value="{{ $message->from }}"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <input type="text" class="form-control" name="title" value="Re: {{ $message->subject }}"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12" id="templateContent">
                        <div class="form-group">

                            <textarea class="form-control" @if($message->drives == 'amazon') id="editor" @else id="textcontent" @endif
                             rows="16" name="content" style="width:100%;height:400px;">{{ old('content') }}</textarea>
                        </div>
                    </div>
                </div>

                @if($message->drives == 'amazon')
                    <script type="text/javascript" charset="utf-8"> var editor = UM.getEditor('editor'); </script>
                @endif
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <button id="save" type="submit" class="btn btn-primary">回复</button>
                            <div style="float: right;">
                            <?php
                            for ($i = 0; $i < 54; $i++) {
                                echo '<img class="aliimg" id="ali_' . str_pad($i, 3, "0", STR_PAD_LEFT) . '" onClick="setImg(this.id)" width="20" src="http://i02.i.aliimg.com/wimg/feedback/emotions/' . $i . '.gif" />&nbsp;';
                                if (($i + 1) % 9 == 0) echo '<br />';
                            }
                            ?>
                            </div>
                        </div>
                    </div>




                </div>
                <input type="hidden" id="tem_type" name="type_id">
            </form>
        </div>
    </div>
<script>
    function check() {
        var to_email = $("#to_email").val();
        //对电子邮件的验证
        if (!to_email.indexOf("@")) {
            alert('提示\n\n请输入有效的E_mail！');
            return false;
        }
        return true;
    }
</script>
<div class="panel panel-primary">
    <div class="panel-heading"><strong>操作</strong></div>
    <div class="panel-body">
        <div class="row form-group">
            <div class="col-lg-6">
                <form action="{{ route('message.assignToOther', ['id'=>$message->id]) }}" method="POST">
                    {!! csrf_field() !!}
                    <div class="input-group">
                        <select class="form-control" name="assign_id">
                            <option>请选择</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ ($message->last and ($user->id == $message->last->assign_id)) ? 'selected' : '' }}>
                                    {{ ($message->last and ($user->id == $message->last->assign_id)) ? '历史客服: ' : '' }}{{ $user->name }}
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
            window.onload = function () {
                $('#loadingDiv').hide();

                // 加载页面时判断是否有数据并加载\localStorage["a"]
                var kai = $("#editor").val();
                var content1 = localStorage["http_crm_jinjidexiaoxuesheng_com_message_processeditor-drafts-data"];
                if (!window.localStorage) {
                    console.log("error");
                } else {
                    if (localStorage["http_crm_jinjidexiaoxuesheng_com_message_processeditor-drafts-data"] != null) {
                        editor.setContent(content1)
                        //$("#editor").val(content1)
                    } else {
                        $("#editor").val("");
                    }
                }
            };
            // 点击发表时删除数据
            document.getElementById("save").onclick = function () {
                editor.value = "";
                if (!window.localStorage) {
                    UserData.remove('editor-text');
                } else {
                    localStorage.removeItem('editor-text');
                }
            };

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