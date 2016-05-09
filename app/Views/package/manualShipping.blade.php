@extends('common.form')
@section('formAction')@stop
@section('formBody')
<div class='row'>
    <div class="col-lg-12">
        <table class="table table-bordered table-striped table-hover sortable">
            <thead>
            <tr>
                <th><input type='checkbox' name='select_all[]' class='select_all'></th>
                <th>Package ID</th>
                <th>订单号</th>
                <th>Shop</th>
                <th>状态</th>
            </tr>
            </thead>
            <tbody>
            @foreach($packages as $model)
            <tr>
                <td><input type='checkbox' name='select[]' class='select_single'></td>
                <td>{{$model->id}}</td>
                <td>{{$model->order ? $model->order->ordernum : ''}}</td>
                <td>{{$model->channel->name}}</td>
                <td>{{$model->status_name}}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop
@section('formButton')
<button type="submit" class="btn btn-success">打印发货单</button>
<button type="reset" class="btn btn-default">取消</button>
@stop

@section('pageJs')
<script type='text/javascript'>
$(document).ready(function(){
    $('.select_all').click(function(){
        if($(this).prop('checked') == true) {
            $.each($('.select_single'), function(){
                $(this).prop('checked', true);
            })
        } else {
            $.each($('.select_single'), function(){
                $(this).prop('checked', false);
            })
        }
    });
});
</script>
@stop
