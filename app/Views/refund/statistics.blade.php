@extends('common.detail')

@section('detailBody')

    <div class="row">
        <div class="col-lg-8">
            <div class="row">
                <div class="col-lg-2">
                    <label>方式:</label>
                    <select name="time-type" class="form-control">
                        <option value="1">交易时间</option>
                        <option value="2">退款时间</option>
                    </select>
                </div>
                <div class="col-lg-2 form-group">
                    <label>开始时间:</label>
                    <input type="text" value="" class="form-control datetime_select" name="start" placeholder="开始时间">
                </div>
                <div class="col-lg-2 form-group">
                    <label>结束区间:</label>
                    <input type="text" value="" class="form-control datetime_select" name="end" placeholder="结束时间">
                </div>

        </div>
        <div class="row">
            <div class="col-lg-2">
                <label>渠  道:</label>
                <select name="channel" id="channel" class="form-control js-example-basic-multiple" multiple="multiple">
                    @foreach($channels as $channel)
                        <option value="{{$channel->id}}">{{$channel->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2">
                <label>账   号:</label>
                <select name="account" class="form-control  js-example-basic-multiple" multiple="multiple" id="account">
                    <option value="all">==此渠道全部账号==</option>
                </select>

            </div>
            <div class="col-lg-2">
                <label>退款原因：</label>
                <select  class="form-control js-example-basic-multiple" multiple="multiple" name="select_channel">
                    @foreach(config('order.reason') as $value => $name)
                        <option value="{{$value}}" class='aa'>{{$name}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        </div>
        <div class="col-lg-4">

                <div class="col-lg-2">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            选择操作
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:" class="export" data-status="pass" >按SKU导出</a></li>
                            <li><a href="javascript:" id="do_edit" data-status="notpass" >按原因导出</a></li>
                            <li><a href="javascript:" id="do_edit" data-status="notpass" >导出详情</a></li>
                        </ul>
                    </div>
                </div>

        </div>

    </div>

@stop
@section('pageJs')
    <link href="{{ asset('css/multiple-select.css') }}" rel="stylesheet">
    <script src="{{ asset('js/multiple-select.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.datetime_select').datetimepicker({theme: 'default'});
            $(".js-example-basic-multiple").select2();
            $("#channel").change(function(){
                getChannelAccount($(this).val());

            });
        });

        function getChannelAccount(channelId){
            $.ajax({
                url:"{{route('refund.getChannelAccount')}}",
                dataType:'JSON',
                type:'POST',
                data:{channel_id:channelId},
                success:function($returnInfo){
                    $('#account').html('');
                    $('#account').append('<option value="all"> ==此渠道全部账号== </option>');
                    $.each($returnInfo,function (index,item) {
                        $('#account').append('<option value="' + item.id + '">' + item.account + '</option>');
                    });
                }
            });
        }

    </script>
@stop