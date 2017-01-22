@extends('layouts.default')
@section('content')

    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class='row'>


                    <div class="col-lg-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <label>纠纷详情</label>
                            </div>
                            <div class="panel-body">
                                <div class='row'>
                                    <div class="col-lg-2">
                                        <strong>纠纷原因</strong>: {{$issue->list->reasonChinese}}
                                    </div>
                                    <div class="col-lg-2">
                                        <strong>纠纷状态</strong>: <font color="green">纠纷协商中</font>
                                    </div>
                                    <div class="col-lg-2">
                                        <strong>订单号</strong>: {{$issue->list->orderId}}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        价格：<strong>{{$issue->ProductInfo}}</strong><br>

                                        产品名称：
                                        <strong>{{$issue->productName}}</strong><br>
                                        纠纷原因：

                                        <small>{{$issue->issueReason}}</small>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="panel panel-danger">
                            <div class="panel-heading">
                                <label>Buyer solution list</label>
                            </div>

                            <div class="panel-body">
                                @foreach($issue->BuyerSolutionInfo as $buyer_info)
                                    <div class="row">
                                        <div class="col-lg-10 bg-info">
                                            solutionType:{{$buyer_info->solutionType}}<br/>
                                            status:{{$buyer_info->status}}<br/>
                                            content:{{$buyer_info->content}}<br/>
                                            solutionOwner:{{$buyer_info->solutionOwner}}<br/>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="panel panel-success">

                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <label>订单详情</label>
                            </div>
                            <div class="panel-body">
                                <div class='row'>
                                    <div class="col-lg-2">
                                        <strong>纠纷原因</strong>: {{$issue->list->reasonChinese}}
                                    </div>
                                    <div class="col-lg-2">
                                        <strong>纠纷状态</strong>: <font color="green">纠纷协商中</font>
                                    </div>
                                    <div class="col-lg-2">
                                        <strong>订单号</strong>: {{$issue->list->orderId}}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        价格：<strong>{{$issue->ProductInfo}}</strong><br>

                                        产品名称：
                                        <strong>{{$issue->productName}}</strong><br>
                                        纠纷原因：

                                        <small>{{$issue->issueReason}}</small>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                    {{--        <div class="col-lg-4">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        产品
                                    </div>

                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12">

                                                产品名称：
                                                <strong>{{$issue->productName}}</strong><br>

                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>--}}

            </div>
    </div>

@stop