@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">汇总</div>
        <div class="panel-body">
            <p>全部数据共{{ count($errors[0])}}条，出错{{ count($errors)-1 }}条</p>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">详细情况</div>
        <div class="panel-body">
            <div class='row'>
                <div class='col-lg-2'><label>sku</label></div>
                <div class='col-lg-2'><label>库位</label></div>
                <div class='col-lg-2'><label>总数量</label></div>
                <div class='col-lg-2'><label>可用数量</label></div>
                <div class='col-lg-2'><label>hold数量</label></div>
                <div class='col-lg-2'><label>单价</label></div>
            </div>
            @foreach($errors as $key => $value)
            @if($key != 0)
            <div class='row'>
                <div class='col-lg-2'><input type='text' class='form-control' value="{{ iconv('gb2312','utf-8',$errors[0][$value]['sku']) }}"></div>
                <div class='col-lg-2'><input type='text' class='form-control' value="{{ iconv('gb2312','utf-8',$errors[0][$value]['position']) }}"></div>
                <div class='col-lg-2'><input type='text' class='form-control' value="{{ $errors[0][$value]['all_quantity']}}"></div>
                <div class='col-lg-2'><input type='text' class='form-control' value="{{ $errors[0][$value]['available_quantity']}}"></div>
                <div class='col-lg-2'><input type='text' class='form-control' value="{{ $errors[0][$value]['hold_quantity']}}"></div>
                <div class='col-lg-2'><input type='text' class='form-control' value="{{ $errors[0][$value]['unit_cost']}}"></div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
@stop