@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-lg-10">
            <div class="panel panel-primary">
                <div class="panel-heading">
                <label>详情</label>
                </div>
                <div class="panel-body">
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
                <div class="panel-heading">
                    <label>Seller solution list</label>
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
        @stop
        @section('pageJs')
            <script>
                $(document).ready(function(){
                });
            </script>

















































@stop