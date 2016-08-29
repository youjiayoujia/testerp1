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
                    处理
                </div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
                                    Option one is this and that&mdash;be sure to include why it's great
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
                                    Option two can be something else and selecting it will deselect option one
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
                                    Option two can be something else and selecting it will deselect option one
                                </label>
                                <button style="float: right;" type="button" class="btn btn-success btn-translation" need-translation-content="">
                                    选择
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    订单信息
                </div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@stop