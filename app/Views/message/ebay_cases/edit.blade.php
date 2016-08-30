@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <span class="label label-success">{{$case->open_reason}}</span><br/>
                    <strong>CaseId:&nbsp;{{$case->case_id}}</strong>
                    <small>
                        <i><strong>{{ $case->buyer_id }}</strong></i>&nbsp;&nbsp;&nbsp; Date:&nbsp;{{ $case->creation_date }}
                    </small><br/>
                    <strong>Title:&nbsp;{{$case->item_title}}</strong><br/>

                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            {!!$case->CaseContent!!}
                        </div>
                    </div>

                </div>
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    Cases详情
                </div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">

                        <ul class="list-group">
                            <li class="list-group-item ">ItemID: {{$case->case_id}}</li>
                            <li class="list-group-item ">总金额：{{$case->case_amount}}</li>
                            <li class="list-group-item ">数量：{{$case->case_quantity}}</li>
                            <li class="list-group-item ">最后回复时间：{{$case->last_modify_date}}</li>

                        </ul>
                        </div>
                    </div>

                </div>
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    处理
                </div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="nav nav-tabs nav-justified">
                                <li role="presentation" class="process-tab" tabindex="1"><a href="javascript:void(0);">Add tracking details</a></li>
                                <li role="presentation" class="process-tab" tabindex="2"><a href="javascript:void(0);" >Refund the buyer</a></li>
                                <li role="presentation" class="process-tab" tabindex="3"><a href="javascript:void(0);">Send a message to the buyer</a></li>
                            </ul>
                        </div>
                    </div>
                    <!--case 操作表单begin-->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-success tab-content tabhide" tabindex="1">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Add tracking details:</h3>
                                </div>
                                <div class="panel-body">
                                    <form class="form-horizontal">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="optionsRadios" value="option1" checked="">
                                                有跟踪号
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="optionsRadios" value="option1" checked="">
                                                没有跟踪号
                                            </label>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 control-label">trackingNumber*</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 control-label">carrier*</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
                                            </div>
                                        </div>
                                        <label>comments: </label>
                                        <textarea class="form-control" rows="3"></textarea>
                                        <div class="row">
                                            <div class="col-lg-12">

                                            <button type="button" class="btn btn-primary" style="float: right">提交</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>


                            <div class="panel panel-danger tab-content tabhide" tabindex="2">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Refund the buyer:</h3>
                                </div>
                                <div class="panel-body">
                                    <form class="form-horizontal">
                                        <label>退款原因: </label>
                                        <select class="form-control">
                                            @foreach(config('order.reason') as $key => $reason)
                                                <option value="">{{$reason}}</option>
                                            @endforeach
                                        </select>
                                        <label>comments: </label>
                                        <textarea class="form-control" rows="3"></textarea>
                                        <div class="row">
                                            <div class="col-lg-12">

                                                <button type="button" class="btn btn-primary" style="float: right">提交</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="panel panel-success tab-content tabhide" tabindex="3">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Send a message to the buyer:</h3>
                                </div>
                                <div class="panel-body">
                                    <form class="form-horizontal" action="{{route('MessageToBuyer')}}">
                                        {!! csrf_field() !!}
                                        <label>comments: </label>
                                        <input type="hidden" name="id" value="{{$case->id}}">
                                        <textarea class="form-control" rows="3" name="messgae_content"></textarea>
                                        <div class="row">
                                            <div class="col-lg-12">

                                                <button type="submit" class="btn btn-primary" style="float: right">提交</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--case 操作表单  end-->

                </div>
            </div>

        </div>

        <div class="col-lg-4">
            @if($case->related_order_id)
                @include('message.ebay_cases.order')
            @endif
        </div>
@stop
@section('pageJs')
    <script>
        $(document).ready(function(){
            $('.process-tab').click(function () {
                var index = $(this).attr('tabindex');
                $('.process-tab').removeClass('active');
                $(this).addClass('active');
                $('.tab-content').each(function(){
                    $(this).addClass('tabhide');
                    if($(this).attr('tabindex') == index){
                        $(this).removeClass('tabhide');
                        return;
                    }
                });
            });
        });
    </script>

















































@stop