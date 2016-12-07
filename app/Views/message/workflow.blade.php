@extends('layouts.default')
@section('content')
    <!--编辑工具插件-->
    <link href="{{ asset('plugins/ueditor/themes/default/css/umeditor.css') }}" rel="stylesheet">{{-- OUR CSS --}}

    <script src="{{ asset('plugins/ueditor/umeditor.config.js') }}"></script>
    <script src="{{ asset('plugins/ueditor/umeditor.min.js') }}"></script>
    <script src="{{ asset('plugins/ueditor/lang/zh-cn/zh-cn.js') }}"></script>

    <div class="row message-group">

    </div>
@stop
@section('pageJs')
    <script type="text/javascript">
        $(document).ready(function () {
            $.ajax({
                url: "{{route('ajaxGetMsgInfo')}}",
                data: 'message_id=' + 26,
                type: 'POST',
                success: function (data) {
                    $('.message-group').append(data);
                }
            });
        });
    </script>
@stop