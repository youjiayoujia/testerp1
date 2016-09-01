@extends('layouts.default')
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading"> 速卖通批量留言（订单留言） </div>
        <div class="panel-body">
            <form action="{{route('paypal.update_rates')}}" method="POST">
                <a href="{{route('aliexpressCsvFormat')}}" class=" download-csv">Excel格式
                    <i class="glyphicon glyphicon-arrow-down"></i>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="file" class="file" id="qualifications" placeholder="上传审核资料" name="excel" value="">


                </a>
                <textarea class="form-control" rows="10" name="comments"></textarea>
                <button type="submit" class="btn btn-success">提交</button>
            </form>
        </div>
    </div>
@stop